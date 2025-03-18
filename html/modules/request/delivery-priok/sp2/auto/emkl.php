<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, KD_PELANGGAN, NO_NPWP from MST_PELANGGAN 
                   WHERE KODE_CABANG = 'TPK' AND UPPER(NAMA_PERUSAHAAN) LIKE '%$nama%' and pelanggan_aktif = '1'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>