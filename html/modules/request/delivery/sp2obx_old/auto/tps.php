<?php
//utk menon-aktifkan template default
outputRaw();
$tps = strtoupper($_GET["term"]);
$db = getDB();

$query = "SELECT TRIM(NM_TPS) NM_TPS, 
					KD_TPS
		  FROM MST_TPL
			WHERE UPPER(NM_TPS) LIKE '%$tps%' 
				ORDER BY KD_TPS";
//print_r($query);die;
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>