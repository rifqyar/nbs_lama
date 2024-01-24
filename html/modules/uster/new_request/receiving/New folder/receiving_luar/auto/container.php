<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT NO_CONTAINER, 
                          SIZE_ AS SIZE_, 
                          TYPE_ AS TYPE_ 
                   FROM MASTER_CONTAINER 
                   WHERE NO_CONTAINER LIKE '%$no_cont%' AND LOCATION = 'GATO'
						 ";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>