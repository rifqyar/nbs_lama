<?php
outputRaw();
$db = getDB('ora');
$vessel		= strtoupper($_GET["term"]);
$query = "SELECT NO_UKK,NM_KAPAL,VOYAGE_IN,VOYAGE_OUT
			FROM V_PKK_CONT
		   WHERE (NM_KAPAL LIKE '%$vessel%' OR VOYAGE_IN LIKE '%$vessel%' OR VOYAGE_OUT LIKE '%$vessel%')
			 AND KD_CABANG = '01'
		ORDER BY TGL_JAM_TIBA DESC";

$result	= $db->query($query);
$row = $result->getAll();	
echo json_encode($row);
?>