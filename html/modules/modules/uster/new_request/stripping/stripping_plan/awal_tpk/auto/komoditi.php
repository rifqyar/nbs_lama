<?php
$komo			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
$db2 			= getDB("ora");

	
$query 			= "select * from PETIKEMAS_CABANG.V_MST_COMMODITY WHERE NM_COMMODITY LIKE '%$komo%' ORDER BY NM_COMMODITY ASC";

//echo $query;
$result			= $db2->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>