<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 		= "SELECT * FROM v_mst_pelabuhan WHERE NM_PELABUHAN LIKE '%$nama%'";
//echo $query;die;
$result		= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>