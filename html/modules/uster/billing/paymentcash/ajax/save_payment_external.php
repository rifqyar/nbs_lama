<?php
// UNCOMMENT UNTUK CEK NO_PROFORMA EBPP DI USTER
// $db = getDb("storage");
// $proforma = '17.2023.027640';
// $q = "SELECT * FROM (SELECT NO_REQUEST, NO_NOTA_MTI FROM NOTA_STRIPPING UNION SELECT NO_REQUEST, NO_NOTA_MTI FROM nota_stuffing UNION SELECT NO_REQUEST, NO_NOTA_MTI FROM NOTA_DELIVERY UNION SELECT NO_REQUEST, NO_NOTA_MTI FROM NOTA_BATAL_MUAT) WHERE NO_NOTA_MTI = '$proforma'";
// $fetch = $db->query($q)->fetchRow();
// echo json_encode($fetch);
// die;

require_lib('praya.php');
// require_lib('request_integration.php');

// takes raw data from the request 
$json = file_get_contents('php://input');
// Converts it into a PHP object 
$payload_uster_save = json_decode($json, true);

$url_uster_save = PRAYA_API_INTEGRATION . "/api/usterSave";

$payloadBatalMuat = $payload_uster_save["PAYLOAD_BATAL_MUAT"];
$jenis = $payload_uster_save["JENIS"];
$id_req = $payload_uster_save["ID_REQUEST"];
$bankAccountNumber = $payload_uster_save["BANK_ACCOUNT_NUMBER"];
$paymentCode = $payload_uster_save["PAYMENT_CODE"];
$charge = empty($payloadBatalMuat) ? "Y" : "N"; //kalau payload batal muat ada berarti tdk bayar
$db = getDb("storage");

// Variable untuk logging container, hanya variable penting yg dimasukan karena container bisa jadi sngt bnyk (Sementara utk PERP_STRIP)
$containerListLog = array();

if (empty($payload_uster_save)) {
  $jenis = $_POST['JENIS'];
  $bankAccountNumber = $_POST["BANK_ACCOUNT_NUMBER"];
  $paymentCode = $_POST["PAYMENT_CODE"];
}

$del_no_request = empty($payloadBatalMuat) ? $id_req : $payloadBatalMuat['ex_noreq'];

//INI UNTUK QUERY JENIS DELIVERY & BATAL MUAT TANPA KENA CHARGE
//Kalau menggunakan NO_PROFORMA, ID_REQUEST jangan dikirim dan sebaliknya
if (!empty($_POST["NO_PROFORMA"]) && $jenis == 'DELIVERY') {  
  $payload_log = '';
  if (empty($payload_uster_save)) {
    $payload_log = array(
      "NO_PROFORMA" => $_POST['NO_PROFORMA'],
      "JENIS" => $_POST['JENIS'],
      "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
      "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
    );
  } else {
    $payload_log = $payload_uster_save;
  }
  $del_no_request = "";
  $proforma = $_POST["NO_PROFORMA"];
  $q_id_req = "SELECT NO_REQUEST FROM NOTA_DELIVERY WHERE NO_NOTA_MTI = '$proforma'";
  $fetch_id_req = $db->query($q_id_req)->fetchRow();
  if (!empty($fetch_id_req) && isset($fetch_id_req)) {
    $del_no_request = $fetch_id_req["NO_REQUEST"];
    $id_req = $fetch_id_req["NO_REQUEST"];
  } else {
    $notes = "Payment Cash - " . $jenis . " - NO PROFORMA DELIVERY BUKAN MILIK MTI";
    $response_uster_save = array(
      'code' => "0",
      'msg' => "No. Proforma Delivery bukan milik MTI"
    );
    insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

    echo json_encode($response_uster_save);
    die();
  }
}

$queryDelivery =
  "SELECT
    --	rd.*,
    rd.NO_REQUEST,
    rd.NO_BOOKING,
    rd.KD_EMKL,
    rd.O_VESSEL,
    rd.VOYAGE,
    rd.KD_PELABUHAN_ASAL, --POL
    rd.KD_PELABUHAN_TUJUAN, --POD
    rd.O_VOYIN,
    rd.O_VOYOUT,
    rd.DELIVERY_KE,
    rd.TGL_REQUEST,
    rd.DI,
    -- vpc.*,
    vpc.KD_KAPAL,
    vpc.NM_KAPAL,
    vpc.VOYAGE_IN,
    vpc.VOYAGE_OUT,
    vpc.PELABUHAN_TUJUAN,
    vpc.PELABUHAN_ASAL,
    vpc.NM_AGEN,
    vpc.KD_AGEN,
    --	nd.*,
    nd.NO_NOTA,
    nd.NO_FAKTUR_MTI,
    nd.TAGIHAN,
    nd.PPN,
    nd.TOTAL_TAGIHAN,
    nd.EMKL,
    nd.ALAMAT,
    nd.NPWP,
    TO_CHAR(nd.TGL_NOTA ,'YYYY-MM-DD HH24:MI:SS') TGLNOTA,
    TO_CHAR(rd.TGL_REQUEST,'YYYY-MM-DD HH24:MI:SS') TGLSTART,
    TO_CHAR(rd.TGL_REQUEST + INTERVAL '4' DAY,'YYYY-MM-DD HH24:MI:SS') TGLEND,
    -- vmp.*,
    vmp.NO_ACCOUNT_PBM KD_PELANGGAN
  FROM
    REQUEST_DELIVERY rd
  LEFT JOIN V_PKK_CONT vpc ON
    rd.NO_BOOKING = vpc.NO_BOOKING
  JOIN NOTA_DELIVERY nd ON
    nd.NO_REQUEST = rd.NO_REQUEST
  JOIN V_MST_PBM vmp ON
    vmp.KD_PBM = rd.KD_EMKL  
  WHERE  
    rd.NO_REQUEST = '$del_no_request'";
// DIATAS UNTUK QUERY DELIVERY

