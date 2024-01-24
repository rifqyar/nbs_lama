<?php
$xml_str = $_POST['xml_'];

$db 	= getDB();

$data = simplexml_load_string($xml_str);

$name 		= $_POST['yard_name'];
$width  	= $data->width;
$height 	= $data->height;

$query_yard_area = "INSERT INTO YD_YARD_AREA(NAMA_YARD, WIDTH, LENGTH) VALUES('$name',$width,$height)";



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
	$query_yard_cell = "INSERT INTO YD_YARD_CELL(ID_YARD_AREA, INDEX_CELL, STATUS_STACK) VALUES('$yard_id',$i,0)";
	
	$db->query($query_yard_cell);
}

$stack_cell = $data->index; 
$index 		= explode(",", $stack_cell);

$index_sum	 	= count($index);

foreach ($index as $index_)
{
	$query_update_stack = "UPDATE YD_YARD_CELL SET STATUS_STACK = 1 WHERE INDEX_CELL = '$index_' AND ID_YARD_AREA = '$yard_id'";
	$db->query($query_update_stack);
}
	
$block 			= $data->block;
$block_sum	 	= count($block);

foreach ($block as $block_)
{
	$block_name  = $block_->name;	
//	$block_color = $block_->color;
        $block_tier  = $block_->tier;	
        $block_posisi = $block_->posisi;
        $block_kategori = $block_->kategori;
	
	$query_blocking_area = "INSERT INTO YD_BLOCKING_AREA(ID_YARD_AREA, NAME, COLOR, TIER, POSISI) VALUES('$yard_id', '$block_name', '', '$block_tier', '$block_posisi')";
	$db->query($query_blocking_area);
	
	$query_block_id = "SELECT * FROM YD_BLOCKING_AREA WHERE NAME = '$block_name' AND ID_YARD_AREA = '$yard_id'";
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
			
			$query_block_cell = "INSERT INTO YD_BLOCKING_CELL(INDEX_CELL, ID_BLOCKING_AREA, ROW_, SLOT_, STATUS_BM) VALUES($cell[$j], $block_id, $row, $slot,'$block_kategori')";
			$db->query($query_block_cell);
		}
		else
		{
			 if ($block_posisi == 'horizontal'){
                                        if($cell[$j-1] == ($cell[$j]-1))
                                        {
                                                $slot++;
                                        }
                                        else
                                        {
                                            $row++;
                                            $slot = 1;
                                        }
			
                                        $query_block_cell = "INSERT INTO YD_BLOCKING_CELL(INDEX_CELL, ID_BLOCKING_AREA, ROW_, SLOT_, STATUS_BM) VALUES($cell[$j], $block_id, $row, $slot ,'$block_kategori')";			
                                        $db->query($query_block_cell);
                                        
                         } else {
                                        if($cell[$j-1] == ($cell[$j]-1))
                                        {
                                            $row++;
                                        }
                                        else
                                        {
                                            $slot++;
                                            $row = 1;
                                        }
                                        
                                        $query_block_cell = "INSERT INTO YD_BLOCKING_CELL(INDEX_CELL, ID_BLOCKING_AREA, ROW_, SLOT_, STATUS_BM) VALUES($cell[$j], $block_id, $row, $slot, '$block_kategori')";			
                                        $db->query($query_block_cell);
                         }
		}
	}
}

              $query_blocking_area = "INSERT INTO YD_BLOCKING_AREA(ID_YARD_AREA, NAME, COLOR, TIER, POSISI) VALUES('$yard_id', 'NULL', 'ffffff', '0', '')";
              $db->query($query_blocking_area);
	
              $max = "SELECT MAX(ID) ID FROM YD_BLOCKING_AREA";
              $result_l=$db->query($max);
              $row =$result_l->fetchRow();
              $block_id = $row['ID'];
              
              $data = "SELECT a.INDEX_CELL FROM YD_YARD_CELL a WHERE a.ID_YARD_AREA = '$yard_id' AND a.INDEX_CELL NOT IN (SELECT d.INDEX_CELL
                                        FROM YD_BLOCKING_AREA e, YD_BLOCKING_CELL d
                                        WHERE e.ID = d.ID_BLOCKING_AREA
                                        AND e.ID_YARD_AREA = '$yard_id')";
              $result   =$db->query($data);
              $row_     =$result->getAll();
              
              foreach ($row_ as $row1){
                  $id = $row1['INDEX_CELL'];
                  
                  $query_block_cell = "INSERT INTO YD_BLOCKING_CELL(INDEX_CELL, ID_BLOCKING_AREA, ROW_, SLOT_, STATUS_BM) VALUES('$id', '$block_id', '0', '0', '')";			
                  $db->query($query_block_cell);
              }        

echo "success";


?>




















