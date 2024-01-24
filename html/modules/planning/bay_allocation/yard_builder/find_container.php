<?php

include_once '../config/config.php';
$cont_no 	= $_POST['container_no'];

$query_get_id 	= "SELECT * FROM TB_CONTAINER WHERE NO_CONTAINER = '$cont_no'";
$result_id 		= dbQuery($query_get_id);
$row_tb_cont	= dbFetchArray($result_id);

$cont_id		= $row_tb_cont['ID'];

$query_loc 		= "SELECT * FROM PLACEMENT WHERE ID_CONTAINER = '$cont_id'";
$result_loc		= dbQuery($query_loc);
$row_loc		= dbFetchArray($result_loc);

$block_id 		= $row_loc['ID_BLOCK'];
$slot			= $row_loc['SLOT'];
$row			= $row_loc['ROW'];
$tier			= $row_loc['TIER'];

$query_get_block_name 	= "SELECT * FROM BLOCKING_AREA WHERE ID = '$block_id'";
$result_block			= dbQuery($query_get_block_name);
$row_block				= dbFetchArray($result_block);

$block_name 	= $row_block['NAME'];

$ret			= "$block_name-$slot-$row-$tier";
echo $ret;
?>