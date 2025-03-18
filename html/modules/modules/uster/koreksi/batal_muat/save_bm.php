<?php
$db =  getDB("storage");
$db2 =  getDB("ora");
$jenis_bm 				= $_POST["jenis_batal"];	//echo $jenis_bm.'<br/>';
$status_gate 			= $_POST["status_gate"];	//echo $status_gate.'<br/>';
$biaya 					= $_POST["biaya"];			//echo $biaya.'<br/>';
$kd_pbm 				= $_POST["KD_PELANGGAN"];	//echo $kd_pbm.'<br/>';
$kd_kapal 				= $_POST["KD_KAPAL"];		//echo $kd_kapal.'<br/>';
$kd_pelabuhan_asal	 	= $_POST["KD_PELABUHAN_ASAL"];	//echo $kd_pelabuhan_asal.'<br/>';
$kd_pelabuhan_tujuan 	= $_POST["KD_PELABUHAN_TUJUAN"];		//echo $kd_pelabuhan_tujuan.'<br/>';
//$no_request			 	= $_POST["NO_REQUEST"];			//echo $no_request.'<br/>';
$no_booking_new		 	= $_POST["NO_BOOKING"];		//echo $no_booking_new.'<br/>';
$no_req_ict			 	= $_POST["NO_REQ_ICT"];		//echo $no_req_ict.'<br/>';
$id_user				= $_SESSION["LOGGED_STORAGE"];	//echo $id_user.'<br/>';
$id_yard				= $_SESSION["IDYARD_STORAGE"];	//echo $id_yard.'<br/>';
$etd					= $_POST["ETD"];
$NO_UKK					= $_POST["NO_UKK"];
$TGL_MUAT				= $_POST["TGL_MUAT"];
$TGL_STACKING			= $_POST["TGL_STACKING"];
$nm_kapal				= $_POST["NM_KAPAL"];
$voyage_in				= $_POST["VOYAGE_IN"];
//$status					= $_POST["KDSTATUS"];
$type					= $_POST["KDTYPE"];

debug($_POST);

$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM,
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_BATAL_MUAT
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
$result_cek	= $db->query($query_cek);
$jum_		= $result_cek->fetchRow();
$jum		= $jum_["JUM"];
$month		= $jum_["MONTH"];
$year		= $jum_["YEAR"];

$no_req_bm	= "BMU".$month.$year.$jum;

