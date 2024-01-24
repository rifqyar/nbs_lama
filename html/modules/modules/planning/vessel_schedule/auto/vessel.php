<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "select KD_KAPAL, NM_KAPAL from MASTER_KAPAL WHERE NM_KAPAL LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>