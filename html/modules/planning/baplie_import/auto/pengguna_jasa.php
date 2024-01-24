<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT KODE_PELANGGAN, NAMA_PELANGGAN
					FROM MASTER_PELANGGAN
                    WHERE NAMA_PELANGGAN LIKE UPPER('%$nama%')
					GROUP BY KODE_PELANGGAN, NAMA_PELANGGAN
					ORDER BY KODE_PELANGGAN DESC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>