if ($jenis == 'STRIPPING' || $jenis == 'PERP_STRIP') { //DELIVERY KALO DARI SISI TPK

  //Kalau menggunakan NO_PROFORMA, ID_REQUEST jangan dikirim dan sebaliknya
  if (!empty($_POST["NO_PROFORMA"])) {
    $payload_log = '';
    if (empty($payload_uster_save)) {
      $payload_log = array(
        "NO_PROFORMA" => $_POST['NO_PROFORMA'],
        "JENIS" => $_POST['JENIS'],
        "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
        "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
      );
    } else {
      $payload_log = $payload_uster_save;
    }
    $id_req = "";
    $proforma = $_POST["NO_PROFORMA"];
    $q_id_req = "SELECT NO_REQUEST FROM NOTA_STRIPPING WHERE NO_NOTA_MTI = '$proforma'";
    $fetch_id_req = $db->query($q_id_req)->fetchRow();
    if (!empty($fetch_id_req) && isset($fetch_id_req)) {
      $id_req = $fetch_id_req["NO_REQUEST"];
    } else {
      $notes = "Payment Cash - " . $jenis . " - NO PROFORMA STRIPPING BUKAN MILIK MTI";
      $response_uster_save = array(
        'code' => "0",
        'msg' => "No. Proforma Stripping bukan milik MTI"
      );
      insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

      echo json_encode($response_uster_save);
      die();
    }
  }
  $queryStripping =
    "SELECT
    rs.NO_BOOKING,
    rs.NO_BL,
    rs.TYPE_STRIPPING,
    vpc.KD_KAPAL,
    vpc.NM_KAPAL,
    vpc.VOYAGE_IN,
    vpc.VOYAGE_OUT,
    vpc.VOYAGE, -- KOLOM BELUM DIISI DI DEV
    vpc.PELABUHAN_TUJUAN,
    vpc.PELABUHAN_ASAL,
    vpc.NM_AGEN,
    vpc.KD_AGEN,
    vpc.NO_UKK,
    ns.NO_NOTA,
    ns.NO_FAKTUR_MTI,
    ns.TAGIHAN,
    ns.PPN,
    ns.TOTAL_TAGIHAN,
    ns.EMKL,
    ns.ALAMAT,
    ns.NPWP,
    vmp.NO_ACCOUNT_PBM KD_PELANGGAN,
    TO_CHAR(rs.TGL_AWAL,'YYYY-MM-DD HH24:MI:SS') TGLAWAL, 
	  TO_CHAR(rs.TGL_AKHIR,'YYYY-MM-DD HH24:MI:SS') TGLAKHIR,
    TO_CHAR(cs.TGL_APPROVE, 'YYYY-MM-DD HH24:MI:SS') TGLAPPROVE,
    TO_CHAR(cs.TGL_APP_SELESAI, 'YYYY-MM-DD HH24:MI:SS') TGLAPPROVE_SELESAI,
    TO_CHAR(ns.TGL_NOTA,'YYYY-MM-DD HH24:MI:SS') TGLNOTA,
    -- TO_CHAR(pcs.TGL_SELESAI, 'YYYY-MM-DD HH24:MI:SS') TGLSELESAI,
    CASE
        WHEN rs.TGL_AWAL IS NULL OR rs.TGL_AKHIR IS NULL THEN 4
        ELSE rs.TGL_AKHIR - rs.TGL_AWAL
    END AS COUNT_DAYS
  FROM
    REQUEST_STRIPPING rs
  LEFT JOIN V_PKK_CONT vpc ON
    rs.NO_BOOKING = vpc.NO_BOOKING
  JOIN NOTA_STRIPPING ns ON
    ns.NO_REQUEST = rs.NO_REQUEST
  JOIN CONTAINER_STRIPPING cs ON
  	rs.NO_REQUEST = cs.NO_REQUEST
  JOIN V_MST_PBM vmp ON
    vmp.KD_PBM = ns.KD_EMKL
  -- JOIN PLAN_REQUEST_STRIPPING prs ON rs.NO_REQUEST = prs.NO_REQUEST_APP_STRIPPING 
  -- JOIN PLAN_CONTAINER_STRIPPING pcs ON pcs.NO_REQUEST = prs.NO_REQUEST
  WHERE
        rs.NO_REQUEST = '$id_req'";
  $fetchStripping = $db->query($queryStripping)->fetchRow();
  // CHOSSY.P (26/12/2023)
  // penambahan paythru utk perp strip
  $queryContainerStripping =
    "SELECT cs.*, mc.*, TO_CHAR(cs.TGL_SELESAI, 'YYYY-MM-DD HH24:MI:SS') TGLSELESAI, TO_CHAR(cs.END_STACK_PNKN, 'YYYY-MM-DD HH24:MI:SS') TGLSELESAI_PERP FROM CONTAINER_STRIPPING cs JOIN MASTER_CONTAINER mc ON cs.NO_CONTAINER = mc.NO_CONTAINER WHERE cs.NO_REQUEST = '$id_req'";
  // END 
  $fetchContainerStripping = $db->query($queryContainerStripping)->getAll();
  $queryNotaStripping =
    "SELECT ns.*, nsd.*, TO_CHAR(ns.TGL_NOTA,'YYYY-MM-DD HH24:MI:SS') TGLNOTA, (SELECT STATUS FROM ISO_CODE ic WHERE ic.ID_ISO = nsd.ID_ISO) STATUS, TO_CHAR(nsd.START_STACK,'YYYY-MM-DD HH24:MI:SS') AWAL_PENUMPUKAN, TO_CHAR(nsd.END_STACK,'YYYY-MM-DD HH24:MI:SS') AKHIR_PENUMPUKAN FROM NOTA_STRIPPING ns JOIN NOTA_STRIPPING_D nsd ON nsd.NO_NOTA = ns.NO_NOTA WHERE ns.NO_REQUEST = '$id_req' ";
  $fetchNotaStripping = $db->query($queryNotaStripping)->getAll();
  $queryGetAdmin =
    "SELECT TARIF FROM NOTA_STRIPPING ns JOIN NOTA_STRIPPING_D nsd ON nsd.NO_NOTA = ns.NO_NOTA WHERE ns.NO_REQUEST = '$id_req' AND nsd.ID_ISO = 'ADM' ";
  $adminComponent = $db->query($queryGetAdmin)->fetchRow();

  $get_vessel = getVessel($fetchStripping['NM_KAPAL'], $fetchStripping['VOYAGE'], $fetchStripping['VOYAGE_IN'], $fetchStripping['VOYAGE_OUT']);

  $get_container_list = getContainer(NULL, $fetchStripping['KD_KAPAL'], $fetchStripping['VOYAGE_IN'], $fetchStripping['VOYAGE_OUT'], $fetchStripping['VOYAGE'], "I", "DEL");

  $get_iso_code = getIsoCode();

  if (empty($get_iso_code)) {
    $payload_log = '';
    if(empty($payload_uster_save)){
      $payload_log = array(
        "NO_PROFORMA" => $_POST['NO_PROFORMA'],
        "JENIS" => $_POST['JENIS'],
        "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
        "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
      );
    }else{
      $payload_log = $payload_uster_save;
    }
    $notes = "Payment Cash - " . $jenis . " - GAGAL GET ISO CODE";
    $response_uster_save = array(
      'code' => "0",
      'msg' => "Gagal mengambil Iso Code ke Praya, silahkan ulangi Kembali hit process ini."
    );
    insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

    echo json_encode($response_uster_save);
    die();
  }

  $tgl_awal = $fetchStripping['TGLAWAL'];
  $tgl_akhir = $fetchStripping['TGLAKHIR'];
  if (empty($tgl_awal) || empty($tgl_akhir)) {
    $tgl_awal = $fetchStripping['TGLAPPROVE'];
    $tgl_akhir = $fetchStripping['TGLAPPROVE_SELESAI'];
  }

  // echo json_encode($get_vessel);
  // echo json_encode($get_container_list);

  $pelabuhan_asal = $fetchStripping['PELABUHAN_ASAL'];
  $pelabuhan_tujuan = $fetchStripping['PELABUHAN_TUJUAN'];

  $idRequest = $id_req;
  $trxNumber = $fetchStripping['NO_NOTA'];
  $paymentDate = $fetchStripping['TGLNOTA'];
  $invoiceNumber = $fetchStripping['NO_FAKTUR_MTI'];
  $requestType = 'STRIPPING';
  $parentRequestId = '';
  $parentRequestType = 'STRIPPING';
  $serviceCode = 'DEL';
  $vesselId = $fetchStripping['KD_KAPAL'];
  $vesselName = $fetchStripping['NM_KAPAL'];
  $voyage = empty($fetchStripping['VOYAGE']) ? '' : $fetchStripping['VOYAGE'];
  $voyageIn = empty($fetchStripping['VOYAGE_IN']) ? '' : $fetchStripping['VOYAGE_IN'];
  $voyageOut = empty($fetchStripping['VOYAGE_OUT']) ? '' : $fetchStripping['VOYAGE_OUT'];
  $voyageInOut = empty($voyageIn) || empty($voyageOut) ? '' : $voyageIn . '/' . $voyageOut;
  $eta = empty($get_vessel['eta']) ? '' : $get_vessel['eta'];
  $etb = empty($get_vessel['etb']) ? '' : $get_vessel['etb'];
  $etd = empty($get_vessel['etd']) ? '' : $get_vessel['etd'];
  $ata = empty($get_vessel['ata']) ? '' : $get_vessel['ata'];
  $atb = empty($get_vessel['atb']) ? '' : $get_vessel['atb'];
  $atd = empty($get_vessel['atd']) ? '' : $get_vessel['atd'];
  $startWork = empty($get_vessel['start_work']) ? '' : $get_vessel['start_work'];
  $endWork = empty($get_vessel['end_work']) ? '' : $get_vessel['end_work'];
  $pol = $pelabuhan_asal;
  $pod = $pelabuhan_tujuan;
  $dischargeDate = $get_vessel['discharge_date'];
  $shippingLineName = $fetchStripping['NM_AGEN'];
  $customerCode = $fetchStripping['KD_PELANGGAN'];
  $customerCodeOwner = '';
  $customerName = $fetchStripping['EMKL'];
  $customerAddress = $fetchStripping['ALAMAT'];
  $npwp = $fetchStripping['NPWP'];
  $blNumber = $fetchStripping['NO_BL'];
  $bookingNo = $fetchStripping['NO_BOOKING'];
  // $deliveryDate = $fetchStripping['TGLSELESAI']; //paythruDate
  $doNumber = $fetchStripping['NO_BOOKING'];
  // $doDate = "";
  $tradeType = $fetchStripping['TYPE_STRIPPING'] == 'D' ? 'I' : 'O';
  $customsDocType = "";
  $customsDocNo = "";
  $customsDocDate = "";
  if ((int)$fetchStripping['TOTAL_TAGIHAN'] > 5000000) {
    $amount = (int)$fetchStripping['TOTAL_TAGIHAN'] + 10000;
  } else {
    (int)$amount = $fetchStripping['TOTAL_TAGIHAN'];
  }
  if ($adminComponent) {
    $administration = $adminComponent['TARIF'];
  }
  if (empty($fetchStripping['PPN'])) {
    $ppn =  'N';
  } else {
    $ppn = 'Y';
  };
  $amountPpn  = (int)$fetchStripping['PPN'];
  $amountDpp = (int)$fetchStripping['TAGIHAN'];
  if ((int)$fetchStripping['TAGIHAN'] > 5000000) {
    $amountMaterai = 10000;
  } else {
    $amountMaterai = 0;
  }
  $approvalDate = empty($fetchStripping['TGLAPPROVE']) ? '' : $fetchStripping['TGLAPPROVE'];
  $status = 'PAID';
  $changeDate = $fetchStripping['TGLNOTA'];
  $charge = 'Y';

  $detailList = array();
  $containerList = array();
  foreach ($fetchContainerStripping as $k => $v) {
    foreach ($get_container_list as $k_container => $v_container) {
      if ($v_container['containerNo']  == $v['NO_CONTAINER']) {
        $_get_container = $v_container;
        break;
      }
    }

    $reslt = array();
    foreach ($get_iso_code as $key => $value) {
      if (strtoupper($value['type']) == strtoupper($v['TYPE_']) && strtoupper($value['size']) == strtoupper($v['SIZE_'])) {
        array_push($reslt, $value);
      }
    }
    $array_iso_code = array_values($reslt);
    $new_iso = mapNewIsoCode($array_iso_code[0]["isoCode"]);

    // CHOSSY.P (26/12/2023)
    // penambahan paythru utk perp strip
    $paythru = $v['TGLSELESAI'];
    if(substr($idRequest, 0, 3) == "STP"){
      $paythru = $v['TGLSELESAI_PERP'];

      array_push($containerListLog, array(
        "containerNo" => $v['NO_CONTAINER'],
        "containerDeliveryDate" => $paythru
      ));
    }
    // END
    
    array_push($containerList, $v['NO_CONTAINER']);
    array_push(
      $detailList,
      array(
        "detailDescription" => "CONTAINER",
        "containerNo" => $v['NO_CONTAINER'],
        "containerSize" => $v['SIZE_'],
        "containerType" => $v['TYPE_'],
        "containerStatus" => "FULL",
        "containerHeight" => "8.5",
        "hz" => empty($v['HZ']) ? (empty($_get_container['hz']) ? 'N' : $_get_container['hz']) : $v['HZ'],
        "imo" => "N",
        "unNumber" => empty($_get_container['unNumber']) ? '' : $_get_container['unNumber'],
        "reeferNor" => "N",
        "temperatur" => "",
        "ow" => "",
        "oh" => "",
        "ol" => "",
        "overLeft" => "",
        "overRight" => "",
        "overFront" => "",
        "overBack" => "",
        "weight" => "",
        "commodityCode" => trim($v['COMMODITY'], " "),
        "commodityName" => trim($v['COMMODITY'], " "),
        "carrierCode" => $fetchStripping['KD_AGEN'],
        "carrierName" => $fetchStripping['NM_AGEN'],
        "isoCode" => $new_iso,
        "plugInDate" => "",
        "plugOutDate" => "",
        "ei" => "I",
        "dischLoad" => "",
        "flagOog" => empty($_get_container['flagOog']) ? '' : $_get_container['flagOog'],
        "gateInDate" => "",
        "gateOutDate" => "",
        "startDate" => $tgl_awal,
        "endDate" => $tgl_akhir,
        "containerDeliveryDate" => $paythru,
        "containerLoadingDate" => "",
        "containerDischargeDate" => $get_vessel['discharge_date'],
        "disabled" => "Y"
      )
    );
  }

  $strContList = implode(", ", $containerList);
  $detailPranotaList = array();
  foreach ($fetchNotaStripping as $k => $v) {
    $status = "";
    if(!empty($v['STATUS']) && $v['STATUS'] != "-"){
      $status = $v['STATUS'] == "FCL" ? "FULL" : "EMPTY";
    }
    array_push(
      $detailPranotaList,
      array(
        "lineNumber" => $v['LINE_NUMBER'],
        "description" => $v['KETERANGAN'],
        "flagTax" => "Y",
        "componentCode" => $v['KETERANGAN'],
        "componentName" => $v['KETERANGAN'],
        "startDate" => $v['AWAL_PENUMPUKAN'],
        "endDate" => $v['AKHIR_PENUMPUKAN'],
        "quantity" => $v['JML_CONT'],
        "tarif" => $v['TARIF'],
        "basicTarif" => $v['TARIF'],
        "containerList" => $strContList,
        "containerSize" => $fetchContainerStripping[0]['SIZE_'],
        "containerType" => $fetchContainerStripping[0]['TYPE_'],
        "containerStatus" => $status,
        "containerHeight" => "8.5",
        "hz" => empty($v['HZ']) ? 'N' : $v['HZ'],
        "ei" => "I",
        "equipment" => "",
        "strStartDate" => $v['AWAL_PENUMPUKAN'],
        "strEndDate" => $v['AKHIR_PENUMPUKAN'],
        "days" => $fetchStripping['COUNT_DAYS'],
        "amount" => $v['BIAYA'],
        "via" => "YARD",
        "package" => "",
        "unit" => "BOX",
        "qtyLoading" => "",
        "qtyDischarge" => "",
        "equipmentName" => "",
        "duration" => "",
        "flagTool" => "N",
        "itemCode" => "",
        "oog" => "N",
        "imo" => "",
        "blNumber" => empty($fetchStripping['NO_BL']) ? '' : $fetchStripping['NO_BL'],
        "od" => "N",
        "dg" => "N",
        "sling" => "N",
        "changeDate" => $v['TGLNOTA'],
        "changeBy" => "Admin Uster"
      )
    );
  }
} elseif ($jenis == 'STUFFING' /* || $jenis == 'PERP_PNK' */) { //RECEIVING
  //Kalau menggunakan NO_PROFORMA, ID_REQUEST jangan dikirim dan sebaliknya
  if (!empty($_POST["NO_PROFORMA"])) {
    $payload_log = '';
    if(empty($payload_uster_save)){
      $payload_log = array(
        "NO_PROFORMA" => $_POST['NO_PROFORMA'],
        "JENIS" => $_POST['JENIS'],
        "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
        "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
      );
    }else{
      $payload_log = $payload_uster_save;
    }
    $id_req = "";
    $proforma = $_POST["NO_PROFORMA"];
    $q_id_req = "SELECT NO_REQUEST FROM NOTA_STUFFING WHERE NO_NOTA_MTI = '$proforma'";
    $fetch_id_req = $db->query($q_id_req)->fetchRow();
    if (!empty($fetch_id_req) && isset($fetch_id_req)) {
      $id_req = $fetch_id_req["NO_REQUEST"];
    } else {
      $notes = "Payment Cash - " . $jenis . " - NO PROFORMA STUFFING BUKAN MILIK MTI";
      $response_uster_save = array(
        'code' => "0",
        'msg' => "No. Proforma Stuffing bukan milik MTI"
      );
      insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

      echo json_encode($response_uster_save);
      die();
    }
  }
  $queryStuffing =
    "SELECT
    rs.NO_BOOKING,
    rs.NO_BL,
    rs.NO_NPE, --customDocs
    rs.DI,
    rs.STUFFING_DARI, --ASAL STUFFING HARUS DARI TPK
    vpc.KD_KAPAL,
    vpc.NM_KAPAL,
    vpc.VOYAGE_IN,
    vpc.VOYAGE_OUT,
    vpc.VOYAGE, -- KOLOM BELUM DIISI
    vpc.PELABUHAN_TUJUAN,
    vpc.PELABUHAN_ASAL,
    vpc.NM_AGEN,
    vpc.KD_AGEN,
    vpc.NO_UKK,
    ns.NO_NOTA,
    ns.NO_FAKTUR_MTI,
    ns.TAGIHAN,
    ns.PPN,
    ns.TOTAL_TAGIHAN,
    ns.EMKL,
    ns.ALAMAT,
    ns.NPWP,
    vmp.NO_ACCOUNT_PBM KD_PELANGGAN,
    cs.ASAL_CONT, --ASAL CONTAINER HARUS DARI TPK
    TO_CHAR(pcs.TGL_APPROVE,'YYYY-MM-DD HH24:MI:SS') TGLAPPROVE,
    TO_CHAR(ns.TGL_NOTA,'YYYY-MM-DD HH24:MI:SS') TGLNOTA,
    TO_CHAR(rs.TGL_REQUEST,'YYYY-MM-DD HH24:MI:SS') TGLSTART,
    TO_CHAR(rs.TGL_REQUEST + INTERVAL '4' DAY,'YYYY-MM-DD HH24:MI:SS') TGLEND
  FROM
    REQUEST_STUFFING rs
  LEFT JOIN V_PKK_CONT vpc ON
    rs.NO_BOOKING = vpc.NO_BOOKING
  JOIN NOTA_STUFFING ns ON
    ns.NO_REQUEST = rs.NO_REQUEST
  JOIN V_MST_PBM vmp ON
    vmp.KD_PBM = ns.KD_EMKL
  JOIN CONTAINER_STUFFING cs ON
  	cs.NO_REQUEST = rs.NO_REQUEST
  JOIN PLAN_REQUEST_STUFFING prs ON
    prs.NO_REQUEST_APP = rs.NO_REQUEST
  JOIN PLAN_CONTAINER_STUFFING pcs ON
    pcs.NO_REQUEST = prs.NO_REQUEST
  WHERE rs.NO_REQUEST = '$id_req'";
  $fetchStuffing = $db->query($queryStuffing)->fetchRow();

  if (!empty($fetchStuffing) && isset($fetchStuffing['STUFFING_DARI']) && $fetchStuffing['STUFFING_DARI'] == 'TPK') {
    $queryContainerStuffing =
      "SELECT cs.*, mc.*, TO_CHAR(cs.START_PERP_PNKN,'YYYY-MM-DD HH24:MI:SS') TGLPAYTHRU FROM CONTAINER_STUFFING cs JOIN MASTER_CONTAINER mc ON cs.NO_CONTAINER = mc.NO_CONTAINER WHERE cs.NO_REQUEST = '$id_req'";
    $fetchContainerStuffing = $db->query($queryContainerStuffing)->getAll();
    $queryNotaStuffing =
      "SELECT ns.*, nsd.*, TO_CHAR(ns.TGL_NOTA,'YYYY-MM-DD HH24:MI:SS') TGLNOTA, (SELECT STATUS FROM ISO_CODE ic WHERE ic.ID_ISO = nsd.ID_ISO) STATUS, TO_CHAR(nsd.START_STACK,'YYYY-MM-DD HH24:MI:SS') AWAL_PENUMPUKAN, TO_CHAR(nsd.END_STACK,'YYYY-MM-DD HH24:MI:SS') AKHIR_PENUMPUKAN FROM NOTA_STUFFING ns JOIN NOTA_STUFFING_D nsd ON nsd.NO_NOTA = ns.NO_NOTA WHERE ns.NO_REQUEST = '$id_req' ";
    $fetchNotaStuffing = $db->query($queryNotaStuffing)->getAll();
    $queryGetAdmin =
      "SELECT TARIF FROM NOTA_STUFFING ns JOIN NOTA_STUFFING_D nsd ON nsd.NO_NOTA = ns.NO_NOTA WHERE ns.NO_REQUEST = '$id_req' AND nsd.ID_ISO = 'ADM' ";
    $adminComponent = $db->query($queryGetAdmin)->fetchRow();

    // $get_vessel = getVessel($fetchStuffing['NM_KAPAL'], $fetchStuffing['VOYAGE'], $fetchStuffing['VOYAGE_IN'], $fetchStuffing['VOYAGE_OUT']);

    // $get_container_list = getContainer(NULL, $fetchStuffing['KD_KAPAL'], $fetchStuffing['VOYAGE_IN'], $fetchStuffing['VOYAGE_OUT'], $fetchStuffing['VOYAGE'], "E", "REC");

    $get_iso_code = getIsoCode();

    if (empty($get_iso_code)) {
      $payload_log = '';
      if(empty($payload_uster_save)){
        $payload_log = array(
          "NO_PROFORMA" => $_POST['NO_PROFORMA'],
          "JENIS" => $_POST['JENIS'],
          "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
          "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
        );
      }else{
        $payload_log = $payload_uster_save;
      }
      $notes = "Payment Cash - " . $jenis . " - GAGAL GET ISO CODE";
      $response_uster_save = array(
        'code' => "0",
        'msg' => "Gagal mengambil Iso Code ke Praya, silahkan ulangi Kembali hit process ini."
      );
      insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

      echo json_encode($response_uster_save);
      die();
    }

    // echo json_encode($get_vessel) . '<<ves';
    // echo json_encode($get_container_list) . '<<cont';

    $tgl_awal = $fetchStuffing['TGLSTART'];
    $tgl_akhir = $fetchStuffing['TGLEND'];

    $pelabuhan_asal = $fetchStuffing['PELABUHAN_ASAL'];
    $pelabuhan_tujuan = $fetchStuffing['PELABUHAN_TUJUAN'];

    $idRequest = $id_req;
    $trxNumber = $fetchStuffing['NO_NOTA'];
    $paymentDate = $fetchStuffing['TGLNOTA'];
    $invoiceNumber = $fetchStuffing['NO_FAKTUR_MTI'];
    $requestType = 'STUFFING';
    $parentRequestId = '';
    $parentRequestType = 'STUFFING';
    $serviceCode = 'DEL';
    $vesselId = "";
    $vesselName = "";
    $voyage = "";
    $voyageIn = "";
    $voyageOut = "";
    $voyageInOut = "";
    $eta = "";
    $etb = "";
    $etd = "";
    $ata = "";
    $atb = "";
    $atd = "";
    $startWork = "";
    $endWork = "";
    $pol = $pelabuhan_asal;
    $pod = $pelabuhan_tujuan;
    $dischargeDate = $get_vessel['discharge_date'];
    $shippingLineName = $fetchStuffing['NM_AGEN'];
    $customerCode = $fetchStuffing['KD_PELANGGAN'];
    $customerCodeOwner = '';
    $customerName = $fetchStuffing['EMKL'];
    $customerAddress = $fetchStuffing['ALAMAT'];
    $npwp = $fetchStuffing['NPWP'];
    $blNumber = empty($fetchStuffing['NO_BL']) ? "" : $fetchStuffing['NO_BL'];
    $bookingNo = $fetchStuffing['NO_BOOKING'];
    $deliveryDate = $fetchStuffing['TGLAPPROVE']; //paythrudate
    $doNumber = $fetchStuffing['NO_BOOKING'];
    // $doDate = '';
    $tradeType = $fetchStuffing['DI'] == 'D' ? 'I' : 'O';
    $customsDocType = $fetchStuffing['DI'] == 'D' ? "NPE" : "";
    $customsDocNo = $fetchStuffing['DI'] == 'D' ? (empty($fetchStuffing["NO_NPE"]) ? "" : $fetchStuffing["NO_NPE"]) : "";
    $customsDocDate = "";
    if ((int)$fetchStuffing['TOTAL_TAGIHAN'] > 5000000) {
      $amount = (int)$fetchStuffing['TOTAL_TAGIHAN'] + 10000;
    } else {
      (int)$amount = $fetchStuffing['TOTAL_TAGIHAN'];
    }
    if ($adminComponent) {
      $administration = $adminComponent['TARIF'];
    }
    if (empty($fetchStuffing['PPN'])) {
      $ppn =  'N';
    } else {
      $ppn = 'Y';
    };
    $amountPpn  = (int)$fetchStuffing['PPN'];
    $amountDpp = (int)$fetchStuffing['TAGIHAN'];
    if ($fetchStuffing['TAGIHAN'] > 5000000) {
      $amountMaterai = 10000;
    } else {
      $amountMaterai = 0;
    }
    $approvalDate = empty($fetchStuffing['TGLAPPROVE']) ? '' : $fetchStuffing['TGLAPPROVE'];
    $status = 'PAID';
    $changeDate = $fetchStuffing['TGLNOTA'];
    $charge = 'Y';

    $detailList = array();
    $containerList = array();
    foreach ($fetchContainerStuffing as $k => $v) {
      // foreach ($get_container_list as $k_container => $v_container) {
      //   if ($v_container['containerNo']  == $v['NO_CONTAINER']) {
      //     $_get_container = $v_container;
      //     break;
      //   }
      // }
      $reslt = array();
      foreach ($get_iso_code as $key => $value) {
        if (strtoupper($value['type']) == strtoupper($v['TYPE_']) && strtoupper($value['size']) == strtoupper($v['SIZE_'])) {
          array_push($reslt, $value);
        }
      }

      $array_iso_code = array_values($reslt);
      $new_iso = mapNewIsoCode($array_iso_code[0]["isoCode"]);

      array_push($containerList, $v['NO_CONTAINER']);
      array_push(
        $detailList,
        array(
          "detailDescription" => "CONTAINER",
          "containerNo" => $v['NO_CONTAINER'],
          "containerSize" => $v['SIZE_'],
          "containerType" => $v['TYPE_'],
          "containerStatus" => "FULL",
          "containerHeight" => "8.5",
          "hz" => empty($v['HZ']) ? 'N' : $v['HZ'],
          "imo" => "N",
          // "unNumber" => empty($_get_container['unNumber']) ? '' : $_get_container['unNumber'],
          "unNumber" => "",
          "reeferNor" => "N",
          "temperatur" => "",
          "ow" => "",
          "oh" => "",
          "ol" => "",
          "overLeft" => "",
          "overRight" => "",
          "overFront" => "",
          "overBack" => "",
          "weight" => $v['BERAT'],
          "commodityCode" => trim($v['COMMODITY'], " "),
          "commodityName" => trim($v['COMMODITY'], " "),
          "carrierCode" => $fetchStuffing['KD_AGEN'],
          "carrierName" => $fetchStuffing['NM_AGEN'],
          "isoCode" => $new_iso,
          "plugInDate" => "",
          "plugOutDate" => "",
          "ei" => "I",
          "dischLoad" => "",
          // "flagOog" => empty($_get_container['flagOog']) ? '' : $_get_container['flagOog'],
          "flagOog" => "",
          "gateInDate" => "",
          "gateOutDate" => "",
          "startDate" => $fetchStuffing['TGLSTART'],
          "endDate" => $fetchStuffing['TGLEND'],
          "containerDeliveryDate" => $v['TGLPAYTHRU'],
          "containerLoadingDate" => "",
          "containerDischargeDate" => "",
          "disabled" => "Y"
        )
      );
    }

    $strContList = implode(", ", $containerList);
    $detailPranotaList = array();
    foreach ($fetchNotaStuffing as $k => $v) {
      $status = "";
      if(!empty($v['STATUS']) && $v['STATUS'] != "-"){
        $status = $v['STATUS'] == "FCL" ? "FULL" : "EMPTY";
      }
      array_push(
        $detailPranotaList,
        array(
          "lineNumber" => $v['LINE_NUMBER'],
          "description" => $v['KETERANGAN'],
          "flagTax" => "Y",
          "componentCode" => $v['KETERANGAN'],
          "componentName" => $v['KETERANGAN'],
          "startDate" => $v['AWAL_PENUMPUKAN'],
          "endDate" => $v['AKHIR_PENUMPUKAN'],
          "quantity" => $v['JML_CONT'],
          "tarif" => $v['TARIF'],
          "basicTarif" => $v['TARIF'],
          "containerList" => $strContList,
          "containerSize" => $fetchContainerStuffing[0]['SIZE_'],
          "containerType" => $fetchContainerStuffing[0]['TYPE_'],
          "containerStatus" => $status,
          "containerHeight" => "8.5",
          "hz" => empty($v['HZ']) ? 'N' : $v['HZ'],
          "ei" => "I",
          "equipment" => "",
          "strStartDate" => $v['AWAL_PENUMPUKAN'],
          "strEndDate" => $v['AKHIR_PENUMPUKAN'],
          "days" => "4", //TGL_END - TGL_START INTERVAL 4 HARI
          "amount" => $v['BIAYA'],
          "via" => "YARD",
          "package" => "",
          "unit" => "BOX",
          "qtyLoading" => "",
          "qtyDischarge" => "",
          "equipmentName" => "",
          "duration" => "",
          "flagTool" => "N",
          "itemCode" => "",
          "oog" => "N",
          "imo" => "",
          "blNumber" => empty($fetchStuffing['NO_BL']) ? '' : $fetchStuffing['NO_BL'],
          "od" => "N",
          "dg" => "N",
          "sling" => "N",
          "changeDate" => $v['TGLNOTA'],
          "changeBy" => "Admin Uster"
        )
      );
    }
  } else {
    $payload_log = '';
    if(empty($payload_uster_save)){
      $payload_log = array(
        "NO_PROFORMA" => $_POST['NO_PROFORMA'],
        "JENIS" => $_POST['JENIS'],
        "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
        "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
      );
    }else{
      $payload_log = $payload_uster_save;
    }
    $notes = "Payment Cash - " . $jenis . " - STUFFING BUKAN DARI TPK";
    $response_uster_save = array(
      'code' => "0",
      'msg' => "Asal Stuffing Bukan Dari TPK"
    );
    insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

    echo json_encode($response_uster_save);
    die();
  }
} elseif ($jenis == 'DELIVERY') {

  $fetchDelivery = $db->query($queryDelivery)->fetchRow();

  //IF DELIVERY KE TPK
  if (!empty($fetchDelivery) && isset($fetchDelivery['DELIVERY_KE']) && $fetchDelivery['DELIVERY_KE'] == 'TPK') {

    // UPDATE BY CHOSSY PRATAMA
    $queryContainerDelivery =
      "SELECT cd.*, mc.*, TO_CHAR(cd.START_STACK,'YYYY-MM-DD HH24:MI:SS') AWAL_PENUMPUKAN, TO_CHAR(cd.TGL_DELIVERY,'YYYY-MM-DD HH24:MI:SS') AKHIR_PENUMPUKAN FROM CONTAINER_DELIVERY cd JOIN MASTER_CONTAINER mc ON cd.NO_CONTAINER = mc.NO_CONTAINER WHERE cd.NO_REQUEST = '$id_req'";
    // END UPDATE  
    $fetchContainerDelivery = $db->query($queryContainerDelivery)->getAll();
    $queryNotaDelivery =
      "SELECT nd.*, ndd.*, (SELECT STATUS FROM ISO_CODE ic WHERE ic.ID_ISO = ndd.ID_ISO) STATUS, (SELECT SIZE_ FROM ISO_CODE ic WHERE ic.ID_ISO = ndd.ID_ISO) SIZE_, (SELECT TYPE_ FROM ISO_CODE ic WHERE ic.ID_ISO = ndd.ID_ISO) TYPE_, TO_CHAR(ndd.START_STACK,'YYYY-MM-DD HH24:MI:SS') AWAL_PENUMPUKAN, TO_CHAR(ndd.END_STACK,'YYYY-MM-DD HH24:MI:SS') AKHIR_PENUMPUKAN FROM NOTA_DELIVERY nd 
    JOIN NOTA_DELIVERY_D ndd ON
    ndd.ID_NOTA = nd.NO_NOTA WHERE nd.NO_REQUEST = '$id_req'";
    $fetchNotaDelivery = $db->query($queryNotaDelivery)->getAll();
    $queryGetAdmin =
      "SELECT TARIF FROM NOTA_DELIVERY nd 
    JOIN NOTA_DELIVERY_D ndd ON
    ndd.ID_NOTA = nd.NO_NOTA WHERE nd.NO_REQUEST = '$id_req' AND ndd.ID_ISO = 'ADM' ";
    $adminComponent = $db->query($queryGetAdmin)->fetchRow();

    $get_vessel = getVessel($fetchDelivery['NM_KAPAL'], $fetchDelivery['VOYAGE'], $fetchDelivery['VOYAGE_IN'], $fetchDelivery['VOYAGE_OUT']);

    // $get_container_list = getContainer(NULL, $fetchDelivery['KD_KAPAL'], $fetchDelivery['VOYAGE_IN'], $fetchDelivery['VOYAGE_OUT'], $fetchDelivery['VOYAGE']);

    $get_iso_code = getIsoCode();

    if (empty($get_iso_code)) {
      $payload_log = '';
      if(empty($payload_uster_save)){
        $payload_log = array(
          "NO_PROFORMA" => $_POST['NO_PROFORMA'],
          "JENIS" => $_POST['JENIS'],
          "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
          "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
        );
      }else{
        $payload_log = $payload_uster_save;
      }
      $notes = "Payment Cash - " . $jenis . " - GAGAL GET ISO CODE";
      $response_uster_save = array(
        'code' => "0",
        'msg' => "Gagal mengambil Iso Code ke Praya, silahkan ulangi Kembali hit process ini."
      );
      insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

      echo json_encode($response_uster_save);
      die();
    }

    $pelabuhan_asal = $fetchDelivery['KD_PELABUHAN_ASAL'];
    $pelabuhan_tujuan = $fetchDelivery['KD_PELABUHAN_TUJUAN'];

    $idRequest = $id_req;
    $trxNumber = $fetchDelivery['NO_NOTA'];
    $paymentDate = $fetchDelivery['TGLNOTA'];
    $invoiceNumber = $fetchDelivery['NO_FAKTUR_MTI'];
    $requestType = 'RECEIVING';
    $parentRequestId = $id_req;
    $parentRequestType = 'RECEIVING';
    $serviceCode = 'REC';
    $vesselId = $fetchDelivery['KD_KAPAL']; //
    $vesselName = $fetchDelivery['NM_KAPAL']; //
    $voyage = empty($fetchDelivery['VOYAGE']) ? '' : $fetchDelivery['VOYAGE']; //
    $voyageIn = empty($fetchDelivery['VOYAGE_IN']) ? '' : $fetchDelivery['VOYAGE_IN']; //
    $voyageOut = empty($fetchDelivery['VOYAGE_OUT']) ? '' : $fetchDelivery['VOYAGE_OUT']; //
    $voyageInOut = empty($voyageIn) || empty($voyageOut) ? '' : $voyageIn . '/' . $voyageOut; //
    $eta = empty($get_vessel['eta']) ? '' : $get_vessel['eta'];
    $etb = empty($get_vessel['etb']) ? '' : $get_vessel['etb'];
    $etd = empty($get_vessel['etd']) ? '' : $get_vessel['etd'];
    $ata = empty($get_vessel['ata']) ? '' : $get_vessel['ata'];
    $atb = empty($get_vessel['atb']) ? '' : $get_vessel['atb'];
    $atd = empty($get_vessel['atd']) ? '' : $get_vessel['atd'];
    $startWork = empty($get_vessel['start_work']) ? '' : $get_vessel['start_work'];
    $endWork = empty($get_vessel['end_work']) ? '' : $get_vessel['end_work'];
    $pol = $pelabuhan_asal;
    $pod = $pelabuhan_tujuan;
    $fpod = $pelabuhan_tujuan;
    $dischargeDate = $get_vessel['discharge_date'];
    $shippingLineName = $fetchDelivery['NM_AGEN']; //
    $customerCode = $fetchDelivery['KD_PELANGGAN']; //
    $customerCodeOwner = '';
    $customerName = $fetchDelivery['EMKL']; //KD_EMKL
    $customerAddress = $fetchDelivery['ALAMAT']; //ALAMAT EMKL
    $npwp = $fetchDelivery['NPWP']; //
    $blNumber = '';
    $bookingNo = $fetchDelivery['NO_BOOKING']; //
    $deliveryDate = '';
    $doNumber = $fetchDelivery['NO_BOOKING'];  //
    // $doDate = '';
    #DEFAULT 06,08,24
    // $tradeType = $fetchDelivery['DI'] == 'D' ? 'I' : 'O';
    $tradeType = "I";
    $customsDocType = "";
    $customsDocNo = "";
    $customsDocDate = "";
    if ((int)$fetchDelivery['TOTAL_TAGIHAN'] > 5000000) {
      $amount = (int)$fetchDelivery['TOTAL_TAGIHAN'] + 10000;
    } else {
      (int)$amount = $fetchDelivery['TOTAL_TAGIHAN'];
    }
    if ($adminComponent) {
      $administration = $adminComponent['TARIF'];
    }
    if (empty($fetchDelivery['PPN'])) {
      $ppn =  'N';
    } else {
      $ppn = 'Y';
    };
    $amountPpn  = (int)$fetchDelivery['PPN']; //
    $amountDpp = (int)$fetchDelivery['TAGIHAN']; //
    if ($fetchDelivery['TAGIHAN'] > 5000000) {
      $amountMaterai = 10000;
    } else {
      $amountMaterai = 0;
    } //
    $approvalDate = empty($fetchDelivery['TGLAPPROVE']) ? '' : $fetchDelivery['TGLAPPROVE'];
    $status = 'PAID';
    $changeDate = $fetchDelivery['TGLNOTA'];
    $charge = 'Y';

    $detailList = array();
    $containerList = array();
    foreach ($fetchContainerDelivery as $k => $v) {
      $container_status = $v['STATUS'] == 'FCL' ? 'FULL' : 'EMPTY';

      $cont = $v["NO_CONTAINER"];
      // $q_get_rec_req = "SELECT NO_REQUEST FROM (SELECT
      //     NO_REQUEST
      //   FROM
      //     HISTORY_CONTAINER hc
      //   WHERE
      //     NO_CONTAINER = '$cont'
      //     AND KEGIATAN = 'GATE IN' --KALAU GAK ADA CARI DARI REALISASI STRIPPING
      //     AND NO_REQUEST LIKE 'REC%'
      //   ORDER BY TGL_UPDATE DESC) WHERE ROWNUM <= 1";
      // $get_rec_req = $db->query($q_get_rec_req)->fetchRow();
      // $rec_req = $get_rec_req['NO_REQUEST'];
      // $q_get_tgl_in = " SELECT TO_CHAR(TGL_IN ,'YYYY-MM-DD HH24:MI:SS') TGLIN FROM GATE_IN WHERE NO_REQUEST = '$rec_req' AND NO_CONTAINER = '$cont'";
      // $get_tgl_in = $db->query($q_get_tgl_in)->fetchRow();

      $reslt = array();
      foreach ($get_iso_code as $key => $value) {
        if (strtoupper($value['type']) == strtoupper($v['TYPE_']) && strtoupper($value['size']) == strtoupper($v['SIZE_'])) {
          array_push($reslt, $value);
        }
      }

      $array_iso_code = array_values($reslt);
      $new_iso = mapNewIsoCode($array_iso_code[0]["isoCode"]);

      array_push($containerList, $v['NO_CONTAINER']);
      array_push(
        $detailList,
        array(
          "detailDescription" => "CONTAINER",
          "containerNo" => $v['NO_CONTAINER'],
          "containerSize" => $v['SIZE_'],
          "containerType" => $v['TYPE_'],
          "containerStatus" => $container_status,
          "containerHeight" => "8.5", //hardcode
          "hz" => empty($v['HZ']) ? 'N' : $v['HZ'],
          "imo" => "N",
          "unNumber" => "",
          "reeferNor" => "N",
          "temperatur" => "",
          "ow" => "",
          "oh" => "",
          "ol" => "",
          "overLeft" => "",
          "overRight" => "",
          "overFront" => "",
          "overBack" => "",
          "weight" => $v['BERAT'],
          "commodityCode" => trim($v['KOMODITI'], " "),
          "commodityName" => trim($v['KOMODITI'], " "),
          "carrierCode" => $fetchDelivery['KD_AGEN'],
          "carrierName" => $fetchDelivery['NM_AGEN'],
          "isoCode" => $new_iso,
          "plugInDate" => "",
          "plugOutDate" => "",
          "ei" => "E",
          "dischLoad" => "",
          "flagOog" => "N",
          // UPDATE BY CHOSSY PRATAMA
          "gateInDate" => $v["AWAL_PENUMPUKAN"],
          "gateOutDate" => $v['AKHIR_PENUMPUKAN'],
          "startDate" => $v["AWAL_PENUMPUKAN"],
          "endDate" => $v['AKHIR_PENUMPUKAN'],
          // END UPDATE
          // "containerDeliveryDate" => "",
          "containerDeliveryDate" => $v['AKHIR_PENUMPUKAN'], //update rifqy 17/4/2024
          "containerLoadingDate" => "",
          "containerDischargeDate" => "",
        )
      );
    }

    $strContList = implode(", ", $containerList);
    $detailPranotaList = array();
    foreach ($fetchNotaDelivery as $k => $v) {
      // Menghilangkan nota materai
      if ($v["KETERANGAN"] == "MATERAI") {
        continue;
      }
      // Pemisahan Container Stack yg muncul di container listnya (edited by Chossy PIP (11962624) - Tonus)
      if ($v["START_STACK"]) {
        $newContainerList = array();
        foreach ($fetchContainerDelivery as $kContainer => $vContainer) {
          if ($vContainer['START_STACK'] == $v['START_STACK'] && $vContainer['TGL_DELIVERY'] == $v['END_STACK'] && $vContainer['STATUS'] == $v['STATUS'] && $vContainer['SIZE_'] == $v['SIZE_'] && $vContainer['TYPE_'] == $v['TYPE_']) {
            array_push($newContainerList, $vContainer['NO_CONTAINER']);
          }
        }
        $newStrContList = implode(", ", $newContainerList);
      } else {
        $newStrContList = $strContList;
      }
      $status = "";
      if (!empty($v['STATUS']) && $v['STATUS'] != "-") {
        $status = $v['STATUS'] == "FCL" ? "FULL" : "EMPTY";
      }
      $type = $v['TYPE_'] == "-" ? "" : $v['TYPE_'];
      $size = $v['SIZE_'] == "-" ? "" : $v['SIZE_'];
      array_push(
        $detailPranotaList,
        array(
          "lineNumber" => $v['LINE_NUMBER'],
          "description" => $v['KETERANGAN'],
          "flagTax" => "Y",
          "componentCode" => $v['KETERANGAN'],
          "componentName" => $v['KETERANGAN'],
          "startDate" => $v['AWAL_PENUMPUKAN'],
          "endDate" => $v['AKHIR_PENUMPUKAN'],
          "quantity" => $v['JML_CONT'],
          "tarif" => $v['TARIF'],
          "basicTarif" => $v['TARIF'],
          "containerList" => $newStrContList,
          "containerSize" => $fetchContainerDelivery[0]['SIZE_'],
          "containerType" => $fetchContainerDelivery[0]['TYPE_'],
          "containerStatus" => $status,
          "containerHeight" => "8.5",
          "hz" => $v['HZ'],
          "ei" => "I",
          "equipment" => "",
          "strStartDate" => $v['AWAL_PENUMPUKAN'],
          "strEndDate" => $v['AKHIR_PENUMPUKAN'],
          "days" => "4", //TGL_END - TGL_START INTERVAL 4 HARI
          "amount" => $v['BIAYA'],
          "via" => "YARD",
          "package" => "",
          "unit" => "BOX",
          "qtyLoading" => "",
          "qtyDischarge" => "",
          "equipmentName" => "",
          "duration" => "",
          "flagTool" => "N",
          "itemCode" => "",
          "oog" => "N",
          "imo" => "",
          "blNumber" => "",
          "od" => "N",
          "dg" => "N",
          "sling" => "N",
          "changeDate" => $fetchDelivery['TGLNOTA'],
          "changeBy" => "Admin Uster"
        )
      );
    }
  } else {
    $payload_log = '';
    if(empty($payload_uster_save)){
      $payload_log = array(
        "NO_PROFORMA" => $_POST['NO_PROFORMA'],
        "JENIS" => $_POST['JENIS'],
        "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
        "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
      );
    }else{
      $payload_log = $payload_uster_save;
    }
    $notes = "Payment Cash - " . $jenis . " - DELIVERY BUKAN KE TPK";
    $response_uster_save = array(
      'code' => "0",
      'msg' => "Tujuan Delivery bukan menuju TPK"
    );
    insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

    echo json_encode($response_uster_save);
    die();
  }
} elseif ($jenis == 'BATAL_MUAT') {
  //Kalau menggunakan NO_PROFORMA, ID_REQUEST jangan dikirim dan sebaliknya
  if (!empty($_POST["NO_PROFORMA"])) {
    $payload_log = '';
    if(empty($payload_uster_save)){
      $payload_log = array(
        "NO_PROFORMA" => $_POST['NO_PROFORMA'],
        "JENIS" => $_POST['JENIS'],
        "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
        "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
      );
    }else{
      $payload_log = $payload_uster_save;
    }
    $id_req = "";
    $proforma = $_POST["NO_PROFORMA"];
    $q_id_req = "SELECT NO_REQUEST FROM NOTA_BATAL_MUAT WHERE NO_NOTA_MTI = '$proforma'";
    $fetch_id_req = $db->query($q_id_req)->fetchRow();
    if (!empty($fetch_id_req) && isset($fetch_id_req)) {
      $id_req = $fetch_id_req["NO_REQUEST"];
    } else {
      $notes = "Payment Cash - " . $jenis . " - NO PROFORMA BATAL MUAT BUKAN MILIK MTI";
      $response_uster_save = array(
        'code' => "0",
        'msg' => "No. Proforma Batal Muat bukan milik MTI"
      );
      insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

      echo json_encode($response_uster_save);
      die();
    }
  }

  if ($charge == "N") {
    $fetchExDelivery = $db->query($queryDelivery)->fetchRow();
    if ($fetchExDelivery['DELIVERY_KE'] == 'TPK') {

      // NOTA TIDAK ADA UNTUK BATAL MUAT TANPA BAYAR
      // $queryNotaExDelivery =
      //   "SELECT * FROM NOTA_DELIVERY nd 
      // JOIN NOTA_DELIVERY_D ndd ON
      // ndd.ID_NOTA = nd.NO_NOTA WHERE nd.NO_REQUEST = '$id_req'";
      // $fetchNotaExDelivery = $db->query($queryNotaExDelivery)->getAll();
      // $queryGetAdmin =
      //   "SELECT TARIF FROM NOTA_DELIVERY nd 
      // JOIN NOTA_DELIVERY_D ndd ON
      // ndd.ID_NOTA = nd.NO_NOTA WHERE nd.NO_REQUEST = '$id_req' AND ndd.ID_ISO = 'ADM' ";
      // $adminComponent = $db->query($queryGetAdmin)->fetchRow();

      $get_vessel = getVessel($payloadBatalMuat['vesselName'], $payloadBatalMuat['voyage'], $payloadBatalMuat['voyageIn'], $payloadBatalMuat['voyageOut']);

      $get_iso_code = getIsoCode();

      if (empty($get_iso_code)) {
        $payload_log = '';
        if(empty($payload_uster_save)){
          $payload_log = array(
            "NO_PROFORMA" => $_POST['NO_PROFORMA'],
            "JENIS" => $_POST['JENIS'],
            "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
            "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
          );
        }else{
          $payload_log = $payload_uster_save;
        }
        $notes = "Payment Cash - " . $jenis . " - GAGAL GET ISO CODE";
        $response_uster_save = array(
          'code' => "0",
          'msg' => "Gagal mengambil Iso Code ke Praya, silahkan ulangi Kembali hit process ini."
        );
        insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

        echo json_encode($response_uster_save);
        die();
      }

      $pelabuhan_asal = $payloadBatalMuat['pelabuhan_asal'];
      $pelabuhan_tujuan = $payloadBatalMuat['pelabuhan_tujuan'];

      $idRequest = $id_req;
      $trxNumber = "";
      $paymentDate = "";
      $invoiceNumber = ""; //NO CHARGE KOSONG
      $requestType = 'LOADING CANCEL - BEFORE GATEIN';
      $parentRequestId = "";
      $parentRequestType = 'LOADING CANCEL - BEFORE GATEIN';
      $serviceCode = 'LCB';
      $jenisBM = "alih_kapal";
      $vesselId = $payloadBatalMuat["vesselId"];
      $vesselName = $payloadBatalMuat["vesselName"];
      $voyage = $payloadBatalMuat['voyage']; //
      $voyageIn = $payloadBatalMuat['voyageIn']; //
      $voyageOut = $payloadBatalMuat['voyageOut']; //
      $voyageInOut = empty($voyageIn) || empty($voyageOut) ? '' : $voyageIn . '/' . $voyageOut; //
      $eta = empty($get_vessel['eta']) ? '' : $get_vessel['eta'];
      $etb = empty($get_vessel['etb']) ? '' : $get_vessel['etb'];
      $etd = empty($get_vessel['etd']) ? '' : $get_vessel['etd'];
      $ata = empty($get_vessel['ata']) ? '' : $get_vessel['ata'];
      $atb = empty($get_vessel['atb']) ? '' : $get_vessel['atb'];
      $atd = empty($get_vessel['atd']) ? '' : $get_vessel['atd'];
      $startWork = empty($get_vessel['start_work']) ? '' : $get_vessel['start_work'];
      $endWork = empty($get_vessel['end_work']) ? '' : $get_vessel['end_work'];
      $pol = $pelabuhan_asal;
      $pod = $pelabuhan_tujuan;
      $dischargeDate = $get_vessel['discharge_date'];
      $shippingLineName = $payloadBatalMuat['nm_agen']; //
      $customerCode = $fetchExDelivery['KD_PELANGGAN']; //
      $customerCodeOwner = '';
      $customerName = $fetchExDelivery['EMKL']; //
      $customerAddress = $fetchExDelivery['ALAMAT']; //
      $npwp = $fetchExDelivery['NPWP']; //
      $blNumber = "";
      $bookingNo = $fetchExDelivery['NO_BOOKING'];
      $deliveryDate = '';
      $doNumber = "";
      // $doDate = '';
      $tradeType = $fetchExDelivery['DI']; //Value : I / O
      $customsDocType = "";
      $customsDocNo = "";
      $customsDocDate = "";
      $amount = 0;
      $administration = 0;
      if (empty($fetchExDelivery['PPN'])) {
        $ppn =  'N';
      } else {
        $ppn = 'Y';
      };
      $amountPpn  = 0;
      $amountDpp = 0;
      $amountMaterai = 0;
      $approvalDate = empty($fetchExDelivery['TGLAPPROVE']) ? '' : $fetchExDelivery['TGLAPPROVE'];
      $status = 'PAID';
      $changeDate = $fetchExDelivery['TGLNOTA'];
      $charge = 'N';

      $detailList = array();
      $containerList = $payloadBatalMuat['cont_list'];
      foreach ($payloadBatalMuat['cont_list'] as $no_cont) {
        $queryContainerExDelivery =
          "SELECT cd.NO_CONTAINER, cd.KOMODITI, mc.SIZE_, mc.TYPE_, mc.NO_BOOKING, vpc.KD_KAPAL, vpc.VOYAGE, vpc.VOYAGE_IN, vpc.VOYAGE_OUT FROM CONTAINER_DELIVERY cd JOIN MASTER_CONTAINER mc ON cd.NO_CONTAINER = mc.NO_CONTAINER JOIN V_PKK_CONT vpc ON mc.NO_BOOKING = vpc.NO_BOOKING WHERE cd.NO_CONTAINER = '$no_cont'";
        $fetchContainerExDelivery = $db->query($queryContainerExDelivery)->fetchRow();

        $get_container_list = getContainer($no_cont, $fetchContainerExDelivery['KD_KAPAL'], $fetchContainerExDelivery['VOYAGE_IN'], $fetchContainerExDelivery['VOYAGE_OUT'], $fetchContainerExDelivery['VOYAGE'], NULL, NULL);

        $reslt = array();
        foreach ($get_iso_code as $key => $value) {
          if (strtoupper($value['type']) == strtoupper($fetchContainerExDelivery['TYPE_']) && strtoupper($value['size']) == strtoupper($fetchContainerExDelivery['SIZE_'])) {
            array_push($reslt, $value);
          }
        }

        $array_iso_code = array_values($reslt);
        $new_iso = mapNewIsoCode($array_iso_code[0]["isoCode"]);

        array_push(
          $detailList,
          array(
            "detailDescription" => "CONTAINER",
            "containerNo" => $fetchContainerExDelivery['NO_CONTAINER'],
            "containerSize" => $fetchContainerExDelivery['SIZE_'],
            "containerType" => $fetchContainerExDelivery['TYPE_'],
            "containerStatus" => "FULL",
            "containerHeight" => "8.5",
            "hz" => empty($fetchContainerExDelivery['HZ']) ? (empty($get_container_list[0]['hz']) ? 'N' : $get_container_list[0]['hz']) : $v['HZ'],
            "imo" => "N",
            "unNumber" => empty($get_container_list[0]['unNumber']) ? '' : $get_container_list[0]['unNumber'],
            "reeferNor" => "N",
            "temperatur" => "",
            "ow" => "",
            "oh" => "",
            "ol" => "",
            "overLeft" => "",
            "overRight" => "",
            "overFront" => "",
            "overBack" => "",
            "weight" => "",
            "commodityCode" => trim($fetchContainerExDelivery['KOMODITI'], " "),
            "commodityName" => trim($fetchContainerExDelivery['KOMODITI'], " "),
            "carrierCode" => $payloadBatalMuat['kd_agen'],
            "carrierName" => $payloadBatalMuat['nm_agen'],
            "isoCode" => $new_iso,
            "plugInDate" => "",
            "plugOutDate" => "",
            "ei" => "E",
            "dischLoad" => "",
            "flagOog" => empty($get_container_list[0]['flagOog']) ? '' : $get_container_list[0]['flagOog'],
            "gateInDate" => "",
            "gateOutDate" => "",
            "startDate" => "",
            "endDate" => "",
            "containerDeliveryDate" => "",
            "containerLoadingDate" => "",
            "containerDischargeDate" => "",
          )
        );
      }
    } else {
      $payload_log = '';
      if(empty($payload_uster_save)){
        $payload_log = array(
          "NO_PROFORMA" => $_POST['NO_PROFORMA'],
          "JENIS" => $_POST['JENIS'],
          "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
          "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
        );
      }else{
        $payload_log = $payload_uster_save;
      }
      $notes = "Payment Cash - " . $jenis . " - BUKAN EX KEGIATAN REPO ATAU JENIS BM BUKAN ALIH KAPAL";
      $response_uster_save = array(
        'code' => "0",
        'msg' => "Nota Batal Muat bukan Ex Kegiatan Repo (Status Gate 2) atau Jenis Batal Muat Bukan Alih Kapal"
      );
      insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

      echo json_encode($response_uster_save);
      die();
    }
  } else {
    $queryBatalMuat = "SELECT
      --	rbm.*,
      rbm.NO_REQUEST, --REQUIRED
      rbm.KD_EMKL, --UNTUK LOG
      rbm.JENIS_BM, --hanya yg alih_kapal
      rbm.KAPAL_TUJU, --NO_BOOKING
      rbm.STATUS_GATE, --IF STATUS GATE 2
      rbm.NO_REQ_BARU,
      rbm.O_VESSEL,
      rbm.BIAYA,
      -- rbm.O_VOYIN,
      -- rbm.O_VOYOUT,
      rbm.DI,
      -- nbm.*,
      nbm.NO_NOTA,
      nbm.NO_FAKTUR_MTI,
      nbm.EMKL,
      nbm.ALAMAT,
      nbm.NPWP,
      nbm.TAGIHAN,
      nbm.TOTAL_TAGIHAN,
      nbm.STATUS,
      nbm.PPN,
      TO_CHAR(nbm.TGL_NOTA ,'YYYY-MM-DD HH24:MI:SS') TGLNOTA,
      vpc.VOYAGE,
      vpc.VOYAGE_IN,
      vpc.VOYAGE_OUT,
      vpc.PELABUHAN_ASAL,
      vpc.PELABUHAN_TUJUAN,
      vpc.NM_AGEN,
      vpc.KD_AGEN,
      vpc.NM_KAPAL,
      vpc.KD_KAPAL,
      vmp.NO_ACCOUNT_PBM KD_PELANGGAN,    
      cbm.NO_REQ_BATAL
    FROM
      REQUEST_BATAL_MUAT rbm 
    LEFT JOIN V_PKK_CONT vpc ON
      rbm.KAPAL_TUJU = vpc.NO_BOOKING
    LEFT JOIN NOTA_BATAL_MUAT nbm ON
      nbm.NO_REQUEST = rbm.NO_REQUEST
    JOIN V_MST_PBM vmp ON
        vmp.KD_PBM = rbm.KD_EMKL
    JOIN CONTAINER_BATAL_MUAT cbm ON
      cbm.NO_REQUEST = rbm.NO_REQUEST
    WHERE rbm.NO_REQUEST = '$id_req'";

    $fetchBatalMuat = $db->query($queryBatalMuat)->fetchRow();

    if (!empty($fetchBatalMuat) && $fetchBatalMuat['STATUS_GATE'] == '2' && $fetchBatalMuat['JENIS_BM'] == 'alih_kapal') {
      $queryContainerBatalMuat =
        "SELECT * FROM CONTAINER_BATAL_MUAT cbm JOIN MASTER_CONTAINER mc ON cbm.NO_CONTAINER = mc.NO_CONTAINER WHERE cbm.NO_REQUEST = '$id_req'";
      $fetchContainerBatalMuat = $db->query($queryContainerBatalMuat)->getAll();
      $queryNotaBatalMuat =
        "SELECT nbm.*, nbmd.*, TO_CHAR(nbm.TGL_NOTA,'YYYY-MM-DD HH24:MI:SS') TGLNOTA FROM NOTA_BATAL_MUAT nbm JOIN NOTA_BATAL_MUAT_D nbmd ON nbm.NO_NOTA = nbmd.ID_NOTA WHERE nbm.NO_REQUEST = '$id_req'";
      $fetchNotaBatalMuat = $db->query($queryNotaBatalMuat)->getAll();
      $queryGetAdmin =
        "SELECT TARIF FROM NOTA_BATAL_MUAT nbm JOIN NOTA_BATAL_MUAT_D nbmd ON nbm.NO_NOTA = nbmd.ID_NOTA WHERE nbm.NO_REQUEST = '$id_req' AND nbmd.ID_ISO = 'ADM' ";
      $adminComponent = $db->query($queryGetAdmin)->fetchRow();

      $get_vessel = getVessel($fetchBatalMuat['NM_KAPAL'], $fetchBatalMuat['VOYAGE'], $fetchBatalMuat['VOYAGE_IN'], $fetchBatalMuat['VOYAGE_OUT']);

      $get_container_list = getContainer(NULL, $fetchBatalMuat['KD_KAPAL'], $fetchBatalMuat['VOYAGE_IN'], $fetchBatalMuat['VOYAGE_OUT'], $fetchBatalMuat['VOYAGE'], "E", "LCB");

      $get_iso_code = getIsoCode();

      if (empty($get_iso_code)) {
        $payload_log = '';
        if(empty($payload_uster_save)){
          $payload_log = array(
            "NO_PROFORMA" => $_POST['NO_PROFORMA'],
            "JENIS" => $_POST['JENIS'],
            "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
            "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
          );
        }else{
          $payload_log = $payload_uster_save;
        }
        $notes = "Payment Cash - " . $jenis . " - GAGAL GET ISO CODE";
        $response_uster_save = array(
          'code' => "0",
          'msg' => "Gagal mengambil Iso Code ke Praya, silahkan ulangi Kembali hit process ini."
        );
        insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

        echo json_encode($response_uster_save);
        die();
      }

      // echo json_encode($get_vessel . ' <<getvessel');
      // echo json_encode($get_container_list . ' <<getcontainerlist');
      // die();

      $pelabuhan_asal = $fetchBatalMuat['PELABUHAN_ASAL'];
      $pelabuhan_tujuan = $fetchBatalMuat['PELABUHAN_TUJUAN'];

      $idRequest = $id_req;
      $trxNumber = $fetchBatalMuat['NO_NOTA'];
      $paymentDate = $fetchBatalMuat['TGLNOTA'];
      $invoiceNumber = $fetchBatalMuat['NO_FAKTUR_MTI'];
      $requestType = 'LOADING CANCEL - BEFORE GATEIN';
      $parentRequestId = $fetchBatalMuat['NO_REQ_BATAL'];
      $parentRequestType = 'LOADING CANCEL - BEFORE GATEIN';
      $serviceCode = 'LCB';
      $jenisBM = $fetchBatalMuat['JENIS_BM'];
      $vesselId = $fetchBatalMuat['KD_KAPAL']; //
      $vesselName = $fetchBatalMuat['NM_KAPAL']; //
      $voyage = empty($fetchBatalMuat['VOYAGE']) ? '' : $fetchBatalMuat['VOYAGE']; //
      $voyageIn = empty($fetchBatalMuat['VOYAGE_IN']) ? '' : $fetchBatalMuat['VOYAGE_IN']; //
      $voyageOut = empty($fetchBatalMuat['VOYAGE_OUT']) ? '' : $fetchBatalMuat['VOYAGE_OUT']; //
      $voyageInOut = empty($voyageIn) || empty($voyageOut) ? '' : $voyageIn . '/' . $voyageOut; //
      $eta = empty($get_vessel['eta']) ? '' : $get_vessel['eta'];
      $etb = empty($get_vessel['etb']) ? '' : $get_vessel['etb'];
      $etd = empty($get_vessel['etd']) ? '' : $get_vessel['etd'];
      $ata = empty($get_vessel['ata']) ? '' : $get_vessel['ata'];
      $atb = empty($get_vessel['atb']) ? '' : $get_vessel['atb'];
      $atd = empty($get_vessel['atd']) ? '' : $get_vessel['atd'];
      $startWork = empty($get_vessel['start_work']) ? '' : $get_vessel['start_work'];
      $endWork = empty($get_vessel['end_work']) ? '' : $get_vessel['end_work'];
      $pol = $pelabuhan_asal;
      $pod = $pelabuhan_tujuan;
      $dischargeDate = $get_vessel['discharge_date'];
      $shippingLineName = $fetchBatalMuat['NM_AGEN']; //
      $customerCode = $fetchBatalMuat['KD_PELANGGAN']; //
      $customerCodeOwner = '';
      $customerName = $fetchBatalMuat['EMKL']; //
      $customerAddress = $fetchBatalMuat['ALAMAT']; //
      $npwp = $fetchBatalMuat['NPWP']; //
      $blNumber = "";
      $bookingNo = $fetchBatalMuat['KAPAL_TUJU'];
      $deliveryDate = '';
      $doNumber = "";
      // $doDate = '';
      $tradeType = $fetchBatalMuat['DI']; //Value : I / O
      $customsDocType = "";
      $customsDocNo = "";
      $customsDocDate = "";
      if ((int)$fetchBatalMuat['TOTAL_TAGIHAN'] > 5000000) {
        $amount = (int)$fetchBatalMuat['TOTAL_TAGIHAN'] + 10000;
      } else {
        (int)$amount = $fetchBatalMuat['TOTAL_TAGIHAN'];
      }
      if ($adminComponent) {
        $administration = $adminComponent['TARIF'];
      }
      if (empty($fetchBatalMuat['PPN'])) {
        $ppn =  'N';
      } else {
        $ppn = 'Y';
      };
      $amountPpn  = (int)$fetchBatalMuat['PPN'];
      $amountDpp = (int)$fetchBatalMuat['TAGIHAN'];
      if ($fetchBatalMuat['TAGIHAN'] > 5000000) {
        $amountMaterai = 10000;
      } else {
        $amountMaterai = 0;
      }
      $approvalDate = empty($fetchBatalMuat['TGLAPPROVE']) ? '' : $fetchBatalMuat['TGLAPPROVE'];
      $status = 'PAID';
      $changeDate = $fetchBatalMuat['TGLNOTA'];
      $charge = 'Y';

      $detailList = array();
      $containerList = array();
      foreach ($fetchContainerBatalMuat as $k => $v) {
        foreach ($get_container_list as $k_container => $v_container) {
          if ($v_container['containerNo']  == $v['NO_CONTAINER']) {
            $_get_container = $v_container;
            break;
          }
        }
        // $array_iso_code = array_values(array_filter($get_iso_code, function ($value) use ($v) {
        //   return strtoupper($value['type']) == strtoupper($v['TYPE_']) && strtoupper($value['size']) == strtoupper($v['SIZE_']);
        // }));

        $reslt = array();
        foreach ($get_iso_code as $key => $value) {
          if (strtoupper($value['type']) == strtoupper($v['TYPE_']) && strtoupper($value['size']) == strtoupper($v['SIZE_'])) {
            array_push($reslt, $value);
          }
        }

        $array_iso_code = array_values($reslt);
        $new_iso = mapNewIsoCode($array_iso_code[0]["isoCode"]);

        array_push($containerList, $v['NO_CONTAINER']);
        array_push(
          $detailList,
          array(
            "detailDescription" => "CONTAINER",
            "containerNo" => $v['NO_CONTAINER'],
            "containerSize" => $v['SIZE_'],
            "containerType" => $v['TYPE_'],
            "containerStatus" => "FULL",
            "containerHeight" => "8.5",
            "hz" => empty($v['HZ']) ? (empty($_get_container['hz']) ? 'N' : $_get_container['hz']) : $v['HZ'],
            "imo" => "N",
            "unNumber" => empty($_get_container['unNumber']) ? '' : $_get_container['unNumber'],
            "reeferNor" => "N",
            "temperatur" => "",
            "ow" => "",
            "oh" => "",
            "ol" => "",
            "overLeft" => "",
            "overRight" => "",
            "overFront" => "",
            "overBack" => "",
            "weight" => "",
            "commodityCode" => trim($v['COMMODITY'], " "),
            "commodityName" => trim($v['COMMODITY'], " "),
            "carrierCode" => $fetchBatalMuat['KD_AGEN'],
            "carrierName" => $fetchBatalMuat['NM_AGEN'],
            "isoCode" => $new_iso,
            "plugInDate" => "",
            "plugOutDate" => "",
            "ei" => "E",
            "dischLoad" => "",
            "flagOog" => empty($_get_container['flagOog']) ? '' : $_get_container['flagOog'],
            "gateInDate" => "",
            "gateOutDate" => "",
            "startDate" => "",
            "endDate" => "",
            "containerDeliveryDate" => "",
            "containerLoadingDate" => "",
            "containerDischargeDate" => "",
          )
        );
      }

      $strContList = implode(", ", $containerList);
      $detailPranotaList = array();
      foreach ($fetchNotaBatalMuat as $k => $v) {

        array_push(
          $detailPranotaList,
          array(
            "lineNumber" => $v['LINE_NUMBER'],
            "description" => $v['KETERANGAN'],
            "flagTax" => "Y",
            "componentCode" => $v['KETERANGAN'],
            "componentName" => $v['KETERANGAN'],
            "startDate" => "",
            "endDate" => "",
            "quantity" => $v['JML_CONT'],
            "tarif" => $v['TARIF'],
            "basicTarif" => $v['TARIF'],
            "containerList" => $strContList,
            "containerSize" => $fetchContainerBatalMuat[0]['SIZE_'],
            "containerType" => $fetchContainerBatalMuat[0]['TYPE_'],
            "containerStatus" => "",
            "containerHeight" => "8.5",
            "hz" => empty($v['HZ']) ? "N" : $v['HZ'],
            "ei" => "I",
            "equipment" => "",
            "strStartDate" => "",
            "strEndDate" => "",
            "days" => "1", //REQUEST DATE - REQUEST DATE
            "amount" => $v['BIAYA'],
            "via" => "YARD",
            "package" => "",
            "unit" => "BOX",
            "qtyLoading" => "",
            "qtyDischarge" => "",
            "equipmentName" => "",
            "duration" => "",
            "flagTool" => "N",
            "itemCode" => "",
            "oog" => "",
            "imo" => "",
            "blNumber" => "",
            "od" => "N",
            "dg" => "N",
            "sling" => "N",
            "changeDate" => $v['TGLNOTA'],
            "changeBy" => "Admin Uster"
          )
        );
      }
    } else {
      $payload_log = '';
      if(empty($payload_uster_save)){
        $payload_log = array(
          "NO_PROFORMA" => $_POST['NO_PROFORMA'],
          "JENIS" => $_POST['JENIS'],
          "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
          "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
        );
      }else{
        $payload_log = $payload_uster_save;
      }
      $notes = "Payment Cash - " . $jenis . " - BUKAN EX KEGIATAN REPO ATAU JENIS BM BUKAN ALIH KAPAL";
      $response_uster_save = array(
        'code' => "0",
        'msg' => "Nota Batal Muat bukan Ex Kegiatan Repo (Status Gate 2) atau Jenis Batal Muat Bukan Alih Kapal"
      );
      insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

      echo json_encode($response_uster_save);
      die();
    }
  }
} else {
  $payload_log = '';
  if (empty($payload_uster_save)) {
    $payload_log = array(
      "NO_PROFORMA" => $_POST['NO_PROFORMA'],
      "JENIS" => $_POST['JENIS'],
      "BANK_ACCOUNT_NUMBER" => $_POST["BANK_ACCOUNT_NUMBER"],
      "PAYMENT_CODE" => $POST["PAYMENT_CODE"]
    );
  } else {
    $payload_log = $payload_uster_save;
  }
  $notes = "Payment Cash - " . $jenis . " - BUKAN STRIPPING, STUFFING, DELIVERY, BATAL MUAT";
  $response_uster_save = array(
    'code' => "0",
    'msg' => "Request ini bukan milik MTI - " . $jenis . " - (Bukan service STRIPPING, STUFFING, DELIVERY, BATAL MUAT)"
  );
  insertPrayaServiceLog($url_uster_save, $payload_log, $response_uster_save, $notes);

  echo json_encode($response_uster_save);
  die();
}

