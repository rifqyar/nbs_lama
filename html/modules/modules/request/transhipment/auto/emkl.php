<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, KD_PELANGGAN, NO_NPWP from MST_PELANGGAN WHERE NAMA_PERUSAHAAN LIKE '%$nama%' AND KODE_CABANG='TPK'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>