<?php
$param	= strtoupper($_GET["term"]);

$db 	= getDB();
	
$query 	= "SELECT KD_KAPAL, NM_KAPAL FROM MASTER_KAPAL WHERE PROFILE_FLAG = 'Y' AND NM_KAPAL LIKE '$param%'";
$result	= $db->query($query);
$row	= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>