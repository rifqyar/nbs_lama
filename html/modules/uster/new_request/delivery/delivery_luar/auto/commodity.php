<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select KD_COMMODITY, NM_COMMODITY from BILLING_NBS.MASTER_COMMODITY WHERE UPPER(NM_COMMODITY) LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>