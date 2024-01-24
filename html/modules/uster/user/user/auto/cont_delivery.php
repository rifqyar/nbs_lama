<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT DISTINCT a.NO_CONTAINER, a.SIZE_ AS SIZE_, a.TYPE_ AS TYPE_ , 
                  b.STATUS_CONT STATUS FROM MASTER_CONTAINER a LEFT JOIN HISTORY_CONTAINER b ON A.NO_CONTAINER = B.NO_CONTAINER AND A.NO_BOOKING = B.NO_BOOKING AND A.COUNTER = B.COUNTER WHERE 
                   a.NO_CONTAINER LIKE '%$no_cont%' AND a.LOCATION = 'IN_YARD'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>