<?php
$iso = strtoupper($_GET["term"]);

$db 			= getDB();	
$query 			= "SELECT TRIM(ISO_CODE) AS ISO_CODE,
						  TRIM(SIZE_) AS SIZE_, 
						  TRIM(TYPE_) AS TYPE_,
						  TRIM(H_ISO) AS H_ISO,
						  DESCRIPTION
				   FROM MASTER_ISO_CODE 
				   WHERE ISO_CODE LIKE '$iso%'";
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>