<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT b.ID_VS ID, a.NAMA_VESSEL NAMA, b.VOYAGE VOYAGE FROM MASTER_VESSEL a, VESSEL_SCHEDULE b WHERE b.ID_VES = a.KODE_KAPAL AND a.NAMA_VESSEL LIKE '$param%' AND b.STATUS = 'AKTIF'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>