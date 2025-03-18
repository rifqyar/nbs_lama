<?php
$size	= $_POST["SIZE"]; 
$type	= $_POST["TYPE"]; 
$status	= $_POST["STATUS"]; 
$no_req	= $_POST["ID_REQ"]; 
$id_dtl	= $_POST["ID_DETAILS"];
$brg = $_POST["BARANG"];
$jumlah	= $_POST["JML_CONT"];
$jumlah_palka = $_POST["JML_PALKA"];

$db=getDB();

if($brg=="container")
{
	$query3 = "INSERT INTO GLC_PRODUKSI (ID_DETAILS,ID_REQ,ID_CONT,JUMLAH_CONT) 
			VALUES ('$id_dtl','$no_req',(SELECT KODE_BARANG FROM MASTER_BARANG WHERE UKURAN='$size' AND TYPE='$type' AND STATUS='$status'),'$jumlah')";
	//print_r($query);die;		
	$db->query($query3);
}
else
{
	$query3 = "INSERT INTO GLC_PRODUKSI (ID_DETAILS,ID_REQ,ID_CONT,JUMLAH_CONT) 
			VALUES ('$id_dtl','$no_req','PALKA','$jumlah_palka')";
	//print_r($query);die;		
	$db->query($query3);
}
?>