<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
/*$query 			= "select ID, NAMA from MASTER_PBM WHERE NAMA LIKE '%$nama%' "; */

$query 			= "SELECT KD_PBM,NM_PBM,ALMT_PBM,NO_NPWP_PBM FROM v_mst_pbm where KD_CABANG='05' AND NM_PBM LIKE '%$nama%'"; 

$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>