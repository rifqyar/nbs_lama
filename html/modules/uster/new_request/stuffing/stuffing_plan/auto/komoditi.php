<?php
$komo			= strtoupper($_GET["term"]);

$db 			= getDB("storage");

	
$query 			= "select * from PETIKEMAS_CABANG.MST_COMMODITY@DBINT_KAPALPROD WHERE NM_COMMODITY LIKE '%$komo%' ORDER BY NM_COMMODITY ASC";

//echo $query;
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>