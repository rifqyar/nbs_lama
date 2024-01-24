<?php
$xml_str 	= $_POST['xml_'];
$pel_tuj 	= $_POST['ID_TUJ'];
$id_vs 		= $_POST['ID_VS'];
$sts 		= $_POST['STATUS_C'];
$idb 		= $_POST['ID_BOOK'];
$act_ei 	= $_POST['ACT'];
$yard 		= $_POST['ID_YARD'];
$hz			= $_POST['HZ'];
$user_id	= $_SESSION['NAMA_PENGGUNA'];
$kate 		= $_POST['KATE'];
$tier_head  = $_POST['TIER_HEAD'];

$db 		= getDB();
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

$ud=$db->query("SELECT NAME FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA='$yard' AND ID='$id_block'");
$posq=$ud->fetchRow();
$nama=$posq['NAME'];
	
$query_8          = "SELECT NVL(MAX(ID),0)+1 ID FROM YD_TEMP_CELL";			
$result_8         = $db->query($query_8);
$hasil_8          = $result_8->fetchRow();
$id  			  = $hasil_8['ID'];

$x = 0;
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
	
	$ud=$db->query("SELECT NAME FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA='$yard' AND ID='$id_block'");
	$posq=$ud->fetchRow();
	$nama=$posq['NAME'];

	$query_          = "SELECT INDEX_CELL FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' ORDER BY INDEX_CELL ASC";			
	$result_         = $db->query($query_);
	
	//print_r($cell_sum);die;

	for ($j=0;$j<=$cell_sum;$j++) {
		
		
	
		if ($id_block == 100){
			if($cell[$j]==0 ||$cell[$j]==1||$cell[$j]==12 ||$cell[$j]==13||$cell[$j]==24 ||$cell[$j]==25||$cell[$j]==36||$cell[$j]==37||$cell[$j]==48||$cell[$j]==49){
				$j = $j-1;
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
					
					for ($i=1;$i<=$tier_head;$i++)
					{
						$cek_placement = "select count(1) JML from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";			
						//print_R($query_slot_row.'<br>');
						$result2        = $db->query($cek_placement);
						$hasil2         = $result2->fetchRow();
						$place          = $hasil2['JML'];
					
						if ($place <= 0){
						$query_2          = "SELECT SLOT_, ROW_ FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL = '$index_cell'";			
						$result_2         = $db->query($query_2);
						$hasil_2          = $result_2->fetchRow();
						$sl  			  = $hasil_2['SLOT_'];
						$ro			      = $hasil_2['ROW_'];
						
						$query9 = "INSERT INTO YD_TEMP_CELL (ID,CELL_NUMBER,
															  SLOT_,
															  ROW_,
															  ID_BLOCKING_AREA,
															  NAMA_BLOCK,
															  STATUS) VALUES 
															('$id',
															'$index_cell',
															'$sl',
															'$ro',
															'$id_block',
															'$nama',
															'ALO')";
						$db->query($query9);
						}
					}
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
					
					for ($i=1;$i<=$tier_head;$i++)
					{
						$cek_placement = "select count(1) JML from yd_placement_yard where id_cell = '$index_cell' and tier_yard = '$i'";			
						//print_R($query_slot_row.'<br>');
						$result2        = $db->query($cek_placement);
						$hasil2         = $result2->fetchRow();
						$place          = $hasil2['JML'];
					
						if ($place <= 0){
						$query_2          = "SELECT SLOT_, ROW_ FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' AND INDEX_CELL = '$index_cell'";			
						$result_2         = $db->query($query_2);
						$hasil_2          = $result_2->fetchRow();
						$sl  			  = $hasil_2['SLOT_'];
						$ro			      = $hasil_2['ROW_'];
						
						$query9 = "INSERT INTO YD_TEMP_CELL (ID,CELL_NUMBER,
															  SLOT_,
															  ROW_,
															  ID_BLOCKING_AREA,
															  NAMA_BLOCK,
															  STATUS) VALUES 
															('$id',
															'$index_cell',
															'$sl',
															'$ro',
															'$id_block',
															'$nama',
															'ALO')";
						$db->query($query9);
						}
				}
		}
	}
}


$ud2=$db->query("SELECT MIN(ROW_) RM, MIN(SLOT_) SM,  MAX(ROW_) RA, MAX(SLOT_) SA, COUNT(CELL_NUMBER) JML   FROM YD_TEMP_CELL WHERE STATUS = 'ALO' AND ID_BLOCKING_AREA = '$id_block' AND ID = '$id'");
$posq2=$ud2->fetchRow();
$rm=$posq2['RM'];
$sm=$posq2['SM'];
$ra=$posq2['RA'];
$sa=$posq2['SA'];
$ka=$posq2['JML'];

$query9 = "INSERT INTO YD_LOG_ALLOCATION (ID_LOG,NAMA_BLOCK,ID_KATEGORI, KATEGORI, ID_USER,SLOT_AWAL,SLOT_AKHIR,ROW_AWAL,ROW_AKHIR,TGL_UPDATE, ACTIVITY) VALUES 
('','$nama','$kate','$kateg','$user_id','$sm','$sa','$rm','$ra',SYSDATE,'ALLOCATE')";
		$db->query($query9);
		
		

$ud2=$db->query(" SELECT COUNT(1) JML FROM yd_yard_allocation_planning WHERE  ID_BLOCKING_AREA = '$id_block'");
$posq2=$ud2->fetchRow();
$jml_block=$posq2['JML'];

$ud3=$db->query("SELECT TEUS FROM TB_BOOKING_CONT_AREA_GR WHERE  ID_KATEGORI = '$kate'");
$posq3=$ud3->fetchRow();
$teus=$posq3['TEUS'];

$ud4=$db->query("SELECT COUNT(1)*5 JML FROM YD_BLOCKING_CELL WHERE  ID_BLOCKING_AREA = '$id_block'");
$posq4=$ud4->fetchRow();
$jml_alok_block=$posq4['JML'];


$ud6=$db->query("SELECT COUNT(1) JML FROM YD_YARD_ALLOCATION_PLANNING WHERE  ID_BLOCKING_AREA = '$id_block' AND ID_BOOK = '$kate'");
$posq6=$ud6->fetchRow();
$jml_kate=$posq6['JML'];

$ud7=$db->query("SELECT COUNT(1) JML FROM YD_PLACEMENT_YARD WHERE  ID_BLOCKING_AREA = '$id_block'");
$posq7=$ud7->fetchRow();
$jml_real=$posq7['JML'];


$query9 = "DELETE FROM YD_TEMP_ALOKASI where ID_BLOCK = '$id_block' AND ID_KATEGORI = '$kate'";
$db->query($query9);
	
$query5 = "INSERT INTO YD_TEMP_ALOKASI (ID_BLOCK,NAMA_BLOCK, ID_KATEGORI, ALOKASI, JUMLAH_ALOKASI,TOTAL_ALOKASI,SISA_ALOKASI_KATEGORI,SISA_KATEGORI_BLOK, ID_USER, TGL_UPDATE) VALUES 
('$id_block','$nama','$kate','$ka',$jml_kate+$ka,$jml_block+$ka,$teus-($jml_kate+$ka),$jml_alok_block-($ka+$jml_block+$jml_real),'$user_id',SYSDATE)";
$db->query($query5);


//echo "sukses";


?>





















