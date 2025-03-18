<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB('dbint');	
$query 			= "SELECT CDG_OPER_CODE AS CODE, CDG_OPER_NAME AS LINE_OPERATOR FROM M_CDG_OPERATOR WHERE UPPER(CDG_OPER_CODE) LIKE '%$nama%' OR CDG_OPER_NAME LIKE '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>