<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_yard = $_POST["ID_YARD"];
$blok = $_POST["ID_BLOCK"];
$nama_blok = $_POST["NM_BLOCK"];
$slot_ = $_POST["SLOT"];
$tier = $_POST["TIER"];
//echo $id_vs;exit;



		$arr = $_REQUEST['p'];
		// open loop through each array element
		$n=0;
		foreach ($arr as $p)
		{
			// detach values from each parameter
			list($id, $row, $cell) = explode('_', $p);
			// instead of print, you can store accepted parameteres to the database
			$tier_yard = ($tier-($row-1));
			$row_yard = $cell;
			
			list($vs, $no_container, $size_, $type_, $status_, $hz_, $gross_, $pel_asal, $pod, $keg, $kode_pbm, $no_booking, $id_plc, $tinggi, $iso_code, $imo_class, $celcius) = explode(',', $id); 
			
			$seq_plc2   	= "select ID_VS,ID_CELL,ID_PLACEMENT FROM yd_placement_yard WHERE NO_CONTAINER = '$no_container'";
			$jml_plc2   	= $db->query($seq_plc2);
			$jml_hsl2   	= $jml_plc2->fetchRow();
							
			$id_plcm		= $jml_hsl2['ID_PLACEMENT'];
			$id_cell20		= $jml_hsl2['ID_CELL'];
			$no_ukk			= $jml_hsl2['ID_VS'];
			
			//echo " Id=$id Row=$row Cell=$cell Alamat=$alamat_cell"; die;
			
			//-------------- cek size container ----------------//
			if(($size_=='40')||($size_=='45'))
					{			
												$cell_index = "SELECT INDEX_CELL, SIZE_PLAN_ALLO,ID_KATEG, SIZE_PLAN_PLC
														    FROM YD_BLOCKING_CELL 
															WHERE ID_BLOCKING_AREA = '$blok' 
																AND ROW_ = '$row_yard' 
																AND SLOT_ = '$slot_'";								
												$result4      = $db->query($cell_index);
												$cellindex 	  = $result4->fetchRow();
												$cell_address = $cellindex['INDEX_CELL'];
												$id_kateg	  = $cellindex['ID_KATEG'];
						
											
												$posisi_block = "SELECT POSISI
																	FROM YD_BLOCKING_AREA 
																	WHERE ID_YARD_AREA = '$id_yard' 
																		AND ID = '$blok'";
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
													
													$slot_yard = $slot_;
													$slot2_yard = $slot_+1;					
													
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
																$update_relocate12 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '' WHERE id_blockING_AREA = '$blok' AND ID_CELL = '$id_cell'";
																$db->query($update_relocate12);
														}
														
														
														
														$relokasi = "begin relokasi_hh('$id_cell', '$slot_yard', '$row_yard', '$tier_yard','$blok', '$id2_cell', '$nama_blok', '$id_user', '$id_plcmnt', '$no_container','BONGKAR'); end;";
														$db->query($relokasi);
														
														$update_relocate1 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '40d' WHERE id_blockING_AREA = '$blok' AND SLOT_ = '$slot_yard' AND ROW_ = '$row_yard'";
														$db->query($update_relocate1);
											
														
													}
													else
													{
														
														$seq_plc1   = "SELECT COUNT(1) JML FROM YD_PLACEMENT_YARD WHERE ID_CELL = '$id2_cell'";
														$jml_plc1   = $db->query($seq_plc1);
														$jml_hsl1   = $jml_plc->fetchRow();
														$jm			= $jml_hsl1['JML'];
														
														if ($jm == 1){
																$update_relocate12 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '' WHERE id_blockING_AREA = '$blok' AND ID_CELL = '$id2_cell'";
																$db->query($update_relocate12);
														}
														
														$relokasi = "begin relokasi_hh('$id2_cell', '$slot2_yard', '$row_yard', '$tier_yard','$blok', '$id2_cell', '$nama_blok', '$id_user', '$id_plcmnt', '$no_container', 'BONGKAR'); end;";
														$db->query($relokasi);
														
														$update_relocate2 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '40b' WHERE ID_BLOCKING_AREA = '$blok' AND SLOT_ = '$slot2_yard' AND ROW_ = '$row_yard'";
														$db->query($update_relocate2);
													}
													
													
													$n++;
												}
											
					}
					else
					{
						
											$cell_index = "SELECT INDEX_CELL, SIZE_PLAN_ALLO,ID_KATEG, SIZE_PLAN_PLC
														    FROM YD_BLOCKING_CELL 
															WHERE ID_BLOCKING_AREA = '$blok' 
																AND ROW_ = '$row_yard' 
																AND SLOT_ = '$slot_'";								
												$result4      = $db->query($cell_index);
												$cellindex 	  = $result4->fetchRow();
												$cell_address = $cellindex['INDEX_CELL'];
												
											$seq_plc = "SELECT ID_PLACEMENT FROM YD_PLACEMENT_YARD WHERE NO_CONTAINER = '$no_container'";
											$jml_plc   = $db->query($seq_plc);
											$jml_hsl   = $jml_plc->fetchRow();
											
											$seq_plc1   = "SELECT COUNT(1) JML FROM YD_PLACEMENT_YARD WHERE ID_CELL = '$id_cell20'";
											$jml_plc1   = $db->query($seq_plc1);
											$jml_hsl1   = $jml_plc->fetchRow();
											$jm			= $jml_hsl1['JML'];
														
											if ($jm == 1){
														$update_relocate12 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '' WHERE id_blockING_AREA = '$blok' AND ID_CELL = '$id_cell20'";
														$db->query($update_relocate12);
											}

											$id_plcmnt = $jml_hsl['ID_PLACEMENT'];
											$relokasi = "begin relokasi_hh('$cell_address', '$slot_', '$row_yard', '$tier_yard','$blok', 'NULL', '$nama_blok', '$id_user', '$id_plcmnt', '$no_container','BONGKAR'); end;";
											$db->query($relokasi);
													
											$update_relocate3 = "UPDATE YD_BLOCKING_CELL SET SIZE_PLAN_PLC = '20' WHERE ID_BLOCKING_AREA = '$blok' AND SLOT_ = '$slot_' AND ROW_ = '$row_yard' AND INDEX_CELL = '$cell_address'";
											$db->query($update_relocate3);

						
					}	
	}
	
					$query_slot = "select id_blocking_area, slot_yard, row_yard, tier_yard from yd_placement_yard where no_container = '$no_container' 
					and slot_yard = (select min(slot_yard) from yd_placement_yard where no_container = '$no_container') 
					and row_yard = (select min(row_yard) from yd_placement_yard where no_container = '$no_container')  ";
					$data = $db->query($query_slot);
					$date = $data->fetchRow();
					
					$quer = "select kode_status from isws_list_container where no_container = '$no_container' and no_ukk = '$no_ukk'";
					$data2 = $db->query($quer);
					$date2 = $data2->fetchRow();
					$kd_status = $date2['KODE_STATUS'];
					
					if (($date['ID_BLOCKING_AREA'] == "$blok") AND ($date['SLOT_YARD'] == "$slot_") AND ($date['ROW_YARD'] == "$row_yard") AND ($date['TIER_YARD'] == "$tier_yard")){
						
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
															''
														 )";
								$db->query($isws_hist);
								
								$isws_list = "UPDATE ISWS_LIST_CONTAINER SET KODE_STATUS = '03R' WHERE NO_CONTAINER = '$no_container' AND NO_UKK = '$no_ukk'";
								$db->query($isws_list);
							
						
						echo "OK";
					} else {
						echo "GAGAL";
					}
	
	
?>