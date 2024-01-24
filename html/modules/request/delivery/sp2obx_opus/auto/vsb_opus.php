<?php
//utk menon-aktifkan template default
outputRaw();
$vsb		= strtoupper($_GET["term"]);
$db 			= getDB('dbint');

$query = "SELECT TRIM(VESSEL) VESSEL, 
				 TRIM(VOYAGE_IN) VOY_IN,
				 TRIM(VOYAGE_OUT) VOY_OUT,
				 ID_VSB_VOYAGE
			FROM M_VSB_VOYAGE
			WHERE TRIM(VESSEL) LIKE '%$vsb%' 
				 OR TRIM(VOYAGE_IN) LIKE '%$vsb%'
				 OR TRIM(VOYAGE_OUT) LIKE '%$vsb%'";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>