<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db 			= getDB('dbint');

$query ="select VESSEL NM_KAPAL, 
				ID_VSB_VOYAGE as NO_UKK,
				VOYAGE_IN, 
				VOYAGE_IN AS VOYAGE, 
				VOYAGE_OUT,
				OPERATOR_NAME AS NM_PEMILIK
		from m_vsb_voyage 
		WHERE VESSEL LIKE '%$shipper%' or VOYAGE_IN LIKE '%$shipper%' ORDER BY ETA DESC";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>