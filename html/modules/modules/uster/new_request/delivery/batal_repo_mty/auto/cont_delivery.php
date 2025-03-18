<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT a.NO_CONTAINER, a.SIZE_ AS SIZE_, a.TYPE_ AS TYPE_ , 
                   b.STATUS FROM MASTER_CONTAINER a INNER JOIN PLACEMENT b 
				   ON a.NO_CONTAINER= b.NO_CONTAINER WHERE a.NO_CONTAINER LIKE '%$no_cont%' AND a.LOCATION = 'IN_YARD'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>