$PNK_PAYMENT_VIA = '';
if (empty($_POST["NO_PROFORMA"])) {
  $PNK_PAYMENT_VIA = "CMS OR BY POSTMAN";
} else {
  $PNK_PAYMENT_VIA = "EBPP";
}

$payload_header = array(
  "PNK_REQUEST_ID" => $id_req,
  "PNK_NO_PROFORMA" => $_POST["NO_PROFORMA"],
  "PNK_CONTAINER_LIST" => $strContList,
  "PNK_JENIS_SERVICE" => $jenis,
  "PNK_JENIS_BATAL_MUAT" => $jenisBM,
  "PNK_PAYMENT_VIA" => $PNK_PAYMENT_VIA,
  "EBPP_CREATED_DATE" => $_POST["EBPP_CREATED_DATE"],
  "idRequest" => $idRequest,
  "billerId" => "00009",
  "trxNumber" => $trxNumber,
  "paymentDate" => $paymentDate,
  "invoiceNumber" => $invoiceNumber,
  "orgId" => (string)PRAYA_ITPK_PNK_ORG_ID,
  "orgCode" => PRAYA_ITPK_PNK_ORG_CODE,
  "terminalId" => (string)PRAYA_ITPK_PNK_TERMINAL_ID,
  "terminalCode" => PRAYA_ITPK_PNK_TERMINAL_CODE,
  "branchId" => (string)PRAYA_ITPK_PNK_BRANCH_ID,
  "branchCode" => (string)PRAYA_ITPK_PNK_BRANCH_CODE,
  "areaTerminal" => (string)PRAYA_ITPK_PNK_AREA_CODE,
  "bankAccountNumber" => $bankAccountNumber,
  "administration" => $administration,
  "requestType" => $requestType,
  "parentRequestId" => $parentRequestId,
  "parentRequestType" => $parentRequestType,
  "serviceCode" => $serviceCode,
  "vesselId" => $vesselId,
  "vesselName" => $vesselName,
  "voyage" => $voyage,
  "voyageIn" => $voyageIn,
  "voyageOut" => $voyageOut,
  "voyageInOut" => $voyageInOut,
  "eta" => $eta,
  "etb" => $etb,
  "etd" => $etd,
  "ata" => $ata,
  "atb" => $atb,
  "atd" => $atd,
  "startWork" => $startWork,
  "endWork" => $endWork,
  "pol" => $pol,
  "pod" => $pod,
  "fpod" => $fpod,
  "dischargeDate" => $dischargeDate,
  "shippingLineName" => $shippingLineName,
  "customerCodeOwner" => $customerCodeOwner,
  "customerCode" => $customerCode,
  "customerName" => $customerName,
  "customerAddress" => $customerAddress,
  "npwp" => $npwp,
  "blNumber" => $blNumber,
  "bookingNo" => $bookingNo,
  "deliveryDate" => $deliveryDate,
  "via" => "YARD",
  "doNumber" => $doNumber,
  // "doDate" => $doDate,
  "tradeType" => $tradeType,
  "customsDocType" => $customsDocType,
  "customsDocNo" => $customsDocNo,
  "customsDocDate" => $customsDocDate,
  "amount" => $amount,
  "ppn" => $ppn,
  "amountPpn" => $amountPpn,
  "amountMaterai" => $amountMaterai,
  "amountDpp" => $amountDpp,
  "approval" => "Y",
  "approvalDate" => $approvalDate,
  "approvalBy" => "Admin Uster",
  "remarkReject" => "",
  "status" => "PAID",
  "changeBy" => "Admin Uster",
  "changeDate" => $changeDate,
  "charge" => $charge
);

