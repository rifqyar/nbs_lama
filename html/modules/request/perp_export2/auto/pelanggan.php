<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
/*$query 			= "SELECT NAMA_PERUSAHAAN,
					   ALAMAT_PERUSAHAAN,
					   KD_PELANGGAN,
					   NO_NPWP
				  FROM MST_PELANGGAN_CABANG
				 WHERE NAMA_PERUSAHAAN LIKE '%$nama%'";*/
$query 			= "select NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, KD_PELANGGAN, NO_NPWP from MST_PELANGGAN 
                   WHERE KODE_CABANG = 'PTK' AND UPPER(NAMA_PERUSAHAAN) LIKE '%$nama%' and pelanggan_aktif = '1'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>