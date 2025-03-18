<?php
$xml_str = $_POST['xml_'];
$pel_tuj = $_POST['ID_TUJ'];
$id_vs = $_POST['ID_VS'];
$sts = $_POST['STATUS_C'];
$idb = $_POST['ID_BOOK'];
$act_ei = $_POST['ACT'];
$yard = $_POST['ID_YARD'];
$hz=	$_POST['HZ'];
$user_id=$_SESSION['NAMA_PENGGUNA'];
$kate = $_POST['KATE'];
$tier_head = $_POST['TIER_HEAD'];

$db 	= getDB();


//print($xml_str.'<br>');
$data = simplexml_load_string($xml_str);


$block 			= $data->block;
$block_sum	 	= count($block);

$first='';

$v1=1;
$v2=1;


$ay=$db->query("SELECT TRIM(KATEGORI) KATEGORI FROM TB_BOOKING_CONT_AREA_GR WHERE ID_KATEGORI ='$kate' AND ID_VS = '$id_vs'");
$ayu=$ay->fetchRow();
$kateg=$ayu['KATEGORI'];

// insert cell last selected
$j = 0;
foreach ($block as $block_)
{
	$size   	= $block_->size;	
	$type   	= $block_->type;
	$tier   	= $block_->tier;
    $vessel 	= $block_->vessel;
    $kategori 	= $block_->kategori;
    $id_block 	= $block_->id_block;

	$cell		= explode(",",$block_->cell);
	$cell_sum	= count($cell);
	$jml_cont_book = $cell_sum*$tier_head;
	$pel_tujx = str_replace(' ','',$pel_tuj);
	$idbx= str_replace(' ','',$idb);

	$ud=$db->query("SELECT POSISI FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA='$yard' AND ID='$id_block'");
	$posq=$ud->fetchRow();
	$posisi=$posq['POSISI'];
	
	if ($posisi=='vertical')
	{
		$qjr="SELECT MAX(ROW_) J_M FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA='$id_block'";
		$jr=$db->query($qjr);
		$jrq=$jr->fetchRow();
		$r_max=$jrq['J_M'];
	}
	//print_r($r_max);//die;
	
	$query_          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
	$result_         = $db->query($query_);
	
	//print_r($cell_sum);die;
	
	
	if ($j<= $cell_sum)
	{
		if ($id_block == 100){
			if($cell[$j]==0 ||$cell[$j]==1||$cell[$j]==12 ||$cell[$j]==13||$cell[$j]==24 ||$cell[$j]==25||$cell[$j]==36||$cell[$j]==37||$cell[$j]==48||$cell[$j]==49){
				$a = 0;
			} else {
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
				
				$sizea = 20;
				$sizex = 20;
				//echo $id_block;
				//echo $index_cell;
				//echo 'dama';
			//	echo "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='$sizea' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
				$query_b     = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='$sizea', ID_KATEGORI = '$kateg', ID_KATEG = '$kate' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
				$az=$db->query($query_b);
				//ECHO $query_b;
				
				$query_slot_row = "SELECT SLOT_, ROW_ FROM YD_BLOCKING_CELL WHERE INDEX_CELL = $index_cell AND ID_BLOCKING_AREA = '$id_block'";			
				//print_R($query_slot_row.'<br>');
				$result         = $db->query($query_slot_row);
				$hasil          = $result->fetchRow();
				$slot_          = $hasil['SLOT_'];
				$row_           = $hasil['ROW_'];
				
				//print_r($tier);
				//echo "SELECT MAX(TIER_) TIER FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slot_' AND ROW_ = '$row_'";
				if ($tier == ''){
					$query_tier	   = "SELECT MAX(TIER_) TIER FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slot_' AND ROW_ = '$row_'";			
					//print_R($query_slot_row.'<br>');
					$result_tier   = $db->query($query_tier);
					$hasil_tier    = $result_tier->fetchRow();
					$tier          = $hasil_tier['TIER'];
				}
				//echo 'dama';
				//echo $tier;
				//echo 'dama';
				//echo $sizex;
				for ($i=1;$i<=$tier;$i++)
				{
				//	echo "select id_placement from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";
					$cek_placement = "select id_placement from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";			
					//print_R($query_slot_row.'<br>');
					$result2        = $db->query($cek_placement);
					$hasil2         = $result2->fetchRow();
					$place          = $hasil2['ID_PLACEMENT'];
				
					if ($place == NULL){
							$query_block_cell3 = "delete from YD_YARD_ALLOCATION_PLANNING where
													ID_BLOCKING_AREA ='$id_block' and INDEX_CELL='$index_cell'
													and ROW_ = '$row_' and SLOT_='$slot_' and TIER_='$i'";
							$db->query($query_block_cell3);
							
							if (($sizex<>NULL) OR ($sizex<>0))
							{
								$query_block_cell = "INSERT INTO YD_YARD_ALLOCATION_PLANNING
												(
													ID,
													ID_BLOCKING_AREA,
													INDEX_CELL,
													ROW_,
													SLOT_,
													ID_VS,
													STATUS_BM,
													SIZE_,
													TYPE_,
													KATEGORI,
													TIER_,
													STATUS_CONT,
													HZ,
													ID_USER,
													ID_BOOK
												) 
												VALUES
												(
													seq_allo_plan_h.nextval,
													'$id_block',
													'$index_cell',
													'$row_',
													'$slot_',
													'$id_vs',
													'Bongkar',
													'$size',
													'$type',
													'$kateg',
													'$i',
													'$sts',
													'$hz',
													'$user_id',
													'$kate'
												)";			
							$query_block_cell2 = "INSERT INTO YD_YARD_ALLO_PLANNING_HIST
												(
													ID,
													ID_BLOCKING_AREA,
													INDEX_CELL,
													ROW_,
													SLOT_,
													ID_VS,
													STATUS_BM,
													SIZE_,
													TYPE_,
													KATEGORI,
													TIER_,
													STATUS_CONT,
													HZ,
													ID_USER,
													ID_BOOK
												) 
												VALUES
												(
													seq_allo_plan_h.nextval,
													'$id_block',
													'$index_cell',
													'$row_',
													'$slot_',
													'$id_vs',
													'Bongkar',
													'$size',
													'$type',
													'$kateg',
													'$i',
													'$sts',
													'$hz',
													'$user_id',
													'$kate'
												)";			
												
							$db->query($query_block_cell);
							$db->query($query_block_cell2);
						} else {
							$query_end="UPDATE TB_BOOKING_CONT_AREA_GR SET PLAN_STATUS='Y' WHERE ID_KATEGORI='$kate'";
							$db->query($query_end);
						//	$query_end2="UPDATE RBM_H SET FLAG_YAI=1 WHERE trim(NO_UKK)=TRIM('$id_vs')";
						//	$db->query($query_end2);			
						}
					}
				}
				$j++;
			}
		} else {
		
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
			
			$sizea = 20;
			$sizex = 20;
			//echo $id_block;
			//echo $index_cell;
			//echo 'dama';
		//	echo "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='$sizea' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
			$query_b     = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='$sizea', ID_KATEGORI = '$kateg', ID_KATEG = '$kate' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
			$az=$db->query($query_b);
			//ECHO $query_b;
			
			$query_slot_row = "SELECT SLOT_, ROW_ FROM YD_BLOCKING_CELL WHERE INDEX_CELL = $index_cell AND ID_BLOCKING_AREA = '$id_block'";			
			//print_R($query_slot_row.'<br>');
			$result         = $db->query($query_slot_row);
			$hasil          = $result->fetchRow();
			$slot_          = $hasil['SLOT_'];
			$row_           = $hasil['ROW_'];
			
			//print_r($tier);
			//echo "SELECT MAX(TIER_) TIER FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slot_' AND ROW_ = '$row_'";
			if ($tier == ''){
				$query_tier	   = "SELECT MAX(TIER_) TIER FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slot_' AND ROW_ = '$row_'";			
				//print_R($query_slot_row.'<br>');
				$result_tier   = $db->query($query_tier);
				$hasil_tier    = $result_tier->fetchRow();
				$tier          = $hasil_tier['TIER'];
			}
			//echo 'dama';
			//echo $tier;
			//echo 'dama';
			//echo $sizex;
			for ($i=1;$i<=$tier;$i++)
			{
			//	echo "select id_placement from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";
				$cek_placement = "select id_placement from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";			
				//print_R($query_slot_row.'<br>');
				$result2        = $db->query($cek_placement);
				$hasil2         = $result2->fetchRow();
				$place          = $hasil2['ID_PLACEMENT'];
			
				if ($place == NULL){
						$query_block_cell3 = "delete from YD_YARD_ALLOCATION_PLANNING where
												ID_BLOCKING_AREA ='$id_block' and INDEX_CELL='$index_cell'
												and ROW_ = '$row_' and SLOT_='$slot_' and TIER_='$i'";
						$db->query($query_block_cell3);
						
						if (($sizex<>NULL) OR ($sizex<>0))
						{
							$query_block_cell = "INSERT INTO YD_YARD_ALLOCATION_PLANNING
											(
												ID,
												ID_BLOCKING_AREA,
												INDEX_CELL,
												ROW_,
												SLOT_,
												ID_VS,
												STATUS_BM,
												SIZE_,
												TYPE_,
												KATEGORI,
												TIER_,
												STATUS_CONT,
												HZ,
												ID_USER,
												ID_BOOK
											) 
											VALUES
											(
												seq_allo_plan_h.nextval,
												'$id_block',
												'$index_cell',
												'$row_',
												'$slot_',
												'$id_vs',
												'Bongkar',
												'$size',
												'$type',
												'$kateg',
												'$i',
												'$sts',
												'$hz',
												'$user_id',
												'$kate'
											)";			
						$query_block_cell2 = "INSERT INTO YD_YARD_ALLO_PLANNING_HIST
											(
												ID,
												ID_BLOCKING_AREA,
												INDEX_CELL,
												ROW_,
												SLOT_,
												ID_VS,
												STATUS_BM,
												SIZE_,
												TYPE_,
												KATEGORI,
												TIER_,
												STATUS_CONT,
												HZ,
												ID_USER,
												ID_BOOK
											) 
											VALUES
											(
												seq_allo_plan_h.nextval,
												'$id_block',
												'$index_cell',
												'$row_',
												'$slot_',
												'$id_vs',
												'Bongkar',
												'$size',
												'$type',
												'$kateg',
												'$i',
												'$sts',
												'$hz',
												'$user_id',
												'$kate'
											)";			
											
						$db->query($query_block_cell);
						$db->query($query_block_cell2);
					} else {
						$query_end="UPDATE TB_BOOKING_CONT_AREA_GR SET PLAN_STATUS='Y' WHERE ID_KATEGORI='$kate'";
						$db->query($query_end);
					//	$query_end2="UPDATE RBM_H SET FLAG_YAI=1 WHERE trim(NO_UKK)=TRIM('$id_vs')";
					//	$db->query($query_end2);			
					}
				}
			}
		$j++;
		}
}
}

