<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT NO_UKK ID, NM_KAPAL NAMA, VOYAGE_IN VOYAGE FROM (SELECT NO_UKK, NM_KAPAL, VOYAGE_IN FROM rbm_h WHERE NM_KAPAL LIKE '$param%' order by NO_UKK DESC) WHERE ROWNUM<5";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>