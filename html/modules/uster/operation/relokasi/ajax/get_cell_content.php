<?php
include_once '../config/config.php';

$yard_id	= $_SESSION["IDYARD_STORAGE"];
$block		= $_POST['block'];
$slot		= $_POST['slot'];
$row		= $_POST['row'];

$html_block = "";
$db			= getDB("storage");

$query_blocking_area = "SELECT * FROM BLOCKING_AREA WHERE ID = '$block'";
$result_block		 = $db->query($query_blocking_area);
$row_blocking		 = $result_block->fetchRow();
$block_name			 = $row_blocking["NAME"];

$html_block = "<center><table border=1>";

	$query_placement = "SELECT * FROM PLACEMENT WHERE ID_BLOCKING_AREA = '$block' AND ROW_ = '$row' AND SLOT_ = '$slot' ORDER BY TIER_ DESC";
	
	$result_placement 		 = $db->query($query_placement);
	$row_placement_			 = $result_placement->getAll();
	
	$i = 1;
	
	$html_block = "";
	
	$html_block = "<center><table>";
	foreach($row_placement_	 as $row_placement )
	{	
		$cont_id[$i]		 = $row_placement["NO_CONTAINER"];
		$tier[$i]			 = $row_placement["TIER_"];
		
		$query_container 	= "SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$cont_id[$i]'";
		$result_container 	= $db->query($query_container);
		$row_container		= $result_container->fetchRow();
		
		$no_container[$i]		= $row_container['NO_CONTAINER'];
		$type[$i]				= $row_container['TYPE_'];
		$size[$i]				= $row_container['SIZE_'];
		
		$html_block 	.= "<tr  valign=\"middle\"><td><b>Tier $tier[$i]<b></td><td><img src=\"images/row_cont.png\" width=\"40\" height=\"40\"></td><td></td><td><b>$no_container[$i]<br>$size[$i] | $type[$i]</b></td></tr>";
		$i++;
	}
	
	$html_block .= "</table><b><br>Blok $block_name - Slot $slot - Row $row</b></center>";
	


echo "$html_block";

?>




















