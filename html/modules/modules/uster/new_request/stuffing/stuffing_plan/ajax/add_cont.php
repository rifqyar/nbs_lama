<?php

// --ADD/TAMBAH CONTAINER YG DI STUFFING
// --Model Dokumentasi
// -- Daftar Isi
// -Terdapat 2 mode pada modul ini,
// 1 - Pertama container yang di stuffing berasal dari TPK, langkah2
	// 1.1 - Update status di TPK
			// 1.1.2 - Query semua data container TPK
	
	// 1.2 - Insert Ke container_receiving 
			// 1.2.1 - Tambahkan data container receiving ke history container
	
// 2 - Kedua, container yang distuffing berasal dari luar
 
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
$tgl_stack_awal	= $_POST["TGL_STACK"];
$status			= $_POST["STATUS"];
$bp_id			= $_POST["BP_ID"];
$sp2			= $_POST["SP2"];
$tgl_stack	= $_POST["TGL_BONGKAR"];	
$no_sppb		= $_POST["NO_SPPB"];
$tgl_sppb		= $_POST["TGL_SPPB"];
$blok_tpk		= $_POST["BLOK"];
$slot_tpk		= $_POST["SLOT"];
$row_tpk		= $_POST["ROW"];
$tier_tpk		= $_POST["TIER"];
$lokasi_		= $blok_tpk."/".$row_tpk."-".$slot_tpk."-".$tier_tpk;
$asal_cont_stuf	= $_POST["ASAL_CONT"];
$tgl_empty 		= $_POST["TGL_EMPTY"];
$early_stuff	= $_POST["EARLY_STUFF"];
$remark_sp2		= $_POST["REMARK_SP2"];
$no_req_sp2		= $_POST["NO_REQ_SP2"];
// $tgl_early_stuff = $_POST["TGL_EARLY_STUFF"];

//echo $size; die;
// debug($asal_cont_stuf);die;

/*  if($size == '1'){
	$size = '20';
}
else if($size == '2') {
	$size = '40'; 
}  */

$query_cek2 = "SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$result_cek2		= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$no_cont_cek		= $row_cek2["NO_CONTAINER"];

if($no_cont_cek != NULL){
	echo "EXIST_DEL";
	exit();
} 

$cek_gati = "SELECT AKTIF FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$r_gati = $db->query($cek_gati);
$rw_gati = $r_gati->fetchRow();
$aktif_rec = $rw_gati['AKTIF'];
if($aktif_rec == 'Y'){
	echo 'EXIST_REC';
	exit();
}

if($asal_cont_stuf == 'DEPO'){
	$cek_loc = "SELECT LOCATION FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
	$r_loc = $db->query($cek_loc);
	$rw_loc = $r_loc->fetchRow();
	$aktif_loc = $rw_loc['LOCATION'];
	if($aktif_loc != 'IN_YARD'){
		echo 'BELUM_REC';
		exit(); 
	}
}

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
		
		
			if($asal_cont_stuf == 'DEPO' && $remark_sp2 != "Y")
			{
				
				
				$query_tgl_stack_depo = "SELECT TGL_UPDATE , NO_REQUEST, KEGIATAN
                                            FROM HISTORY_CONTAINER 
                                            WHERE no_container = '$no_cont' 
                                            AND kegiatan IN ('GATE IN','REALISASI STRIPPING')
                                            ORDER BY TGL_UPDATE DESC";
				
				$tgl_stack_depo	= $db->query($query_tgl_stack_depo);
				$row_tgl_stack_depo		= $tgl_stack_depo->fetchRow();
				//$tgl_stack	= $row_tgl_stack_depo["TGL_STACK"];	
				$ex_keg	= $row_tgl_stack_depo["KEGIATAN"];	
				$no_re_st	= $row_tgl_stack_depo["NO_REQUEST"];	
				if($ex_keg == "REALISASI STRIPPING"){
					$qtgl_r = $db->query("SELECT TGL_REALISASI FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_re_st'");
					$rtgl_r = $qtgl_r->fetchRow();
					$tgl_stack = $rtgl_r["TGL_REALISASI"];
				} else if($ex_keg == "GATE IN"){
					$qtgl_r = $db->query("SELECT TGL_IN FROM GATE_IN WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_re_st'");
					$rtgl_r = $qtgl_r->fetchRow();
					$tgl_stack = $rtgl_r["TGL_IN"];
				}
			}
			
			// if($early_stuff == 'Y')
			// {

			if ($asal_cont_stuf == 'TPK') {
					$cek_bongkar = "select   CASE SIKLUS WHEN 'BONGKAR'
						            THEN 'TPK'
						            ELSE
						                'DEPO'
						            END AS ASAL_CONT,
						            CASE SIKLUS WHEN 'BONGKAR'
						            THEN 'GATO'
						            ELSE
						                'IN_YARD'
						            END AS LOCATION, 
						            SIKLUS
						            from V_MTY_AREA_TPK_NEW
						            WHERE NO_CONTAINER = '$no_cont'";
					$rw_bongkar = $db->query($cek_bongkar);
					$rbg = $rw_bongkar->fetchRow();
					$asal_cont_stuf = $rbg["ASAL_CONT"]; 
					$new_location = $rbg["LOCATION"];
					$siklus = $rbg["SIKLUS"];
			}

				if($remark_sp2 == "Y"){
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
															   TGL_APPROVE,
															   TGL_MULAI,
															   KD_COMMODITY,
															   NO_REQ_SP2
															   ) 
														VALUES('$no_cont', 
															   '$no_req_stuf',
															   'Y',
															   '$hz',
															   '$commodity',
															   '$type_stuffing',
															   TO_DATE('$tgl_stack','dd-mm-rrrr')+1,
															   '$asal_cont_stuf',
															   '$no_seal',
															   '$berat',
															   '$keterangan',
															   '$depo_tujuan',
															   TO_DATE('$tgl_empty','dd-mm-rrrr'),
															   TO_DATE('$tgl_stack_awal','dd-mm-rrrr'),
															   '$kd_commodity',
															   '$no_req_sp2'
															   )";
				} else {
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
															   TGL_APPROVE,
															   KD_COMMODITY,
															   LOKASI_TPK
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
															   TO_DATE('$tgl_empty','dd/mm/rrrr'),
															   '$kd_commodity',
															   '$lokasi_'
															   )";
				}
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
																'$new_location', '$no_booking', 1)
									";						
			$db->query($query_insert_mstr);
			}
			else{
		
				
				$q_getcounter2 = "SELECT NO_BOOKING, COUNTER, MLO FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
				$r_getcounter2 = $db->query($q_getcounter2);
				$rw_getcounter2 = $r_getcounter2->fetchRow();
				$last_counter = $rw_getcounter2["COUNTER"]+1;
				$cek_mlo =$rw_getcounter2["MLO"]; 
				//$cur_booking2 = $rw_getcounter2["NO_BOOKING"]; 
				if($asal_cont_stuf == 'TPK' && $cek_mlo == 'MLO' && $last_counter == 2){
					$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'";
					$db->query($q_update_book2);
				}
				if ($asal_cont_stuf == 'TPK'){
					$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', COUNTER = '$last_counter', LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'";
					$db->query($q_update_book2);
				} 
				else{
					if ($new_location != NULL) {
						$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', LOCATION = '$new_location' , COUNTER = '$last_counter' WHERE NO_CONTAINER = '$no_cont'";
					}
					else{
						$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', COUNTER = '$last_counter' WHERE NO_CONTAINER = '$no_cont'";
					}
					
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