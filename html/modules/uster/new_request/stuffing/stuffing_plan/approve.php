<?php
//$tl 	=  xliteTemplate('cont_list.htm');

// --APPROVE CONTAINER YG DI STUFFING
// --Model Dokumentasi
// -- Daftar Isi
// -Terdapat 2 mode pada modul ini,
// 1 - Pertama container yang di stuffing berasal dari TPK, langkah2
	// 1.1 - Update status di TPK
			// 1.1.2 - Query semua data container TPK
	
	// 1.2 - Insert Ke container_receiving 
			// 1.2.1 - Tambahkan data container receiving ke history container
	
// 2 - Kedua, container yang distuffing berasal dari luar

$db 				= getDB("storage");

$asalcontstuff 		= $_POST["ASAL_CONT"];
$tgl_approve 		= $_POST["tgl_approve"];
$no_cont 			= $_POST["no_cont"];
$no_req 			= $_POST["no_req"];
$no_req_rec			= $_POST["NO_REQ_REC"]; 
$no_req_del			= $_POST["NO_REQ_DEL"];
$nm_user 			= $_SESSION["NAME"];
$id_user 			= $_SESSION["LOGGED_STORAGE"];
$id_yard			= $_SESSION["IDYARD_STORAGE"];

$no_req_ict			= $_POST["NO_REQ_ICT"];
$no_req_ict2		= $_POST["NO_REQ_ICT2"];
$no_do				= $_POST["NO_DO"];
$no_bl				= $_POST["NO_BL"];
$sp2				= $_POST["SP2"];
$no_booking			= $_POST["NO_BOOKING"];

$container_size					= $_POST["CONTAINER_SIZE"];
$container_type					= $_POST["CONTAINER_TYPE"];
$container_status				= $_POST["CONTAINER_STATUS"];
$container_hz					= $_POST["CONTAINER_HZ"];
$container_imo					= $_POST["CONTAINER_IMO"];
$container_iso_code				= $_POST["CONTAINER_ISO_CODE"];
$container_height				= $_POST["CONTAINER_HEIGHT"];
$container_carrier				= $_POST["CONTAINER_CARRIER"];
$container_reefer_temp			= $_POST["CONTAINER_REEFER_TEMP"];
$container_booking_sl			= $_POST["CONTAINER_BOOKING_SL"];
$container_over_width			= $_POST["CONTAINER_OVER_WIDTH"];
$container_over_length			= $_POST["CONTAINER_OVER_LENGTH"];
$container_over_height			= $_POST["CONTAINER_OVER_HEIGHT"];
$container_over_front			= $_POST["CONTAINER_OVER_FRONT"];
$container_over_rear			= $_POST["CONTAINER_OVER_REAR"];
$container_over_left			= $_POST["CONTAINER_OVER_LEFT"];
$container_over_right			= $_POST["CONTAINER_OVER_RIGHT"];
$container_un_number			= $_POST["CONTAINER_UN_NUMBER"];
$container_pod					= $_POST["CONTAINER_POD"];
$container_pol					= $_POST["CONTAINER_POL"];
$container_vessel_confirm		= $_POST["CONTAINER_VESSEL_CONFIRM"];
$container_comodity_type_code	= $_POST["CONTAINER_COMODITY_TYPE_CODE"];
//echo $tgl_approve;die;
// echo $asalconstuff;die;

