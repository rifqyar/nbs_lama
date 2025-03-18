<?php
$xml_str = $_POST['xml_'];

$db 	= getDB();

$data = simplexml_load_string($xml_str);


$block 			= $data->block;
$block_sum	 	= count($block);

foreach ($block as $block_)
{
	$size   = $block_->size;	
	$type   = $block_->type;
        $vessel = $block_->vessel;
        $kategori = $block_->kategori;
        $id_block = $block_->id_block;

	$cell	= explode(",",$block_->cell);
	$cell_sum	= count($cell);
	
	
	for ($j = 0; $j < $cell_sum; $j++)
	{
                        //mencari index_cell ke ke i
                        $query_          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
			$result_         = $db->query($query_);
                        $hasil_          = $result_->getAll();
                        $i = 0;
                        foreach ($hasil_ as $row){
                            if ($i == $cell[$j]){
                                $index_cell = $row['INDEX_CELL'];
                            }
                        $i++;    
                        }
                        
                        $query_slot_row = "SELECT SLOT_, ROW_ FROM YD_BLOCKING_CELL WHERE INDEX_CELL = '$index_cell' AND ID_BLOCKING_AREA = '$id_block'";			
			$result         = $db->query($query_slot_row);
                        $hasil          = $result->fetchRow();
                        $slot_          = $hasil['SLOT_'];
                        $row_           = $hasil['ROW_'];
                        
			$query_block_cell = "INSERT INTO YD_YARD_ALLOCATION_PLANNING(INDEX_CELL, ID_BLOCKING_AREA, ROW_, SLOT_, ID_VS, STATUS_BM, SIZE_, TYPE_, KATEGORI) VALUES('$index_cell', '$id_block', '$row_', '$slot_', '$vessel', 'M', '$size','$type','$kategori')";			
			$db->query($query_block_cell);
	}
}

echo "success";


?>




















