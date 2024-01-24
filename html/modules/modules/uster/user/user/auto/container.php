<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

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