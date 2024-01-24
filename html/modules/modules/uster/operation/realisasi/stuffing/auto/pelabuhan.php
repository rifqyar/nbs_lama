<?php

$nama			= strtolower($_GET["term"]);

$db 			= getDB("manifest");
	
$query 			= "select ID, NAMA from m_pelabuhan WHERE NAMA LIKE '%$nama%' LIMIT 0,7";
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>