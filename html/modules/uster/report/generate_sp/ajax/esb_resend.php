<?php
//ESB Implementasi Include Class
include 'esbhelper/class_lib.php';
$esb = new esbclass();
//===END ESB===

$id_nota=$_POST['IDN'];
$trx_number=$_POST['NOTA'];
$jenis = $_POST['KG'];
//echo $jenis; die();
// $db=getDb('dbint');
$db=getDb('storage');

$sql_header = "select distinct * from itpk_nota_header 
where status in ('2','4a','4b')
    and status_nota=0
    and trx_number = '". $trx_number ."'";
$rsheader = $db->query($sql_header);
$rs = $rsheader->fetchRow();

// $sql_header = "select distinct * from itpk_nota_header where trx_number = '". $trx_number ."'";
// $rsheader = $db->query($sql_header);
// $rs = $rsheader->fetchRow();

$sql_detail = "select * from itpk_nota_detail where trx_number = '" . $trx_number . "'";
$rsLine = $db->query($sql_detail);
$rsLines = $rsLine->GetAll();

$kirimesb = $esb->usterAr($rs,$rsLines);
$kirimEsbResponese = json_decode($kirimesb, true);

$response = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
$erroMessage = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];


if ($response == "S"){

$kirimreceipt = $esb->usterReceipt($rs);
$kirimEsbreceiptResponese = json_decode($kirimreceipt, true);
$responseReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
$erroMessageReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];

if ($responseReceipt == "S") {
    $kirimApply = $esb->usterApply($rs,$user);
} else {
    // $del_sql="DELETE FROM ITPK_NOTA_HEADER where TRX_NUMBER = '". $trx_number. "'";
    // $deleteStaging = $db->query($del_sql);
    $erroMessage = $erroMessageReceipt;
}
} else {
// $del_sql="DELETE FROM ITPK_NOTA_HEADER where TRX_NUMBER = '". $trx_number. "'";
// $deleteStaging = $db->query($del_sql);
}
echo $erroMessage;
die;
//END ESB


?>