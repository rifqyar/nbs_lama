<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER NOT IN (
					SELECT NO_CONTAINER FROM MASTER_CONTAINER WHERE MLO = 'MLO')
					AND NO_CONTAINER LIKE '%$no_cont%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>