<?php
// Test CVS
//echo $_FILES[uploadfile];die;
//echo $_POST['id_vs'];
//echo $_POST['modus'];//ssecho 'dama';die;

require_once 'excel/reader.php';

$data = new Spreadsheet_Excel_Reader();

$data->setOutputEncoding('CP1251');

$data->read($_FILES['uploadfile']['tmp_name']);
error_reporting(E_ALL ^ E_NOTICE);

$db = getDB();
    
                //echo 'dama';die;
                $db   = getDB();
			/*	$id_vs = $_POST['id_vs'];
				$id_user = $_SESSION["PENGGUNA_ID"];
                if ($_POST['modus'] == 'overwrite'){
                     $delete = "DELETE FROM UPLOAD_BAPLIE WHERE NO_UKK = '$id_vs'";
                     $db->query($delete);
                } */

				$jum1  		  = "SELECT COUNT(DISTINCT(NO_CONTAINER)) JUM FROM YD_PLACEMENT_YARD";
				$result_jum1  = $db->query($jum1);
				$jum2		  = $result_jum1->fetchRow();
				$jum       	  = $jum2['JUM'];
				
for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
				
				$no_cont 		= $data->sheets[0]['cells'][$i][2];
				//echo $no_cont;
				$size_ 			= $data->sheets[0]['cells'][$i][3];
				$blok 			= $data->sheets[0]['cells'][$i][4];
				$slot_ 			= $data->sheets[0]['cells'][$i][5];
				$slot2 			= $data->sheets[0]['cells'][$i][6];
				$row_yard		= $data->sheets[0]['cells'][$i][7];
				$tier_yard 		= $data->sheets[0]['cells'][$i][8];
				
				
                $tgl		= date('Y-m-d H:i:s');
				$id_user 	= $_SESSION["ID_USER"];
				$nama_user  = $_SESSION["NAMA_PENGGUNA"];
				
				
				$no_ukk_   = "SELECT  b.NO_UKK, a.KD_SIZE, a.KD_TYPE, a.KD_STATUS_CONT, a.DISC_PORT, a.LOAD_PORT, a.BAY_BP, a.ROW_BP, a.TIER_BP,  a.SLOT_BP LOKASI_BAY, a.CONT_TYPE ISO_CODE, a.GROSS, a.TGL_STACK, a.COMMODITY
								  FROM ttd_bp_cont@prodlinkx a, ttm_bp_cont@prodlinkx b
								 WHERE a.BP_ID = b.BP_ID AND a.cont_no_bp = '$no_cont'
							      AND a.status_cont IN ('03','04')";
				$result_ukk   = $db->query($no_ukk_);
				$no_uk		  = $result_ukk->fetchRow();
				$no_ukk       = $no_uk['NO_UKK'];
				$tipe         = $no_uk['KD_TYPE'];
				$status       = $no_uk['KD_STATUS_CONT'];
				$disc         = $no_uk['DISC_PORT'];
				$load         = $no_uk['LOAD_PORT'];
				$bay          = $no_uk['BAY_BP'];
				$row_         = $no_uk['ROW_BP'];
				$tier_        = $no_uk['TIER_BP'];
				$lokasi_bay   = $no_uk['LOKASI_BAY'];
				$iso_code     = $no_uk['ISO_CODE'];
				$gross        = $no_uk['GROSS'];
				$tgl_stack    = $no_uk['TGL_STACK'];
				$commo        = $no_uk['COMMODITY'];
				
				//echo $no_ukk;die;
				$seq_plc1   = "SELECT a.ID FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF' AND a.NAME = TRIM(UPPER('$blok'))";
				$jml_plc1   = $db->query($seq_plc1);
				$jml_hsl1   = $jml_plc1->fetchRow();
								
				$id_blok	= $jml_hsl1['ID'];
				//echo $no_ukk; die;
				if (($no_ukk <> '') OR ($no_ukk <> NULL)){
				
					
				$jum3  		  = "SELECT COUNT(1) JUM FROM ISWS_LIST_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_UKK = '$no_ukk' ";
				$result_jum3  = $db->query($jum3);
				$jum4		  = $result_jum3->fetchRow();
				$jumlah        = $jum4['JUM'];
	
				//echo $no_cont;
				//echo $jumlah; die;
				if ($jumlah <= 0){
				// echo 'dama';die;
							$jum5  		  = "INSERT INTO ISWS_LIST_CONTAINER (POD,
														BAY,
														LOKASI_BP,
														NO_CONTAINER,
														SIZE_,
														BERAT,
														TYPE_,
														HEIGHT,
														STATUS,
														POL,
														DEL_PORT,
														CARRIER,
														ISO_CODE,
														E_I,
														NO_UKK,
														IMO,
														HZ,
														TEMP,
														TYPE_REFFER,
														COMODITY,
														REMARK)
												VALUES ('$disc',
														'$bay',
														'$lokasi_bay',
														'$no_cont',
														'$size_',
														'$gross',
														'$tipe',
														'',
														'$status',
														'$load',
														'',
														'',
														'$iso_code',
														'I',
														'$no_ukk',
														'',
														'',
														'',
														'',
														'$commo',
														'OPNAME')";

								$jum6  		  = " INSERT INTO isws_list_cont_hist (NO_UKK,
														NO_CONTAINER,
														KEGIATAN,
														E_I,
														KODE_STATUS,
														DATE_STATUS,
														NM_USER)
													VALUES ('$no_ukk',
														    '$no_cont',
															'OPNAME',
															'I',
															'03',
															SYSDATE,
															'$nama_user')";
															
															
								$db->query($jum5);
								$db->query($jum6);
							}
			
					//	echo 'tes';die;
						if(($size_=='40')||($size_=='45'))
						{			
							/*echo "SELECT INDEX_CELL, SIZE_PLAN_ALLO
											FROM YD_BLOCKING_CELL 
											WHERE ID_BLOCKING_AREA = '$id_blok' 
												AND ROW_ = '$row_' 
												AND SLOT_ = '$slot_'";die;*/
							$cell_index = "SELECT INDEX_CELL, SIZE_PLAN_ALLO
											FROM YD_BLOCKING_CELL 
											WHERE ID_BLOCKING_AREA = '$id_blok' 
												AND ROW_ = '$row_yard' 
												AND SLOT_ = '$slot_'";								
							$result4 = $db->query($cell_index);
							$cellindex = $result4->fetchRow();
							$cell_address = $cellindex['INDEX_CELL'];
							
							/*				echo "BEGIN
												placement_hh ('','BONGKAR','$id_user','','$blok','$slot_' ,'$row_yard' ,'$tier_yard','','','','$no_cont','$no_ukk');		
											END;";*/
											
								$cek 		=" BEGIN
												placement_hh_opname ('','BONGKAR','$id_user','','$blok','$slot_' ,'$row_yard' ,'$tier_yard','','','','$no_cont','$no_ukk');		
											END;";
								$db->query($cek);
									
						}
						else
						{
					/*	echo "SELECT INDEX_CELL, SIZE_PLAN_ALLO
											FROM YD_BLOCKING_CELL 
											WHERE ID_BLOCKING_AREA = '$id_blok' 
												AND ROW_ = '$row_' 
												AND SLOT_ = '$slot_'";die;*/
							$cell_index = "SELECT INDEX_CELL, TRIM(SIZE_PLAN_ALLO) AS SIZE_PLAN_ALLO
											FROM YD_BLOCKING_CELL 
											WHERE ID_BLOCKING_AREA = '$id_blok' 
												AND ROW_ = '$row_yard' 
												AND SLOT_ = '$slot_'";								
							$result4 = $db->query($cell_index);
							$cellindex = $result4->fetchRow();
							$cell_address = $cellindex['INDEX_CELL'];
							
						/*echo "BEGIN
													placement_hh ('','BONGKAR','$id_user','','$blok','$slot_' ,'$row_yard' ,'$tier_yard','','','','$no_cont','$no_ukk');		
												END;";*/
									$cek 		=" BEGIN
													placement_hh_opname ('','BONGKAR','$id_user','','$blok','$slot_' ,'$row_yard' ,'$tier_yard','','','','$no_cont','$no_ukk');		
												END;";

									$db->query($cek);
								
										
						}
					} 
					
					$remark_op  = "UPDATE ISWS_LIST_CONTAINER SET REMARK = 'sdh gate out ict' WHERE NO_CONTAINER = '$no_cont' and NO_UKK = '$no_ukk'";
					$db->query($remark_op);
	
                  
}

$jum3 		  = "SELECT COUNT(DISTINCT(NO_CONTAINER)) JUM FROM YD_PLACEMENT_YARD";
$result_jum3  = $db->query($jum3);
$jum4		  = $result_jum3->fetchRow();
$jum_akhir    = $jum4['JUM'];
				
$insert       = $jum_akhir - $jum;



 header('Location: '.HOME.'maintenance.upload_opname?jum='.$insert);	
?>




