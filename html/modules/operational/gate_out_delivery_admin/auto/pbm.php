<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select KODE_PBM, NAMA, ALAMAT, NPWP from MASTER_PBM WHERE NAMA LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>