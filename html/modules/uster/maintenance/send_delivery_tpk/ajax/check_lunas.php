<?php
// Connection to USTER
$db 	= getDB("storage");

// takes raw data from the request 
$json = file_get_contents('php://input');
// Converts it into a PHP object 
$payload_uster_save = json_decode($json, true);

$jenis = $payload_uster_save["JENIS"];
$id_req = $payload_uster_save["ID_REQUEST"];
$bankAccountNumber = $payload_uster_save["BANK_ACCOUNT_NUMBER"];
$paymentCode = $payload_uster_save["PAYMENT_CODE"];

$lunas = false;

//query from nota_receiving
if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_RECEIVING WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();
  
    if(!empty($result['TANGGAL_LUNAS'])) {
      $lunas = true;
    }
}
  //query from nota_stuffing
if(empty($jenis)) {
      $query   = "SELECT * FROM NOTA_STUFFING WHERE NO_REQUEST ='$id_req'";
      $result = $db->query($query)->fetchRow();
  
    if(!empty($result['TANGGAL_LUNAS'])) {
      $lunas = true;
    }
}
  // query from pnkn stuffing
if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_PNKN_STUF WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();
  
    if(!empty($result['TANGGAL_LUNAS'])) {
      $lunas = true;
    }
}
  //query from nota_stripping
if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_STRIPPING WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();
  
    if(!empty($result['TANGGAL_LUNAS'])) {
      $lunas = true;
    }
}
  
  //query from nota_delivery
if(!empty($jenis)) {
    $query   = "SELECT * FROM NOTA_DELIVERY WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();
  
    if(!empty($result['TANGGAL_LUNAS'])) {
      $lunas = true;
    }
}
  //query from nota_batal_muat
if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_BATAL_MUAT WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();
  
    if(!empty($result['TANGGAL_LUNAS'])) {
      $lunas = true;
    }
}
  // query from relokasi_mty
if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_RELOKASI_MTY WHERE NO_REQUEST ='$id_req'";
    $result = $db->query($query)->fetchRow();
  
    if(!empty($result['TANGGAL_LUNAS'])) {
      $lunas = true;
    }
}
  

if($lunas){
    $response = array(
        "code" => "1",
        "msg" => "Request Lunas"
    );
}else{
    $response = array(
        "code" => "0",
        "msg" => "Request Belum lunas"
    );
}
echo json_encode($response);