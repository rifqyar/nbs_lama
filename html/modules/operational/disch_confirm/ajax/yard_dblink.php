<?php
$xml_str = $_POST['xml_'];
$id_user = $_SESSION["ID_USER"];

$db = getDB();
$data = simplexml_load_string($xml_str);

$bay = $_POST['bay'];
if ($bay==1) 
{ 
	$bays = $bay."(".($bay+1).")";  
} 
else if (($bay-1)%4==0) 
{ 
	$bays = $bay."(".($bay+1).")"; 
} 
else 
{ 
	$bays = $bay; 
} 
$id_vs = $_POST['id_vs'];

$get_id_bay_area = "SELECT ID AS ID_BAY FROM STW_BAY_AREA WHERE BAY = '$bay' AND ID_VS = '$id_vs'";
$result10 = $db->query($get_id_bay_area);
$id_bay = $result10->fetchRow();
$bay_area = $id_bay['ID_BAY'];

$id_vessel = $data->vessel;
$width_h = $data->width;
$height_h = $data->height;
$posisi_stack = $data->posisi;
$posisi_palka = $data->palka;
//echo $posisi_stack;die;

$stack_cell = $data->index; 
$index 		= explode(",", $stack_cell);
//echo $stack_cell;die;

$index_sum	= count($index);

foreach ($index as $index_)
{
	if($posisi_stack=='below')
	{
		$cell_add = $index_+$posisi_palka;
	}
	else
	{
		$cell_add = $index_;
	}

	$query_update_stack = "UPDATE STW_BAY_CELL SET STATUS_STACK = 'A' WHERE CELL_NUMBER = '$cell_add' AND ID_BAY_AREA = '$bay_area'";
	$db->query($query_update_stack);
}
	
$block 			= $data->block;
$block_sum	 	= count($block);

foreach ($block as $block_)
{
	$block_name  = $block_->name;	
	$block_color = $block_->color;
	$block_rfr = $block_->rfr;
	
	if($block_imo==NULL)
	{
		$blck_imo = "-";
	}
	else
	{
		$blck_imo = $block_imo;
	}
	
	$cell	= explode(",",$block_->cell);
	$cell_sum	= count($cell);	
	
	foreach ($cell as $cell_)
	{
		if($posisi_stack=='below')
		{
			$address_cell = $cell_+$posisi_palka;
		}
		else
		{
			$address_cell = $cell_;
		}
	
		$alokasi_bay = "begin bay_draw('$address_cell', '$bay_area', '$block_rfr'); end;";
		$db->query($alokasi_bay);
	}
}

	if($posisi_stack == 'above')
	{
		$update_bay_status = "UPDATE STW_BAY_AREA SET ABOVE = 'AKTIF' WHERE ID = '$bay_area'";
		$db->query($update_bay_status);
	}
	else
	{
		$update_bay_status = "UPDATE STW_BAY_AREA SET BELOW = 'AKTIF' WHERE ID = '$bay_area'";
		$db->query($update_bay_status);
	}	

	$keterangan = "ALLOCATE BAY ".$bays." ".$posisi_stack;
	$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','$keterangan',SYSDATE,'$id_user')";
	
	if($db->query($insert_history))
	{				
		echo "OK";
	}
	else
	{
		echo "gagal";
	}

?>




















