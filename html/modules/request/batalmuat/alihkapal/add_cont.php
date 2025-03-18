<?php
$size		= $_POST["SIZE"]; 
$type		= $_POST["TYPE"]; 
$status		= $_POST["STATUS"]; 
$no_cont 	= $_POST["NO_CONT"]; 
$hz			= $_POST["HZ"]; 
$no_req		= $_POST["ID_REQ"]; 

$db=getDB();
$query="INSERT INTO BH_DETAIL_REQUEST (ID_REQUEST,ID_BARANG,NO_CONTAINER,HAZZARD) 
		VALUES ('$no_req',(SELECT KODE_BARANG m FROM (SELECT KODE_BARANG FROM MASTER_BARANG WHERE UKURAN='$size' AND TYPE='$type' AND STATUS='$status' and HEIGHT_CONT<>'BIASA') m where rownum=1),upper('$no_cont'),'$hz')";
//print_r($query);die;

$result_q=$db->query($query);
?>