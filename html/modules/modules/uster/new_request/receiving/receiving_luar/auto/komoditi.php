<?php
$komo			= strtoupper($_GET["term"]);

$db 			= getDB("storage");

	
$query 			= "select KD_COMMODITY, NM_COMMODITY from BILLING.MASTER_COMMODITY WHERE UPPER(NM_COMMODITY) LIKE '%$komo%'";

//echo $query;
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>