<?php
require_lib('praya.php');
$db =  getDB("storage");
//$db2 =  getDB("ora");
$jenis_bm 				= $_POST["jenis_batal"];	//echo $jenis_bm.'<br/>';
$status_gate 			= $_POST["status_gate"];	//echo $status_gate.'<br/>';
$biaya 					= $_POST["biaya"];			//echo $biaya.'<br/>';
$kd_pbm 				= $_POST["KD_PELANGGAN"];	//echo $kd_pbm.'<br/>';
$kd_kapal 				= $_POST["KD_KAPAL"];		//echo $kd_kapal.'<br/>';
$nm_agen 				= $_POST["NM_AGEN"];		//echo $kd_kapal.'<br/>';
$account_pbm			= $_POST["NO_ACCOUNT_PBM"];		//echo $kd_kapal.'<br/>';
$kd_pelabuhan_asal	 	= $_POST["KD_PELABUHAN_ASAL"];	//echo $kd_pelabuhan_asal.'<br/>';
$kd_pelabuhan_tujuan 	= $_POST["KD_PELABUHAN_TUJUAN"];		//echo $kd_pelabuhan_tujuan.'<br/>';
//$no_request			 	= $_POST["NO_REQUEST"];			//echo $no_request.'<br/>';
$no_booking_new		 	= $_POST["NO_BOOKING"];		//echo $no_booking_new.'<br/>';
$no_req_ict			 	= $_POST["NO_REQ_ICT"];		//echo $no_req_ict.'<br/>';
$id_user				= $_SESSION["LOGGED_STORAGE"];	//echo $id_user.'<br/>';
$id_yard				= $_SESSION["IDYARD_STORAGE"];	//echo $id_yard.'<br/>';
$etd					= $_POST["ETD"];
$openstack				= $_POST["TGL_STACKING"];
$no_ukk_new				= $_POST["NO_UKK"];
$TGL_MUAT				= $_POST["TGL_MUAT"];
$TGL_STACKING			= $_POST["TGL_STACKING"];
$nm_kapal				= $_POST["NM_KAPAL"];
$voyage_in				= $_POST["VOYAGE_IN"];
//$status					= $_POST["KDSTATUS"];
$type					= $_POST["KDTYPE"];
$oi 					= $_POST["OI"];
$NPE 					= $_POST["NPE"];
$PEB 					= $_POST["PEB"];
$voyage_out				= $_POST["VOYAGE_OUT"];
$eta 					= $_POST["ETA"];
$kd_agen 				= $_POST["KD_AGEN"];
$voyage 				= $_POST["VOYAGE"];
$call_sign 				= $_POST["CALL_SIGN"];

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

// SEND TO PRAYA BEGIN
// if($status_gate == '2' && $jenis_bm == 'alih_kapal' && $biaya == 'T'){
// 	$ex_noreq = $_POST['NO_REQUEST'];
// 	$payload_batal_muat = array(
// 		"ex_noreq" => $ex_noreq,
// 		"vesselId" => $kd_kapal,
// 		"vesselName" => $nm_kapal,
// 		"voyage" => $voyage,
// 		"voyageIn" => $voyage_in,
// 		"voyageOut" => $voyage_out,
// 		"nm_agen" => $nm_agen,
// 		"kd_agen" => $kd_agen,
// 		"pelabuhan_tujuan" => $kd_pelabuhan_tujuan,
// 		"pelabuhan_asal" => $kd_pelabuhan_asal,
// 		"cont_list" => $_POST['BM_CONT'],
// 	);
// 	$save_payment = save_payment_uster_batal_muat($no_req_bm, $payload_batal_muat);
// 	echo $save_payment;
// 	if($save_payment['code'] == 0 || $save_payment['status'] == 'error'){
// 		die();
// 	}
// }

// SEND TO PRAYA END