if($no_booking_new == NULL){
	$no_booking_new = "VESSEL_NOTHING";
}
// echo $no_req_bm.'<br/>';					
// echo 'BOOKING OLD : '.$no_booking_old.'<br/>';
// echo 'ICT : '.$no_req_ict.'<br/>';
if($status_gate == 1){ //batal muat after stuffing
	
	/*insert to table request batal muat*/
	$q_batal = "insert into request_batal_muat(no_request,tgl_request,kd_emkl,biaya,jenis_bm,kapal_tuju,status_gate)
				values('$no_req_bm',SYSDATE,'$kd_pbm','$biaya','$jenis_bm','$no_booking_new','$status_gate')";
	//$db->startTransaction();
	if($db->query($q_batal)){
		debug(count($_POST['BM_CONT']));
		for($i=0;$i<count($_POST['BM_CONT']);$i++){
			$no_cont = $_POST['BM_CONT'][$i];
			$start_pnkn = $_POST['AWAL'][$i];			
			$end_pnkn = $_POST['AKHIR'][$i];			
			$exno_req = $_POST['BMNO_REQ'][$i];	
			$status	 = $_POST["KDSTATUS"][$i];
			debug($no_cont);
			//die;
			$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_counter = $rw_getcounter["COUNTER"];			
						
			/*ambil data ex kegiatan*/						
			$q_ex_req = "select no_request, no_booking, no_npe, no_peb
						from request_stuffing where no_request = '$exno_req'";
			$r_ex_req 		= $db->query($q_ex_req);
			$r_ex			= $r_ex_req->fetchRow();
			$no_booking_old = $r_ex['NO_BOOKING'];			
					
			$q_disable_cont = "update container_stuffing set aktif = 'T', f_batal_muat = 'Y' where no_container = '$no_cont' and no_request = '$exno_req'";
			$query_update_plan	= "UPDATE PLAN_CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = REPLACE('$exno_req','S','P')";
			$q_history_c   = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER, STATUS_CONT)  
							VALUES ('$no_cont','$no_req_bm','REQUEST BATALMUAT',SYSDATE,'$id_user','$id_yard','$no_booking_new', '$cur_counter','$status')";	
			
			$q_cont_batal = "insert into container_batal_muat(no_container, no_request, status, start_pnkn, end_pnkn, no_req_batal, ex_kapal) 
			values ('$no_cont','$no_req_bm','$status',TO_DATE('$start_pnkn','dd/mm/rrrr'),TO_DATE('$end_pnkn','dd-mm-rrrr'), '$exno_req', '$no_booking_old' )";
			
			if($db->query($q_cont_batal)){ /*insert container batal muat*/
				$db->query($q_history_c);				
				//$db->query($q_disable_cont);
				//$db->query($query_update_plan);
			}			
			
		}	
	}
	//$db->endTransaction();
} else if($status_gate == 2) { //batal muat ex repo
	
	/*insert to table request batal muat*/
	$q_batal = "insert into request_batal_muat(no_request,tgl_request,kd_emkl,biaya,jenis_bm,kapal_tuju,status_gate)
				values('$no_req_bm',SYSDATE,'$kd_pbm','$biaya','$jenis_bm','$no_booking_new','$status_gate')";
	//$db->startTransaction();
	if($db->query($q_batal)){
		for($i=0;$i<count($_POST['BM_CONT']);$i++){
			$no_cont = $_POST['BM_CONT'][$i];
			$start_pnkn = $_POST['AWAL'][$i];
			$end_pnkn = $_POST['AKHIR'][$i];
			//$q_update_m = "update master_container set no_booking = '$no_booking_new' where no_container = '$no_cont'";
			$exno_req = $_POST['BMNO_REQ'][$i];	
			$status	 = $_POST["KDSTATUS"][$i];
			$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_counter = $rw_getcounter["COUNTER"];			
						
			/*ambil data ex kegiatan*/						
			$q_ex_req = "select no_request, kd_pelabuhan_asal, kd_pelabuhan_tujuan, no_booking, no_ro, npe, peb,
							tgl_berangkat, no_req_ict
							from request_delivery where no_request = '$exno_req'";
			$r_ex_req 		= $db->query($q_ex_req);
			$r_ex			= $r_ex_req->fetchRow();
			$no_booking_old = $r_ex['NO_BOOKING'];
			$no_req_ict 	= $r_ex['NO_REQ_ICT'];
			
			$q_history_c   = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER, STATUS_CONT)  
							VALUES ('$no_cont','$no_req_bm','REQUEST BATALMUAT',SYSDATE,'$id_user','$id_yard','$no_booking_new', '$cur_counter','$status')";	
			
			$q_cont_batal = "insert into container_batal_muat(no_container, no_request, status, start_pnkn, end_pnkn, no_req_batal, ex_kapal) 
			values ('$no_cont','$no_req_bm','$status',TO_DATE('$start_pnkn','dd-mm-rrrr'),TO_DATE('$end_pnkn','dd-mm-rrrr'), '$exno_req', '$no_booking_old' )";
			$db->query($q_history_c);
			$db->query($q_cont_batal);
			$query_pmb ="SELECT ptk.KD_PMB_DTL AS KD_PMB_DTL,NO_SEAL,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,HZ
				FROM PETIKEMAS_CABANG.TTD_CONT_EXBSPL ptk
				WHERE KD_PMB = '$no_req_ict' AND NO_CONTAINER = '$no_cont'";
			$result_pmb_dtl = $db2->query($query_pmb);
			$row_pmb_dtl = $result_pmb_dtl -> FetchRow();
			$kd_pmb_dtl = $row_pmb_dtl["KD_PMB_DTL"];
			$no_seal = $row_pmb_dtl["NO_SEAL"];
			$sizetpk = $row_pmb_dtl["KD_SIZE"];
			$typetpk = $row_pmb_dtl["KD_TYPE_CONT"];
			$jenistpk = $row_pmb_dtl["KD_JENIS_PEMILIK"];
			$komodityict = $row_pmb_dtl["KD_COMMODITY"];
			$beratict = $row_pmb_dtl["GROSS"];
			$hzict    = $row_pmb_dtl["HZ"];
			
			
			$query_del_exbspl = "UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL SET STATUS_PP = 'Y' WHERE KD_PMB = '$no_req_ict' AND NO_CONTAINER = '$no_cont' AND KD_PMB_DTL = '$kd_pmb_dtl'";			
			$query_del_peb = "DELETE FROM PETIKEMAS_CABANG.TTD_CONT_PEB WHERE KD_PMB = '$no_req_ict' AND KD_PMB_DTL = '$kd_pmb_dtl' ";
			$update_batal_delivery= "UPDATE CONTAINER_DELIVERY SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$exno_req' AND NO_CONTAINER='$no_cont'";
			if($db2->query($query_del_exbspl)){
				$db2->query($query_del_peb);
				$db->query($update_batal_delivery);
			}
			
		}
	}						
	//$db->endTransaction();
} else if ($status_gate == 3){ //batal muat before stuffing
	$q_batal = "insert into request_batal_muat(no_request,tgl_request,kd_emkl,biaya,jenis_bm,kapal_tuju,status_gate)
				values('$no_req_bm',SYSDATE,'$kd_pbm','$biaya','$jenis_bm','$no_booking_new','$status_gate')";
	//$db->startTransaction();
	if($db->query($q_batal)){
		for($i=0;$i<count($_POST['BM_CONT']);$i++){
			$no_cont = $_POST['BM_CONT'][$i];
			$start_pnkn = $_POST['AWAL'][$i];
			$end_pnkn = $_POST['AKHIR'][$i];
			$exno_req = $_POST['BMNO_REQ'][$i];	
			$status	 = $_POST["KDSTATUS"][$i];
			$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_counter = $rw_getcounter["COUNTER"];			
						
			/*ambil data ex kegiatan*/						
			$q_ex_req = "select no_request, no_booking, no_npe, no_peb
						from request_stuffing where no_request = '$exno_req'";
			$r_ex_req 		= $db->query($q_ex_req);
			$r_ex			= $r_ex_req->fetchRow();
			$no_booking_old = $r_ex['NO_BOOKING'];
			
			$q_disable_cont = "update container_stuffing set aktif = 'T', f_batal_muat = 'Y' where no_container = '$no_cont' and no_request = '$exno_req'";
			if($status_gate == 3){ 
				$query_update_plan	= "UPDATE PLAN_CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = REPLACE('$exno_req','S','P')";  
				$db->query($query_update_plan);
			}
			$q_history_c   = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER, STATUS_CONT) VALUES ('$no_cont','$no_req_bm','REQUEST BATALMUAT',SYSDATE,'$id_user','$id_yard','$no_booking_new', '$cur_counter','$status')";
			$q_cont_batal = "insert into container_batal_muat(no_container, no_request, status, start_pnkn, end_pnkn, no_req_batal, ex_kapal) 
			values ('$no_cont','$no_req_bm','$status',TO_DATE('$start_pnkn','dd-mm-rrrr'),TO_DATE('$end_pnkn','dd-mm-rrrr'), '$exno_req', '$no_booking_old')";
			$db->query($q_history_c);
			$db->query($q_cont_batal);
			$db->query($q_disable_cont);
			
		}
	}
	//$db->endTransaction();
}
	
