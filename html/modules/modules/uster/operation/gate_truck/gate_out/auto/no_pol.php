<?php
$no_kartu		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT GATE_IN_TRUCK.*, MASTER_CONTAINER.SIZE_
				   FROM GATE_IN_TRUCK INNER JOIN MASTER_CONTAINER ON GATE_IN_TRUCK.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
				   WHERE NO_POL LIKE '%$no_pol%'  
				   AND AKTIF = 'Y' 
				   AND ROWNUM <= 5 ORDER BY NO_KARTU ASC";
				   
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>