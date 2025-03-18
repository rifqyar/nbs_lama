<?php
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
$no_bundle = $_GET['no_bundle'];
$id_group = $_SESSION["ID_GROUP"];
$page = isset($_POST['page']) ? $_POST['page'] : 1;  // get the requested page
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx']) ? $_POST['sidx'] : 'id_bprp'; // get index row - i.e. user click to sort

if (!$sidx) $sidx = 1;
$db = getDB("storage");
//Menampilkan NO_FAKTUR_MTI
$query = "SELECT * FROM (
		SELECT * FROM (
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'RECEIVING' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN ,STATUS, KD_EMKL 
		FROM nota_receiving WHERE STATUS <> 'BATAL' 
		UNION
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, CASE WHEN STATUS = 'PERP' THEN 'PERP_PNK' ELSE 'STUFFING' END KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL 
		FROM nota_stuffing WHERE  STATUS <> 'BATAL'
		UNION 
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, CASE WHEN SUBSTR(NO_NOTA,0,2) ='03' THEN 'STRIPPING' ELSE 'PERP_STRIP' end KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN ,STATUS,KD_EMKL FROM nota_stripping 
		WHERE STATUS <> 'BATAL'
		UNION
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL,  CASE WHEN STATUS = 'PERP' THEN 'PERP_DEV' ELSE 'DELIVERY' END KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN ,STATUS,KD_EMKL FROM nota_delivery WHERE STATUS <> 'BATAL'
		UNION
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL,  'RELOKASI' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL  FROM nota_relokasi WHERE STATUS <> 'BATAL'
		UNION
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'BATAL_MUAT' KEGIATAN, TGL_NOTA TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_batal_muat WHERE STATUS <> 'BATAL'
		UNION
		SELECT NO_NOTA,NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'RELOK_MTY' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_relokasi_mty WHERE STATUS <> 'BATAL'
		UNION
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'DEL_PNK' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_pnkn_del WHERE STATUS <> 'BATAL'
		UNION
		SELECT NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'STUF_PNK' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_pnkn_stuf WHERE STATUS <> 'BATAL')
		ORDER BY TGL_NOTA_1 DESC) WHERE ROWNUM < 100 ";

if (isset($_GET['idreq'])) {
	$idreq = $_GET['idreq'];
	$query .= " AND NO_REQUEST = '$idreq'";
}
$res = $db->query($query)->fetchRow();
$count = $res[NUMBER_OF_ROWS];



if ($count > 0) {
	$total_pages = ceil($count / $limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page = $total_pages;
$start = $limit * $page - $limit; // do not put $limit*($page - 1)	

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i = 0;

$query = "SELECT * FROM (SELECT * FROM (
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'RECEIVING' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN, STATUS,KD_EMKL FROM nota_receiving WHERE STATUS <> 'BATAL'
			UNION
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, CASE WHEN STATUS = 'PERP' THEN 'PERP_PNK' ELSE 'STUFFING' END KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_stuffing WHERE  STATUS <> 'BATAL'
			UNION 
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL,  CASE WHEN SUBSTR(NO_NOTA,0,2) ='03' THEN 'STRIPPING' ELSE 'PERP_STRIP' end KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS ,KD_EMKL FROM nota_stripping WHERE STATUS <> 'BATAL'
			UNION
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL,  CASE WHEN STATUS = 'PERP' THEN 'PERP_DEV' ELSE 'DELIVERY' END  KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN ,STATUS,KD_EMKL FROM nota_delivery WHERE STATUS <> 'BATAL'
			UNION
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL,  'RELOKASI' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN ,STATUS,KD_EMKL FROM nota_relokasi WHERE STATUS <> 'BATAL'
			UNION
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'BATAL_MUAT' KEGIATAN, TGL_NOTA TGL_NOTA_1,TOTAL_TAGIHAN ,STATUS,KD_EMKL FROM nota_batal_muat WHERE STATUS <> 'BATAL'
			UNION
			SELECT PAYMENT_CODE , NO_NOTA,NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'RELOK_MTY' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_relokasi_mty WHERE STATUS <> 'BATAL'
			UNION
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'DEL_PNK' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_pnkn_del WHERE STATUS <> 'BATAL'
			UNION
			SELECT PAYMENT_CODE , NO_NOTA, NO_FAKTUR, NO_REQUEST, NO_NOTA_MTI, NO_FAKTUR_MTI, EMKL, 'STUF_PNK' KEGIATAN, TGL_NOTA_1,TOTAL_TAGIHAN,STATUS,KD_EMKL FROM nota_pnkn_stuf WHERE STATUS <> 'BATAL')
			ORDER BY TGL_NOTA_1 DESC) WHERE ROWNUM < 100 ";

if (isset($_GET['idreq'])&& $_GET['idreq'] != '') {
	$idreq = $_GET['idreq'];
	$query .= " AND NO_REQUEST = '$idreq' OR NO_NOTA = '$idreq'";
}
$res = $db->query($query);

