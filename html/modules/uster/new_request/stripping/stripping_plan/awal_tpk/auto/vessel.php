<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB('dbint');	
$query 			= "SELECT VESSEL,
                   ID_VSB_VOYAGE,
                   VOYAGE_IN,
                   VOYAGE_OUT,
                   TO_CHAR (TO_DATE (OPEN_STACK, 'YYYYMMDDHH24MISS'),
                            'DD-MM-YYYY HH24:Mi')
                      OPEN_STACK,
                   TO_CHAR (TO_DATE (ATA, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi')
                      TGL_JAM_TIBA,
                   TO_CHAR (TO_DATE (START_WORK, 'YYYYMMDDHH24MISS'),
                            'DD-MM-YYYY HH24:Mi')
                      START_WORK,
                   VESSEL_CODE,
                   CALL_SIGN,
                   'BP' || VESSEL_CODE || ID_VSB_VOYAGE AS NO_BOOKING
              FROM M_VSB_VOYAGE
             WHERE VESSEL LIKE '%$nama%' OR VOYAGE_IN LIKE '%$nama%' ORDER BY ATA DESC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>