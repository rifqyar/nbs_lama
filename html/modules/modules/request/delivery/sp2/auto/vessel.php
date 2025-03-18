<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB('dbint');	
$query 			= "select VESSEL, ID_VSB_VOYAGE, VOYAGE_IN, VOYAGE_OUT, TO_CHAR(TO_DATE(OPEN_STACK,'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi') OPEN_STACK, 
                    TO_CHAR(TO_DATE(ATA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') TGL_JAM_TIBA, 
                    TO_CHAR(TO_DATE(START_WORK,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') START_WORK, VESSEL_CODE AS CALL_SIGN
                    from M_VSB_VOYAGE WHERE VESSEL LIKE '%$nama%' OR VOYAGE_IN LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>