if (!empty($paymentCode)) {
  $payment_code = array(
    "paymentCode" => $paymentCode
  );
  $payload_header = array_merge($payload_header, $payment_code);
}

$payload_body = array(
  "detailList" => $detailList,
  "detailPranotaList" => $detailPranotaList
);

$payload = array_merge($payload_header, $payload_body);

// if(!empty($idRequest) && substr($idRequest, 0, 3) == "STP"){
//   $payload_stp_logging = array(
//     "PNK_REQUEST_ID" => $id_req,
//     "PNK_NO_PROFORMA" => $_POST["NO_PROFORMA"],
//     "PNK_CONTAINER_LIST" => $strContList,
//     "PNK_JENIS_SERVICE" => $jenis,
//     "PNK_JENIS_BATAL_MUAT" => $jenisBM,
//     "PNK_PAYMENT_VIA" => $PNK_PAYMENT_VIA,
//     "EBPP_CREATED_DATE" => $_POST["EBPP_CREATED_DATE"],
//     "detailList" => $containerListLog
//   );
//   $notes_stp_logging = "STP - " . $idRequest . " - CONTAINER LOGGING";
//   $response_uster_save_logging_test = array(
//     "code" => '1',
//     "msg" => 'Success'
//   );

//   // LOGGING FOR PAYTHRU PERP_STRIP
//   insertPrayaServiceLog($url_uster_save, $payload_stp_logging, $response_uster_save_logging_test, $notes_stp_logging);
// }

