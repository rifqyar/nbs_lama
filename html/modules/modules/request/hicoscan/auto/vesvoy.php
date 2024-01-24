<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db 			= getDB('dbint');

$query = "select VESSEL NM_KAPAL, 
				 ID_VSB_VOYAGE as NO_UKK,
				 VOYAGE_IN, 
				 VOYAGE_IN AS VOYAGE, 
				 VOYAGE_OUT, 
				 OPERATOR_NAME AS NM_PEMILIK,
				 VESSEL_CODE
		   from M_VSB_VOYAGE 
		   WHERE VESSEL LIKE '%$shipper%' OR  VOYAGE_IN LIKE '%$shipper%'
		   ORDER BY ETA DESC";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>