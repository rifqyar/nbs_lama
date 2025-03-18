<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select KODE_KAPAL, NAMA_VESSEL from MASTER_VESSEL WHERE NAMA_VESSEL LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>