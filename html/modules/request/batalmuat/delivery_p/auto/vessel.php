<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select NM_KAPAL, NO_UKK, VOYAGE_IN, VOYAGE_OUT, TO_CHAR(OPEN_STACK, 'DD-MM-YYYY HH24:Mi') OPEN_STACK, 
					TO_CHAR(CLOSING_TIME,'DD-MM-YYYY HH24:Mi') CLOSING_TIME, 
					TO_CHAR(CLOSING_TIME_DOC,'DD-MM-YYYY HH24:Mi') CLOSING_TIME_DOC, 
					TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:Mi') TGL_JAM_TIBA, 
					TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:Mi') TGL_JAM_BERANGKAT
					from RBM_H WHERE NM_KAPAL LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>