<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT CODE, LINE_OPERATOR FROM MASTER_CARRIERS
                    WHERE upper(LINE_OPERATOR) LIKE '%$param%'
					ORDER BY CODE DESC";
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();		

//print_r($row);

echo json_encode($row);


?>