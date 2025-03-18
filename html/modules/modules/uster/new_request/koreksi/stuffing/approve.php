<?php
//$tl 	=  xliteTemplate('cont_list.htm');
$asalcontstuff = $_POST["ASAL_CONT"];
$tgl_approve = $_POST["tgl_approve"];
$no_cont = $_POST["no_cont"];
$no_req = $_POST["no_req"];
$no_req_rec		= $_POST["NO_REQ_REC"]; 
$no_req_del		= $_POST["NO_REQ_DEL"];
$nm_user = $_SESSION["NAME"];
$id_user = $_SESSION["LOGGED_STORAGE"];
$db = getDB("storage");
$no_req_ict	= $_POST["NO_REQ_ICT"];
$no_do			= $_POST["NO_DO"];
$no_bl			= $_POST["NO_BL"];
$sp2			= $_POST["SP2"];
$no_booking		= $_POST["NO_BOOKING"];




if($asalcontstuff == "TPK"){
	//detail container
	$cont_plan = "SELECT DISTINCT PLAN_CONTAINER_STUFFING.*, PLAN_CONTAINER_STUFFING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
							   FROM PLAN_CONTAINER_STUFFING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A			
							   ON PLAN_CONTAINER_STUFFING.NO_CONTAINER = A.CONT_NO_BP
							   WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STUFFING.NO_CONTAINER = '$no_cont'";
	$rcont_plan = $db->query($cont_plan);
	$rowcont_plan	= $rcont_plan->fetchRow();

	$commodity = $rowcont_plan['COMMODITY'];
	$no_seal = $rowcont_plan['NO_SEAL'];
	$berat = $rowcont_plan['BERAT'];
	$hz = $rowcont_plan['HZ'];
	$depo_tujuan = $rowcont_plan['DEPO_TUJUAN'];

	$querycommodity  = "select * from PETIKEMAS_CABANG.V_MST_COMMODITY WHERE NM_COMMODITY LIKE '%$commodity%' ORDER BY NM_COMMODITY ASC";
	$resultcommo	= $db->query($querycommodity);
	$rowcommo		= $resultcommo->fetchRow();
	$kd_commodity = $rowcommo['KD_COMMODITY'];

	$qdetailcont =   "SELECT 
				TTD_BP_CONT.CONT_NO_BP, 
				TTD_BP_CONT.KD_SIZE,
				TTD_BP_CONT.KD_TYPE,
				TTD_BP_CONT.KD_STATUS_CONT,
				TTD_BP_CONT.GROSS,
				TTD_BP_CONT.CLASS,            
				YARD.ARE_BLOK BLOK_,            
				YARD.ARE_SLOT SLOT_,  
				YARD.ARE_ROW ROW_,
				YARD.ARE_TIER TIER_,   
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
				PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,
				PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,   
				PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,
				PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM ,
				PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN YARD         
				WHERE TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID 
				AND ROWNUM <= 7
				AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK 
				AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP 
				AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK 
				AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID 
				AND TTM_BP_CONT.KD_CABANG ='05'       
				AND TTD_BP_CONT.STATUS_CONT = '03'  
				AND YARD.ARE_ID =TTD_BP_CONT.ARE_ID
				AND TTD_BP_CONT.CONT_NO_BP LIKE '%$no_cont%'
				AND TTD_BP_CONT.KD_STATUS_CONT = 'MTY'
				order by TTD_BP_CONT.CONT_NO_BP asc ";
	//echo $result;
	//print_r($result); exit();
	$rsqdetailcont  = $db->query($qdetailcont);
	$rowxcont = $rsqdetailcont->fetchRow();

	$bp_id = $rowxcont["BP_ID"];
	$size = $rowxcont["KD_SIZE"];
	$type = $rowxcont["KD_TYPE"];
	$status = $rowxcont["KD_STATUS_CONT"];
	$no_ukk = $rowxcont["NO_UKK"];
	$blok_tpk = $rowxcont["BLOK_"];
	$slot_tpk = $rowxcont["SLOT_"];
	$row_tpk = $rowxcont["ROW_"];
	$tier_tpk = $rowxcont["TIER_"];
	$tgl_bongkar = $rowxcont["TGL_BONGKAR"];
	$tgl_stack = $rowxcont["TGL_STACK"];

	$sqlsize  	= "SELECT KD_SIZE FROM PETIKEMAS_CABANG.MST_SIZE_CONT WHERE SIZE_CONT = '$size'"; 	
	$rssize		= $db->query($sqlsize);
	$sizetpk_	= $rssize->fetchRow();
	$sizetpk	= $sizetpk_["KD_SIZE"];

	$sqltype	= "SELECT STY_CODE FROM PETIKEMAS_CABANG.MST_TYPE_CONT WHERE STY_NAME = '$type'";
	$rstype		= $db->query($sqltype);
	$typetpk_	= $rstype->fetchRow();
	$typetpk	= $typetpk_["STY_CODE"];

	/*$sqljenis 	= "SELECT KD_JENIS_PEMILIK FROM PETIKEMAS_CABANG.MST_JENIS_PEMILIK WHERE NM_JENIS_PEMILIK = '$status'";
	$rsjenis	= $db->query($sqljenis);
	$jenistpk_	= $rsjenis->fetchRow();*/
	$jenistpk	= '3';

	if($typetpk=="07"){
			$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_RFR WHERE NO_BOOKING='$no_booking'";					
	}else{
			$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING WHERE NO_BOOKING='$no_booking'";
	}
	$rsm = $db->query($sqlm);
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
		
			if($sizetpk !='1'){
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

				// ===========================================  END SIMOP ==============================================//
		$sql = "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req_ict'";
				$sqldb 			= $db->query($sql);
				$row_sql		= $sqldb->fetchRow();
				$no_req_del_		= $row_sql["NO_REQ_DEL"];
				
				if($no_req_del_ != NULL){
					$result_seq = "SELECT MAX(SEQ_DEL) as SEQ from PETIKEMAS_CABANG.TTD_DEL_REQ where  NO_REQ_DEL ='$no_req_del'";
					$rs 		= $db->query( $result_seq );
					$row		= $rs->FetchRow() ;			
					$seq	 	= $row['SEQ']+1; 				
					
				}
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
							   '$commodity',
							   to_date('".$tgl_stack."','DD-MM-YYYY'),
							   to_date('".$tgl_bongkar."','DD-MM-YYYY'),  
							   '$hz',
							   '$size',
							   '$type',
							   '$status',
							   '$no_ukk',
							   '$kd_commodity')"; 
				
				$sqlttdxxx		 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET    
								   STATUS_CONT	    ='04U',    
								   COMMODITY 	    ='$commodity', 
								   NO_SP2           ='$sp2',
								   NO_REQ 		    ='$no_req_del',
								   NO_BL		    ='$no_bl',
								   NO_DO		    ='$no_do',
								   KD_PBM		    ='$kd_commodity',    
								   TUJUAN		    ='USTER'
								   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
				if($db->query($sqlinsert)){
					//echo "insert delivery tpk succeed";
				}
				$db->query($sqlttdxxx);
				
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
							)";
							
					// ===========================================  END SIMOP ==============================================//
					
	

	$query_cont_rec_uster	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
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
													   TO_DATE('$tgl_bongkar','dd-mm-yy'),
													   '$commodity',
													   '$depo_tujuan',
													   '$blok_tpk',
													   '$slot_tpk',
													   '$row_tpk',
													   '$tier_tpk')";

	   $query_del_uster  = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, 
						  KELUAR,HZ, KOMODITI,KETERANGAN,
						  NO_SEAL,BERAT,VIA,ID_YARD, 
						  NOREQ_PERALIHAN, START_STACK, ASAL_CONT) 
						  VALUES('$no_cont', '$no_req_del', '$status','Y',
						  'N','$hz','$commodity','$keterangan',
						  '$no_seal','$berat','DARAT','$id_yard',
						  '$no_request', '$tgl_bongkar', '$asal_cont')";
						 
		$query_cek_mascont			= "SELECT NO_CONTAINER 
							FROM  MASTER_CONTAINER
							WHERE NO_CONTAINER ='$no_cont'
							";
		$result_cek_mascont = $db->query($query_cek_mascont);
		$row_cek_mascont	 = $result_cek_mascont->fetchRow();
		$cek_mascont		 = $row_cek_mascont["NO_CONTAINER"];

		if($cek_mascont == NULL)
		{
			$query_insert_mstr	= "INSERT INTO MASTER_CONTAINER(NO_CONTAINER,
																SIZE_,
																TYPE_,
																LOCATION, NO_BOOKING, COUNTER)
														 VALUES('$no_cont',
																'$size',
																'$type',
																'GATO', '$no_booking', 1)
									";						
			$db->query($query_insert_mstr);
		}
		else{
				$q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
				$r_getcounter2 = $db->query($q_getcounter2);
				$rw_getcounter2 = $r_getcounter2->fetchRow();
				$last_counter = $rw_getcounter2["COUNTER"]+1;
				//$cur_booking2 = $rw_getcounter2["NO_BOOKING"];
				$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking_', COUNTER = '$last_counter' WHERE NO_CONTAINER = '$no_cont'";
				$db->query($q_update_book2);
		}
		$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
		$r_getcounter1 = $db->query($q_getcounter1);
		$rw_getcounter1 = $r_getcounter1->fetchRow();
		$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
		$cur_counter1  = $rw_getcounter1["COUNTER"];
		
		$history_rec        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
								VALUES ('$no_cont','$no_req_rec','REQUEST RECEIVING',SYSDATE,'$id_user', '$cur_booking1', '$cur_counter1')";
														  
		if($db->query($history_rec)){
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
		}

	

	if($db->query($query_cont_rec_uster)){
			echo "insert container receiving uster succeed";
	}
	else{
			echo "insert container receiving uster failed";
	}
														  
														  
														  
														  
	$query = "UPDATE PLAN_CONTAINER_STUFFING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			 WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
			


	 $query_r = "SELECT * FROM PLAN_REQUEST_STUFFING WHERE NO_REQUEST = '$no_req'";
	$result_r = $db->query($query_r);
	$row_r	= $result_r->fetchRow();
	//print_r($row_r);die;
	$no_request = $row_r['NO_REQUEST'];
	$id_yard = $row_r['ID_YARD'];
	$keterangan = $row_r['KETERANGAN'];
	$no_book = $row_r['NO_BOOKING'];
	$tgl_req = $row_r['TGL_REQUEST'];
	$dokumen = $row_r['NO_DOKUMEN'];
	$jpb = $row_r['NO_JPB'];
	$bprp = $row_r['BPRP'];
	$rec = $row_r['NO_REQUEST_RECEIVING'];
	$id_user = $row_r['ID_USER'];
	$dev = $row_r['NO_REQUEST_DELIVERY'];
	$consig = $row_r['KD_CONSIGNEE'];
	$tumpuk = $row_r['KD_PENUMPUKAN_OLEH'];
	$kapal = $row_r['NM_KAPAL'];
	$peb = $row_r['NO_PEB'];
	$npe = $row_r['NO_NPE'];
	$voy = $row_r['VOYAGE'];
	$query_c = "SELECT DISTINCT PLAN_CONTAINER_STUFFING.*, PLAN_CONTAINER_STUFFING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
							   FROM PLAN_CONTAINER_STUFFING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A			
							   ON PLAN_CONTAINER_STUFFING.NO_CONTAINER = A.CONT_NO_BP
							   WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STUFFING.NO_CONTAINER = '$no_cont'";
	$result_c = $db->query($query_c);
	$row_c	= $result_c->getAll();

	$no_req_stuf = str_replace('P', 'S', $no_req);
	$query_cek_request = "SELECT * FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req_stuf'";
	$result_cek_request = $db->query($query_cek_request);
	$row_cek_request	= $result_cek_request->getAll();

	$query_cek_cont = "SELECT * FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_req_stuf' AND NO_CONTAINER = '$no_cont'";
	$result_cek_cont = $db->query($query_cek_cont);
	$row_cek_cont	= $result_cek_cont->getAll();

	$query_tgl_app = "UPDATE CONTAINER_STUFFING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			WHERE NO_REQUEST = '$no_req_stuf' AND NO_CONTAINER = '$no_cont'";
			
	if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){ //jika request telah ada dan container telah ada
			$db->query($query);
			
			$db->query($query_tgl_app);
	}
	else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
		$no_req_stuf = str_replace('P', 'S', $no_req);
		$db->query($query);
		foreach($row_c as $rc){
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_mulai = $rc['TGL_MULAI'];
			$stat_req = $rc['STATUS_REQ'];
			$tgl_app = $rc['TGL_APPROVE'];
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER, NO_REQUEST, AKTIF, HZ, COMMODITY, TYPE_STUFFING, START_STACK,
							ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, TGL_MULAI, STATUS_REQ, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_stuf',
							'$aktif',
							'$hz',
							'$comm',
							'$type_st',
							to_date('".$start_stack."','DD-MM-YYYY'),
							'$asal',
							'$seal',
							'$berat',
							'$keterangan',
							'$tgl_mulai',
						'$stat_req',
						TO_DATE('$tgl_approve','yy-mm-dd'))";
			$db->query($query_ic);
		} 
		
		
	}
	else{
		$db->query($query);
		$no_req_stuf = str_replace('P', 'S', $no_req);
		$query_ir = "INSERT INTO REQUEST_STUFFING(NO_REQUEST, ID_YARD, CETAK_KARTU_SPPS, KETERANGAN, NO_BOOKING,
						TGL_REQUEST, NO_DOKUMEN, NO_JPB, BPRP, ID_PEMILIK, ID_EMKL, NO_REQUEST_RECEIVING, ID_USER,
						NO_REQUEST_DELIVERY, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, NM_KAPAL, NO_PEB, NO_NPE, VOYAGE, STUFFING_DARI, NOTA) 
						VALUES('$no_req_stuf',
						'$id_yard',
						0,
						'$keterangan',
						'$no_book',
						'$tgl_req',
						'$dokumen',
						'$jpb',
						'$bprp',
						'',
						'',
						'$rec',
						'$id_user',
						'$dev',
						'$consig',
						'$tumpuk',
						'$kapal',
						'$peb',
						'$npe',
						'$voy',
						'TPK','T')";
		$db->query($query_ir);


		foreach($row_c as $rc){
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_mulai = $rc['TGL_MULAI'];
			$stat_req = $rc['STATUS_REQ'];
			$tgl_app = $rc['TGL_APPROVE'];
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER, NO_REQUEST, AKTIF, HZ, COMMODITY, TYPE_STUFFING, START_STACK,
							ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, TGL_MULAI, STATUS_REQ, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_stuf',
							'$aktif',
							'$hz',
							'$comm',
							'$type_st',
							to_date('".$start_stack."','DD-MM-YYYY'),
							'$asal',
							'$seal',
							'$berat',
							'$keterangan',
							'$tgl_mulai',
						'$stat_req',
						TO_DATE('$tgl_approve','yy-mm-dd'))";
			$db->query($query_ic);
		}  
	}
	
	$q_getcounter3 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
	$r_getcounter3 = $db->query($q_getcounter3);
	$rw_getcounter3 = $r_getcounter3->fetchRow();
	$cur_booking3  = $rw_getcounter3["NO_BOOKING"];
	$cur_counter3  = $rw_getcounter3["COUNTER"];
	
	$history_del        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req_stuf','REQUEST STUFFING',SYSDATE,'$id_user', '$cur_booking3', '$cur_counter3')";
	$db->query($history_del);
	if($db->query($query_del_uster)){
				
				$history_delivery        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
											VALUES ('$no_cont','$no_req','REQUEST DELIVERY',SYSDATE,'$id_user', '$cur_booking3', '$cur_counter3')";
				
				$db->query($history_delivery);
					
				if($db->query($sqldet)){			
						
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
			//}
	}
	else{
			echo "insert container delivery uster failed";
	}
	echo "OK";
	}
