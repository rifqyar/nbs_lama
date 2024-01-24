<?php
$size	= $_POST["SIZE"]; 
$type		= $_POST["TYPE"]; 
$status		= $_POST["STATUS"]; 
$jumlah             = $_POST["JUMLAH"]; 
$hz	= $_POST["HZ"]; 
$no_req	= $_POST["ID_REQ"]; 
$date=date('Y/m/d h:i:s');
$db=getDB();
$query="INSERT INTO TB_REQ_DELIVERY_D (ID_REQ,ID_CONT,HZ,JUMLAH) 
		VALUES ('$no_req',(SELECT KODE_BARANG FROM MASTER_BARANG WHERE UKURAN='$size' AND TYPE='$type' AND STATUS='$status'),'$hz','$jumlah')";
		

$result_q=$db->query($query);
?>