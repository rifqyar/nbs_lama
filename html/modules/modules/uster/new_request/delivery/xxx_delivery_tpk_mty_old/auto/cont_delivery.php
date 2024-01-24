<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT a.NO_CONTAINER, a.SIZE_ AS SIZE_, a.TYPE_ AS TYPE_ , vb.NM_KAPAL, a.NO_BOOKING NO_BOOKING,
                   b.STATUS_CONT FROM MASTER_CONTAINER a INNER JOIN HISTORY_CONTAINER b
				   ON a.NO_CONTAINER= b.NO_CONTAINER INNER JOIN v_booking_stack_tpk vb ON a.NO_BOOKING = vb.NO_BOOKING 
				   WHERE a.NO_CONTAINER LIKE '%$no_cont%' AND a.LOCATION = 'IN_YARD'
				   AND b.TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER LIKE '%$no_cont%')";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>