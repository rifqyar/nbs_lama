<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("dblocal");
	
$query 			= " SELECT NO_CONTAINER, UKURAN, TYPE_ FROM MASTER_CONTAINER WHERE NO_CONTAINER LIKE '%$no_cont%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>