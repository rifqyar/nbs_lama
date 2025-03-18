<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

$query			= "SELECT MASTER_CONTAINER.NO_CONTAINER AS NO_CONTAINER, 
                          MASTER_CONTAINER.SIZE_ AS KD_SIZE, 
                          MASTER_CONTAINER.TYPE_ AS KD_TYPE,
                          HISTORY_CONTAINER.STATUS_CONT KD_STATUS_CONT,                      
                       'DEPO' ASAL_CONT,
                       V_BOOKING_STACK_TPK.NM_KAPAL, V_BOOKING_STACK_TPK.VOYAGE_IN
                   FROM MASTER_CONTAINER, HISTORY_CONTAINER, V_BOOKING_STACK_TPK  V_BOOKING_STACK_TPK                               
                   WHERE  MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%' AND MASTER_CONTAINER.NO_BOOKING =  HISTORY_CONTAINER.NO_BOOKING
                          AND MASTER_CONTAINER.COUNTER = HISTORY_CONTAINER.COUNTER
                          AND MASTER_CONTAINER.NO_BOOKING(+) = V_BOOKING_STACK_TPK.NO_BOOKING                          
                   AND ROWNUM <= 7                   
					";

$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>