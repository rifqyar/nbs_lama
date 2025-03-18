<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT DISTINCT ypy.NO_CONTAINER,
							ypy.SIZE_, 
							ypy.TYPE_CONT, 
							ypy.STATUS_CONT, 
							ypy.TON,
							ypy.ID_PEL_TUJ AS POD,
							rbmh.NM_KAPAL AS VESSEL,
							rbmh.VOYAGE_IN||'/'||rbmh.VOYAGE_OUT AS VOYAGE
				   FROM YD_PLACEMENT_YARD ypy, RBM_H rbmh
				   WHERE ypy.ACTIVITY = 'BONGKAR'
						AND ypy.ID_VS = rbmh.NO_UKK
						AND ypy.NO_CONTAINER LIKE '$param%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>