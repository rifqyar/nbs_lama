<?php

$db 			= getDB("storage");

$nm_user		= $_SESSION["NAME"];
$id_user		= $_SESSION["LOGGED_STORAGE"];
$no_cont		= $_POST["NO_CONT"]; 
$no_req_stuf	= $_POST["NO_REQ_STUF"];
$no_req_rec		= $_POST["NO_REQ_REC"]; 
$no_req_del		= $_POST["NO_REQ_DEL"];
$no_do			= $_POST["NO_DO"];
$no_bl			= $_POST["NO_BL"]; 
$hz				= $_POST["BERBAHAYA"]; 
$commodity		= $_POST["COMMODITY"];
$kd_commodity	= $_POST["KD_COMMODITY"];
$type_stuffing	= $_POST["JENIS"];
$no_seal		= $_POST["NO_SEAL"];
$berat			= $_POST["BERAT"];
$keterangan		= $_POST["KETERANGAN"];
$no_req_ict		= $_POST["NO_REQ_ICT"];
$size			= $_POST["SIZE"];
$type			= $_POST["TYPE"];
$hz				= $_POST["BERBAHAYA"];
$depo_tujuan	= $_POST["DEPO_TUJUAN"];
$no_booking		= $_POST["NO_BOOKING"];
$no_ukk			= $_POST["NO_UKK"];
$voyage			= $_POST["VOYAGE"];
$vessel			= $_POST["VESSEL"];
$tgl_stack		= $_POST["TGL_STACK"];
$status			= $_POST["STATUS"];
$bp_id			= $_POST["BP_ID"];
$sp2			= $_POST["SP2"];
$tgl_bongkar	= $_POST["TGL_BONGKAR"];	
$no_sppb		= $_POST["NO_SPPB"];
$tgl_sppb		= $_POST["TGL_SPPB"];
$blok_tpk		= $_POST["BLOK"];
$slot_tpk		= $_POST["SLOT"];
$row_tpk		= $_POST["ROW"];
$tier_tpk		= $_POST["TIER"];
$asal_cont_stuf	= $_POST["ASAL_CONT"];
//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus di Lapangan dan sudah Gate In