// echo json_encode($payload);
// die();

$response_uster_save = sendDataFromUrlTryCatch($payload, $url_uster_save, 'POST', getTokenPraya());

$notes = $jenis == "DELIVERY" ? "Payment Cash - " . $jenis . " EX REPO" : "Payment Cash - " . $jenis;

// var_dump($response_uster_save);
// echo "<<uster_save";

$first_char_http_code = substr(strval($response_uster_save['httpCode']), 0, 1);

if ($first_char_http_code == 5 || $first_char_http_code == 1) {
  $decodedRes = json_decode($response_uster_save["response"]["msg"]) ? json_decode($response_uster_save["response"]["msg"]) : $response_uster_save["response"]["msg"];
  $defaultRes = "Service is Unavailable, please try again (HTTP Error Code : " . $response_uster_save["httpCode"] . ")";
  $response_uster_save_logging = array(
    "code" => "0",
    "msg" => $defaultRes,
    "response" => $decodedRes
  );

  insertPrayaServiceLog($url_uster_save, $payload_header, $response_uster_save_logging, $notes);

  echo json_encode($response_uster_save_logging);
  die();
}

$response_uster_save_decode = json_decode($response_uster_save['response'], true);

// echo var_dump(json_encode($response_uster_save) . ' <<ustSave');
// echo var_dump(json_encode($response_uster_save_decode) . ' <<ustSaveDecode');

