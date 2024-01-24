<?php
$pel		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= " SELECT PELABUHAN, ID_PEL, NAMA_NEG FROM MASTER_PELABUHAN WHERE PELABUHAN LIKE '%$pel%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>