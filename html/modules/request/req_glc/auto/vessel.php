<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT KODE_KAPAL, NAMA_VESSEL FROM MASTER_VESSEL WHERE NAMA_VESSEL LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>