$response_uster_save_logging = $response_uster_save_decode["code"] == 0 ? array(
  "code" => $response_uster_save_decode['code'],
  "msg" => $response_uster_save_decode['msg']
) : $response_uster_save_decode;

if(!empty($idRequest) && substr($idRequest, 0, 3) == "STP"){
  $payload_stp_logging = array(
    "PNK_REQUEST_ID" => $id_req,
    "PNK_NO_PROFORMA" => $_POST["NO_PROFORMA"],
    "PNK_CONTAINER_LIST" => $strContList,
    "PNK_JENIS_SERVICE" => $jenis,
    "PNK_JENIS_BATAL_MUAT" => $jenisBM,
    "PNK_PAYMENT_VIA" => $PNK_PAYMENT_VIA,
    "EBPP_CREATED_DATE" => $_POST["EBPP_CREATED_DATE"],
    "detailList" => $containerListLog
  );
  $notes_stp_logging = "STP - " . $idRequest . " - CONTAINER LOGGING";

  // LOGGING FOR PAYTHRU PERP_STRIP
  insertPrayaServiceLog($url_uster_save, $payload_stp_logging, $response_uster_save_logging, $notes_stp_logging);
}

insertPrayaServiceLog($url_uster_save, $payload_header, $response_uster_save_logging, $notes);