if($jenis_bm == 'alih_kapal'){ //batal muat alih kapal
	
	$q_pkk = $db->query("SELECT NO_UKK FROM V_PKK_CONT WHERE NO_BOOKING='$no_booking_new'");
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
 WHERE TM.NO_BOOKING = '$no_booking_new'";
		$db->query($q_insert);
		
	}
	
	for($i=0;$i<count($_POST['BM_CONT']);$i++){
		$no_cont = $_POST['BM_CONT'][$i];
		$q_update_m = "update master_container set no_booking = '$no_booking_new' where no_container = '$no_cont'";
		$db->query($q_update_m);
	}
	if($status_gate == 2){
		//auto repo muat
		
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
		
		$no_req_del	= "DEL".$month.$year.$jum;
		$IDKDPMB_N 	= 'UD'.$month.$year.$jum;
		//header
		$query_upd = "UPDATE request_batal_muat SET NO_REQ_BARU = '$no_req_del' where NO_REQUEST = '$no_req_bm'";
		$db->query($query_upd);
		
		if ($biaya == 'Y') 
		{$bayarict = '5';}else{$bayarict = '0';}
		
		$q_hh_del   = "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, TGL_REQUEST, CETAK_KARTU, ID_USER, DELIVERY_KE, PERALIHAN, ID_YARD, STATUS,PEB, NPE, KD_EMKL, KD_EMKL2, VESSEL, TGL_BERANGKAT, KD_PELABUHAN_ASAL, KD_PELABUHAN_TUJUAN, NO_BOOKING, NO_REQ_ICT, TGL_MUAT, TGL_STACKING, NO_RO) 
						  VALUES ('$no_req_del','$no_req_del',SYSDATE, '0', $id_user, 'TPK','NOTA_KIRIM','$id_yard','AUTO_REPO','$PEB','$NPE','$kd_pbm', '$kd_pbm', '$kd_kapal',TO_DATE('$etd','dd-mm-rrrr'),'$kd_pelabuhan_asal','$kd_pelabuhan_tujuan', '$no_booking_new','$IDKDPMB_N', to_date('$TGL_MUAT','dd-mm-rrrr'), to_date('$TGL_STACKING','dd-mm-rrrr'), '')";
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
									'$IDKDPMB_N',
									'05',
									'$NO_UKK',
									to_date('$TGL_MUAT','DD/MM/YYYY'),
									to_date('$TGL_STACKING','DD/MM/YYYY'),
									SYSDATE,
									'$kd_pelabuhan_tujuan',
									'$kd_pbm',
									'BATAL MUAT USTER',
									'$bayarict',
									'0',
									'$PEB',
									'$IDKDPMB_N',
									'USTER',
									'$NPE',								
									'$no_booking_new',
									'',
									'$kd_pelabuhan_asal',
									'$kd_pbm'
					)";
					
		if($db2->query($sqlhead)){
			$db->query($q_hh_del);
		}
		
		//detail
		for($i=0;$i<count($_POST['BM_CONT']);$i++){
			$no_cont = $_POST['BM_CONT'][$i];
			$start_pnkn = $_POST['AWAL'][$i];			
			$end_pnkn = $_POST['AKHIR'][$i];
			$exno_req = $_POST['BMNO_REQ'][$i];	

			$res_bm = $db->query("SELECT * FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$exno_req'");
			$row_bm = $res_bm->fetchRow();
			$commodity = $row_bm["KOMODITI"];
			$status = $row_bm["STATUS"];
			$via = $row_bm["VIA"];
			$asal_cont = $row_bm["ASAL_CONT"];
			$tgl_delivery = $row_bm["TGL_DELIVERY"];
			$berat = $row_bm["BERAT"];
			
			
			$res_bm_ = $db->query("SELECT * FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$exno_req'");
			$row_bm_ = $res_bm_->fetchRow();
			$jn_repo = $row_bm_["JN_REPO"];
			$no_req_ict = $row_bm_['NO_REQ_ICT'];
			 
			$db->query("UPDATE REQUEST_DELIVERY SET JN_REPO = '$jn_repo' WHERE NO_REQUEST = '$no_req_del'");

			$query_insert   = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR, NOREQ_PERALIHAN, HZ, KOMODITI, VIA, ID_YARD, START_STACK, TGL_DELIVERY, ASAL_CONT, BERAT) 
			VALUES('$no_cont', '$no_req_del', '$status','Y','N','$exno_req','N','$commodity','$via','$id_yard', TO_DATE('$start_pnkn','DD/MM/YYYY'), TO_DATE('$end_pnkn','DD/MM/YYYY'), '$asal_cont', '$berat')";
			
			$query_pmb ="SELECT ptk.KD_PMB_DTL AS KD_PMB_DTL,NO_SEAL,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,HZ
				FROM PETIKEMAS_CABANG.TTD_CONT_EXBSPL ptk
				WHERE KD_PMB = '$no_req_ict' AND NO_CONTAINER = '$no_cont'";
			$result_pmb_dtl = $db2->query($query_pmb);
			$row_pmb_dtl = $result_pmb_dtl -> FetchRow();
			$kd_pmb_dtl = $row_pmb_dtl["KD_PMB_DTL"];
			$no_seal = $row_pmb_dtl["NO_SEAL"];
			$sizetpk = $row_pmb_dtl["KD_SIZE"];
			$typetpk = $row_pmb_dtl["KD_TYPE_CONT"];
			$jenistpk = $row_pmb_dtl["KD_JENIS_PEMILIK"];
			$komodityict = $row_pmb_dtl["KD_COMMODITY"];
			$beratict = $row_pmb_dtl["GROSS"];
			$hzict    = $row_pmb_dtl["HZ"];
			
			if($db->query($query_insert)){
				//insert ke history
				$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
				$r_getc = $db->query($q_getc);
				$rw_getc = $r_getc->fetchRow();
				$cur_c = $rw_getc["COUNTER"];
				$cur_booking = $rw_getc["NO_BOOKING"];
				$history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER, NO_REQUEST, KEGIATAN, 
														  TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER,STATUS_CONT) 
												  VALUES ('$no_cont','$no_req_del','REQUEST DELIVERY',
														  SYSDATE,'$id_user','$id_yard','$cur_booking','$cur_c','$status')";
															  
				$db->query($history);
			}
			
			if($typetpk=="07"){
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_RFR WHERE NO_BOOKING='$no_booking_new'";					
			}else{
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING WHERE NO_BOOKING='$no_booking_new'";
			}			
			if($jenistpk=='3'){
				$sqlm = "SELECT * FROM PETIKEMAS_CABANG.V_DATA_BOOKING_MTY WHERE NO_BOOKING='$no_booking_new'";
			}
					$rsm = $db2->query($sqlm);
					$rsbook  = $rsm->FetchRow();
					$blk_id = $rsbook["ARE_BLOK"];
					$sqlxx  = "SELECT ARE_ID,ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT FROM PETIKEMAS_CABANG.V_MST_YARD_EX WHERE KD_STATUS_CY='0' AND
					 KD_CABANG='05' AND ARE_BLOK='".$rsbook["ARE_BLOK"]."' AND  ARE_ROW BETWEEN '".$rsbook["M_ROW"]."' AND '".$rsbook["S_ROW"]."' AND 
					 ARE_SLOT BETWEEN '".$rsbook["M_SLOT"]."' AND '".$rsbook["S_SLOT"]."' AND  ARE_TIER BETWEEN '".$rsbook["M_TIER"]."' 
					 AND '".$rsbook["S_TIER"]."' ORDER BY ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
				
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

		
				$sqldet   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,NO_CONTAINER,
				KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,
				STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,STATUS_PP,ARE_ID,ARE_ID2,TGL_AWAL,TGL_AKHIR,KD_PMB2_LAMA,KD_PMB_DTL_LAMA) 
				VALUES
				(
				PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.NEXTVAL,
				'$IDKDPMB_N',						
				'".$no_cont."',
				'$sizetpk',
				'$typetpk',
				'$jenistpk',
				'".$komodityict."',
				'$beratict',
				'".$no_seal."',
				'BATAL MUAT USTER',
				'0U',
				'0',
				'USTER',
				'1',
				'$hzict',
				'T',
				'$yp',
				'$yp2',
				TO_DATE('$start_pnkn','dd-mm-rrrr'),
				TO_DATE('$end_pnkn','dd-mm-rrrr'),
				'$no_req_ict',
				'$kd_pmb_dtl'
				)";
				
				//echo $sqldet;exit;
				
				if(!$db2->query($sqldet))
				{
				if($jenistpk =='3')
				{
				$sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) 
				VALUES(
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
		}
	}
	else if($status_gate == 1 || $status_gate == 3){
		if($status_gate == 1){
			$aktif = 'T';
			$status_cont_st = 'FCL';
		}
		else {
			$aktif = 'Y';
			$status_cont_st = 'MTY';
		}
		//perlu auto request stuffing tapi tidak dikenakan biaya. untuk mengupdate tujuan kapal
		
		//Ambil data PERP_DARI dari request stuffing lama, untuk menetukan masa penumpukan
			/*ambil data ex kegiatan*/						
			$q_perp_dari = "select PERP_DARI, ID_PENUMPUKAN
							from request_stuffing where no_request = '$exno_req'";
			
		$result_perp_dari 	= $db->query($q_perp_dari);
		$row_perp_dari	 	= $result_perp_dari->fetchRow();
		$perp_dari				= $row_perp_dari["PERP_DARI"];
		$kd_id_tumpuk				= $row_perp_dari["ID_PENUMPUKAN"];

			
			$query_select	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_STUFFING
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
					   AND SUBSTR(request_stuffing.NO_REQUEST,0,3) = 'ASF'";
		
		$result_select 	= $db->query($query_select);
		$row_select 	= $result_select->fetchRow();
		$no_req			= $row_select["JUM"];
		$month			= $row_select["MONTH"];
		$year			= $row_select["YEAR"];
		//$no_req_s		= "APF".$month.$year.$no_req;
		$no_req_p		= "ASF".$month.$year.$no_req;
		$query_ir = "INSERT INTO REQUEST_STUFFING(NO_REQUEST, ID_YARD, CETAK_KARTU_SPPS, NO_BOOKING,
						TGL_REQUEST, ID_USER, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, NM_KAPAL, VOYAGE, STUFFING_DARI, NOTA, PERP_DARI,ID_PENUMPUKAN) 
						VALUES('$no_req_p',
						'$id_yard',
						0,
						'$no_booking_new',
						SYSDATE,				
						'$id_user',
						'$kd_pbm',
						'$kd_pbm',
						'$nm_kapal',
						'$voyage_in',
						'AUTO','Y','$perp_dari','$kd_id_tumpuk')";
		if($db->query($query_ir)){
			for($i=0;$i<count($_POST['BM_CONT']);$i++){
				$no_cont = $_POST['BM_CONT'][$i];
				$start_pnkn = $_POST['AWAL'][$i];			
				$end_pnkn = $_POST['AKHIR'][$i];
				$exno_req = $_POST['BMNO_REQ'][$i];
				$status	 = $_POST["KDSTATUS"][$i];
				$qwstuf = $db->query("select * from container_stuffing where no_container = '$no_cont' and no_request = '$exno_req'");
				$rwstuf = $qwstuf->fetchRow();
				//$aktif = $rwstuf["AKTIF"];
				$hz = $rwstuf["HZ"];
				$comm = $rwstuf["COMMODITY"];
				$type_st = $rwstuf["TYPE_STUFFING"];
				$start_stack = $rwstuf["START_STACK"];
				$asal = $rwstuf["ASAL_CONT"];
				$seal = $rwstuf["NO_SEAL"];
				$berat = $rwstuf["BERAT"];
				$keterangan = $rwstuf["KETERANGAN"];
				$tgl_app = $rwstuf["TGL_APPROVE"];
				$tgl_gate = $rwstuf["TGL_GATE"];
				$tgl_approve = $rwstuf["START_PERP_PNKN"];
				$kd_comm = $rwstuf["KD_COMMODITY"];
				
				/*$query_ic	= "INSERT INTO CONTAINER_STUFFING (	NO_CONTAINER, NO_REQUEST, 
									AKTIF, HZ, COMMODITY, TYPE_STUFFING, 
									START_STACK,
									ASAL_CONT, NO_SEAL, BERAT, KETERANGAN, 
									TGL_APPROVE, 
									TGL_GATE,
									START_PERP_PNKN,
									KD_COMMODITY)
							VALUES(	'$no_cont',
									'$no_req_p',
									'$aktif',
									'$hz',
									'$comm',
									'$type_st',
									to_date('".$start_stack."','dd-mm-rrrr'),
									'$asal',
									'$seal',
									'$berat',
									'$keterangan',
									TO_DATE('$tgl_app','dd-mm-rrrr'),
									TO_DATE('$tgl_gate','dd-mm-rrrr'),
									TO_DATE('$tgl_approve','dd-mm-rrrr'),
									'$kd_comm')";*/

				$query_ic = "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER,
										NO_REQUEST,
										AKTIF,
										HZ,
										COMMODITY,
										KD_COMMODITY,
										TYPE_STUFFING,
										START_STACK,
										ASAL_CONT,
										NO_SEAL,
										BERAT,
										KETERANGAN,
										STATUS_REQ,									
										TGL_APPROVE,
										TGL_GATE,									
										START_PERP_PNKN,
										END_STACK_PNKN,
										TGL_MULAI_FULL,
										TGL_SELESAI_FULL,
										TGL_REALISASI,
										ID_USER_REALISASI)
								   SELECT NO_CONTAINER,
										  '$no_req_p',
										  '$aktif',
										  HZ,
										  COMMODITY,
										  KD_COMMODITY,
										  TYPE_STUFFING,
										START_STACK,
										ASAL_CONT,
										NO_SEAL,
										BERAT,
										KETERANGAN,
										'PERP',									
										TGL_APPROVE,
										TGL_GATE,									
										START_PERP_PNKN,
										END_STACK_PNKN,
										TGL_MULAI_FULL,
										TGL_SELESAI_FULL,
										TGL_REALISASI,
										ID_USER_REALISASI
									 FROM CONTAINER_STUFFING
									WHERE NO_CONTAINER = '$no_cont'
										  AND NO_REQUEST = '$exno_req'";
				$db->query($query_ic);

				$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
				$r_getc = $db->query($q_getc);
				$rw_getc = $r_getc->fetchRow();
				$cur_c = $rw_getc["COUNTER"];
				$cur_booking = $rw_getc["NO_BOOKING"];

				$history_st = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER, NO_REQUEST, KEGIATAN, 
														  TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER,STATUS_CONT) 
												  VALUES ('$no_cont','$no_req_p','REQUEST STUFFING',
														  SYSDATE,'$id_user','$id_yard','$cur_booking','$cur_c','$status')";
															  
				$db->query($history_st);
			}
			
			$query_upd = "UPDATE request_batal_muat SET NO_REQ_BARU = '$no_req_p' where NO_REQUEST = '$no_req_bm'";
			
			$db->query($query_upd);
		}
	}
}
else if($jenis_bm == 'delivery'){ //batal muat alih delivery/sp2
	for($i=0;$i<count($_POST['BM_CONT']);$i++){
		if($status_gate == 1 || $status_gate == 3){
			$no_cont = $_POST['BM_CONT'][$i];
			$exno_req = $_POST['BMNO_REQ'][$i];	
			$update_batal_delivery = "UPDATE container_stuffing SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$exno_req' AND NO_CONTAINER='$no_cont'";
			$db->query($update_batal_delivery);
		}
	}
	
}

header('Location: '.HOME.APPID.'/view?no_req='.$no_req_bm);

?>