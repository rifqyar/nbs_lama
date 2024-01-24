<?php

$nama			= strtoupper($_GET["term"]);
/*
EDITED BY GANDUL 17/7/2013 for OPUS REQUIREMENT WE MODIFIED IT					
*/
$terminalid     = $_GET['TERMINAL_ID'];
$db 			= getDB('dbint');	

$query 			= "SELECT 
							VESSEL, 
							ID_VSB_VOYAGE, 
							VOYAGE_IN, 
							VOYAGE_OUT, 
							TO_CHAR(to_date(OPEN_STACK,'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi') OPEN_STACK, 
							TO_CHAR(to_date(ETA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') TGL_JAM_TIBA, 
							TO_CHAR(to_date(START_WORK,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') START_WORK, CALL_SIGN,
							TO_CHAR(to_date(CLOSSING_TIME,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') CLOSING_TIME, 
							TO_CHAR(to_date(CLOSSING_TIME,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') CLOSING_TIME_DOC,
							TO_CHAR(to_date(ETD,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') TGL_JAM_BERANGKAT,
							TO_CHAR(to_date(FIRST_ETD,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') FIRST_ETD
				   FROM 
							M_VSB_VOYAGE 
				   WHERE 
							(UPPER(VESSEL) LIKE '%$nama%' or VOYAGE_IN LIKE '%$nama%') 
							";
//echo $query; die();
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);
die();

?>