<?php
$xml_str = $_POST['xml_'];
$pel_tuj = $_POST['ID_TUJ'];
$id_vs = $_POST['ID_VS'];
$kate = $_POST['KATE'];
$tier_head = $_POST['TIER_HEAD'];
$sts = $_POST['STATUS_C'];
$idb = $_POST['ID_BOOK'];
$act_ei = $_POST['ACT'];
$yard = $_POST['ID_YARD'];
$hz=	$_POST['HZ'];
$user_id=$_SESSION['NAMA_PENGGUNA'];

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
	
	/*if ($posisi=='vertical')
	{
		$qjr="SELECT MAX(ROW_) J_M FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA='$id_block'";
		$jr=$db->query($qjr);
		$jrq=$jr->fetchRow();
		$r_max=$jrq['J_M'];
	}
	print_r($r_max);//die;*/
	
	$query_          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
	$result_         = $db->query($query_);
	
	//print_r($cell_sum);die;
	
	for ($j = 0; $j < $cell_sum; $j++)
	{
		//echo "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";
		$query_1          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
		$result_1         = $db->query($query_1);
        $hasil_1          = $result_1->getAll();
        $i = 0;
        foreach ($hasil_1 as $row)
		{
			if ($cell[$i] == $row['INDEX_CELL']){
				$index_cell = $row['INDEX_CELL'];
				$sizea 		 = 20;
				$query_b     = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='$sizea', ID_KATEGORI = '$kateg', ID_KATEG = '$kate' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
				$az=$db->query($query_b);
			   $i++;
			}
			
		

       
		/*if($posisi=='vertical')
		{
			if($size=='40')
			{
					
				if($v1==1)
				{
					$first='d';
					if($v2==$r_max)
					{
						$v1=0;
						$v2=1;
					}
					else
					{
						$v2++;	
					}
				}
				else
				{
					print_r('v2='.$v2.'<br>');
					$first='b';
					if($v2==$r_max)
					{
						$v1=1;
						$v2=1;
					}
					else
					{
						$v2++;	
					}
				}
							
				$sizex='40';
				$sizea=$sizex.''.$first;
			}
			else if($size=='45')
			{
				if($v1==1)
				{
					$first='d';
					
					if($v2==$r_max)
					{
						$v1=0;
						$v2=1;
					}
					else
					{
						$v2++;	
					}
				}
				else
				{
					$first='b';
					if($v2==$r_max)
					{
						$v1=1;
						$v2=1;
					}
					else
					{
						$v2++;	
					}
				}
				$sizex='40';
				$sizea=$sizex.''.$first;
			}
			else if($size=='20')
			{
				$sizex='20';
				$sizea=$sizex.''.$first;
			}
			else 
			{
				$sizex=NULL;
				$sizea=NULL;
			}
			
		}
		else
		{
			if($size=='40')
			{
					
					if($first=='')
					{
						$first='d';
					}
					else if($first=='d')
					{
						$first='b';
					}
					else if($first=='b')
					{
						$first='d';
					}	
				
				$sizex='40';
				$sizea=$sizex.''.$first;
			}
			else if($size=='45')
			{
					
					if($first=='')
					{
						$first='d';
					}
					else if($first=='d')
					{
						$first='b';
					}
					else if($first=='b')
					{
						$first='d';
					}	
				
				$sizex='40';
				$sizea=$sizex.''.$first;
			}
			else if($size=='20')
			{
				$sizex='20';
				$sizea=$sizex.''.$first;
			}
			else 
			{
				$sizex=NULL;
				$sizea=NULL;
			}
		}*/
		
        $query_slot_row = "SELECT SLOT_, ROW_ FROM YD_BLOCKING_CELL WHERE INDEX_CELL = '$index_cell' AND ID_BLOCKING_AREA = '$id_block'";			
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
		$sizex=20;
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
											ID_KATEGORI
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
											ID_KATEGORI
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
					
					$query_end="UPDATE TB_BOOKING_CONT_AREA_GR SET PLAN_STATUS='Y' WHERE ID_KATEGORI='$kate'";
					$db->query($query_end);
					
				} else {
					$query_end="UPDATE TB_BOOKING_CONT_AREA_GR SET PLAN_STATUS='Y' WHERE ID_KATEGORI='$kate'";
					$db->query($query_end);
				//	$query_end2="UPDATE RBM_H SET FLAG_YAI=1 WHERE trim(NO_UKK)=TRIM('$id_vs')";
				//	$db->query($query_end2);			
			}
		}
	}
 }}}

$query_kate  = "SELECT COUNT(INDEX_CELL)*$tier_head JML FROM YD_BLOCKING_CELL WHERE ID_KATEG = '$kate'";			
$result1     = $db->query($query_kate);
$hasil1      = $result1->fetchRow();
$allo        = $hasil1['JML'];

$query_end="UPDATE TB_BOOKING_CONT_AREA_GR SET ALLOCATED='$allo', ALLOCATED_LEFT = ALLOCATED_LEFT-$allo WHERE trim(ID_KATEGORI)=TRIM('$kate')";
$db->query($query_end);
$query_end2="UPDATE RBM_H SET FLAG_YAI=1 WHERE trim(NO_UKK)=TRIM('$id_vs')";
$db->query($query_end2);

echo "sukses";


?>





















