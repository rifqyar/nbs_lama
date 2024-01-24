<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select a.NAMA_VESSEL, b.NO_BOOKING, b.VOYAGE from vessel a, voyage b WHERE a.KODE_VESSEL = b.KODE_VESSEL AND a.NAMA_VESSEL LIKE '%$nama%' ";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>