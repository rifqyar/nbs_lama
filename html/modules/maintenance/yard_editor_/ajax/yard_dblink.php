<?php
$xml_str = $_POST['xml_'];

$db 	= getDB();

$data = simplexml_load_string($xml_str);

	
$block 			= $data->block;
$block_sum	 	= count($block);

foreach ($block as $block_)
{
	$block_name  = $block_->name;	
	$block_color = $block_->color;
        $block_tier  = $block_->tier;	
        $block_posisi = $block_->posisi;
        $block_kategori = $block_->kategori;
	
	$query_blocking_area = "INSERT INTO YD_BLOCKING_AREA(ID_YARD_AREA, NAME, COLOR, TIER, POSISI) VALUES('$yard_id', '$block_name', '$block_color', '$block_tier', '$block_posisi')";
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

echo "success";


?>




















