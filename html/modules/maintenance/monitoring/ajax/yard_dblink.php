<?php
$xml_str = $_POST['xml_'];

$db 	= getDB();

$data = simplexml_load_string($xml_str);

$name 		= $_POST['yard_name'];
$width  	= $data->width;
$height 	= $data->height;

$query_yard_area = "INSERT INTO YD_YARD_AREA(NAMA_YARD, WIDTH, HEIGHT) VALUES('$name',$width,$height)";



if($db->query($query_yard_area))
{
	$query_yard_id = "SELECT * FROM YD_YARD_AREA WHERE NAMA_YARD = '$name' ORDER BY ID DESC";
	$result	  = $db->query($query_yard_id);
	$row  	  = $result->fetchRow();
	$yard_id  = $row["ID"];
}


$total = $width * $height;

for($i = 0; $i < $total; $i++)
{
	$query_yard_cell = "INSERT INTO YD_YARD_CELL(ID_YARD_AREA, INDEX_CELL, STACK) VALUES('$yard_id',$i,0)";
	
	$db->query($query_yard_cell);
}

$stack_cell = $data->index; 
$index 		= explode(",", $stack_cell);

$index_sum	 	= count($index);

foreach ($index as $index_)
{
	$query_update_stack = "UPDATE YD_YARD_CELL SET STACK = 1 WHERE INDEX_CELL = '$index_' AND ID_YARD_AREA = '$yard_id'";
	$db->query($query_update_stack);
}
	
$block 			= $data->block;
$block_sum	 	= count($block);

foreach ($block as $block_)
{
	$block_name  = $block_->name;	
	$block_color = $block_->color;
	
	$query_blocking_area = "INSERT INTO YD_BLOCKING_AREA(ID_YARD_AREA, NAME, COLOR) VALUES('$yard_id', '$block_name', '$block_color')";
	$db->query($query_blocking_area);
	
	$query_block_id = "SELECT * FROM BLOCKING_AREA WHERE NAME = '$block_name' AND ID_YARD_AREA = '$yard_id'";
	$result	  		= $db->query($query_block_id);
	$row  	  		= $result->fetchRow();
	$block_id  		= $row["ID"];
	
	$cell	= explode(",",$block_->cell);
	$cell_sum	= count($cell);
	
	
	for ($j = 0; $j < $cell_sum; $j++)
	{
		//set row and slot
		if($j == 0)
		{
			$slot = 1;
			$row  = 1;
			
			$query_block_cell = "INSERT INTO YD_BLOCKING_CELL(INDEX_CELL, ID_BLOCKING_AREA, ROW_, SLOT_) VALUES($cell[$j], $block_id, $row, $slot)";
			$db->query($query_block_cell);
		}
		else
		{
			if($cell[$j-1] == ($cell[$j]-1))
			{
				$slot++;
			}
			else
			{
				$row++;
				$slot = 1;
			}
			
			$query_block_cell = "INSERT INTO YD_BLOCKING_CELL(INDEX_CELL, ID_BLOCKING_AREA, ROW_, SLOT_) VALUES($cell[$j], $block_id, $row, $slot)";			
			$db->query($query_block_cell);
		}
	}
}

echo "success";


?>




















