<?php
$no_truck		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT * FROM TRUCK WHERE NO_TRUCK LIKE '%$no_truck%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>