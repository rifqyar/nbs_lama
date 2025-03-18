<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
/*$query 			= "select ID, NAMA from MASTER_PBM WHERE NAMA LIKE '%$nama%' "; */

$query 			= "SELECT pbm.KD_PBM,pbm.NM_PBM,pbm.ALMT_PBM,pbm.NO_NPWP_PBM FROM KAPAL_CABANG.MST_PBM pbm, KAPAL_CABANG.mst_pelanggan pelanggan
				where pbm.KD_CABANG='05' AND pbm.NO_ACCOUNT_PBM = pelanggan.kd_pelanggan AND pbm.NM_PBM LIKE '%$nama%' AND pbm.ALMT_PBM IS NOT NULL AND pelanggan.status_pelanggan = 1"; 

$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>