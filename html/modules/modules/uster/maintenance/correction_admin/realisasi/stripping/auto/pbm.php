<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select ID, NAMA from MASTER_PBM WHERE NAMA LIKE '%$nama%' ";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>