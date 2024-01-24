<?php
$OWNER			= strtoupper($_GET["term"]);

$db 			= getDB("storage");

$query 			= "SELECT KD_PBM KD_OWNER, NM_PBM NM_OWNER  FROM V_MST_PBM WHERE UPPER(NM_PBM)  LIKE '%$OWNER%' ";

//echo $query;
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>