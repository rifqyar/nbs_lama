<?php 
$no_cont		= strtoupper($_GET["term"]);
$db 			= getDB();
	
$query 			= "select NO_UKK,NM_KAPAL,VOYAGE_IN,VOYAGE_OUT,NM_AGEN,NO_ACCOUNT, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, INFO_69_74	 
				from v_pkk_nbs@dbint_kapalprod WHERE KD_CABANG='05' AND NM_KAPAL LIKE upper('%$no_cont%') order by tgl_jam_tiba desc";
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);
?>