<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db 			= getDB('dbint');

$query = "SELECT ID_VES_SCD AS NO_UKK, TRIM(VESSEL) NM_KAPAL, VOYAGE_IN AS VOYAGE,VOYAGE_OUT, OPERATOR_NAME
			FROM ops_ves_scd
			WHERE VESSEL LIKE '%$shipper%'
			ORDER BY ETA DESC";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>