if($asalcontstuff == "TPK"){
	//detail container
	$dateTimeVesselConfirm = DateTime::createFromFormat("YmdHis", $container_vessel_confirm);
	$formattedDateVesselConfirm = $dateTimeVesselConfirm->format("d-m-Y H:i:s");

	// ===========================================  START SIMOP TPK ==============================================//
	$qpaidthru  = "SELECT TGL_APPROVE FROM PLAN_CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
    $rpaid      = $db->query($qpaidthru)->fetchRow();
    
	// 1.1 - Update status di TPK
	$no_req_stuf = str_replace('P', 'S', $no_req);
    $opus_param = array("in_nocont" => $no_cont,
                       "in_planreq" => $no_req,
                       "in_req" => $no_req_stuf,
                       "in_asalcont" => $asalcontstuff,
                       "in_tglsp2" => $rpaid['TGL_APPROVE'],
                       "in_container_size" => $container_size != 'null' ? $container_size: '',
                       "in_container_type" => $container_type != 'null' ? $container_type: '',
                       "in_container_status" => $container_status != 'null' ? $container_status: '',
                       "in_container_hz" => $container_hz != 'null' ? $container_hz: '',
                       "in_container_imo" => $container_imo != 'null' ? $container_imo: '',
                       "in_container_iso_code" => $container_iso_code != 'null' ? $container_iso_code: '',
                       "in_container_height" => $container_height != 'null' ? $container_height: '',
                       "in_container_carrier" => $container_carrier != 'null' ? $container_carrier: '',
                       "in_container_reefer_temp" => $container_reefer_temp != 'null' ? $container_reefer_temp: '',
                       "in_container_booking_sl" => $container_booking_sl != 'null' ? $container_booking_sl: '',
                       "in_container_over_width" => $container_over_width != 'null' ? $container_over_width: '',
                       "in_container_over_length" => $container_over_length != 'null' ? $container_over_length: '',
                       "in_container_over_height" => $container_over_height != 'null' ? $container_over_height: '',
                       "in_container_over_front" => $container_over_front != 'null' ? $container_over_front: '',
                       "in_container_over_rear" => $container_over_rear != 'null' ? $container_over_rear: '',
                       "in_container_over_left" => $container_over_left != 'null' ? $container_over_left: '',
                       "in_container_over_right" => $container_over_right != 'null' ? $container_over_right: '',
                       "in_container_un_number" => $container_un_number != 'null' ? $container_un_number: '',
                       "in_container_pod" => $container_pod != 'null' ? $container_pod: '',
                       "in_container_pol" => $container_pol != 'null' ? $container_pol: '',
                       "in_container_vessel_confirm" => $formattedDateVesselConfirm != 'null' ? $formattedDateVesselConfirm: '',
                       "in_container_comodity" => $komoditi != 'null' ? trim($komoditi): '',
                       "in_container_c_type_code" => $container_comodity_type_code != 'null' ? $container_comodity_type_code: '',
                       "p_ErrMsg" => '');

   	// echo var_dump($opus_param);die;
    $opus_q     = "declare begin pack_create_req_stuffing.approve_stuf_praya(:in_nocont,:in_planreq,:in_req,:in_asalcont,:in_tglsp2,:in_container_size,:in_container_type,:in_container_status,:in_container_hz,:in_container_imo,:in_container_iso_code,:in_container_height,:in_container_carrier,:in_container_reefer_temp,:in_container_booking_sl,:in_container_over_width,:in_container_over_length,:in_container_over_height,:in_container_over_front,:in_container_over_rear,:in_container_over_left,:in_container_over_right,:in_container_un_number,:in_container_pod,:in_container_pol,:in_container_vessel_confirm,:in_container_comodity,:in_container_c_type_code,:p_ErrMsg); end;";
    //print_r($opus_param);

    if($db->query($opus_q,$opus_param)){
        $msg = $opus_param["p_ErrMsg"];
    }

    if($msg != 'OK'){
        echo $msg;
        exit();
    }else {
							
        // ===========================================================  END SIMOP TPK==============================================================================//
					
	
	   // 1.2 - Insert Ke Container_Receiving
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
													   'MTY',
													   'Y',
													   '$hz',
													   TO_DATE('$tgl_bongkar','dd-mm-yy'),
													   '$commodity',
													   '$depo_tujuan',
													   '$blok_tpk',
													   '$slot_tpk',
													   '$row_tpk',
													   '$tier_tpk')";

	   
						 
	// 1.2.1 - Tambahkan data container receiving ke history container 
		//add history container
		$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
		$r_getcounter1 = $db->query($q_getcounter1);
		$rw_getcounter1 = $r_getcounter1->fetchRow();
		$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
		$cur_counter1  = $rw_getcounter1["COUNTER"];
		
		$history_rec        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD,STATUS_CONT, NO_BOOKING, COUNTER) 
								VALUES ('$no_cont','$no_req_rec','REQUEST RECEIVING',SYSDATE,'$id_user', '$id_yard','MTY', '$cur_booking1', '$cur_counter1')";
	//end history
				
														  
		if($db->query($history_rec)){
			$query_cek1		= "SELECT tes.NO_REQUEST, 
										CASE SUBSTR(KEGIATAN,9)
											WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) 
																	FROM request_receiving a 
																	WHERE a.NO_REQUEST = tes.NO_REQUEST)
											ELSE SUBSTR(KEGIATAN,9)
										END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI')) tes
										WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI'))";
			$result_cek1		= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			$no_request_	= $row_cek1["NO_REQUEST"];
			$kegiatan		= $row_cek1["KEGIATAN"];
			
			IF ($kegiatan == 'RECEIVING_LUAR') {
					$query_cek1		= "SELECT SUBSTR(TO_CHAR(b.TGL_IN+5,'dd/mm/rrrr'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
					$result_cek1		= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					// $start_stack	  	= $row_cek1["START_STACK"];
					$asal_cont 		= 'LUAR';
			} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
					$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					// $start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'TPK';
			} ELSE IF ($kegiatan == 'STRIPPING') {
					$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,9) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_'";
					$result_cek1	= $db->query($query_cek1);
					$row_cek1		= $result_cek1->fetchRow();
					// $start_stack	= $row_cek1["START_STACK"];
					$asal_cont 		= 'DEPO';
			}
		}

	

	if($db->query($query_cont_rec_uster)){
			echo "insert container receiving uster succeed";
	}
	else{
			echo "insert container receiving uster failed";
	}
										
			


	$query_r 	= "SELECT 	NO_REQUEST,
							ID_YARD,ID_YARD,TGL_REQUEST,
							NO_DOKUMEN,NO_JPB,BPRP, NO_REQUEST_RECEIVING, 
							ID_USER,NO_REQUEST_DELIVERY, KD_CONSIGNEE, 	
							KD_PENUMPUKAN_OLEH, NM_KAPAL,NO_PEB, NO_NPE, VOYAGE 
					FROM PLAN_REQUEST_STUFFING 
					WHERE NO_REQUEST = '$no_req'";
	$result_r 	= $db->query($query_r);
	$row_r		= $result_r->fetchRow();
	//print_r($row_r);die;
	$no_request = $row_r['NO_REQUEST'];
	$id_yard 	= $row_r['ID_YARD'];
	$keterangan = $row_r['ID_YARD'];
	$no_book	= $row_r['NO_BOOKING'];
	$tgl_req 	= $row_r['TGL_REQUEST'];
	$dokumen 	= $row_r['NO_DOKUMEN'];
	$jpb 		= $row_r['NO_JPB'];
	$bprp 		= $row_r['BPRP'];
	$rec 		= $row_r['NO_REQUEST_RECEIVING'];
	$id_user 	= $row_r['ID_USER'];
	$dev 		= $row_r['NO_REQUEST_DELIVERY'];
	$consig 	= $row_r['KD_CONSIGNEE'];
	$tumpuk 	= $row_r['KD_PENUMPUKAN_OLEH'];
	$kapal 		= $row_r['NM_KAPAL'];
	$peb 		= $row_r['NO_PEB'];
	$npe 		= $row_r['NO_NPE'];
	$voy 		= $row_r['VOYAGE'];
	
	
	
	$query_c 	= "SELECT DISTINCT PLAN_CONTAINER_STUFFING.NO_CONTAINER,
								   PLAN_CONTAINER_STUFFING.AKTIF,
								   PLAN_CONTAINER_STUFFING.HZ,
								   PLAN_CONTAINER_STUFFING.TYPE_STUFFING,
								   PLAN_CONTAINER_STUFFING.ASAL_CONT,
								   PLAN_CONTAINER_STUFFING.NO_SEAL,
								   PLAN_CONTAINER_STUFFING.BERAT,
								   PLAN_CONTAINER_STUFFING.KETERANGAN,
								   PLAN_CONTAINER_STUFFING.TGL_APPROVE,
								   TO_CHAR(PLAN_CONTAINER_STUFFING.START_STACK,'dd-mm-yyyy') STACK,
								   PLAN_CONTAINER_STUFFING.COMMODITY,
								   PLAN_CONTAINER_STUFFING.KD_COMMODITY
					   FROM PLAN_CONTAINER_STUFFING 
					   WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STUFFING.NO_CONTAINER = '$no_cont'";
	$result_c = $db->query($query_c);
	$row_c	= $result_c->getAll();

	$no_req_stuf = str_replace('P', 'S', $no_req);
	$query_cek_request = "SELECT NO_REQUEST FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req_stuf'";
	$result_cek_request = $db->query($query_cek_request);
	$row_cek_request	= $result_cek_request->getAll();

	$query_cek_cont = "SELECT NO_CONTAINER FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_req_stuf' AND NO_CONTAINER = '$no_cont'";
	$result_cek_cont = $db->query($query_cek_cont);
	$row_cek_cont	= $result_cek_cont->getAll();

	
			
	if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){ //jika request telah ada dan container telah ada
			$db->query($query);
			
			$db->query($query_tgl_app);
	}
	else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
		$no_req_stuf = str_replace('P', 'S', $no_req);
		$db->query($query);
		foreach($row_c as $rc){
			$hz = $rc['HZ'];
			//$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			
			//CEK TGL GATE
			$tes = "select TO_CHAR(TGL_UPDATE,'dd/mm/rrrr') TGL_GATE from history_container where no_container = '$cont' AND KEGIATAN = 'BORDER GATE IN' AND TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM history_container WHERE NO_CONTAINER = '$cont')";
			$result_tes = $db->query($tes);
			$gate	= $result_tes->fetchRow();
			$tgl_gate = $gate['TGL_GATE'];
			
			//$early_stuff = $rc['EARLY_STUFF'];
			$start_stack = $rc['STACK'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$kd_comm = $rc['KD_COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_app = $rc['TGL_APPROVE'];
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (	NO_CONTAINER, NO_REQUEST, 
															AKTIF, HZ, COMMODITY, TYPE_STUFFING, 
															START_STACK,
															ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, 
															TGL_APPROVE, 
															TGL_GATE,
															START_PERP_PNKN,
															KD_COMMODITY)
													VALUES(	'$cont',
															'$no_req_stuf',
															'$aktif',
															'$hz',
															'$comm',
															'$type_st',
															to_date('".$start_stack."','dd-mm-rrrr'),
															'$asal',
															'$seal',
															'$berat',
															'$keterangan',
															SYSDATE,
															TO_DATE('$tgl_gate','dd-mm-rrrr'),
															TO_DATE('$tgl_app','dd-mm-rrrr'),
															'$kd_comm')";
			if($db->query($query_ic))
			{
				echo "sukses";
			};
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
			
			//$early_stuff = $rc['EARLY_STUFF'];
			$start_stack = $rc['STACK'];
			$hz = $rc['HZ'];
			//$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$kd_comm = $rc['KD_COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_app = $rc['TGL_APPROVE'];
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (	NO_CONTAINER, NO_REQUEST, 
															AKTIF, HZ, COMMODITY, TYPE_STUFFING, 
															START_STACK,
															ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, 
															TGL_APPROVE, 
															TGL_GATE,
															START_PERP_PNKN,
															KD_COMMODITY)
													VALUES(	'$cont',
															'$no_req_stuf',
															'$aktif',
															'$hz',
															'$comm',
															'$type_st',
															to_date('".$start_stack."','dd-mm-rrrr'),
															'$asal',
															'$seal',
															'$berat',
															'$keterangan',
															SYSDATE,
															TO_DATE('$tgl_gate','dd-mm-rrrr'),
															TO_DATE('$tgl_app','dd-mm-rrrr'),
															'$kd_comm')";
			$db->query($query_ic);
		}  
	}
	
	
	//tidak auto delivery ke tpk
	// $query_del_uster  = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, 
						  // KELUAR,HZ, KOMODITI,KETERANGAN,
						  // NO_SEAL,BERAT,VIA,ID_YARD, 
						  // NOREQ_PERALIHAN, START_STACK, ASAL_CONT) 
						  // VALUES('$no_cont', '$no_req_del', '$status','Y',
						  // 'N','$hz','$commodity','$keterangan',
						  // '$no_seal','$berat','DARAT','$id_yard',
						  // '$no_request', TO_DATE('$start_stack','dd-mm-rrrr'), '$asal_cont')";
	// //echo $query_del_uster;
	
	$q_getcounter3 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
	$r_getcounter3 = $db->query($q_getcounter3);
	$rw_getcounter3 = $r_getcounter3->fetchRow();
	$cur_booking3  = $rw_getcounter3["NO_BOOKING"];
	$cur_counter3  = $rw_getcounter3["COUNTER"];
	
	$history_stuff       = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD,STATUS_CONT, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req_stuf','REQUEST STUFFING',SYSDATE,'$id_user', '46','MTY', '$cur_booking3', '$cur_counter3')";
	if($db->query($history_stuff)){
		echo "insert history stuffing ok";
	}
	else {
		echo "insert history stuffing failed";
	}
	
	//tidak auto delivery ke tpk
	//if($db->query($query_del_uster)){
				//echo "insert container delivery uster ok";
				
				// $history_delivery        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD,STATUS_CONT, NO_BOOKING, COUNTER) 
											// VALUES ('$no_cont','$no_req_del','REQUEST DELIVERY',SYSDATE,'$id_user', '$id_yard','MTY', '$cur_booking3', '$cur_counter3')";
				
				// $db->query($history_delivery);
				

				//Insert req receiving TPK,;  update terbaru, tidak auto delivery ke tpk
				// if($db->query($sqldet)){			
					// echo "insert receiving ict succeed";
					
					// if($jenistpk =='3')
					// {
						// $sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
							// (	PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
								// '-',
								// SYSDATE, 
								// '$nm_user',
								// '05',
								// SYSDATE,
								// '$no_req_ict',
								// '',
								// '$no_ukk'
							// )";
						// if($db->query($sqlx3)){
							// echo "insert ttd cont peb succeed";
						// }
						// else
						// {
							// echo "insert ttd cont peb failed";
						// }
					// }
					
				// }
				// else{
					// echo "insert receiving ict failed";
				// }
			//}
	//}
		// else{
				// echo "insert container delivery uster failed";
		// }
		echo "OK";
	}
    
} else if($asalcontstuff == "DEPO") {
	
	
	
	
	
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
	
	
	
	if ($kegiatan == 'RECEIVING_LUAR') {
			$query_cek1		= "SELECT SUBSTR(TO_CHAR(b.TGL_IN, 'MM/DD/YYYY'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
			$result_cek1		= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			//$start_stack	  	= $row_cek1["START_STACK"];
			$asal_cont 		= 'LUAR';
	} else if ($kegiatan == 'RECEIVING_TPK') {
			$query_cek1		= "SELECT TGL_BONGKAR START_STACK FROM container_receiving WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
			$result_cek1	= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			//$start_stack	= $row_cek1["START_STACK"];
			$asal_cont 		= 'TPK';
	} else if ($kegiatan == 'STRIPPING') {
			$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'MM/DD/YYYY'),1,9) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
			$result_cek1	= $db->query($query_cek1);
			$row_cek1		= $result_cek1->fetchRow();
			//$start_stack	= $row_cek1["START_STACK"];
			$asal_cont 		= 'DEPO';
	}

			 
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
	$query_c = "SELECT DISTINCT    PLAN_CONTAINER_STUFFING.NO_CONTAINER,
								   PLAN_CONTAINER_STUFFING.HZ,
								   PLAN_CONTAINER_STUFFING.AKTIF,
								   PLAN_CONTAINER_STUFFING.TYPE_STUFFING,
								   PLAN_CONTAINER_STUFFING.ASAL_CONT,
								   PLAN_CONTAINER_STUFFING.NO_SEAL,
								   PLAN_CONTAINER_STUFFING.BERAT,
								   PLAN_CONTAINER_STUFFING.KETERANGAN,
								   PLAN_CONTAINER_STUFFING.TGL_APPROVE,								   
								   PLAN_CONTAINER_STUFFING.TGL_MULAI,
								   TO_CHAR(PLAN_CONTAINER_STUFFING.START_STACK,'dd-mm-yyyy') STACK,
								   PLAN_CONTAINER_STUFFING.COMMODITY,
								   PLAN_CONTAINER_STUFFING.KD_COMMODITY,
								   PLAN_CONTAINER_STUFFING.NO_REQ_SP2
					   FROM PLAN_CONTAINER_STUFFING 
					   WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STUFFING.NO_CONTAINER = '$no_cont'";
	$result_c = $db->query($query_c);
	$row_c	= $result_c->getAll();

