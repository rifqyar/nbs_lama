<?php
$index 		= $_POST['index'];
$yard_id	= $_POST['yard_id'];

$db			= getDB("storage");

$cell 			= explode(",", $index);

$html_block = "";
	
$html_block = "<center><table border=1>";

$query_count_content = "SELECT BLOCKING_AREA.ID AS ID_BLOCK FROM BLOCKING_CELL INNER JOIN BLOCKING_AREA ON BLOCKING_CELL.ID_BLOCKING_AREA = BLOCKING_AREA.ID  WHERE BLOCKING_AREA.ID_YARD_AREA = '$yard_id' AND BLOCKING_CELL.INDEX_CELL = '$cell[0]'";
$res_content 		 = $db->query($query_count_content);
$row_block			 = $res_content->fetchRow();
$id_block			 = $row_block['ID_BLOCK'];

if(count($cell) > 2)
{
	for($p = 0; $p < count($cell)-1; $p++)
	{
		if($cell[$p] > 0)
		{
			$query_blocking_cell = "SELECT * FROM BLOCKING_CELL WHERE INDEX_CELL = '$cell[$p]' AND ID_BLOCKING_AREA = '$id_block'";
		
			
			$result	  		= $db->query($query_blocking_cell);
			$row_cell  	  	= $result->fetchRow();
			$block_id  		= $row_cell["ID_BLOCKING_AREA"];
			$index_row		= $row_cell["ROW"];
			$index_slot		= $row_cell["SLOT"];
			
			$_row[$p]  = $index_row;
			$_slot[$p] = $index_slot;
			 
			$query_blocking_area = "SELECT * FROM BLOCKING_AREA WHERE ID = '$block_id'";
			$result_block		 = $db->query($query_blocking_area);
			$row_blocking		 = $result_block->fetchRow();
			$block_name			 = $row_blocking["NAME"];
			
			$query_placement 	 = "SELECT * FROM PLACEMENT WHERE ID_BLOCKING_AREA = '$block_id' AND ROW_ = '$index_row' AND SLOT_ = '$index_slot' ORDER BY TIER_ DESC";
			
			$result_placement 	 = $db->query($query_placement);
			$row_placement		 = $result_placement->getAll();
			$i = 1;
			
			
			foreach($row_placement_ as $row_placement)
			{	
				$cont_id[$i]		 = $row_placement["ID_CONTAINER"];
				$tier[$i]			 = $row_placement["TIER"];
				
				$query_container 	= "SELECT * FROM TB_CONTAINER WHERE ID = '$cont_id[$i]'";
				$result_container 	= $db->query($query_container);
				$row_container		= $result_container->fetchRow();
				
				$no_container[$i]		= $row_container['NO_CONTAINER'];
				
				$cell_row[$_row[$p]][$tier[$i]]		=  $no_container[$i];
				$cell_slot[$_slot[$p]][$tier[$i]]	=  $no_container[$i];
				
				$type[$i]				= $row_container['TYPE'];
				$size[$i]				= $row_container['SIZE'];
				
				$i++;
			}
		}
	}
	
	//get length of slot and row and go with the longest
	$length_row  = count($cell_row);
	$length_slot = count($cell_slot);
	
	//find the tallest tier
	
	if($length_slot >= $length_row)
	{	
		$max = 0;
		for($i = 0;$i < $length_slot; $i++)
		{
			if($max < count($cell_slot[$_slot[$i]]))
			{
				$max = count($cell_slot[$_slot[$i]]);
			}
		}
		
		for($j = $max; $j > 0; $j--)
		{
			$html_block	.= "<tr><td style=\" background-color: #c5ddfc\">Tier $j</td>";
			for($q = 0; $q < $length_slot; $q++)
			{	
				$cont = $cell_slot[$_slot[$q]][$j];
				$html_block 	.= "<td>$cont</td>";
			}
			$html_block .= "</tr>";
		}	
		$html_block 	.= "<td></td>";
		
		for($q = 0; $q < $length_slot; $q++)
		{	
			$html_block 	.= "<td style=\" background-color: #c5ddfc\">Slot $_slot[$q]</td>";
		}
		
		$html_block .= "</table>Blok $block_name - Row $_row[0]</center>";
	}
	else if($length_slot < $length_row)
	{	
		$max = 0;
		for($i = 0;$i < $length_row; $i++)
		{
			if($max < count($cell_row[$_row[$i]]))
			{
				$max = count($cell_row[$_row[$i]]);
			}
		}
		
		for($j = $max; $j > 0; $j--)
		{
			$html_block	.= "<tr><td style=\" background-color: #c5ddfc\">Tier $j</td>";
			for($q = 0; $q < $length_row; $q++)
			{	
				$cont = $cell_row[$_row[$q]][$j];
				$html_block 	.= "<td>$cont</td>";
			}
			$html_block .= "</tr>";
		}	
		
		$html_block 	.= "<td></td>";
		
		for($q = 0; $q < $length_row; $q++)
		{	
			$html_block 	.= "<td style=\" background-color: #c5ddfc\">Row $_row[$q]</td>";
		}
	
		$html_block .= "</table>Blok $block_name - Slot $_slot[0]</center>";
	}
}
else
{
	if($cell[0] > 0)
	{
		$query_blocking_cell = "SELECT * FROM BLOCKING_CELL WHERE INDEX_CELL = '$cell[0]' AND ID_BLOCKING_AREA = '$id_block'";
	
	
		$result	  		= $db->query($query_blocking_cell);
		$row_cell  	  	= $result->fetchRow();
		$block_id  		= $row_cell["ID_BLOCKING_AREA"];
		$index_row		= $row_cell["ROW"];
		$index_slot		= $row_cell["SLOT"];
		
		$query_blocking_area = "SELECT * FROM BLOCKING_AREA WHERE ID = '$block_id'";
		$result_block		 = $db->query($query_blocking_area);
		$row_blocking		 = $result_block->fetchRow();
		$block_name			 = $row_blocking["NAME"];
		
		$query_placement 	 = "SELECT * FROM PLACEMENT WHERE ID_BLOCK = '$block_id' AND ROW_ = '$index_row' AND SLOT_ = '$index_slot' ORDER BY TIER_ DESC";
		
		$result_placement 	 = $db->query($query_placement);
		$row_placement_		 = $result_placement->getAll();
		$i = 1;
		
		$html_block = "";
		
		$html_block = "<center><table border=1>";
		foreach($row_placement_	 as $row_placement )
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
	}
}

echo "$html_block";

?>




















