<?php
//$tl 	=  xliteTemplate('cont_list.htm');
$db = getDB("storage");
$nm_user	= $_SESSION["NAME"];
$id_user	= $_SESSION["LOGGED_STORAGE"];
$tgl_approve = $_POST["tgl_approve"];
$no_req_rec		= $_POST["NO_REQ_REC"]; 
$no_cont = $_POST["no_cont"];
$no_req = $_POST["no_req"];
$no_req2	= $_POST["NO_REQ2"]; 
$no_do		= $_POST["NO_DO"];
$no_bl		= $_POST["NO_BL"];
$sp2		= $_POST["SP2"];
$asal_contstrip		= $_POST["ASAL_CONT"];
$kd_consignee	= $_POST["KD_CONSIGNEE"];
			$query_cx = "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, PLAN_CONTAINER_STRIPPING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
                           FROM PLAN_CONTAINER_STRIPPING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A            
                           ON PLAN_CONTAINER_STRIPPING.NO_CONTAINER = A.CONT_NO_BP
                           WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
			$result_cx = $db->query($query_cx);
			$row_cx	= $result_cx->fetchRow();
			$hz = $row_cx['HZ'];
			$komoditi = $row_cx['COMMODITY'];
			$size = $row_cx['UKURAN'];
			$type = $row_cx['TYPE'];
			$no_booking_ = $row_cx['NO_BOOKING'];
			$depo_tujuan = $row_cx['ID_YARD'];
			//$tgl_bongkar = $row_cx['TGL_BONGKAR'];
			
			$result_dcont  = "SELECT 
				TTD_BP_CONT.CONT_NO_BP, 
				TTD_BP_CONT.KD_SIZE,
				TTD_BP_CONT.KD_TYPE,
				TTD_BP_CONT.KD_STATUS_CONT,
				TTD_BP_CONT.GROSS,
				TTD_BP_CONT.CLASS,            
				NULL AS BLOK_,
				NULL AS SLOT_,
				NULL AS ROW_,
				NULL AS TIER_,    
				V_PKK_CONT.NM_KAPAL,
				V_PKK_CONT.NM_AGEN,
				V_PKK_CONT.NO_UKK,
				V_PKK_CONT.VOYAGE_IN,
				V_PKK_CONT.VOYAGE_OUT,
				TTD_BP_CONT.DISC_PORT,
				TTD_BP_CONT.BP_ID,
				TTD_BP_CONT.ARE_ID,
				To_Char(V_PKK_CONT.TGL_JAM_BERANGKAT,'DD-MM-YYYY') AS TGL_JAM_BERANGKAT,
				To_Char(TTD_BP_CONFIRM.CONFIRM_DATE,'DD-MM-YYYY') AS TGL_BONGKAR,
				TTD_BP_CONT.STATUS_CONT, 
				To_Char(TTD_BP_CONT.TGL_STACK,'DD-MM-YYYY') AS TGL_STACK,
				To_Char(TTD_BP_CONFIRM.CONFIRM_DATE+4,'DD-MM-YYYY') AS TGL_BERLAKU   
				FROM
				PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,   
				PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM
				WHERE TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID 
				AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK 
				AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP 
				AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK 
				AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID 
				AND TTM_BP_CONT.KD_CABANG ='05'       
				AND TTD_BP_CONT.STATUS_CONT IN ('03')  
				AND TTD_BP_CONT.CONT_NO_BP LIKE '$no_cont'
				order by TTD_BP_CONT.CONT_NO_BP asc ";      
			$rs_dcont  = $db->query($result_dcont);
			$rowxcont  = $rs_dcont->fetchRow();
			$bp_id = $rowxcont['BP_ID'];
			$no_ukk = $rowxcont['NO_UKK'];
			$tgl_stack = $rowxcont['TGL_STACK'];
