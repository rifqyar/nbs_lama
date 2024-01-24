<?php

$db = getDB();
$bay_area = $_POST["BAY_AREA"];
$cell_address = $_POST["CELL_ADDRESS"];
$width_h = $_POST["WIDTH"];
$under_cell = $cell_address+$width_h;
//echo $bay_area;exit;

$query_width = "SELECT ROW_,TIER_ FROM STW_BAY_CELL WHERE CELL_NUMBER = '$cell_address' AND ID_BAY_AREA = '$bay_area'";
$result10 = $db->query($query_width);
$get_width = $result10->fetchRow();
$row_bay = $get_width['ROW_'];
$tier_bay = $get_width['TIER_'];

$cek_status_stack = "SELECT STATUS_STACK FROM STW_BAY_CELL WHERE CELL_NUMBER = '$under_cell' AND ID_BAY_AREA = '$bay_area'";
$result12 = $db->query($cek_status_stack);
$get_status = $result12->fetchRow();
$stack_status = $get_status['STATUS_STACK'];
//echo $row_bay;exit;

echo $row_bay.",".$tier_bay.",".$stack_status;

?>