if($no_booking_new == NULL){
	$no_booking_new = "VESSEL_NOTHING";
}

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
			values ('$no_cont','$no_req_bm','$status',TO_DATE('$start_pnkn','dd/mm/rrrr'),TO_DATE('".date('d-m-Y',strtotime($end_pnkn))."','dd-mm-rrrr'), '$exno_req', '$no_booking_old' )";

			if($db->query($q_cont_batal)){ /*insert container batal muat*/
				$db->query($q_history_c);				
				//$db->query($q_disable_cont);
				//$db->query($query_update_plan);
			}			
			
		}	
	}
	//$db->endTransaction();
} else if($status_gate == 2) { //batal muat ex repo
	//batal muat delivery 
	if($no_booking_new == "VESSEL_NOTHING"){
		$ex_noreq = $_POST['NO_REQUEST'];

		$q_get_nobooking = "SELECT NO_BOOKING FROM REQUEST_DELIVERY rd WHERE NO_REQUEST = '$ex_noreq'";
		$r_get_nobooking = $db->query($q_get_nobooking);
		$rw_get_nobooking = $r_get_nobooking->fetchRow();

		$no_booking_new = $rw_get_nobooking["NO_BOOKING"];
	}

	/*insert to table request batal muat*/
	$q_batal = "insert into request_batal_muat(no_request,tgl_request,kd_emkl,biaya,jenis_bm,kapal_tuju,status_gate,o_idvsb,o_vessel,
    o_voyin,o_voyout,di)
				values('$no_req_bm',SYSDATE,'$kd_pbm','$biaya','$jenis_bm','$no_booking_new','$status_gate','$no_ukk_new','$nm_kapal',
                '$voyage_in','$voyage_in','$oi')";
	//$db->startTransaction();
	if($db->query($q_batal)){
		for($i=0;$i<count($_POST['BM_CONT']);$i++){
			$no_cont = $_POST['BM_CONT'][$i];
			$start_pnkn = $_POST['AWAL'][$i];
			$end_pnkn = $_POST['AKHIR'][$i];
			$oldvsb = $_POST['UKKLAMA'][$i];
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
			
			$q_cont_batal = "insert into container_batal_muat(no_container, no_request, status, start_pnkn, end_pnkn, no_req_batal, ex_kapal,ex_vsb) 
			values ('$no_cont','$no_req_bm','$status',TO_DATE('$start_pnkn','dd-mm-rrrr'),TO_DATE('$end_pnkn','DD-MM-YYYY HH24:MI:SS'), '$exno_req', '$no_booking_old','$oldvsb' )";

			$update_batal_delivery= "UPDATE CONTAINER_DELIVERY SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$exno_req' AND NO_CONTAINER='$no_cont'";

			$db->query($q_history_c);
			$db->query($q_cont_batal);
			$db->query($update_batal_delivery);
			
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
			values ('$no_cont','$no_req_bm','$status',TO_DATE('$start_pnkn','dd-mm-rrrr'),TO_DATE('$end_pnkn','DD-MM-YYYY HH24:MI:SS'), '$exno_req', '$no_booking_old')";
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
                        NO_BOOKING,
                        VOYAGE,
                        CALL_SIGN)
                    VALUES ('$kd_kapal',
                            '$nm_kapal',
                            '$voyage_in',
                            '$voyage_out',
                            TO_DATE('$eta','DD-MM-YYYY HH24:MI:SS'),
                            TO_DATE('$etd','DD-MM-YYYY HH24:MI:SS'),
                            '$no_ukk_new',
                            '$nm_agen',
                            '$kd_agen',
                            '$kd_pelabuhan_asal',
                            '$kd_pelabuhan_tujuan',
                            '05',
                            '$no_booking_new',
                        	'$voyage',
                        	'$call_sign')";
		$db->query($q_insert);
		
	}
	
	for($i=0;$i<count($_POST['BM_CONT']);$i++){
		$no_cont = $_POST['BM_CONT'][$i];
		$q_update_m = "update master_container set no_booking = '$no_booking_new' where no_container = '$no_cont'";
		$db->query($q_update_m);
	}
	if($status_gate == 2){
		//auto repo muat
		/*
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
		//header
		$q_hh_del   = "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, TGL_REQUEST, CETAK_KARTU, ID_USER, DELIVERY_KE, PERALIHAN, ID_YARD, STATUS,PEB, NPE, KD_EMKL, KD_EMKL2, VESSEL, TGL_BERANGKAT, KD_PELABUHAN_ASAL, KD_PELABUHAN_TUJUAN, NO_BOOKING, TGL_MUAT, TGL_STACKING,
                        o_idvsb,o_vessel,o_voyin,o_voyout,di,VOYAGE) 
						  VALUES ('$no_req_del','$no_req_del',SYSDATE, '0', $id_user, 'TPK','NOTA_KIRIM','$id_yard','AUTO_REPO','$PEB','$NPE','$kd_pbm', '$kd_pbm', '$nm_kapal',TO_DATE('$etd','DD-MM-YYYY HH24:MI:SS'),'$kd_pelabuhan_asal','$kd_pelabuhan_tujuan', '$no_booking_new', to_date('$TGL_MUAT','DD-MM-YYYY HH24:MI:SS'), to_date('$TGL_STACKING','DD-MM-YYYY HH24:MI:SS'),
                          '$no_ukk_new','$nm_kapal','$voyage_in','$voyage_in','$oi','$voyage')";

        if($db->query($q_hh_del)){
            $query_upd = "UPDATE request_batal_muat SET NO_REQ_BARU = '$no_req_del' where NO_REQUEST = '$no_req_bm'";
            $db->query($query_upd);
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
			VALUES('$no_cont', '$no_req_del', '$status','Y','N','$exno_req','N','$commodity','$via','$id_yard', TO_DATE('$start_pnkn','DD/MM/YYYY'), TO_DATE('$end_pnkn','DD-MM-YYYY HH24:MI:SS'), '$asal_cont', '$berat')";

			
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
		} 
		*/
        $paramif = array("in_reqbm"=>$no_req_bm,
                         "in_accpbm"=>$account_pbm,
                         "in_pbm"=>$kd_pbm,
                         "in_vessel"=>$nm_kapal,
                         "in_voyin"=>$voyage_in,
                         "in_voyout"=>$voyage_in,
                         "in_user"=>$id_user,
                         "in_shipping"=>$nm_agen,
                         "in_tglberangkat"=>$etd,
                         "in_openstack"=>$openstack,
                         "in_custnum"=>'',
                         "in_npe"=>$NPE,
                         "in_peb"=>$PEB,
                         "in_booknum"=>'',
                         "in_fpod"=>'',
                         "in_idfpod"=>'',
                         "in_di"=>$oi,
                         "in_accpbm_pnkn"=>'',
                         "in_idvsbnew"=>$no_ukk_new,
                         "in_vessel_code"=>$kd_kapal,
                         "out_noreq"=>'',
                         "out_msg"=>'',
                        );
        //print_r($paramif); die();
        $queryif = "declare begin pack_create_bamu_exrepo.create_bamu_praya(:in_reqbm,:in_accpbm,:in_pbm,:in_vessel,:in_voyin,:in_voyout,:in_user,
        	:in_shipping,:in_tglberangkat,:in_openstack,:in_custnum,:in_npe,:in_peb,:in_booknum,:in_fpod,:in_idfpod,:in_di,:in_accpbm_pnkn,
        	:in_idvsbnew,:in_vessel_code,:out_noreq,:out_msg); end;";