$no_req_stuf = str_replace('P', 'S', $no_req);
	$query_cek_request = "SELECT * FROM REQUEST_STUFFING WHERE NO_REQUEST = '$no_req_stuf'";
	$result_cek_request = $db->query($query_cek_request);
	$row_cek_request	= $result_cek_request->getAll();
	
	//query apakah kontainer telah ada di table cont stuffing
	$query_cek_cont = "SELECT * FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_req_stuf' AND NO_CONTAINER = '$no_cont'";
	$result_cek_cont = $db->query($query_cek_cont);
	$row_cek_cont	= $result_cek_cont->getAll();

	// $query_tgl_app = "UPDATE CONTAINER_STUFFING SET TGL_APPROVE = TO_DATE('$tgl_approve','dd-mm-rrrr')
			// WHERE NO_REQUEST = '$no_req_stuf' AND NO_CONTAINER = '$no_cont'";
			
	if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){ //jika request telah ada dan container telah ada
			$db->query($query);
			
			$db->query($query_tgl_app);
	}
	else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
		$db->query($query);
		$no_req_stuf = str_replace('P', 'S', $no_req);
		foreach($row_c as $rc){
			$hz = $rc['HZ'];
			//$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			
			//CEK TGL GATE
			$tes = "select TO_CHAR(TGL_UPDATE,'dd/mm/rrrr') TGL_GATE from history_container where no_container = '$cont' AND KEGIATAN = 'BORDER GATE IN' AND TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM history_container WHERE NO_CONTAINER = '$cont')";
			$result_tes = $db->query($tes);
			$gate	= $result_tes->fetchRow();
			$tgl_gate = $gate['TGL_GATE'];
			
			$start_stack = $rc['STACK'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$tgl_app = $rc['TGL_APPROVE'];			
			$tgl_mulai = $rc['TGL_MULAI'];
			$req_sp2 = $rc['NO_REQ_SP2'];
			if($req_sp2 == NULL){
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (	NO_CONTAINER, NO_REQUEST, 
															AKTIF, HZ, COMMODITY, TYPE_STUFFING, 
															START_STACK,
															ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, 
															TGL_APPROVE, 
															TGL_GATE,
															START_PERP_PNKN)
													VALUES(	'$cont',
															'$no_req_stuf',
															'$aktif',
															'$hz',
															'$comm',
															'$type_st',
															to_date('".$start_stack."','dd-mm-rrrr'),
															'$asal',
															'$seal',
															'$berat',
															'$keterangan',
															SYSDATE,
															TO_DATE('$tgl_gate','dd-mm-rrrr'),
															TO_DATE('$tgl_app','dd-mm-rrrr'))";
			} else {
				$query_ic	= "INSERT INTO CONTAINER_STUFFING (	NO_CONTAINER, NO_REQUEST, 
															AKTIF, HZ, COMMODITY, TYPE_STUFFING, 
															START_STACK,
															ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, 
															TGL_APPROVE, 
															TGL_GATE,
															START_PERP_PNKN,
															END_STACK_PNKN,
															REMARK_SP2)
													VALUES(	'$cont',
															'$no_req_stuf',
															'$aktif',
															'$hz',
															'$comm',
															'$type_st',
															'$tgl_mulai',
															'$asal',
															'$seal',
															'$berat',
															'$keterangan',
															TO_DATE('$tgl_app','dd-mm-rrrr'),
															TO_DATE('$tgl_gate','dd-mm-rrrr'),
															TO_DATE('".$start_stack."','dd-mm-rrrr'),
															TO_DATE('$tgl_approve','dd-mm-rrrr'),
															'Y')";
			}
			$db->query($query_ic);
			
			//echo "sampai sini";exit;
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
		
			$start_stack = $rc['STACK'];
			$hz = $rc['HZ'];
			//$req = $rc['NO_REQUEST'];
			$cont = $rc['NO_CONTAINER'];
			$aktif = $rc['AKTIF'];
			$comm = $rc['COMMODITY'];
			$kd_comm = $rc['KD_COMMODITY'];
			$type_st = $rc['TYPE_STUFFING'];
			$asal = $rc['ASAL_CONT'];
			$seal = $rc['NO_SEAL'];
			$berat = $rc['BERAT'];
			$keterangan = $rc['KETERANGAN'];
			$stat_req = $rc['STATUS_REQ'];
			$tgl_app = $rc['TGL_APPROVE'];
			
			//CEK TGL GATE
			$tes = "select TO_CHAR(TGL_UPDATE,'dd/mm/rrrr') TGL_GATE from history_container where no_container = '$cont' AND KEGIATAN = 'BORDER GATE IN' AND TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM history_container WHERE NO_CONTAINER = '$cont')";
			$result_tes = $db->query($tes);
			$gate	= $result_tes->fetchRow();
			$tgl_gate = $gate['TGL_GATE'];
			
			
			$query_ic	= "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER, 
														   NO_REQUEST, 
														   AKTIF, 
														   HZ, 
														   COMMODITY, 
														   TYPE_STUFFING, 
														   START_STACK,
														   ASAL_CONT, 
														   NO_SEAL, 
														   BERAT, 
														   KETERANGAN, 
														   STATUS_REQ, 
														   TGL_APPROVE, 
														   TGL_GATE, 
														   TGL_MULAI_FULL, 
														   TGL_SELESAI_FULL,
														   KD_COMMODITY)
													VALUES('$cont',
														   '$no_req_stuf',
														   '$aktif',
														   '$hz',
														   '$comm',
														   '$type_st',
														   to_date('".$start_stack."','dd-mm-rrrr'),
														   '$asal',
														   '$seal',
														   '$berat',
														   '$keterangan',
														   '$stat_req',
													       SYSDATE,
													       TO_DATE('$tgl_gate','dd-mm-rrrr'),
													       TO_DATE('$tgl_app','dd-mm-rrrr')+1,
													       TO_DATE('$tgl_app','dd-mm-rrrr')+5,
														   '$kd_comm')";
			$db->query($query_ic);
		}   
	}

	$q_getcounter4 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
	$r_getcounter4 = $db->query($q_getcounter4);
	$rw_getcounter4 = $r_getcounter4->fetchRow();
	$cur_booking4  = $rw_getcounter4["NO_BOOKING"];
	$cur_counter4  = $rw_getcounter4["COUNTER"];
	
	$history_stuf        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req_stuf','REQUEST STUFFING',SYSDATE,'$id_user', '46', 'MTY', '$cur_booking4', '$cur_counter4')";
	if($db->query($history_stuf))
	{
		echo "history stuf depo masuk";exit;
	}
	else
	{
		echo "gagal masuk history stuf depo";exit;
	};

}
?>