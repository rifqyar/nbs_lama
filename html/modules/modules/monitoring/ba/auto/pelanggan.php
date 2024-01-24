<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT KODE_PELANGGAN,
					      NAMA_PELANGGAN,
						  ACCOUNT_KEU
					FROM MASTER_PELANGGAN
                    WHERE NAMA_PELANGGAN LIKE '%$nama%'
					ORDER BY ID ASC";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>