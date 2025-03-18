<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT NO_CONTAINER, 
                          SIZE_ AS SIZE_, 
                          TYPE_ AS TYPE_
				   FROM MASTER_CONTAINER  
                   WHERE NO_CONTAINER LIKE '%$no_cont%' AND LOCATION NOT LIKE 'GATO' AND NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM CONTAINER_RECEIVING WHERE AKTIF = 'Y')";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>