if ($response_uster_save['response']['code'] == 0) {
  echo json_encode($response_uster_save_logging);
  die();
} else {
  echo $response_uster_save['response'];
  die();
}

function getVessel($vessel, $voy, $voyIn, $voyOut)
{

  $vessel = str_replace(" ", "+", $vessel);

  try {
    $url = PRAYA_API_TOS . "/api/getVessel?pol=" . PRAYA_ITPK_PNK_PORT_CODE . "&eta=1&etd=1&orgId=" . PRAYA_ITPK_PNK_ORG_ID . "&terminalId=" . PRAYA_ITPK_PNK_TERMINAL_ID . "&search=$vessel";
    $json = getDatafromUrl($url);
    $json = json_decode($json, true);

    // echo $voy . ' | ' . $voyIn . ' | ' . $voyOut;
    // echo json_encode($json);

    if ($json['code'] == 1) {
      $vessel_resp = '';
      foreach ($json['data'] as $k => $v) {
        if ($v['voyage'] == $voy && $v['voyage_in'] == $voyIn && $v['voyage_out'] == $voyOut) {
          $vessel_resp = $v;
        }
      }
      // echo json_encode($vessel_resp);
      return $vessel_resp;
    } else {
      echo $json['msg'];
    }
  } catch (Exception $ex) {
    echo $ex->getMessage();
  }
}