else if($asalcontstuff == "DEPO") {

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

			$query = "UPDATE PLAN_CONTAINER_STUFFING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			 WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
			 
	 $query_r = "SELECT * FROM PLAN_REQUEST_STUFFING WHERE NO_REQUEST = '$no_req'";
	$result_r = $db->query($query_r);
	$row_r	= $result_r->fetchRow();
	//print_r($row_r);die;
	$no_request = $row_r['NO_REQUEST'];
	$id_yard = $row_r['ID_YARD'];
	$keterangan = $row_r['KETERANGAN'];
	$no_book = $row_r['NO_BOOKING'];
	$tgl_req = $row_r['TGL_REQUEST'];
	$dokumen = $row_r['NO_DOKUMEN'];
	$jpb = $row_r['NO_JPB'];
	$bprp = $row_r['BPRP'];
	$rec = $row_r['NO_REQUEST_RECEIVING'];
	$id_user = $row_r['ID_USER'];
	$dev = $row_r['NO_REQUEST_DELIVERY'];
	$consig = $row_r['KD_CONSIGNEE'];
	$tumpuk = $row_r['KD_PENUMPUKAN_OLEH'];
	$kapal = $row_r['NM_KAPAL'];
	$peb = $row_r['NO_PEB'];
	$npe = $row_r['NO_NPE'];
	$voy = $row_r['VOYAGE'];
	$query_c = "SELECT DISTINCT PLAN_CONTAINER_STUFFING.*, PLAN_CONTAINER_STUFFING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
							   FROM PLAN_CONTAINER_STUFFING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A			
							   ON PLAN_CONTAINER_STUFFING.NO_CONTAINER = A.CONT_NO_BP
							   WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STUFFING.NO_CONTAINER = '$no_cont'";
	$result_c = $db->query($query_c);
	$row_c	= $result_c->getAll();

$no_req_stuf = str_replace('P', 'S', $no_req);
	$query_cek_request = "SELECT * FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req_stuf'";
	$result_cek_request = $db->query($query_cek_request);
	$row_cek_request	= $result_cek_request->getAll();

	$query_cek_cont = "SELECT * FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_req_stuf' AND NO_CONTAINER = '$no_cont'";
	$result_cek_cont = $db->query($query_cek_cont);
	$row_cek_cont	= $result_cek_cont->getAll();

	$query_tgl_app = "UPDATE CONTAINER_STUFFING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd')
			WHERE NO_REQUEST = '$no_req_stuf' AND NO_CONTAINER = '$no_cont'";
			
	if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){ //jika request telah ada dan container telah ada
			$db->query($query);
			
			$db->query($query_tgl_app);
	}
	else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
		$db->query($query);
		$no_req_stuf = str_replace('P', 'S', $no_req);
		foreach($row_c as $rc){
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_mulai = $rc['TGL_MULAI'];
			$stat_req = $rc['STATUS_REQ'];
			$tgl_app = $rc['TGL_APPROVE'];
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER, NO_REQUEST, AKTIF, HZ, COMMODITY, TYPE_STUFFING, START_STACK,
							ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, TGL_MULAI, STATUS_REQ, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_stuf',
							'$aktif',
							'$hz',
							'$comm',
							'$type_st',
							to_date('".$start_stack."','DD-MM-YYYY'),
							'$asal',
							'$seal',
							'$berat',
							'$keterangan',
							'$tgl_mulai',
						'$stat_req',
						TO_DATE('$tgl_approve','yy-mm-dd'))";
			$db->query($query_ic);
		} 
		
		
	} else { 
		$db->query($query);
		$no_req_stuf = str_replace('P', 'S', $no_req);
		$query_ir = "INSERT INTO REQUEST_STUFFING(NO_REQUEST, ID_YARD, CETAK_KARTU_SPPS, KETERANGAN, NO_BOOKING,
						TGL_REQUEST, NO_DOKUMEN, NO_JPB, BPRP, ID_PEMILIK, ID_EMKL, NO_REQUEST_RECEIVING, ID_USER,
						NO_REQUEST_DELIVERY, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, NM_KAPAL, NO_PEB, NO_NPE, VOYAGE, STUFFING_DARI) 
						VALUES('$no_req_stuf',
						'$id_yard',
						0,
						'$keterangan',
						'$no_book',
						'$tgl_req',
						'$dokumen',
						'$jpb',
						'$bprp',
						'',
						'',
						'$rec',
						'$id_user',
						'$dev',
						'$consig',
						'$tumpuk',
						'$kapal',
						'$peb',
						'$npe',
						'$voy',
						'DEPO')";
		$db->query($query_ir);


		foreach($row_c as $rc){
			$hz = $rc['HZ'];
			$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_mulai = $rc['TGL_MULAI'];
			$stat_req = $rc['STATUS_REQ'];
			$tgl_app = $rc['TGL_APPROVE'];
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER, NO_REQUEST, AKTIF, HZ, COMMODITY, TYPE_STUFFING, START_STACK,
							ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, TGL_MULAI, STATUS_REQ, TGL_APPROVE)
							VALUES('$cont',
							'$no_req_stuf',
							'$aktif',
							'$hz',
							'$comm',
							'$type_st',
							to_date('".$start_stack."','DD-MM-YYYY'),
							'$asal',
							'$seal',
							'$berat',
							'$keterangan',
							'$tgl_mulai',
						'$stat_req',
						TO_DATE('$tgl_approve','yy-mm-dd'))";
			$db->query($query_ic);
		}   
	}

	$q_getcounter4 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
	$r_getcounter4 = $db->query($q_getcounter4);
	$rw_getcounter4 = $r_getcounter4->fetchRow();
	$cur_booking4  = $rw_getcounter4["NO_BOOKING"];
	$cur_counter4  = $rw_getcounter4["COUNTER"];
	
	$history_stuf        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req_stuf','REQUEST STUFFING',SYSDATE,'$id_user', '$cur_booking4', '$cur_counter4')";
	$db->query($history_stuf);

}
?>