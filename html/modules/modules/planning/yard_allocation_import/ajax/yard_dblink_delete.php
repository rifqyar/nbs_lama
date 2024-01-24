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
$tier_head = $_POST['TIER_HEAD'];

$db 	= getDB();



//print($xml_str.'<br>');
$data = simplexml_load_string($xml_str);


$block 			= $data->block;
$block_sum	 	= count($block);

$first='';

$v1=1;
$v2=1;

$query_8          = "SELECT NVL(MAX(ID),0)+1 ID FROM YD_TEMP_CELL";			
$result_8         = $db->query($query_8);
$hasil_8          = $result_8->fetchRow();
$id  			  = $hasil_8['ID'];



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

	
	//print_r($r_max);//die;
	
	$query_          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
	$result_         = $db->query($query_);
	
	//print_r($cell_sum);die;
	
	for ($j = 0; $j < $cell_sum; $j++)
	{
		if ($id_block == 100){
					if ($cell[$j] == 0 ||$cell[$j] == 1 ||$cell[$j] == 12 ||$cell[$j] == 13 ||$cell[$j] == 24 ||$cell[$j] == 25 ||$cell[$j] == 36 ||$cell[$j] == 37 ||$cell[$j] == 48 ||$cell[$j] == 49){
							$a=0;
						} else if (($cell[$j]>=2) AND ($cell[$j] <=11)){
						  $a = $cell[$j]-2;
						} else if (($cell[$j]>=14) AND ($cell[$j] <=23)){
						  $a = $cell[$j]-4;
						} else if (($cell[$j]>=26) AND ($cell[$j] <=35)){
						  $a = $cell[$j]-6;
						} else if (($cell[$j]>=38) AND ($cell[$j] <=47)){
						  $a = $cell[$j]-8;
						} else if ($cell[$j]>=50){
						  $a = $cell[$j]-10;
						}
						
				$query_          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
				$result_         = $db->query($query_);
				$hasil_          = $result_->getAll();
				$i = 0;
				foreach ($hasil_ as $row)
				{
					if ($i == $a)
					{
						$index_cell = $row['INDEX_CELL'];
					}
					$i++;    
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
		}
		
		
		$query_b     = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_ALLO='', ID_KATEGORI = '', ID_KATEG = '' WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL='$index_cell'";
		$az=$db->query($query_b);
		
		$query_2          = "SELECT SLOT_, ROW_ FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL = '$index_cell'";			
		$result_2         = $db->query($query_2);
	    $hasil_2          = $result_2->fetchRow();
		$sl  			  = $hasil_2['SLOT_'];
		$ro			      = $hasil_2['ROW_'];
		
		$query_3          = "SELECT ID_BOOK FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL = '$index_cell'";			
		$result_3         = $db->query($query_3);
	    $hasil_3          = $result_3->fetchRow();
		$kate  			  = $hasil_3['ID_BOOK'];
		
		$ud=$db->query("SELECT NAME FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA='$yard' AND ID='$id_block'");
		$posq=$ud->fetchRow();
		$nama=$posq['NAME'];
		
		$query9 = "INSERT INTO YD_TEMP_CELL (ID,CELL_NUMBER,
												  SLOT_,
												  ROW_,
												  ID_BLOCKING_AREA,
												  NAMA_BLOCK,
												  ID_KATEGORI,
												  STATUS) VALUES 
												('$id',
												'$index_cell',
												'$sl',
												'$ro',
												'$id_block',
												'$nama',
												'$kate',
												'DEL')";
		$db->query($query9);
		
		$ay=$db->query("SELECT TRIM(KATEGORI) KATEGORI FROM TB_BOOKING_CONT_AREA_GR WHERE ID_KATEGORI ='$kate' AND ID_VS = '$id_vs'");
		$ayu=$ay->fetchRow();
		$kateg=$ayu['KATEGORI'];
			
		$del  = "DELETE FROM YD_YARD_ALLOCATION_PLANNING WHERE INDEX_CELL = '$index_cell' AND ID_BLOCKING_AREA = '$id_block' AND ID_BOOK = '$kate'";			
		$db->query($del);
	}
}


$ud2=$db->query("SELECT MIN(ROW_) RM, MIN(SLOT_) SM,  MAX(ROW_) RA, MAX(SLOT_) SA, COUNT(CELL_NUMBER) JML   FROM YD_TEMP_CELL WHERE STATUS = 'DEL' AND ID_BLOCKING_AREA = '$id_block' AND ID = '$id'");
$posq2=$ud2->fetchRow();
$rm=$posq2['RM'];
$sm=$posq2['SM'];
$ra=$posq2['RA'];
$sa=$posq2['SA'];
$ka=$posq2['JML'];

$query9 = "INSERT INTO YD_LOG_ALLOCATION (ID_LOG,NAMA_BLOCK,ID_KATEGORI, KATEGORI, ID_USER,SLOT_AWAL,SLOT_AKHIR,ROW_AWAL,ROW_AKHIR,TGL_UPDATE, ACTIVITY) VALUES 
('','$nama','$kate','$kateg','$user_id','$sm','$sa','$rm','$ra',SYSDATE,'DELETE')";
		$db->query($query9);
	
	
$query_kate  = "SELECT COUNT(1) JML FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BOOK = '$kate'";			
$result1     = $db->query($query_kate);
$hasil1      = $result1->fetchRow();
$allo        = $hasil1['JML'];

$query_end="UPDATE TB_BOOKING_CONT_AREA_GR SET ALLOCATED='$allo', ALLOCATED_LEFT = TEUS-$allo WHERE trim(ID_KATEGORI)=TRIM('$kate')";
$db->query($query_end);
$query_end2="UPDATE RBM_H SET FLAG_YAI=1 WHERE trim(NO_UKK)=TRIM('$id_vs')";
$db->query($query_end2);

//DIE;
echo "allocation canceled";


?>





