//        echo "<pre>";
//        echo var_dump($paramif);
//        echo var_dump($db->query($queryif,$paramif));
//        die();
		$db->query($queryif,$paramif);
        //print_r($paramif); die();
        $out_noreq = $paramif["out_noreq"];
        $out_msg   = $paramif["out_msg"];
        if($biaya == 'T'){
            $db2=getDb('dbint');
            $param_payment2= array(
                                 "ID_NOTA"=>'BMtanpabiaya',
                                 "ID_REQ"=>$out_noreq,
                                 "OUT"=>'',
                                 "OUT_MSG"=>''
                                );
            $query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";

            $db2->query($query2,$param_payment2);
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

			$get_disable_container = getDisableContainer($no_cont, $jenis_bm);

            if ($get_disable_container['code'] == '1' && !empty($get_disable_container['dataRec'])) {
                disableContainerSave($get_disable_container, $jenis_bm);

                $update_batal_delivery = "UPDATE container_stuffing SET STATUS_REQ='BATAL', AKTIF='T' WHERE NO_REQUEST ='$exno_req' AND NO_CONTAINER='$no_cont'";
				$db->query($update_batal_delivery);
            }	
		}
	}
}

header('Location: '.HOME.APPID.'/view?no_req='.$no_req_bm);

function save_payment_uster_batal_muat($new_id_request, $payload_batal_muat){
	set_time_limit(360);

	try {

			$curl = curl_init();
			/* set configure curl */
			// $authorization = "Authorization: Bearer $token";
			$payload_request = array(
					"ID_REQUEST" => $new_id_request,
					"JENIS" => "BATAL_MUAT",
					"BANK_ACCOUNT_NUMBER" => "",
					"PAYMENT_CODE" => "",
					"PAYLOAD_BATAL_MUAT" => $payload_batal_muat
			);
			// echo json_encode($payload_request) . '<<payload_req';
			$url = HOME."uster.billing.paymentcash.ajax/save_payment_external";
			// echo var_dump($url);
			// die();
			curl_setopt_array(
			$curl,
			array(
					CURLOPT_URL             => $url,
					CURLOPT_RETURNTRANSFER  => true,
					CURLOPT_ENCODING        => "",
					CURLOPT_MAXREDIRS       => 10,
					CURLOPT_CUSTOMREQUEST   => "POST",
					CURLOPT_POSTFIELDS      => json_encode($payload_request),
					CURLOPT_HTTPHEADER      => array(
					"Content-Type: application/json"
					),
			)
			);


			$response = curl_exec($curl);
			// $err = curl_error($curl);
			echo var_dump($response);

			if ($response === false) {
					throw new Exception(curl_error($curl));
			}

			// Get HTTP status code
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			//Success
			if ($httpCode >= 200 && $httpCode < 300) {
					$response_curl = array(
							'status'   => 'success',
							'httpCode' => $httpCode,
							'response' => $response
					);
			} else if ($httpCode >= 400 && $httpCode < 500) {
					//Client Error
					$response_curl = array(
							'status'   => 'error',
							'httpCode' => $httpCode,
							'response' => $response
					);
			} else {
					//Server Error
					throw new Exception('HTTP Server Error: ' . $httpCode);
			}

			/* execute curl */
			curl_close($curl);

			return $response_curl;

	} catch (Exception $e) {
			// echo $e . "<< error-aftercurl";
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$response_curl = array(
					'status'   => 'error',
					'httpCode' => $httpCode,
					'response' => "cURL Error # " . $e->getMessage()
			);

			return $response_curl;
	}
}

?>