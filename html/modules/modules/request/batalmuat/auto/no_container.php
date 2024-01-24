<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("dblocal");	
$query 			= "SELECT B.ID_CONTAINER, 
						  A.UKURAN, 
						  A.TYPE, 
						  A.STATUS, 
						  B.HZ,
						  B.ID_REQ
					FROM MASTER_BARANG A, TB_REQ_DELIVERY_CONT B 
					WHERE A.KODE_BARANG=B.ID_BARANG 
					AND B.ID_CONTAINER LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>