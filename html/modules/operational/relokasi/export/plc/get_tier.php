<?php

$db = getDB();
$id_yard = $_POST["ID_YARD"];
$id_block = $_POST["ID_BLOCK"];
$slot_yd = $_POST["SLOT_YARD"];
$row_yd = $_POST["ROW_YARD"];
//echo $id_vs;exit;

$query_tier = "SELECT TIER FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$id_yard' AND ID = '$id_block'";
$result10 = $db->query($query_tier);
$get_tier = $result10->fetchRow();
$tier = $get_tier['TIER'];

$query_alokasi = "SELECT SIZE_, TYPE_, STATUS_CONT, ID_PEL_TUJ, KATEGORI FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slot_yd' AND ROW_ = '$row_yd'";
$result_alokasi = $db->query($query_alokasi);
$get_alokasi = $result_alokasi->fetchRow();
$sz = $get_alokasi['SIZE_'];
$ty = $get_alokasi['TYPE_'];
$st = $get_alokasi['STATUS_CONT'];
$pod = $get_alokasi['ID_PEL_TUJ'];
$ctg = $get_alokasi['KATEGORI'];

echo $tier.",".$sz.",".$ty.",".$st.",".$pod.",".$ctg;

?>