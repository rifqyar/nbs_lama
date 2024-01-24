<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT NO_UKK ID, NM_KAPAL NAMA, VOYAGE_IN VOYAGE FROM rbm_h WHERE NM_KAPAL LIKE '$param%' OR VOYAGE_IN LIKE '$param%' ORDER BY DATE_INSERT DESC";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>