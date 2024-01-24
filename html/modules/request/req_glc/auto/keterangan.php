<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT KEGIATAN, STATUS, TARIF FROM MASTER_KEGIATAN WHERE ALAT = 'GLC' AND KEGIATAN LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>