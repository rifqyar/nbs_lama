<?php
//utk menon-aktifkan template default
outputRaw();
$pelanggan		= strtoupper($_GET["term"]);
$db 			= getDB();

$query = "SELECT TRIM(NAMA_PERUSAHAAN) NAMA_PERUSAHAAN, 
				 ALAMAT_PERUSAHAAN,
				 NO_NPWP, 
				 KD_PELANGGAN
			FROM MST_PELANGGAN
			WHERE UPPER(NAMA_PERUSAHAAN) LIKE '%$pelanggan%' 
				 AND KODE_CABANG = 'TPK'
			ORDER BY NAMA_PERUSAHAAN";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>