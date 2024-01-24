<?php

$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB('dbint');

$query 			= "SELECT 							
							NO_CONTAINER,
							VESSEL,
							VOYAGE_IN, 
							VOYAGE_OUT, 
							STATUS, 
							replace(DISCHARGE_CONFIRM,' ','') as DISCHARGE_CONFIRM						
					FROM 
							M_COARRI 					
					WHERE							
							NO_CONTAINER = '$no_cont'";
$result			= $db->query($query);
$row			= $result->getAll();	


//print_r($row);

echo json_encode($row);

die();
?>