<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select KD_COMMODITY, NM_COMMODITY from MASTER_COMMODITY WHERE UPPER(NM_COMMODITY) LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>