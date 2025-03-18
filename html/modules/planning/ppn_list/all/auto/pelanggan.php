<?php

$nama			= strtoupper($_GET["term"]);


$db 			= getDB();	
$query 			= "SELECT KD_PELANGGAN, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, NO_NPWP 
				   FROM MST_PELANGGAN WHERE (NAMA_PERUSAHAAN LIKE '%$nama%' OR KD_PELANGGAN LIKE '%$nama%') AND PELANGGAN_AKTIF = '1'
				   ORDER BY KD_PELANGGAN ASC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>