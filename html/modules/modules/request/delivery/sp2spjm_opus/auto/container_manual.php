<?php
//utk menon-aktifkan template default
outputRaw();
$nocont = strtoupper($_GET["term"]);
$ukk = TRIM($_GET['no_ukk']);
$db = getDB('dbint');

$query = "select B.ID_VSB_VOYAGE no_ukk,
		 B.VESSEL vessel,
		 B.voyage_in||'-'||B.voyage_out voyage,
		 A.NO_CONTAINER no_container,
		 trim(a.SIZE_CONT)||'-'||trim(a.TYPE_CONT)||'-'||trim(a.STATUS) jenis,
		 trim(a.ISO_CODE) isocode,
		 trim(a.height) height,
		 trim(a.carrier) carrier,
		 to_char(A.VESSEL_CONFIRM) tgl_disch
			FROM M_CYC_CONTAINER A INNER JOIN M_VSB_VOYAGE B ON (A.VESSEL = B.VESSEL AND A.VOYAGE_IN = B.VOYAGE_IN AND A.VOYAGE_OUT = B.VOYAGE_OUT)
		   WHERE A.CONT_LOCATION = 'Yard'
				 AND 
				 B.ID_VSB_VOYAGE LIKE '$ukk'
				 AND A.NO_CONTAINER like '%$nocont%'
				 ORDER BY A.NO_CONTAINER";
			  
//print_r($query);die;
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>