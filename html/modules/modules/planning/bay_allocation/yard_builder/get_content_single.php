<?php
include_once '../config/config.php';
$index 		= $_POST['index'];
$yard_id	= $_POST['yard'];

$query_blocking_cell = "SELECT * FROM BLOCKING_CELL WHERE INDEX_CELL = '$index'";


$result	  		= dbQuery($query_blocking_cell);
$row_cell  	  	= dbFetchArray($result);
$block_id  		= $row_cell["ID_BLOCKING_AREA"];
$index_row		= $row_cell["ROW"];
$index_slot		= $row_cell["SLOT"];

$query_blocking_area = "SELECT * FROM BLOCKING_AREA WHERE ID = '$block_id'";
$result_block		 = dbQuery($query_blocking_area);
$row_blocking		 = dbFetchArray($result_block);
$block_name			 = $row_blocking["NAME"];

$query_placement = "SELECT * FROM PLACEMENT WHERE ID_BLOCK = '$block_id' AND ROW = '$index_row' AND SLOT = '$index_slot' ORDER BY TIER DESC";

$result_placement 		 = dbQuery($query_placement);

$i = 1;

$html_block = "";

$html_block = "<center><table border=1>";
while($row_placement	 = dbFetchArray($result_placement) )
{	
	$cont_id[$i]		 = $row_placement["ID_CONTAINER"];
	$tier[$i]			 = $row_placement["TIER"];
	
	$query_container 	= "SELECT * FROM TB_CONTAINER WHERE ID = '$cont_id[$i]'";
	$result_container 	= dbQuery($query_container);
	$row_container		= dbFetchArray($result_container);
	
	$no_container[$i]		= $row_container['NO_CONTAINER'];
	$type[$i]				= $row_container['TYPE'];
	$size[$i]				= $row_container['SIZE'];
	
	$html_block 	.= "<tr><td style=\" background-color: #c5ddfc\">Tier $tier[$i]</td><td>$no_container[$i]</td><td>$size[$i]</td><td>$type[$i]</td></tr>";
	$i++;
}

$html_block .= "</table>Blok $block_name - Slot $index_slot - Row $index_row</center>";

echo "$html_block";

?>




















