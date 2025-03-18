<?php

$nama_kapal		= strtoupper($_GET["term"]);

$db 			= getDB("dbint");
	

$query 			= "SELECT VESSEL,
                   ID_VSB_VOYAGE,
                   VOYAGE_IN,
                   VOYAGE_OUT,
                   TO_CHAR (TO_DATE (OPEN_STACK, 'YYYYMMDDHH24MISS'),
                            'DD-MM-YYYY HH24:Mi')
                      OPEN_STACK,
                   TO_CHAR (TO_DATE (ETA, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi')
                      TGL_JAM_TIBA,
                   TO_CHAR (TO_DATE (START_WORK, 'YYYYMMDDHH24MISS'),
                            'DD-MM-YYYY HH24:Mi')
                      START_WORK,
                   CALL_SIGN,
                   TO_CHAR (TO_DATE (CLOSSING_TIME, 'YYYYMMDDHH24MISS'),
                            'DD-MM-YYYY HH24:Mi')
                      CLOSING_TIME,
                   TO_CHAR (TO_DATE (CLOSSING_TIME, 'YYYYMMDDHH24MISS'),
                            'DD-MM-YYYY HH24:Mi')
                      CLOSING_TIME_DOC,
                   TO_CHAR (TO_DATE (ETD, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi')
                      TGL_JAM_BERANGKAT,
                   TO_CHAR (TO_DATE (FIRST_ETD, 'YYYYMMDDHH24MISS'),
                            'DD-MM-YYYY HH24:Mi')
                      FIRST_ETD,VESSEL_CODE,'BS' || VESSEL_CODE || ID_VSB_VOYAGE AS NO_BOOKING, POL, POD, ID_POL
              FROM M_VSB_VOYAGE WHERE 
              sysdate BETWEEN TO_DATE(OPEN_STACK,'YYYYMMDDHH24MISS') and TO_DATE(CLOSSING_TIME,'YYYYMMDDHH24MISS') AND
              VESSEL LIKE '%$nama_kapal%' or VOYAGE_IN LIKE '%$nama_kapal%'
              order by open_stack desc";
$result		= $db->query($query);
$row		= $result->getAll();	
//echo $query;
echo json_encode($row);


?>