if($asal_contstrip == "TPK"){
			
			//$db->query($history_del);
			
			//$tgl_bongkar = $rowxcont['TGL_BONGKAR'];
			
	//---------------------------------------------------Interface Ke ICT----------------------------------------------------------------------------------------------
			$sql 			= "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req2'";
			$sqldb 			= $db->query($sql);
			$row_sql		= $sqldb->fetchRow();
			$no_req_del		= $row_sql["NO_REQ_DEL"];
			
			//echo $no_req_del;exit;
			
			//debug($no_req_del);die;
			if ($no_req_del != NULL)
			{
				$result_seq = "SELECT MAX(SEQ_DEL) as SEQ from PETIKEMAS_CABANG.TTD_DEL_REQ where  NO_REQ_DEL ='$no_req_del'";
				$rs 		= $db->query( $result_seq );
				$row		= $rs->fetchRow() ;			
				$seq	 	= $row['SEQ']+1; 
				
				$sqlinsert	= "INSERT INTO PETIKEMAS_CABANG.TTD_DEL_REQ(NO_REQ_DEL,NO_DO,CONT_NO_BP,SEQ_DEL,NO_SP2,NO_BL,NO_BP_ID,ENTRY_BY,ENTRY_DATE,
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
			   '$komoditi',
			   to_date('".$tgl_stack."','DD-MM-YYYY'),
			   to_date('".$tgl_bongkar."','DD-MM-YYYY'),  
			   '$hz',
			   '$size',
			   '$type',
			   '04U',
			   '$no_ukk',
			   '$kd_consignee')";        
			   //echo $sqlinsert;// exit;
			   
				if($db->query($sqlinsert))
				{		 		
				$sqlttm = "UPDATE PETIKEMAS_CABANG.TTM_DEL_REQ SET NO_UKK = '$no_ukk' WHERE NO_REQ_DEL = '$no_req_del'";
				//echo $sqlttm;exit;
				$db->query($sqlttm);
				
			   $sqlttd		 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET    
			   STATUS_CONT	    ='04U',    
			   COMMODITY 	    ='$komoditi', 
			   NO_SP2           ='$sp2',
			   NO_REQ 		    ='$no_req_del',
			   NO_BL		    ='$no_bl',
			   NO_DO		    ='$no_do',
			   KD_PBM		    ='$kd_consignee',    
			   TUJUAN		    ='USTER'
			   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
			   
			   $db->query($sqlttd);
				
				}
			}
