<?php
$xml_str = $_POST['xml_'];

$db 	= getDB();

$data = simplexml_load_string($xml_str);


$block 			= $data->block;
$block_sum	 	= count($block);
$first='';
foreach ($block as $block_)
{

    $kategori 	= $block_->kategori;
	if ($kategori == 'b'){
		$kate = 'Bongkar';
		$kate2 = 'Muat';
	} else {
		$kate = 'Muat';
		$kate2 = 'Bongkar';
	}
    $id_block 	= $block_->id_block;

	$cell		= explode(",",$block_->cell);
	$cell_sum	= count($cell);
	
	for ($j = 0; $j < $cell_sum; $j++)
	{
		
		$query_          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
		$result_         = $db->query($query_);
        $hasil_          = $result_->getAll();
        $i = 0;
        foreach ($hasil_ as $row)
		{
			if ($i == $cell[$j])
			{
				$index_cell = $row['INDEX_CELL'];
            }
            $i++;    
        }
		
		$query_b     = "UPDATE YD_BLOCKING_CELL SET STATUS_BM = '$kate' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL=$index_cell";
		$az=$db->query($query_b);

		$query_block_cell = "INSERT INTO YD_HISTORY_YARD (BLOK,
								  INDEX_CELL,
								  STATUS_BM_OLD,
								  STATUS_BM_NEW,
								  TGL_UPDATE) VALUES('$id_block', '$index_cell', '$kate2', '$kate', SYSDATE)";			
		$db->query($query_block_cell);
	}
}

echo "success";


?>




















