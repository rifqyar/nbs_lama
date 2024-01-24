<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db 			= getDB();

$query = "SELECT NO_UKK, NM_KAPAL, VOYAGE_IN||'-'||VOYAGE_OUT AS VOYAGE, NM_PEMILIK
			FROM RBM_H
			WHERE NM_KAPAL LIKE '%$shipper%'
			ORDER BY TGL_JAM_TIBA DESC";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>