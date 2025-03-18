<?php
$size	= $_POST["SIZE"]; 
$type		= $_POST["TYPE"]; 
$status		= $_POST["STATUS"]; 
$id_cont             = $_POST["NO_CONT"]; 
$hz	= $_POST["HZ"]; 
$no_req	= $_POST["ID_REQ"]; 
$date=date('Y/m/d h:i:s');
$db=getDB();
$query="INSERT INTO TB_REQ_DELIVERY_CONT (ID_REQ,ID_BARANG,HZ,ID_CONTAINER) 
		VALUES ('$no_req',(SELECT KODE_BARANG FROM MASTER_BARANG WHERE UKURAN='$size' AND TYPE='$type' AND STATUS='$status'),'$hz','$id_cont')";
//print_r($query);die;		

$result_q=$db->query($query);
?>