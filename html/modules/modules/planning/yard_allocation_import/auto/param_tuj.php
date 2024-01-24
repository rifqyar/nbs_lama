<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT ID_PEL, PELABUHAN, ID_NEGARA, NAMA_NEG FROM MASTER_PELABUHAN 
					WHERE PELABUHAN LIKE '$param%'";
					
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>