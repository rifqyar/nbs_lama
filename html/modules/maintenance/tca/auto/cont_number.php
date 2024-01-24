<?php

$term		= strtoupper($_GET["term"]);
$db 			= getDB();	
$query 			= "SELECT F_TCATN, F_TCACN, F_TCAST, B.TRUCK_NUMBER 
                FROM OPUS_REPO.TB_ASSOCIATION A
                LEFT JOIN TID_REPO B ON A.F_TCATN = B.TID
                WHERE F_TCAST = 'R' AND (F_TCATN LIKE '$term%' OR F_TCACN LIKE '$term%')";
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);
?>