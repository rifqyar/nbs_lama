<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT ID_JOB_SLIP, TRIM(NO_CONT) AS NO_CONT, VESSEL, VOYAGE, SIZE_, TYPE_, STATUS_, BERAT, ROW_, ID_BLOCK, SLOT_,TIER_ FROM TB_CONT_JOBSLIP WHERE STATUS_STACK=0 AND NO_CONT LIKE '$param%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>