<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select kode_barang as id_cont from master_barang WHERE KODE_BARANG LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>