//--------------------------------------------------- End of Interface Ke ICT----------------------------------------------------------------------------------------------
	
	
	
	//Insert Uster
	
	$query_insert_rec	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
										   NO_REQUEST,
										   STATUS,
										   AKTIF,
										   HZ,
										   TGL_BONGKAR,
										   KOMODITI,
										   DEPO_TUJUAN) 
									VALUES('$no_cont', 
										   '$no_req_rec',
										   '$status',
										   'Y',
										   '$berbahaya',
											TO_DATE('$tgl_bongkar','dd-mm-yy'),
										   '$komoditi',
										   '$depo_tujuan')";
	//echo $query_insert;exit;			

	/* if($db->query($history_rec)){
		$query_cek1		= "SELECT tes.NO_REQUEST, 
										CASE SUBSTR(KEGIATAN,9)
											WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) FROM request_receiving a WHERE a.NO_REQUEST = tes.NO_REQUEST)
											ELSE SUBSTR(KEGIATAN,9)
										END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI')) tes
										WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI'))";
			$result_cek1		= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			$no_request_	= $row_cek1["NO_REQUEST"];
			$kegiatan		= $row_cek1["KEGIATAN"];
			
			IF ($kegiatan == 'RECEIVING_LUAR') {
					$query_cek1		= "SELECT SUBSTR(TO_CHAR(b.TGL_IN, 'MM/DD/YYYY'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request_'";
					$result_cek1		= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	  	= $row_cek1["START_STACK"];
					$asal_cont 		= 'LUAR';
			} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
					$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'TPK';
			} ELSE IF ($kegiatan == 'STRIPPING') {
					$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,9) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					$start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'DEPO';
			}
	} */
	$query_cek_cont			= "SELECT NO_CONTAINER 
							FROM  MASTER_CONTAINER
							WHERE NO_CONTAINER ='$no_cont'
							";
	$result_cek_cont = $db->query($query_cek_cont);
	$row_cek_cont	 = $result_cek_cont->fetchRow();
	$cek_cont		 = $row_cek_cont["NO_CONTAINER"];

	if($cek_cont == NULL)
	{
		$query_insert_mstr	= "INSERT INTO MASTER_CONTAINER(NO_CONTAINER,
															SIZE_,
															TYPE_,
															LOCATION, NO_BOOKING, COUNTER)
													 VALUES('$no_cont',
															'$size',
															'$type',
															'GATO', '$no_booking_', 1)
								";						
		$db->query($query_insert_mstr);
		
	}else{	
		$query_counter = "SELECT COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
		$r_counter = $db->query($query_counter);
		$rw_counter = $r_counter->fetchRow();
		$last_counter = $rw_counter["COUNTER"]+1;
		$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking_', COUNTER = '$last_counter' WHERE NO_CONTAINER = '$no_cont'";
		$db->query($q_update_book2);
	}
	$db->query($query_insert_rec);

	$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_counter = $rw_getcounter["COUNTER"];
						$cur_booking = $rw_getcounter["NO_BOOKING"];	
	
	
	$history_rec    = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER)  
															  VALUES ('$no_cont','$no_req_rec','REQUEST RECEIVING',SYSDATE,'$id_user','$cur_booking', '$cur_counter' )";	
															  
	$db->query($history_rec);
	
	$query = "UPDATE PLAN_CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			 WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
			 
	 $query_r = "SELECT * FROM PLAN_REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
	$result_r = $db->query($query_r);
	$row_r	= $result_r->fetchRow();
	//print_r($row_r);die;
	$no_request_a = $row_r['NO_REQUEST'];
	$id_yard = $row_r['ID_YARD'];
	$keterangan = $row_r['KETERANGAN'];
	$no_book = $row_r['NO_BOOKING'];
	$tgl_req = $row_r['TGL_REQUEST'];
	$tgl_awal = $row_r['TGL_AWAL'];
	$tgl_akhir = $row_r['TGL_AKHIR'];
	$nodo = $row_r['NO_DO'];
	$nobl = $row_r['NO_BL'];
	$types = $row_r['TYPE_STRIPPING'];
	$strip_d = $row_r['STRIPPING_DARI'];
	$rec = $row_r['NO_REQUEST_RECEIVING'];
	$id_user = $row_r['ID_USER'];
	$consig = $row_r['KD_CONSIGNEE'];
	$tumpuk = $row_r['KD_PENUMPUKAN_OLEH'];
	//$kapal = $row_r['NM_KAPAL'];
	$nosppb = $row_r['NO_SPPB'];
	$tglsppb = $row_r['TGL_SPPB'];
	$after_s = $row_r['AFTER_STRIP'];
	$CONSIGNEE_PERSONAL = $row_r['CONSIGNEE_PERSONAL'];


	$query_c = "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, PLAN_CONTAINER_STRIPPING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
                           FROM PLAN_CONTAINER_STRIPPING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A            
                           ON PLAN_CONTAINER_STRIPPING.NO_CONTAINER = A.CONT_NO_BP
                           WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
			$result_c = $db->query($query_c);
			$row_c	= $result_c->getAll();
			
	$query_cek_request = "SELECT * FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
	$result_cek_request = $db->query($query_cek_request);
	$row_cek_request	= $result_cek_request->getAll();

	$query_cek_cont = "SELECT * FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
	$result_cek_cont = $db->query($query_cek_cont);
	$row_cek_cont	= $result_cek_cont->getAll();
	$no_req_strip = str_replace( 'P', 'S', $no_req);
	$query_tgl_app = "UPDATE CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			WHERE NO_REQUEST = '$no_req_strip' AND NO_CONTAINER = '$no_cont'";
			
	if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){
			$db->query($query);
			
			$db->query($query_tgl_app);
			
			
	}
	else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
		$db->query($query);
		$no_req_strip = str_replace( 'P', 'S', $no_req);
		foreach($row_c as $rc){
			$after_strip = $rc['AFTER_STRIP'];
			$idyard_c = $rc['ID_YARD'];
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_app = $rc['TGL_APPROVE'];
			$tgl_bongkar = $rc['TGL_BONGKAR'];
			$via = $rc['VIA'];
			$voyage = $rc['VOYAGE'];
			$query_ic	= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
							VIA, VOYAGE, TGL_BONGKAR, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_strip',
							'$aktif',
							'$via',
							'$voyage',
							'$tgl_bongkar',
							'$hz',
							'$idyard_c',
							'$after_strip',
							'$tgl_app')";
			$db->query($query_ic);
		} 
		
		
	}
	else{
		$db->query($query);
		$no_req_strip = str_replace( 'P', 'S', $no_req);
		$query_ir = "INSERT INTO REQUEST_STRIPPING(ID_YARD,  KETERANGAN, TGL_REQUEST,
					TGL_AWAL, TGL_AKHIR, NO_DO, NO_BL, TYPE_STRIPPING, STRIPPING_DARI, NO_REQUEST_RECEIVING,
					ID_USER, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, NO_REQUEST, PERP_KE, CONSIGNEE_PERSONAL)
						VALUES(
						'$id_yard',
						'$keterangan',
						'$tgl_req',
						'$tgl_awal',
						'$tgl_akhir',
						'$nodo',
						'$nobl',
						'$types',
						'$strip_d',
						'$rec',
						'$id_user',
						'$consig',
						'$tumpuk','$no_req_strip',1,'$CONSIGNEE_PERSONAL')";
		if($db->query($query_ir)){
			
		}

		$no_req_strip = str_replace( 'P', 'S', $no_req);
		foreach($row_c as $rc){
			$after_strip = $rc['AFTER_STRIP'];
			$idyard_c = $rc['ID_YARD'];
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_app = $rc['TGL_APPROVE'];
			$tgl_bongkar = $rc['TGL_BONGKAR'];
			$via = $rc['VIA'];
			$voyage = $rc['VOYAGE'];
			$query_ic	= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
							VIA, VOYAGE, TGL_BONGKAR, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_strip',
							'$aktif',
							'$via',
							'$voyage',
							'$tgl_bongkar',
							'$hz',
							'$idyard_c',
							'$after_strip',
							'$tgl_app')";
			$db->query($query_ic);
		}  
	}
	
	
	
	$history_stripp        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req_strip','REQUEST STRIPPING',SYSDATE,'$id_user','$cur_booking','$cur_counter')";
	$db->query($history_stripp);
}
else if($asal_contstrip == "DEPO"){

	/* $query_cek1		= "SELECT tes.NO_REQUEST, 
									CASE SUBSTR(KEGIATAN,9)
									WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) FROM request_receiving a WHERE a.NO_REQUEST = tes.NO_REQUEST)
									ELSE SUBSTR(KEGIATAN,9)
								END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI')) tes
								WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI'))";
	$result_cek1		= $db->query($query_cek1);
	$row_cek1		= $result_cek1->fetchRow();
	$no_request_	= $row_cek1["NO_REQUEST"];
	$kegiatan		= $row_cek1["KEGIATAN"];
	
	IF ($kegiatan == 'RECEIVING_LUAR') {
			$query_cek1		= "SELECT SUBSTR(TO_CHAR(b.TGL_IN, 'MM/DD/YYYY'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request_'";
			$result_cek1		= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			$start_stack	  	= $row_cek1["START_STACK"];
			$asal_cont 		= 'LUAR';
	} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
			$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_'";
			$result_cek1	= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			$start_stack	= $row_cek1["START_STACK"];
			$asal_cont 		= 'TPK';
	} ELSE IF ($kegiatan == 'STRIPPING') {
			$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,9) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_'";
			$result_cek1	= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			$start_stack	= $row_cek1["START_STACK"];
			$asal_cont 		= 'DEPO';
	} */
	$query = "UPDATE PLAN_CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			 WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
			 
	 $query_r = "SELECT * FROM PLAN_REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
	$result_r = $db->query($query_r);
	$row_r	= $result_r->fetchRow();
	//print_r($row_r);die;
	$no_request_a = $row_r['NO_REQUEST'];
	$id_yard = $row_r['ID_YARD'];
	$keterangan = $row_r['KETERANGAN'];
	//$no_book = $row_r['NO_BOOKING'];
	$tgl_req = $row_r['TGL_REQUEST'];
	$tgl_awal = $row_r['TGL_AWAL'];
	$tgl_akhir = $row_r['TGL_AKHIR'];
	$nodo = $row_r['NO_DO'];
	$nobl = $row_r['NO_BL'];
	$types = $row_r['TYPE_STRIPPING'];
	$strip_d = $row_r['STRIPPING_DARI'];
	$rec = $row_r['NO_REQUEST_RECEIVING'];
	$id_user = $row_r['ID_USER'];
	$consig = $row_r['KD_CONSIGNEE'];
	$tumpuk = $row_r['KD_PENUMPUKAN_OLEH'];
	//$kapal = $row_r['NM_KAPAL'];
	$nosppb = $row_r['NO_SPPB'];
	$tglsppb = $row_r['TGL_SPPB'];
	$after_s = $row_r['AFTER_STRIP'];


	$query_c = "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, PLAN_CONTAINER_STRIPPING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
                           FROM PLAN_CONTAINER_STRIPPING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A            
                           ON PLAN_CONTAINER_STRIPPING.NO_CONTAINER = A.CONT_NO_BP
                           WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
			$result_c = $db->query($query_c);
			$row_c	= $result_c->getAll();
	$no_req_strip = str_replace( 'P', 'S', $no_req);
	$query_cek_request = "SELECT * FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req_strip'";
	$result_cek_request = $db->query($query_cek_request);
	$row_cek_request	= $result_cek_request->getAll();
	
	$query_cek_cont = "SELECT * FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req_strip' AND NO_CONTAINER = '$no_cont'";
	$result_cek_cont = $db->query($query_cek_cont);
	$row_cek_cont	= $result_cek_cont->getAll();

	$query_tgl_app = "UPDATE CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			WHERE NO_REQUEST = '$no_req_strip' AND NO_CONTAINER = '$no_cont'";
			
	if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){
			$db->query($query);
			
			$db->query($query_tgl_app);
	}
	else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
		$db->query($query);
		
		foreach($row_c as $rc){
			$after_strip = $rc['AFTER_STRIP'];
			$idyard_c = $rc['ID_YARD'];
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_app = $rc['TGL_APPROVE'];
			$tgl_bongkar = $rc['TGL_BONGKAR'];
			$via = $rc['VIA'];
			$voyage = $rc['VOYAGE'];
			$query_ic	= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
							VIA, VOYAGE, TGL_BONGKAR, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_strip',
							'$aktif',
							'$via',
							'$voyage',
							'$tgl_bongkar',
							'$hz',
							'$idyard_c',
							'$after_strip',
							'$tgl_app')";
			$db->query($query_ic);
		} 
		
		
	}
	else{
		$db->query($query);
		
		$query_ir = "INSERT INTO REQUEST_STRIPPING(ID_YARD,  KETERANGAN, TGL_REQUEST,
					TGL_AWAL, TGL_AKHIR, NO_DO, NO_BL, TYPE_STRIPPING, STRIPPING_DARI, NO_REQUEST_RECEIVING,
					ID_USER, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, NO_REQUEST, CONSIGNEE_PERSONAL)
						VALUES(
						'$id_yard',
						'$keterangan',
						'$tgl_req',
						'$tgl_awal',
						'$tgl_akhir',
						'$nodo',
						'$nobl',
						'$types',
						'$strip_d',
						'$rec',
						'$id_user',
						'$consig',
						'$tumpuk','$no_req_strip', '$CONSIGNEE_PERSONAL')";
		if($db->query($query_ir)){
			
		}


		foreach($row_c as $rc){
			$after_strip = $rc['AFTER_STRIP'];
			$idyard_c = $rc['ID_YARD'];
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_app = $rc['TGL_APPROVE'];
			$tgl_bongkar = $rc['TGL_BONGKAR'];
			$via = $rc['VIA'];
			$voyage = $rc['VOYAGE'];
			$query_ic	= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
							VIA, VOYAGE, TGL_BONGKAR, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_strip',
							'$aktif',
							'$via',
							'$voyage',
							'$tgl_bongkar',
							'$hz',
							'$idyard_c',
							'$after_strip',
							'$tgl_app')";
			$db->query($query_ic);
		}  
	}
	
	$q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
	$r_getcounter2 = $db->query($q_getcounter2);
	$rw_getcounter2 = $r_getcounter2->fetchRow();
	$cur_counter2 = $rw_getcounter2["COUNTER"];
	$cur_booking2 = $rw_getcounter2["NO_BOOKING"];
						
	$history_stripp2  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req_strip','REQUEST STRIPPING',SYSDATE,'$id_user','$cur_booking2','$cur_counter2')";
	$db->query($history_stripp2);
}
?>