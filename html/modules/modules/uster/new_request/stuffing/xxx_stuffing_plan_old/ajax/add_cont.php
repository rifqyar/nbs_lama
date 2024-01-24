<?php

$db 			= getDB("storage");

$nm_user		= $_SESSION["NAME"];
$id_user		= $_SESSION["LOGGED_STORAGE"];
$id_yard		= $_SESSION["IDYARD_STORAGE"];
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
$tgl_empty 		= $_POST["TGL_EMPTY"];
$early_stuff	= $_POST["EARLY_STUFF"];
// $tgl_early_stuff = $_POST["TGL_EARLY_STUFF"];


// debug($asal_cont_stuf);die;


if($no_booking == NULL){
	$no_booking = "VESSEL_NOTHING";
}
//Cek status container, apakah masih aktif di table planning(container sedang direquest)
$q_cek_cont = "SELECT NO_CONTAINER FROM PLAN_CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$r_cek_plan = $db->query($q_cek_cont);
$row_cek_plan = $r_cek_plan->fetchRow();

if($row_cek_plan["NO_CONTAINER"] == NULL){
			
     	$query_cek_aktif	= "SELECT NO_CONTAINER FROM PLAN_CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
		$result_cek_aktif   = $db->query($query_cek_aktif);
		$row_cek_aktif		= $result_cek_aktif->fetchRow();
		$cek_aktif			= $row_cek_aktif["NO_CONTAINER"];

		if ($hz == NULL){
			echo "BERBAHAYA";
		} else if ($cek_aktif <> NULL)
		{
			echo "EXIST";
		} else {
		
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
				$query_cek1		= " SELECT SUBSTR(TO_CHAR(b.TGL_IN+5,'dd/mm/rrrr'),1,10) START_STACK FROM GATE_IN b WHERE b.NO_CONTAINER = '$no_cont' AND b.NO_REQUEST = '$no_request'";
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
				$query_cek1		= "SELECT SUBSTR(TO_CHAR(TGL_REALISASI,'dd/mm/rrrr'),1,10) START_STACK FROM container_stripping WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request'";
				$result_cek1	= $db->query($query_cek1);
				$row_cek1		= $result_cek1->fetchRow();
				$start_stack	= $row_cek1["START_STACK"];
				$asal_cont 		= 'DEPO';
		}
		
		//echo $start_stack;exit;
		//TO_DATE('$TGL_BERANGKAT','dd/mon/yy'),
	
		//JIka 	asal cont dari depo, tgl stack dimulai dari get in
		
		
			if($asal_cont_stuf == 'DEPO')
			{
				
				
				$query_tgl_stack_depo = "SELECT MAX(TGL_UPDATE) TGL_STACK , NO_REQUEST
											FROM HISTORY_CONTAINER 
											WHERE no_container = '$no_cont' 
											AND kegiatan IN ('GATE IN','REALISASI STRIPPING')
											GROUP BY NO_REQUEST";
				
				$tgl_stack_depo	= $db->query($query_tgl_stack_depo);
				$row_tgl_stack_depo		= $tgl_stack_depo->fetchRow();
				$tgl_stack	= $row_tgl_stack_depo["TGL_STACK"];	
			}
			
			// if($early_stuff == 'Y')
			// {
				$query_insert_stuff	= "INSERT INTO PLAN_CONTAINER_STUFFING(NO_CONTAINER, 
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
															   DEPO_TUJUAN,
															   TGL_APPROVE
																  ) 
														VALUES('$no_cont', 
															   '$no_req_stuf',
															   'Y',
															   '$hz',
															   '$commodity',
															   '$type_stuffing',
															   TO_DATE('$tgl_stack','dd/mm/rrrr'),
															   '$asal_cont_stuf',
															   '$no_seal',
															   '$berat',
															   '$keterangan',
															   '$depo_tujuan',
															   sysdate
															   )";
			// }
			// else if($early_stuff == 'N')
			// {
				
			//}
				
			
			
			$db->query($query_insert_stuff);	
			
			$query_cek_mascont	= "SELECT NO_CONTAINER 
										FROM  MASTER_CONTAINER
										WHERE NO_CONTAINER ='$no_cont'
									";
			$result_cek_mascont = $db->query($query_cek_mascont);
			$row_cek_mascont	 = $result_cek_mascont->fetchRow();
			$cek_mascont		 = $row_cek_mascont["NO_CONTAINER"];
			
			if ($cek_mascont == NULL){
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
		
				if ($asal_cont_stuf = 'TPK'){
				$q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
				$r_getcounter2 = $db->query($q_getcounter2);
				$rw_getcounter2 = $r_getcounter2->fetchRow();
				$last_counter = $rw_getcounter2["COUNTER"]+1;
				//$cur_booking2 = $rw_getcounter2["NO_BOOKING"];
				$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', COUNTER = '$last_counter' WHERE NO_CONTAINER = '$no_cont'";
				$db->query($q_update_book2);
				} 
			}
		
			$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
			$r_getcounter1 = $db->query($q_getcounter1);
			$rw_getcounter1 = $r_getcounter1->fetchRow();
			$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
			$cur_counter1  = $rw_getcounter1["COUNTER"];
			
			$history_del        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD,STATUS_CONT, NO_BOOKING, COUNTER) 
									VALUES ('$no_cont','$no_req_stuf','PLAN REQUEST STUFFING',SYSDATE,'$id_user', '$id_yard','MTY', '$cur_booking1', '$cur_counter1')";
									
			$db->query($history_del);											
			echo "OK";
	
	}
}
else{
	   echo "SUDAH_REQUEST";
	// echo "Maaf no container ".$no_cont." telah mengajukan request stuffing";
}

?>