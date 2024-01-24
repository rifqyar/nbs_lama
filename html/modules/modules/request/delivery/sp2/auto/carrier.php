<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select CODE, LINE_OPERATOR from MASTER_CARRIERS WHERE UPPER(LINE_OPERATOR) LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>