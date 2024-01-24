<?php
$block_name		= strtoupper($_GET["term"]);

$db 			= getDB();

$query_ 		= "SELECT ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
$result_		= $db->query($query_);
$row_			= $result_->fetchRow();
$id_yard                = $row_['ID'];

$query 			= "SELECT NAME, TIER, POSISI, COLOR FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$id_yard' AND NAME != 'NULL' AND NAME LIKE '$block_name%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>