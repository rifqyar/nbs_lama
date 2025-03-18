<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
//echo "SELECT b.NO_UKK ID, b.NM_KAPAL NAMA, b.VOYAGE_IN VOYAGE FROM TR_VESSEL_SCHEDULE_ICT b WHERE b.NM_KAPAL LIKE '$param%' AND b.STATUS = 'AKTIF'";
$query 			= "SELECT b.NO_UKK ID, b.NM_KAPAL NAMA, b.VOYAGE_IN VOYAGE FROM TR_VESSEL_SCHEDULE_ICT b WHERE b.NM_KAPAL LIKE '$param%' AND b.STATUS = 'AKTIF'";
//$query = 'SELECT * FROM DUAL';
$result			= $db->query($query);
$row			= $result->getAll();		

//print_r($row);

echo json_encode($row);


?>