<?php
// Connection to USTER
$db   = getDB("storage");

// takes raw data from the request 
$json = file_get_contents('php://input');
// Converts it into a PHP object 
$payload_uster_save = json_decode($json, true);

$jenis = $payload_uster_save["JENIS"];
$id_req = $payload_uster_save["ID_REQUEST"];
$bankAccountNumber = $payload_uster_save["BANK_ACCOUNT_NUMBER"];
$paymentCode = $payload_uster_save["PAYMENT_CODE"];

$lunas = false;

switch ($jenis) {
  case 'RECEIVING':
    $query   = "SELECT TANGGAL_LUNAS,LUNAS FROM NOTA_RECEIVING WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();

    if (!empty($result['TANGGAL_LUNAS']) && $result['LUNAS'] == 'YES') {
      $lunas = true;
    }
    break;
  case 'STUFFING':
    $query   = "SELECT TANGGAL_LUNAS,LUNAS FROM NOTA_STUFFING WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();
    $query_pnkn   = "SELECT TANGGAL_LUNAS,LUNAS FROM NOTA_PNKN_STUF WHERE NO_REQUEST ='$id_req'";
    $result_pnkn = $db->query($query_pnkn)->fetchRow();

    if ((!empty($result['TANGGAL_LUNAS']) && $result['LUNAS'] == 'YES') && !empty($result_pnkn['TANGGAL_LUNAS']) && $result_pnkn['LUNAS'] == 'YES') {
      $lunas = true;
    }
    break;
  case 'STRIPPING':
  case 'PERP_STRIP':
    $query   = "SELECT TANGGAL_LUNAS,LUNAS FROM NOTA_STRIPPING WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();

    if (!empty($result['TANGGAL_LUNAS']) && $result['LUNAS'] == 'YES') {
      $lunas = true;
    }
    break;
  case 'DELIVERY':
    $query   = "SELECT TANGGAL_LUNAS,LUNAS FROM NOTA_DELIVERY WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();

    if (!empty($result['TANGGAL_LUNAS']) && $result['LUNAS'] == 'YES') {
      $lunas = true;
    }
    break;
  case 'BATAL_MUAT':
    $query   = "SELECT TGL_LUNAS,LUNAS FROM NOTA_BATAL_MUAT WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();

    if (!empty($result['TGL_LUNAS']) && $result['LUNAS'] == 'YES') {
      $lunas = true;
    }
    break;
  default:
    $lunas = false;
    break;
}

if (!empty($jenis)) {
  $query   = "SELECT TANGGAL_LUNAS,LUNAS FROM NOTA_RELOKASI_MTY WHERE NO_REQUEST ='$id_req'";
  $result = $db->query($query)->fetchRow();

  if (!empty($result['TANGGAL_LUNAS']) && $result['LUNAS'] == 'YES') {
    $lunas = true;
  }
}


if ($lunas) {
  $response = array(
    "code" => "1",
    "msg" => "Request Lunas"
  );
} else {
  $response = array(
    "code" => "0",
    "msg" => "Request Belum lunas"
  );
}

echo json_encode($response);
