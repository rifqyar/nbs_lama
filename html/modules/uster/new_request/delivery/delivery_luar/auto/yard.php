<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "select NAMA_YARD FROM YARD_AREA WHERE NAMA_YARD LIKE '%$nama%' ";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>