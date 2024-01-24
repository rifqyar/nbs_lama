<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();	
/*$query 			= "SELECT DISTINCT ypy.NO_CONTAINER,
							ypy.SIZE_, 
							ypy.TYPE_CONT, 
							ypy.STATUS_CONT, 
							ypy.TON,
							ypy.ID_PEL_TUJ AS POD,
							rbmh.NM_KAPAL AS VESSEL,
							rbmh.VOYAGE_IN||'/'||rbmh.VOYAGE_OUT AS VOYAGE
				   FROM YD_PLACEMENT_YARD ypy, RBM_H rbmh, STW_PLACEMENT_BAY stw
				   WHERE ypy.ACTIVITY = 'MUAT'
						AND ypy.STOWAGE = 'P'
						AND stw.STATUS_PLC = 'PLANNING'
						AND ypy.ID_VS = stw.ID_VS
						AND stw.ID_VS = rbmh.NO_UKK
						AND ypy.ID_VS = rbmh.NO_UKK
						AND ypy.NO_CONTAINER = stw.NO_CONTAINER
						AND ypy.NO_CONTAINER LIKE '$param%'";
*/						
$query 			= "SELECT DISTINCT ypy.NO_CONTAINER,
							ypy.SIZE_, 
							ypy.TYPE_CONT, 
							ypy.STATUS_CONT, 
							ypy.TON,
							ypy.ID_PEL_TUJ AS POD,
							rbmh.NM_KAPAL AS VESSEL,
							rbmh.VOYAGE_IN||'/'||rbmh.VOYAGE_OUT AS VOYAGE
				   FROM YD_PLACEMENT_YARD ypy, RBM_H rbmh
				   WHERE ypy.ACTIVITY = 'MUAT'
						AND ypy.STOWAGE IN ('P','T')
						AND TRIM(ypy.ID_VS) = TRIM(rbmh.NO_UKK)
						AND ypy.NO_CONTAINER LIKE '$param%'";						
						
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>