while ($row = $res->fetchRow()) {

	$act = '';
	// $cek_nota = "SELECT COUNT(*) AS CEK  FROM (SELECT * FROM (
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,  TGL_NOTA_1 FROM nota_receiving WHERE STATUS <> 'BATAL'
	// 	UNION
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,  TGL_NOTA_1  FROM nota_stuffing WHERE  STATUS <> 'BATAL'
	// 	UNION 
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,   TGL_NOTA_1 FROM nota_stripping WHERE STATUS <> 'BATAL'
	// 	UNION
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,  TGL_NOTA_1  FROM nota_delivery WHERE STATUS <> 'BATAL'
	// 	UNION
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,   TGL_NOTA_1 FROM nota_relokasi WHERE STATUS <> 'BATAL'
	// 	UNION
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,   TGL_NOTA TGL_NOTA_1 FROM nota_batal_muat WHERE STATUS <> 'BATAL'
	// 	UNION
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,   TGL_NOTA_1 FROM nota_relokasi_mty WHERE STATUS <> 'BATAL'
	// 	UNION
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,   TGL_NOTA_1 FROM nota_pnkn_del WHERE STATUS <> 'BATAL'
	// 	UNION
	// 	SELECT  NO_REQUEST,  NO_FAKTUR_MTI,  TGL_NOTA_1 FROM nota_pnkn_stuf WHERE STATUS <> 'BATAL')
	// 	ORDER BY TGL_NOTA_1 DESC) WHERE NO_REQUEST = '".$row[NO_REQUEST]."' AND NO_FAKTUR_MTI IS NOT NULL";
	$cek_nota = "select count(*) cek from itpk_nota_header where trx_number='" . $row[NO_FAKTUR] . "'";
	$rnota 	  = $db->query($cek_nota)->fetchRow();
	if ($rnota['CEK']  == 0) {
		// if($_SESSION["ID_GROUP"]=='L' || $_SESSION["ID_GROUP"]=='J' || $_SESSION["ID_GROUP"]=='P' || $_SESSION["ID_GROUP"]=='K')
		// {
		// 	$act="<button title='Payment cash' onclick='pay(\"".$row[NO_NOTA]."\",\"".$row[NO_REQUEST]."\",\"".$row[KEGIATAN]."\",\"".$row[TOTAL_TAGIHAN]."\",\"".$row[KD_EMKL]."\",\"".$row[STATUS]."\",\"".$row[NO_NOTA_MTI]."\",\"".$row[TGL_NOTA_1]."\")'><img src='images/invo.png'></button>";			
		// }
		// else
		// {	
		// 	$act="<font color='red'><i>not yet paid</i></font>";
		// }
		if ($row['PAYMENT_CODE'] != null) {
			$req = $row['NO_REQUEST'];
			$kegiatan = $row['KEGIATAN'];
			$act = "<a href='javascript:void(0)' onclick=\"window.open('/uster.billing.paymentcash.print/print_kode_bayar?no_req=$req&kegiatan=$kegiatan'); return false;\" title='Cetak Kode Bayar SAP'><img style='width:20px' src='images/printbig1.png'></a>";
		} else {
			$act = "<font color='red'><i>not yet paid</i></font>";
		}
	} else {
		$no_nota = $row[NO_NOTA];
		$url = SAP_URL . "PrintNota/CetakNota?ze=$no_nota&ck=6200";
		$act = "<a href='#' onclick='return print(\"" . $row['NO_REQUEST'] . "\",\"" . $row['KEGIATAN'] . "\",\"" . $row['TGL_NOTA_1'] . "\")' title='cetak nota'><img src='images/document-excel.png'></a>";
		
		if ($row['PAYMENT_CODE'] != null) {
			$act .= "<a href='javascript:void(0)' onclick=\"window.open('$url'); return false;\" title='cetak nota SAP'><img style='width:20px' src='images/invoice.png'></a>";
		}
		
		// $act.="<button title='Sync Payment' onclick='sync_payment(\"".$row[NO_REQUEST]."\",\"".$row[NO_NOTA]."\",\"".$row[KEGIATAN]."\")'><img width=\"15\" height=\"15\" src='images/sync.png'></button>";
		//ESB Implementasi Add Button
		// $act.="<button title='Resend ESB' onclick='esb_resend(\"".$row[NO_FAKTUR]."\",\"".$row[NO_NOTA]."\",\"".$row[KEGIATAN]."\")'><img width=\"15\" height=\"15\" src='images/sync_ict.png'></button>";
	}

	$responce->rows[$i]['id'] = $row[NO_NOTA];
	//Menampilkan NO_FAKTUR_MTI 
	$responce->rows[$i]['cell'] = array($act,  $row[NO_NOTA], $row[NO_NOTA_MTI], $row[NO_FAKTUR], $row[NO_REQUEST], $row[EMKL], $row[KEGIATAN], $row[TGL_NOTA_1], number_format($row[TOTAL_TAGIHAN]));


	$i++;
}
echo json_encode($responce);
die();
