<?php
	//$id_js=$_POST["ID_JS"];	
	$id_alat=$_POST["ALAT"];
	$id_user=$_POST["USER"];
	$v_blok=$_POST["BLOK_ID"];
	$v_slot=$_POST["SLOT_ID"];
	$v_row=$_POST["ROW_ID"];
	$v_tier=$_POST["TIER_ID"];
	
	$db=getDB();
	$query1 			= "select COUNT(no_container) JML from yd_placement_yard where id_blocking_area = '$v_blok' and slot_yard = '$v_slot' and row_yard = '$v_row' and tier_yard ='$v_tier'";
	$result1			= $db->query($query1);
	$row1				= $result1->fetchRow();	
	
	$posisi		    	= $row1['JML'];

		if ($posisi >= 1 ){
			$query7 			= "select DISTINCT(a.SIZE_PLAN_PLC) SIZE_PLAN_PLC FROM YD_BLOCKING_CELL a, YD_BLOCKING_AREA b WHERE a.ID_BLOCKING_AREA = b.ID AND  b.ID = '$v_blok' and a.SLOT_ = '$v_slot' and a.ROW_ = '$v_row'";
			$result7			= $db->query($query7);
			$hasil7				= $result7->fetchRow();	
			
			$exist7		    	= $hasil7['SIZE_PLAN_PLC'];
			
			//echo $exist7;die;
			
			if ($exist == 20){
				echo  'lokasi sudah digunakan cont 20';
			} else if (($exist == '40d') OR ($exist == '40b')){
				echo 'lokasi sudah digunakan cont 40';
			} else {
				echo 'lokasi sudah digunakan';
			}		
		} else {
			$seq_plc1   = "SELECT a.ID FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF' AND a.NAME = TRIM(UPPER('$v_blok'))";
			$jml_plc1   = $db->query($seq_plc1);
			$jml_hsl1   = $jml_plc1->fetchRow();
			$id_blok	= $jml_hsl1['ID'];

			if(($size_=='40')||($size_=='45'))
					{			
												$cell_index = "SELECT INDEX_CELL, SIZE_PLAN_ALLO,ID_KATEG, SIZE_PLAN_PLC
														    FROM YD_BLOCKING_CELL 
															WHERE ID_BLOCKING_AREA = '$v_blok' 
																AND ROW_ = '$v_row' 
																AND SLOT_ = '$v_slot'";								
												$result4      = $db->query($cell_index);
												$cellindex 	  = $result4->fetchRow();
												$cell_address = $cellindex['INDEX_CELL'];
												$id_kateg	  = $cellindex['ID_KATEG'];
						
											
												$posisi_block = "SELECT POSISI
																	FROM YD_BLOCKING_AREA 
																	WHERE ID_YARD_AREA = '81' 
																		AND ID = '$v_blok'";
												$result7 = $db->query($posisi_block);
												$blockposisi = $result7->fetchRow();
												$posisi_block = $blockposisi['POSISI'];
												
												if($posisi_block=='horizontal')
												{
													$id2_cell = $cell_address+1;
												}
												else
												{
													$id2_cell = $cell_address+85;
												}
												
												//echo "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE NO_CONTAINER = '$no_container' ORDER BY ID_PLACEMENT ASC";die;
												$seq_plc = "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE NO_CONTAINER = '$no_container' ORDER BY ID_PLACEMENT ASC";
												$jml_plc   = $db->query($seq_plc);
												$jml_hsl   = $jml_plc->getAll();
												
												$n=1;
												foreach ($jml_hsl as $seq)
												{
													//echo 'dama';die;
													$id_plcmnt = $seq['ID_PLACEMENT'];
													$id_cell = $cell_address;
													
													$v_slotyard = $v_slot;
													$slot2_yard = $v_slot+1;					
													
													$seq_plc = "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE NO_CONTAINER = '$no_container' ORDER BY ID_PLACEMENT ASC";
													$jml_plc   = $db->query($seq_plc);
													$jml_hsl   = $jml_plc->getAll();
													
													
													
													if($n==1)
													{
														$seq_plc1   = "SELECT COUNT(1) JML FROM YD_PLACEMENT_YARD WHERE ID_CELL = '$id_cell'";
														$jml_plc1   = $db->query($seq_plc1);
														$jml_hsl1   = $jml_plc->fetchRow();
														$jm			= $jml_hsl1['JML'];
														
														if ($jm == 1){
																$update_relocate12 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '' WHERE id_blockING_AREA = '$v_blok' AND ID_CELL = '$id_cell'";
																$db->query($update_relocate12);
														}
														
														
														
														$relokasi = "begin relokasi_hh('$id_cell', '$v_slotyard', '$v_rowyard', '$tier_yard','$v_blok', '$id2_cell', '$nama_blok', '$id_user', '$id_plcmnt', '$no_container','BONGKAR'); end;";
														$db->query($relokasi);
														
														$update_relocate1 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '40d' WHERE id_blockING_AREA = '$v_blok' AND SLOT_ = '$v_slotyard' AND ROW_ = '$v_rowyard'";
														$db->query($update_relocate1);
											
														
													}
													else
													{
														
														$seq_plc1   = "SELECT COUNT(1) JML FROM YD_PLACEMENT_YARD WHERE ID_CELL = '$id2_cell'";
														$jml_plc1   = $db->query($seq_plc1);
														$jml_hsl1   = $jml_plc->fetchRow();
														$jm			= $jml_hsl1['JML'];
														
														if ($jm == 1){
																$update_relocate12 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '' WHERE id_blockING_AREA = '$v_blok' AND ID_CELL = '$id2_cell'";
																$db->query($update_relocate12);
														}
														
														$relokasi = "begin relokasi_hh('$id2_cell', '$slot2_yard', '$v_rowyard', '$tier_yard','$v_blok', '$id2_cell', '$nama_blok', '$id_user', '$id_plcmnt', '$no_container', 'BONGKAR'); end;";
														$db->query($relokasi);
														
														$update_relocate2 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '40b' WHERE ID_BLOCKING_AREA = '$v_blok' AND SLOT_ = '$slot2_yard' AND ROW_ = '$v_rowyard'";
														$db->query($update_relocate2);
													}
													
													
													$n++;
												}
											
					}
					else
					{
						
											$cell_index = "SELECT INDEX_CELL, SIZE_PLAN_ALLO,ID_KATEG, SIZE_PLAN_PLC
														    FROM YD_BLOCKING_CELL 
															WHERE ID_BLOCKING_AREA = '$v_blok' 
																AND ROW_ = '$v_rowyard' 
																AND SLOT_ = '$v_slot'";								
												$result4      = $db->query($cell_index);
												$cellindex 	  = $result4->fetchRow();
												$cell_address = $cellindex['INDEX_CELL'];
												$id_kateg	  = $cellindex['ID_KATEG'];
												
											$seq_plc = "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE NO_CONTAINER = '$no_container'";
											$jml_plc   = $db->query($seq_plc);
											$jml_hsl   = $jml_plc->fetchRow();
											
											$seq_plc1   = "SELECT COUNT(1) JML FROM YD_PLACEMENT_YARD WHERE ID_CELL = '$id2_cell'";
											$jml_plc1   = $db->query($seq_plc1);
											$jml_hsl1   = $jml_plc->fetchRow();
											$jm			= $jml_hsl1['JML'];
														
											if ($jm == 1){
														$update_relocate12 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '' WHERE id_blockING_AREA = '$v_blok' AND ID_CELL = '$cell_address'";
														$db->query($update_relocate12);
											}

											$id_plcmnt = $jml_hsl['ID_PLACEMENT'];
											$relokasi = "begin relokasi_hh('$cell_address', '$v_slot', '$v_rowyard', '$tier_yard','$v_blok', 'NULL', '$nama_blok', '$id_user', '$id_plcmnt', '$no_container','BONGKAR'); end;";
											$db->query($relokasi);
													
											$update_relocate3 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '20' WHERE ID_BLOCKING_AREA = '$v_blok' AND SLOT_ = '$v_slot' AND ROW_ = '$v_rowyard' AND INDEX_CELL = '$cell_address'";
											$db->query($update_relocate3);

						
					}
					
					
					//echo "select id_blocking_area, slot_yard, row_yard, tier_yard from yd_placement_yard where no_container = '$no_container' 
					//and slot_yard = (select min(slot_yard) from yd_placement_yard where no_container = '$no_container') 
					//and row_yard = (select min(row_yard) from yd_placement_yard where no_container = '$no_container')";die;
					
					$query_slot = "select id_blocking_area, slot_yard, row_yard, tier_yard from yd_placement_yard where no_container = '$no_container' 
					and slot_yard = (select min(slot_yard) from yd_placement_yard where no_container = '$no_container') 
					and row_yard = (select min(row_yard) from yd_placement_yard where no_container = '$no_container')";
					$data = $db->query($query_slot);
					$date = $data->fetchRow();
					
					if (($date['ID_BLOCKING_AREA'] == "$v_blok") AND ($date['SLOT_YARD'] == "$v_slot") AND ($date['ROW_YARD'] == "$v_rowyard") AND ($date['TIER_YARD'] == "$tier_yard"))
					{
						$isws_hist = " INSERT INTO isws_list_cont_hist 
												  (
													NO_UKK,
													NO_CONTAINER,
													KEGIATAN,
													E_I,
													KODE_STATUS,
													DATE_STATUS,
													HH
												  )
												 VALUES
												 (
													'$no_ukk',
													'$no_container',
													'RELOCATION',
													'I',
													'03R',
													SYSDATE,
													'Y'
												 )";
						$db->query($isws_hist);
						
						$isws_list = "UPDATE ISWS_LIST_CONTAINER SET KODE_STATUS = '03R' WHERE NO_CONTAINER = '$no_container' AND NO_UKK = '$no_ukk'";
						$db->query($isws_list);
						
						echo '<br> data berhasil disimpan';
						
					} 
					else 
					{
					
						echo '<br> data gagal disimpan';
						
					}
				} 

?>