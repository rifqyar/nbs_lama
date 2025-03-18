<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

/* $query			= "SELECT DISTINCT MC.*, P.STATUS_CONT STATUS, P.TGL_UPDATE
                    FROM MASTER_CONTAINER MC
                    LEFT JOIN HISTORY_CONTAINER P ON MC.NO_CONTAINER = P.NO_CONTAINER
                    --AND MC.NO_BOOKING = P.NO_BOOKING 
					AND MC.COUNTER = P.COUNTER
                    WHERE MC.NO_CONTAINER = '$no_cont'
					--AND P.TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont')
                    ORDER BY P.TGL_UPDATE DESC
					"; */
					
$query = " SELECT DISTINCT Q.* FROM HISTORY_CONTAINER, (SELECT MC.NO_CONTAINER, MC.SIZE_, MC.TYPE_, MC.LOCATION, P.NO_BOOKING, P.COUNTER
                    FROM MASTER_CONTAINER MC
                    LEFT JOIN HISTORY_CONTAINER P ON MC.NO_CONTAINER = P.NO_CONTAINER
                    WHERE MC.NO_CONTAINER = '$no_cont') Q
				WHERE HISTORY_CONTAINER.NO_CONTAINER = Q.NO_CONTAINER AND HISTORY_CONTAINER.NO_BOOKING = Q.NO_BOOKING
				ORDER BY Q.COUNTER DESC";

$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>