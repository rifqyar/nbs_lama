<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db 			= getDB();

$query = "SELECT NAME FROM TB_USER WHERE ID_GROUP = '6'";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>