<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
/*$query 			= "select ID, NAMA from MASTER_PBM WHERE NAMA LIKE '%$nama%' "; */

$query 			= "SELECT pbm.KD_PBM,pbm.NM_PBM,pbm.ALMT_PBM,pbm.NO_NPWP_PBM,pbm.NO_ACCOUNT_PBM 
                FROM V_MST_PBM pbm where pbm.KD_CABANG='05' AND UPPER(pbm.NM_PBM) LIKE '%$nama%' AND PELANGGAN_AKTIF = '1' AND pbm.ALMT_PBM IS NOT NULL"; 

$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>