//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;

	// mengetahui tanggal start_stack
	
        $query_cek1		= "SELECT tes.NO_REQUEST, 
                                    CASE SUBSTR(KEGIATAN,9)
                                        WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) FROM request_receiving a WHERE a.NO_REQUEST = tes.NO_REQUEST)
                                        ELSE SUBSTR(KEGIATAN,9)
                                    END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI')) tes
                                    WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI'))";
        $result_cek1		= $db->query($query_cek1);
        $row_cek1		= $result_cek1->fetchRow();
        $no_request		= $row_cek1["NO_REQUEST"];
        $kegiatan		= $row_cek1["KEGIATAN"];
		
		IF ($kegiatan == 'RECEIVING_LUAR') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(b.TGL_IN, 'MM/DD/YYYY'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
				$result_cek1		= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	  	= $row_cek1["START_STACK"];
				$asal_cont 		= 'LUAR';
		} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
				$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'TPK';
		} ELSE IF ($kegiatan == 'STRIPPING') {
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,9) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		}
		
		//echo $start_stack;exit;
		//TO_DATE('$TGL_BERANGKAT','dd/mon/yy'),
	
	// =========================================== SIMOP ==============================================//


	
	$sqlcek2 = "SELECT SUM(JUMLAH_BOX) JML_BOX FROM PETIKEMAS_CABANG.TTD_KET_BOOKING WHERE NO_BOOKING = '$no_booking'";
	//echo $sqlcek2; exit;
	
	$rs2 = $db->query($sqlcek2);
	$rowcek2 = $rs2 -> FetchRow();
	$jum_cont_book = $rowcek2["JML_BOX"];
	
	$sqlcekc = "select count (b.no_container) JML from petikemas_cabang.tth_cont_exbspl a, petikemas_cabang.ttd_cont_exbspl b where no_ukk = '$no_ukk' and a.kd_pmb = b.kd_pmb ";
	//echo $sqlcekc; exit;
	
	$rs3 = $db->query($sqlcekc);
	$rowcek3 = $rs3 -> FetchRow();
	$jum_cont_req = $rowcek3["JML"];
	
	//echo $jum_cont_req; echo $jum_cont_book; exit;
	
	if ($jum_cont_req >= $jum_cont_book) 
	{
		echo "Container Request Melebihi Booking Stack"; exit;
	} 
	else 
	{
			
	$sqlsize  	= "SELECT KD_SIZE FROM PETIKEMAS_CABANG.MST_SIZE_CONT WHERE SIZE_CONT = '$size'"; 	
	$rssize		= $db->query($sqlsize);
	$sizetpk_	= $rssize->fetchRow();
	$sizetpk	= $sizetpk_["KD_SIZE"];
	//echo $sqlsize;exit;
	
	$sqltype	= "SELECT STY_CODE FROM PETIKEMAS_CABANG.MST_TYPE_CONT WHERE STY_NAME = '$type'";
	$rstype		= $db->query($sqltype);
	$typetpk_	= $rstype->fetchRow();
	$typetpk	= $typetpk_["STY_CODE"];
	//echo $typetpk;
	
	/*$sqljenis 	= "SELECT KD_JENIS_PEMILIK FROM PETIKEMAS_CABANG.MST_JENIS_PEMILIK WHERE NM_JENIS_PEMILIK = '$status'";
	$rsjenis	= $db->query($sqljenis);
	$jenistpk_	= $rsjenis->fetchRow();*/
	$jenistpk	= '3';
	//}
	
	if($typetpk=="07"){
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_RFR WHERE NO_BOOKING='$no_booking'";					
			}else{
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING WHERE NO_BOOKING='$no_booking'";
			}			
/*	if($jenistpk=='3'){
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_MTY WHERE NO_BOOKING='$no_booking'";
			} 	*/
								
	//		echo $sqlm; exit;
			$rsm = $db->query($sqlm);
			if($rsm->RecordCount()<0){
				echo "Reefer Tidak Ada Dalam Booking Stack"; exit;
			}
			/*else 
			{
				
				 $rsbook  = $rsm->FetchRow();
				$blk_id = $rsbook["ARE_BLOK"];
				
				$sqlxx  = "SELECT ARE_ID,ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE KD_STATUS_CY='0' AND KD_CABANG='05' AND 
						   ARE_BLOK='".$rsbook["ARE_BLOK"]."' AND  ARE_ROW BETWEEN '".$rsbook["M_ROW"]."' AND '".$rsbook["S_ROW"]."' 
						   AND  ARE_SLOT BETWEEN '".$rsbook["M_SLOT"]."' AND '".$rsbook["S_SLOT"]."' AND  ARE_TIER BETWEEN '".$rsbook["M_TIER"]."' 
						   AND '".$rsbook["S_TIER"]."' ORDER BY ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
						   
					//echo $sqlxx; exit;
						   
					if($rsx = $db->selectLimit( $sqlxx,0,1 ))
					{
						
					$rscon  = $rsx->FetchRow();
					$yp     = $rscon["ARE_ID"];
					$slott  = $rscon["ARE_SLOT"] + 1;
					
					if($sizetpk !='1')
						{
							$sql2 = "SELECT ARE_ID FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE ARE_TIER='".$rscon["ARE_TIER"]."' 
							AND ARE_BLOK='".$rscon["ARE_BLOK"]."' AND ARE_ROW='".$rscon["ARE_ROW"]."' AND ARE_SLOT='".$slott."' AND KD_CABANG='05'";
							if( $rss2 = $db->selectLimit( $sql2,0,1 ) )
							{
							$rscon2   = $rss2->FetchRow();
							$yp2      = $rscon2["ARE_ID"];
							}
						}
					
					
					$sqlxxx = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp."'";
					$db->startTransaction();
					$xxxxxx = $db->query($sqlxxx);
					$db->endTransaction();
					
					$sqlxxzz = "UPDATE PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp2."'";
					$db->startTransaction();
					$xxxxzz = $db->query($sqlxxzz);
					$db->endTransaction();	
					
					if($yp==''){
						$yp = 'XXXX';
					}
					
				} 
			}*/

			$sql 			= "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req_ict'";
			$sqldb 			= $db->query($sql);
			$row_sql		= $sqldb->fetchRow();
			$no_req_del_		= $row_sql["NO_REQ_DEL"];
			
			if($no_req_del_ != NULL){
				$result_seq = "SELECT MAX(SEQ_DEL) as SEQ from PETIKEMAS_CABANG.TTD_DEL_REQ where  NO_REQ_DEL ='$no_req_del'";
				$rs 		= $db->query( $result_seq );
				$row		= $rs->FetchRow() ;			
				$seq	 	= $row['SEQ']+1; 				
				
			}
			/* $sqlinsert	= "INSERT INTO PETIKEMAS_CABANG.TTD_DEL_REQ(NO_REQ_DEL,NO_DO,CONT_NO_BP,SEQ_DEL,NO_SP2,NO_BL,NO_BP_ID,ENTRY_BY,ENTRY_DATE,
						   COMMODITY,TGL_STACK,TGL_DISCHARGE,HZ,KD_SIZE,KD_TYPE,KD_STATUS_CONT,NO_UKK,KD_PBM)    
						   VALUES(
						   '$no_req_del',
						   '$no_do', 		  		   
						   '$no_cont',  	 	   
						   '$seq',    
						   '$sp2',
						   '$no_bl',
						   '$bp_id',
						   '$nm_user',
						   SYSDATE,
						   '$commodity',
						   to_date('".$tgl_stack."','DD-MM-YYYY'),
						   to_date('".$tgl_bongkar."','DD-MM-YYYY'),  
						   '$hz',
						   '$size',
						   '$type',
						   '$status',
						   '$no_ukk',
						   '$kd_commodity')"; 
			
			$sqlttd		 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET    
							   STATUS_CONT	    ='04U',    
							   COMMODITY 	    ='$commodity', 
							   NO_SP2           ='$sp2',
							   NO_REQ 		    ='$no_req_del',
							   NO_BL		    ='$no_bl',
							   NO_DO		    ='$no_do',
							   KD_PBM		    ='$kd_commodity',    
							   TUJUAN		    ='USTER'
							   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
							   
//utang are_id,are_id2,temp,class			
			$sqldet   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,NO_CONTAINER,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,STATUS_PP,ARE_ID,ARE_ID2) 
					  VALUES
					 (
						PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.NEXTVAL,
						'$no_req_ict',						
						'$no_cont',
						'$sizetpk',
						'$typetpk',
						'$jenistpk',
						'$kd_commodity',
						'$berat',
						'$no_seal',
						'$keterangan',
						'0US',
						'0',
						'$nm_user',
						'1',
						'$hz',
						'T',
						'$yp',
						'$yp2'
						)"; */
				// ===========================================  END SIMOP ==============================================//
	
				/* $query_cont_rec_uster	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
												   NO_REQUEST,
												   STATUS,
												   AKTIF,
												   HZ,
												   TGL_BONGKAR,
												   KOMODITI,
												   DEPO_TUJUAN,
												   BLOK_TPK,
												   SLOT_TPK,
												   ROW_TPK,
												   TIER_TPK) 
											VALUES('$no_cont', 
												   '$no_req_rec',
												   '$status',
												   'Y',
												   '$hz',
												   TO_DATE('$tgl_bongkar','dd-mm-yyyy'),
												   '$commodity',
												   '$depo_tujuan',
												   '$blok_tpk',
												   '$slot_tpk',
												   '$row_tpk',
												   '$tier_tpk')"; */
										
	$query_insert_stuff	= "INSERT INTO PLAN_CONTAINER_STUFFING(NO_CONTAINER, 
														   NO_REQUEST,
														   AKTIF,
														   HZ,
														   COMMODITY,
														   TYPE_STUFFING,
														   ASAL_CONT,
														   NO_SEAL,
														   BERAT,
														   KETERANGAN,
														   DEPO_TUJUAN
															  ) 
													VALUES('$no_cont', 
														   '$no_req_stuf',
														   'Y',
														   '$hz',
														   '$commodity',
														   '$type_stuffing',
														   '$asal_cont_stuf',
														   '$no_seal',
														   '$berat',
														   '$keterangan',
														   '$depo_tujuan'
														   )";

	/* $query_del_uster  = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, 
														  KELUAR,HZ, KOMODITI,KETERANGAN,
														  NO_SEAL,BERAT,VIA,ID_YARD, 
														  NOREQ_PERALIHAN, START_STACK, ASAL_CONT) 
												   VALUES('$no_cont', '$no_req_del', '$status','Y',
														  'N','$hz','$commodity','$keterangan',
														  '$no_seal','$berat','DARAT','$id_yard',
														  '$no_request', '$', '$asal_cont')";
                     
     */
												   
	$db->query($query_insert_stuff);											   
	$history_del        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER) 
                                                      VALUES ('$no_cont','$no_req_stuf','PLAN REQUEST STUFFING',SYSDATE,'$id_user')";
	$db->query($history_del);											
	/* if($db->query($sqlinsert)){
	
		if($db->query($sqlttd)){		
			if($db->query($query_cont_rec_uster)){			
				
				if($db->query($query_del_uster)){					
					$db->query($history_del);
					if($db->query($sqldet))
					{
						if($jenistpk =='3')
						{
							$sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
								(	PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
									'-',
									SYSDATE, 
									'$nm_user',
									'05',
									SYSDATE,
									'$no_req_ict',
									'',
									'$no_ukk'
								)";
							$db->query($sqlx3);
						}
					}
				}
			}
			else{
				echo "req_rec uster error"; 
			}
		}
	}
	else{
		echo "req_del ict error";
	} */
	//echo $sqldet;exit();
}
	$query_insert_del = "";
	echo "OK";
?>