/*
//insert cell that has been saved in yd_temp_cell
	$query_          = "SELECT CELL_NUMBER FROM YD_TEMP_CELL WHERE STATUS = 'ALO' ORDER BY CELL_NUMBER ASC";			
	$result_         = $db->query($query_);
    $hasil_          = $result_->getAll();

    foreach ($hasil_ as $row)
	{
		$index_cell = $row['CELL_NUMBER'];	 
		$sizea = 20;
		$sizex = 20;
		//echo $id_block;
		//echo $index_cell;
		//echo 'dama';
	//	echo "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='$sizea' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
		$query_b     = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='$sizea', ID_KATEGORI = '$kateg', ID_KATEG = '$kate' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
		$az=$db->query($query_b);
		//ECHO $query_b;
		
		for ($i=1;$i<=$tier_head;$i++)
		{
		//	echo "select id_placement from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";
			$cek_placement = "select id_placement from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";			
			//print_R($query_slot_row.'<br>');
			$result2        = $db->query($cek_placement);
			$hasil2         = $result2->fetchRow();
			$place          = $hasil2['ID_PLACEMENT'];
		
			if ($place == NULL){
					
						$query_block_cell = "INSERT INTO YD_YARD_ALLOCATION_PLANNING
										(
											ID,
											ID_BLOCKING_AREA,
											INDEX_CELL,
											ROW_,
											SLOT_,
											ID_VS,
											STATUS_BM,
											SIZE_,
											TYPE_,
											KATEGORI,
											TIER_,
											STATUS_CONT,
											HZ,
											ID_USER,
											ID_BOOK
										) 
										VALUES
										(
											seq_allo_plan_h.nextval,
											'$id_block',
											'$index_cell',
											'$row_',
											'$slot_',
											'$id_vs',
											'Bongkar',
											'$size',
											'$type',
											'$kateg',
											'$i',
											'$sts',
											'$hz',
											'$user_id',
											'$kate'
										)";			
					$query_block_cell2 = "INSERT INTO YD_YARD_ALLO_PLANNING_HIST
										(
											ID,
											ID_BLOCKING_AREA,
											INDEX_CELL,
											ROW_,
											SLOT_,
											ID_VS,
											STATUS_BM,
											SIZE_,
											TYPE_,
											KATEGORI,
											TIER_,
											STATUS_CONT,
											HZ,
											ID_USER,
											ID_BOOK
										) 
										VALUES
										(
											seq_allo_plan_h.nextval,
											'$id_block',
											'$index_cell',
											'$row_',
											'$slot_',
											'$id_vs',
											'Bongkar',
											'$size',
											'$type',
											'$kateg',
											'$i',
											'$sts',
											'$hz',
											'$user_id',
											'$kate'
										)";			
										
					$db->query($query_block_cell);
					$db->query($query_block_cell2);
				} 
			}
		}

*/
	
$del  = "DELETE FROM YD_TEMP_CELL";			
$db->query($del);		


$query_kate  = "SELECT COUNT(1) JML FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BOOK = '$kate'";			
$result1     = $db->query($query_kate);
$hasil1      = $result1->fetchRow();
$allo        = $hasil1['JML'];

$query_end="UPDATE TB_BOOKING_CONT_AREA_GR SET ALLOCATED='$allo', ALLOCATED_LEFT = TEUS-$allo WHERE trim(ID_KATEGORI)=TRIM('$kate')";
$db->query($query_end);
$query_end2="UPDATE RBM_H SET FLAG_YAI=1 WHERE trim(NO_UKK)=TRIM('$id_vs')";
$db->query($query_end2);

//DIE;
echo "sukses";


?>





















