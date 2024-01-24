<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db 			= getDB();

$query = "SELECT NAMA_ALAT, ID_ALAT FROM MASTER_ALAT WHERE PENEMPATAN = 'BAY'";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>