function getContainer($no_container, $vessel_code, $voyage_in, $voyage_out, $voy, $ei, $serviceCode)
{

  // echo $no_container  . ' | ' .  $vessel_code  . ' | ' .  $voyage_in  . ' | ' .  $voyage_out  . ' | ' .  $voy;

  try {
    $payload = array(
      "orgId" => PRAYA_ITPK_PNK_ORG_ID,
      "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID,
      "vesselId" => $vessel_code,
      "voyageIn" => $voyage_in,
      "voyageOut" => $voyage_out,
      "voyage" => $voy,
      "portCode" => PRAYA_ITPK_PNK_PORT_CODE,
      "ei" => $ei,
      "containerNo" => $no_container,
      "serviceCode" => $serviceCode
    );

    $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/containerList", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);

    if ($response['code'] == 1 && !empty($response["data"])) {
      return $response['data'];
    }
  } catch (Exception $ex) {
    echo $ex->getMessage();
  }
}

function getStuffingContainer($no_container)
{
  try {
    $payload = array(
      "orgId" => PRAYA_ITPK_PNK_ORG_ID,
      "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID,
      "containerNo" => $no_container
    );

    $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/stuffingContainerList", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);

    if ($response['code'] == 1 && !empty($response["dataRec"])) {
      return $response['dataRec'];
    }
  } catch (Exception $ex) {
    echo $ex->getMessage();
  }
}

function getIsoCode()
{

  try {
    $searchFieldColumn = array(
      "size" => "",
      "type" => "",
      "height" => "",
    );

    $payload = array(
      "terminalCode" => PRAYA_ITPK_PNK_TERMINAL_CODE,
      "searchFieldColumn" => $searchFieldColumn,
      "page" => 1,
      "record" => 1000
    );

    // echo json_encode($payload);
    // echo "<<payload";

    $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/isoCodeList", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);

    // echo json_encode($response);die;

    if ($response['code'] == 1 && !empty($response["dataRec"])) {
      return $response['dataRec'];
    }
  } catch (Exception $ex) {
    echo $ex->getMessage();
  }
}

function mapNewIsoCode($iso){
  $new_iso = "";

  switch ($iso) {
    case "42B0":
      $new_iso = "4500"; //DRY 40
      break;
    case "2650":
      $new_iso = "22U1"; //OT 20
      break;
    case "42U0":
      $new_iso = "45G1"; //OT 40
      break;
    case "4260":
      $new_iso = "45G1"; //FLT 40
      break;
    // Penambahan iso code baru untuk container 21ft (Chossy PIP (11962624))
    case "2280":
      $new_iso = "22G1"; //DRY 20
      break;
    // End Penambahan  
    default:
      $new_iso = $iso;  
  };

  return $new_iso;
}