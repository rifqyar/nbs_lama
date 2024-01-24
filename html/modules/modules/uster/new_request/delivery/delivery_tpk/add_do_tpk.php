<?php

	 // debug($_POST);die;
     $id_yard_	= $_SESSION["IDYARD_STORAGE"];
	 $KD_PELANGGAN  		= $_POST["KD_PELANGGAN"]; 
	 $KD_PELANGGAN2  		= $_POST["KD_PELANGGAN2"];
	 $TGL_BERANGKAT 		= $_POST["TGL_BERANGKAT"];
	 $TGL_REQ			= $_POST["TGL_REQ"];
	 $PEB        			= $_POST["NO_PEB"];
	 $NPE     			= $_POST["NO_NPE"];
	 $KD_PELABUHAN_ASAL             = $_POST["KD_PELABUHAN_ASAL"];
	 $KD_PELABUHAN_TUJUAN           = $_POST["KD_PELABUHAN_TUJUAN"];
	 $NM_KAPAL   			= $_POST["NM_KAPAL"];
	 $VOYAGE_IN 			= $_POST["VOYAGE_IN"];
	 $NO_BOOKING			= $_POST["NO_BOOKING"];
	 $KETERANGAN			= $_POST["KETERANGAN"];
	 $ID_USER			= $_SESSION["LOGGED_STORAGE"];
	 $NM_USER			= $_SESSION["NAME"];
	 $ID_YARD			= $_SESSION["IDYARD_STORAGE"];
	 $NM_USER			= $_SESSION["NAME"];
	 $NO_UKK			= $_POST["NO_UKK"];
	 $SHIFT_RFR			= $_POST["SHIFT_RFR"];
	 $TGL_MUAT			= $_POST["TGL_MUAT"];
	 $TGL_STACKING			= $_POST["TGL_STACKING"];
	 $NO_RO			= $_POST["NO_RO"];
	 $JN_REPO		= $_POST["JN_REPO"];
	 $NO_STUFF		= $_POST["NO_REQ_STUFF"];
	 
	 
	 
	$db = getDB("storage");
	$db2 = getDB("ora");
	
	$sqltgl = "SELECT to_date(tgl_stacking,'dd/mm/yyyy') tgl_stacking,to_date(tgl_muat,'dd/mm/yyyy') tgl_muat from V_BOOKING_STACK_TPK where no_booking = '".$NO_BOOKING."'";
					$rstgl		  = $db->query($sqltgl);
					$rowtgl		  = $rstgl->fetchRow();
					$tgl_stackingict = $rowtgl["TGL_STACKING"];
					$tgl_muatict     = $rowtgl["TGL_MUAT"];
	
	//echo $tgl_stacking; 
	//echo $tgl_muat;
	//exit;
	//SELECT LPAD(MAX(NVL(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)),0))+1,6,0)
	
	//Cek tipe delivery TPK, apakah eks stuffing atau empty
	if($JN_REPO == "EMPTY")
	{
		
				$query_cek	= "SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM , 
							   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							   TO_CHAR(SYSDATE, 'YY') AS YEAR 
							   FROM REQUEST_DELIVERY
							   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
								
				$result_cek	= $db->query($query_cek);
				$jum_		= $result_cek->fetchRow();
				$jum		= $jum_["JUM"];
				$month		= $jum_["MONTH"];
				$year		= $jum_["YEAR"];
				
				$no_req	= "DEL".$month.$year.$jum;
				
				
				// $query_cont_stuffing = "SELECT NO_CONTAINER";
			// //-------------------------------------------------- INSERT TO TPK's RECEIVING --------------------------------------------------------//
			// /*TGL_MUAT,TGL_STACK,PELABUHAN_TUJUAN (diprovide),FOREIGN_DISC (diprovide),KD_PELANGGAN (diprovide), */
				$IDKDPMB 	= 'UD'.$month.$year.$jum;
			// //echo $IDKDPMB; die;	
			// // cari no_booking stack ada apa enggak ??
			// /*
				// $sqlcek1 = "SELECT NO_BOOKING FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE KD_CABANG = '05' AND NO_UKK = '$NO_UKK'";
				// //echo $sqlcek1; exit;
				// $rs1 = $db->query($sqlcek1);
				
				// if ($rs1->RecordCount()<0)
				// {
					// echo "Belum Open Stack";
				// }else 
				// {
				// $sqlcek2 = "SELECT SUM(JUMLAH_BOX) FROM PETIKEMAS_CABANG.TTD_KET_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
				// //echo $sqlcek2; exit;
				
				// $rs2 = $db->query($sqlcek2);
				// $rowcek2 = $rs2 -> FetchRow();
				// $jum_cont_book = $rowcek2["JUMLAH_BOX"];
				// }
				// if ($jum_cont_book <= $jum_cont_req) 
				// {
					// echo "Container Request Melebihi Booking Stack";
				// }else
				// {
				
				
				// $sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'YYYY-MM-DD HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
				
				// $rs3 = $db->query($sqlcek3);
				// $rowcek3 = $rs3 -> FetchRow();
				// $datedoc = $rowcek3["TGL_JAM_CLOSE"];
				
				// $sqldate = "SELECT TO_CHAR (SYSDATE,'YYYY-MM-DD HH24:MI:SS')HARINI FROM DUAL";
				
				// $rsdate = $db->query($sqldate);
				// $rowdate = $rsdate -> FetchRow();
				// $sysdate = $rowdate["HARINI"];
				
				
				// if ($datedoc  <= $sysdate)
				// {
					// echo "Masa Closing Time Sudah Habis, Silakan Lakukan Booking Stack Pada Kapal Lain"; exit;
				// }
				// else
				// {
				
				// $sqlpbm = "SELECT * FROM PETIKEMAS_CABANG.TTD_BOOKING_PBM where no_booking = '$NO_BOOKING' AND KD_PBM = '$KD_PELANGGAN'";
				// $rspbm = $db->query($sqlpbm);
				// $rowpbm = $rspbm->FetchRow();
				// }
				// //echo $sqlpbm;exit;
				// if ($rowpbm["NO_BOOKING"] == "")
				// {
					// echo "EMKL Tidak Booking Stack Pada Kapal Ini"; exit;
				// }
				// else
					// {
			// */
					
					
					$q_pkk = $db->query("SELECT NO_UKK FROM V_PKK_CONT WHERE NO_BOOKING='$NO_BOOKING'");
					$r_pkk = $q_pkk->fetchRow();
					if($r_pkk["NO_UKK"] == NULL){
						$q_insert = "INSERT INTO V_PKK_CONT (KD_KAPAL,
                        NM_KAPAL,
                        VOYAGE_IN,
                        VOYAGE_OUT,
                        TGL_JAM_TIBA,
                        TGL_JAM_BERANGKAT,
                        NO_UKK,
                        NM_AGEN,
                        KD_AGEN,
                        PELABUHAN_ASAL,
                        PELABUHAN_TUJUAN,
                        KD_CABANG,
                        NO_BOOKING)
                SELECT A.KD_KAPAL,
                       A.NM_KAPAL,
                       A.VOYAGE_IN,
                       A.VOYAGE_OUT,
                       A.TGL_JAM_TIBA,
                       A.TGL_JAM_BERANGKAT,
                       A.NO_UKK,
                       A.NM_AGEN,
                       A.KD_AGEN,
                       A.PELABUHAN_ASAL,
                       A.PELABUHAN_TUJUAN,
                       A.KD_CABANG,
                       TM.NO_BOOKING BP_ID
                  FROM    petikemas_cabang.v_pkk_cont A
                       JOIN
                          PETIKEMAS_CABANG.TTH_CONT_BOOKING TM
                       ON A.NO_UKK = TM.NO_UKK
                 WHERE TM.NO_BOOKING = '$NO_BOOKING'";
						$db->query($q_insert);
						
					}
					
								$sqlhead	= 	"INSERT INTO PETIKEMAS_CABANG.TTH_CONT_EXBSPL 
												(KD_PMB,
												KD_CABANG,
												NO_UKK,
												TGL_MUAT,
												TGL_STACK,
												TGL_SIMPAN,
												PELABUHAN_TUJUAN,
												KD_PELANGGAN,
												KETERANGAN,
												STATUS_CONT_EXBSPL,
												STATUS_KARTU,
												NO_PEB,
												KD_PMB_LAMA,
												USER_ID,
												NO_NPE,
												NO_BOOKING,
												SHIFT_RFR,
												FOREIGN_DISC,
												KD_PELANGGAN2) 
												VALUES(
												'$IDKDPMB',
												'05',
												'$NO_UKK',
												'$tgl_muatict',
												'$tgl_stackingict',												
												SYSDATE,
												'$KD_PELABUHAN_TUJUAN',
												'$KD_PELANGGAN',
												'$KETERANGAN',
												'0',
												'0',
												'$PEB',
												'$IDKDPMB',
												'USTER',
												'$NPE',								
												'$NO_BOOKING',
												'2',
												'$KD_PELABUHAN_ASAL',
												'$KD_PELANGGAN'
								)";
				// /*	} */
			// //				echo $sqlhead;exit;	
							if ($db2->query($sqlhead))
							{
			// /*					
			// */																
			// //-------------------------------------------------- END INSERT TPK's RECEIVING -------------------------------------------------------//	
				
					
							  $query_req    = "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, 
																			TGL_REQUEST, TGL_REQUEST_DELIVERY, 
																			KETERANGAN, CETAK_KARTU, ID_USER, 
																			DELIVERY_KE, PERALIHAN, ID_YARD, STATUS,PEB, NPE, KD_EMKL, KD_EMKL2, 
																			VESSEL, VOYAGE, TGL_BERANGKAT, KD_PELABUHAN_ASAL, 
																			KD_PELABUHAN_TUJUAN, NO_BOOKING, NO_REQ_ICT, TGL_MUAT, TGL_STACKING, 
																			NO_RO,JN_REPO,NO_REQ_STUFFING) 
																	VALUES ('$no_req','$no_req',
																			SYSDATE, TO_DATE('".$TGL_REQ."','yyyy/mm/dd'),
																			'$KETERANGAN', '0', $ID_USER, 
																			'TPK','NOTA_KIRIM','$ID_YARD','NEW','$PEB','$NPE','$KD_PELANGGAN', '$KD_PELANGGAN2', '$NM_KAPAL','$VOYAGE_IN',TO_DATE('$TGL_BERANGKAT','dd/mm/yyyy'),'$KD_PELABUHAN_ASAL','$KD_PELABUHAN_TUJUAN', '$NO_BOOKING','$IDKDPMB',to_date('$TGL_MUAT','DD-MM-YYYY HH24:MI:SS'),
												to_date('$TGL_STACKING','DD-MM-YYYY HH24:MI:SS'), 
																			'$NO_RO','$JN_REPO','$NO_STUFF')";
																  
						  
					// //echo $query_req;die;

							}
							else
							{
								echo "tidak masuk request_delivery";die;
							}
	}
	else
	{
					$query_cek	= "SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM , 
							   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							   TO_CHAR(SYSDATE, 'YY') AS YEAR 
							   FROM REQUEST_DELIVERY
							   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
								
				$result_cek	= $db->query($query_cek);
				$jum_		= $result_cek->fetchRow();
				$jum		= $jum_["JUM"];
				$month		= $jum_["MONTH"];
				$year		= $jum_["YEAR"];
				
				$no_req	= "DEL".$month.$year.$jum;
				
				
				$query_cont_stuffing = "SELECT NO_CONTAINER";
			//-------------------------------------------------- INSERT TO TPK's RECEIVING --------------------------------------------------------//
			/*TGL_MUAT,TGL_STACK,PELABUHAN_TUJUAN (diprovide),FOREIGN_DISC (diprovide),KD_PELANGGAN (diprovide), */
				$IDKDPMB 	= 'UD'.$month.$year.$jum;
			//echo $IDKDPMB; die;	
			// cari no_booking stack ada apa enggak ??
			/*
				$sqlcek1 = "SELECT NO_BOOKING FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE KD_CABANG = '05' AND NO_UKK = '$NO_UKK'";
				//echo $sqlcek1; exit;
				$rs1 = $db->query($sqlcek1);
				
				if ($rs1->RecordCount()<0)
				{
					echo "Belum Open Stack";
				}else 
				{
				$sqlcek2 = "SELECT SUM(JUMLAH_BOX) FROM PETIKEMAS_CABANG.TTD_KET_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
				//echo $sqlcek2; exit;
				
				$rs2 = $db->query($sqlcek2);
				$rowcek2 = $rs2 -> FetchRow();
				$jum_cont_book = $rowcek2["JUMLAH_BOX"];
				}
				if ($jum_cont_book <= $jum_cont_req) 
				{
					echo "Container Request Melebihi Booking Stack";
				}else
				{
				
				
				$sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'YYYY-MM-DD HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
				
				$rs3 = $db->query($sqlcek3);
				$rowcek3 = $rs3 -> FetchRow();
				$datedoc = $rowcek3["TGL_JAM_CLOSE"];
				
				$sqldate = "SELECT TO_CHAR (SYSDATE,'YYYY-MM-DD HH24:MI:SS')HARINI FROM DUAL";
				
				$rsdate = $db->query($sqldate);
				$rowdate = $rsdate -> FetchRow();
				$sysdate = $rowdate["HARINI"];
				
				
				if ($datedoc  <= $sysdate)
				{
					echo "Masa Closing Time Sudah Habis, Silakan Lakukan Booking Stack Pada Kapal Lain"; exit;
				}
				else
				{
				
				$sqlpbm = "SELECT * FROM PETIKEMAS_CABANG.TTD_BOOKING_PBM where no_booking = '$NO_BOOKING' AND KD_PBM = '$KD_PELANGGAN'";
				$rspbm = $db->query($sqlpbm);
				$rowpbm = $rspbm->FetchRow();
				}
				//echo $sqlpbm;exit;
				if ($rowpbm["NO_BOOKING"] == "")
				{
					echo "EMKL Tidak Booking Stack Pada Kapal Ini"; exit;
				}
				else
					{
			*/
					
								$sqlhead	= 	"INSERT INTO PETIKEMAS_CABANG.TTH_CONT_EXBSPL 
												(KD_PMB,
												KD_CABANG,
												NO_UKK,
												TGL_MUAT,
												TGL_STACK,
												TGL_SIMPAN,
												PELABUHAN_TUJUAN,
												KD_PELANGGAN,
												KETERANGAN,
												STATUS_CONT_EXBSPL,
												STATUS_KARTU,
												NO_PEB,
												KD_PMB_LAMA,
												USER_ID,
												NO_NPE,
												NO_BOOKING,
												SHIFT_RFR,
												FOREIGN_DISC,
												KD_PELANGGAN2) 
												VALUES(
												'$IDKDPMB',
												'05',
												'$NO_UKK',
												'$tgl_muatict',
												'$tgl_stackingict',												
												SYSDATE,
												'$KD_PELABUHAN_TUJUAN',
												'$KD_PELANGGAN',
												'$KETERANGAN',
												'0',
												'0',
												'$PEB',
												'$IDKDPMB',
												'USTER',
												'$NPE',								
												'$NO_BOOKING',
												'2',
												'$KD_PELABUHAN_ASAL',
												'$KD_PELANGGAN'
								)";
				/*	} */
							//echo $sqlhead;exit;	
							if($db2->query($sqlhead)) //(TRUE)
							{
			/*					
			*/																
			//-------------------------------------------------- END INSERT TPK's RECEIVING -------------------------------------------------------//	
				
					
								$query_req    = "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, 
																			TGL_REQUEST, TGL_REQUEST_DELIVERY, 
																			KETERANGAN, CETAK_KARTU, ID_USER, 
																			DELIVERY_KE, PERALIHAN, ID_YARD, STATUS,PEB, NPE, KD_EMKL, KD_EMKL2, 
																			VESSEL, VOYAGE, TGL_BERANGKAT, KD_PELABUHAN_ASAL, 
																			KD_PELABUHAN_TUJUAN, NO_BOOKING, NO_REQ_ICT, TGL_MUAT, TGL_STACKING, 
																			NO_RO,JN_REPO,NO_REQ_STUFFING) 
																	VALUES ('$no_req','$no_req',
																			SYSDATE, TO_DATE('".$TGL_REQ."','yyyy/mm/dd'),
																			'$KETERANGAN', '0', $ID_USER, 
																			'TPK','NOTA_KIRIM','$ID_YARD','NEW','$PEB','$NPE','$KD_PELANGGAN', '$KD_PELANGGAN2', '$NM_KAPAL','$VOYAGE_IN',TO_DATE('$TGL_BERANGKAT','dd/mm/yyyy'),'$KD_PELABUHAN_ASAL','$KD_PELABUHAN_TUJUAN', '$NO_BOOKING','$IDKDPMB',to_date('$TGL_MUAT','DD-MM-YYYY HH24:MI:SS'),
												to_date('$TGL_STACKING','DD-MM-YYYY HH24:MI:SS'), 
																			'$NO_RO','$JN_REPO','$NO_STUFF')";
																	  
						  
					

							}
							else
							{
								echo "tidak masuk request_delivery";die;
							}

					//==== Batas comment
					
						$query_stuffing	= "SELECT CONTAINER_STUFFING.NO_CONTAINER,
												  CONTAINER_STUFFING.HZ,
												  CONTAINER_STUFFING.COMMODITY,
												  CONTAINER_STUFFING.KETERANGAN,
												  CONTAINER_STUFFING.NO_SEAL,
												  CONTAINER_STUFFING.BERAT,
												  CONTAINER_STUFFING.ASAL_CONT,
												  CONTAINER_STUFFING.KD_COMMODITY,
												  MASTER_CONTAINER.SIZE_,
												  MASTER_CONTAINER.TYPE_
											FROM CONTAINER_STUFFING 
												INNER JOIN MASTER_CONTAINER
													ON CONTAINER_STUFFING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
											WHERE CONTAINER_STUFFING.NO_REQUEST='$NO_STUFF'
											AND CONTAINER_STUFFING.AKTIF = 'T'
											AND MASTER_CONTAINER.LOCATION = 'IN_YARD'
											AND CONTAINER_STUFFING.NO_CONTAINER NOT IN ( SELECT NO_CONTAINER FROM CONTAINER_DELIVERY
                                                                                         WHERE AKTIF='Y') ";
						
						$stuffing 	= $db->query($query_stuffing);
						// debug($stuffing);die;
						$stuffing_cont 	= $stuffing->getAll();
						
						// debug($stuffing_cont);die;
						
						foreach($stuffing_cont as $cont_stuff)
						{
							// echo $cont_stuff['NO_CONTAINER'] . "<br>";
							
							// Insert container stuffing ke table container delivery
							
								$query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, 
																				  NO_REQUEST, 
																				  STATUS, 
																				  AKTIF, 
																				  KELUAR,
																				  HZ, 
																				  
																				  KOMODITI,
																				  KETERANGAN,
																				  NO_SEAL,
																				  BERAT,
																				  VIA, 
																				  ID_YARD, 
																				  
																				  NO_REQ_STUFFING, 
																				  ASAL_CONT) 
																		VALUES 	 ( '".$cont_stuff['NO_CONTAINER']."',
																				  '$no_req',
																				  'FCL',
																				  'Y',
																				  'N',
																				  '".$cont_stuff['HZ']."',
																				  
																				  '".$cont_stuff['COMMODITY']."',
																				  '".$cont_stuff['KETERANGAN']."',
																				  '".$cont_stuff['NO_SEAL']."',
																				  '".$cont_stuff['BERAT']."',
																				  '',
																				  '$id_yard_',
																				  
																				  '$NO_STUFF',
																				  '".$cont_stuff['ASAL_CONT']."')";
																				  
																								  
									$db->query($query_insert);

									//insert ke history
									$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '".$cont_stuff['NO_CONTAINER']."' ORDER BY COUNTER DESC";
													$r_getc = $db->query($q_getc);
													$rw_getc = $r_getc->fetchRow();
													$cur_c = $rw_getc["COUNTER"];
													//$cur_booking = $rw_getc["NO_BOOKING"];
									$history = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, 
																			  TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER,STATUS_CONT) 
																	  VALUES ('".$cont_stuff['NO_CONTAINER']."','$no_req','REQUEST DELIVERY',
																			  SYSDATE,'$ID_USER','$ID_YARD','$NO_BOOKING','$cur_c','FCL')";
																				  
									$db->query($history);
							// =========================================== SIMOP ==============================================//
						
			
											$sqlsize  	= "SELECT KD_SIZE FROM PETIKEMAS_CABANG.MST_SIZE_CONT WHERE SIZE_CONT = '".$cont_stuff['SIZE_']."'";
											$rssize		= $db2->query($sqlsize);
											$sizetpk_	= $rssize->fetchRow();
											$sizetpk	= $sizetpk_["KD_SIZE"];
											//echo $sqlsize;exit;
											
											$sqltype	= "SELECT STY_CODE FROM PETIKEMAS_CABANG.MST_TYPE_CONT WHERE STY_NAME = '".$cont_stuff['TYPE_']."'";
											$rstype		= $db2->query($sqltype);
											$typetpk_	= $rstype->fetchRow();
											$typetpk	= $typetpk_["STY_CODE"];
											//echo $typetpk;
											
											$sqljenis 	= "SELECT KD_JENIS_PEMILIK FROM PETIKEMAS_CABANG.MST_JENIS_PEMILIK WHERE NM_JENIS_PEMILIK = 'FCL'";
											$rsjenis	= $db2->query($sqljenis);
											$jenistpk_	= $rsjenis->fetchRow();
											$jenistpk	= $jenistpk_["KD_JENIS_PEMILIK"];
											//}
											
											if($typetpk=="07"){
														$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_RFR WHERE NO_BOOKING='$NO_BOOKING'";					
													}else{
														$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING WHERE NO_BOOKING='$NO_BOOKING'";
													}			
											if($jenistpk=='3'){
														$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_MTY WHERE NO_BOOKING='$NO_BOOKING'";
													} 	
																		
											//		echo $sqlm; exit;
													$rsm = $db2->query($sqlm);
													if($rsm->RecordCount()<0){
														echo "Reefer Atau Empty Tidak Ada Dalam Booking Stack"; exit;
													}
													else 
													{
														
														$rsbook  = $rsm->FetchRow();
														$blk_id = $rsbook["ARE_BLOK"];
														
														$sqlxx  = "SELECT ARE_ID,ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE KD_STATUS_CY='0' AND KD_CABANG='05' AND 
																   ARE_BLOK='".$rsbook["ARE_BLOK"]."' AND  ARE_ROW BETWEEN '".$rsbook["M_ROW"]."' AND '".$rsbook["S_ROW"]."' 
																   AND  ARE_SLOT BETWEEN '".$rsbook["M_SLOT"]."' AND '".$rsbook["S_SLOT"]."' AND  ARE_TIER BETWEEN '".$rsbook["M_TIER"]."' 
																   AND '".$rsbook["S_TIER"]."' ORDER BY ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
																   
															//echo $sqlxx; exit;
																   
															if($rsx = $db2->selectLimit( $sqlxx,0,1 ))
															{
																
																$rscon  = $rsx->FetchRow();
																$yp     = $rscon["ARE_ID"];
																$slott  = $rscon["ARE_SLOT"] + 1;
																
																if($sizetpk !='1')
																	{
																		$sql2 = "SELECT ARE_ID FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE ARE_TIER='".$rscon["ARE_TIER"]."' 
																		AND ARE_BLOK='".$rscon["ARE_BLOK"]."' AND ARE_ROW='".$rscon["ARE_ROW"]."' AND ARE_SLOT='".$slott."' AND KD_CABANG='05'";
																		if( $rss2 = $db2->selectLimit( $sql2,0,1 ) )
																		{
																		$rscon2   = $rss2->FetchRow();
																		$yp2      = $rscon2["ARE_ID"];
																		}
																	}
																
																
																$sqlxxx = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp."'";
																$db2->startTransaction();
																$xxxxxx = $db2->query($sqlxxx);
																$db2->endTransaction();
																
																$sqlxxzz = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp2."'";
																$db2->startTransaction();
																$xxxxzz = $db2->query($sqlxxzz);
																$db2->endTransaction();	
																
																if($yp==''){
																	$yp = 'XXXX';
																}
															
															}
													}

											//utang are_id,are_id2,temp,class			

											if ($cont_stuff['KD_COMMODITY'] == '' )
											   {$kd_komoditi = 'C000000293';} else {$kd_komoditi = $cont_stuff['KD_COMMODITY'];}
											if ($cont_stuff['BERAT'] == '' )
											   {$beratict = '0';}else{$beratict = $cont_stuff['BERAT'];}   
											if ($cont_stuff['HZ'] == 'N')   
											   {$hzict = 'T';}else{$hzict = 'Y';}
														$sqldet   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,NO_CONTAINER,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,STATUS_PP,ARE_ID,ARE_ID2) 
																  VALUES
																 (
																	PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.NEXTVAL,
																	'$IDKDPMB',						
																	'".$cont_stuff['NO_CONTAINER']."',
																	'$sizetpk',
																	'$typetpk',
																	'$jenistpk',
																	'".$kd_komoditi."',
																	'$beratict',
																	'".$cont_stuff['NO_SEAL']."',
																	'".$cont_stuff['KETERANGAN']."',
																	'0U',
																	'0',
																	'USTER',
																	'1',
																	'$hzict',
																	'T',
																	'$yp',
																	'$yp2'
																	)";
																//echo $sqldet;exit();
																
																
																
																if(!$db2->query($sqldet))
																{
																	if($jenistpk =='3')
																	{
																		$sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
																			(
																				PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
																				'-',
																				SYSDATE, 
																				'$NM_USER',
																				'05',
																				SYSDATE,
																				'$IDKDPMB',
																				'',
																				'$NO_UKK'
																			)";
																		$db2->query($sqlx3);
																	}
																}
																
	
								// =============  END SIMOP=========================================================// 	  
						}
						
					//==== Batas comment	
						
						
						
	}
		
			if($db->query($query_req))// (TRUE)
			{
				header('Location: '.HOME.APPID.'/edit?no_req='.$no_req.'&no_req2='.$IDKDPMB);		
			}

		//}
                
					
        
?>