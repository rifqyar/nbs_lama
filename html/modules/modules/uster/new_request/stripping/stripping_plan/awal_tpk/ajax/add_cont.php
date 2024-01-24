<?php

// --TAMBAH CONTAINER REQUEST STRIPPING
// --Model Dokumentasi
// -- Daftar Isi

// [1] - Insert ke tabel PLAN_CONTAINER_STRIPPING
// [2] - Insert ke tabel MASTER_CONTAINER 
// [3] - Insert ke tabel HISTORY_CONTAINER
// [4] - Update SUB_COUNTER ke tabel HISTORY_CONTAINER	

$db 			= getDB("storage");

$nm_user	= $_SESSION["NAME"];
$id_user	= $_SESSION["LOGGED_STORAGE"];
$id_yard	= $_SESSION["IDYARD_STORAGE"];
$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_req2	= $_POST["NO_REQ2"]; 
$status		= $_POST["STATUS"]; 
$berbahaya	= $_POST["BERBAHAYA"];
$no_do		= $_POST["NO_DO"];
$no_bl		= $_POST["NO_BL"];
$size		= $_POST["SIZE"];
$type		= $_POST["TYPE"];
$voyage		= $_POST["VOYAGE"];
$vessel		= $_POST["VESSEL"];
$tgl_stack	= $_POST["TGL_STACK"];
$no_ukk		= $_POST["NO_UKK"];
$nm_agen	= $_POST["NM_AGEN"];
$komoditi	= $_POST["KOMODITI"];
$tgl_bongkar	= $_POST["TGL_BONGKAR"];
$bp_id			= $_POST["BP_ID"];
$kd_consignee	= $_POST["KD_CONSIGNEE"];
$depo_tujuan	= $_POST["DEPO_TUJUAN"];
$sp2			= $_POST["SP2"];
$after_strip	= $_POST["AFTER_STRIP"];
$asal_cont	= $_POST["ASAL_CONT"];
$no_booking	= $_POST["NO_BOOKING"];
$blok_	= $_POST["BLOK"];
$slot_	= $_POST["SLOT"];
$row_	= $_POST["ROW"];
$tier_	= $_POST["TIER"];
$lokasi = $blok_."/".$row_."-".$slot_."-".$tier_;
// debug($_POST);die;

$cek_gato = "SELECT AKTIF
                FROM CONTAINER_DELIVERY
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y' ORDER BY AKTIF DESC";
$d_gato = $db->query($cek_gato);
$r_gato = $d_gato->fetchRow();
$l_gato = $r_gato["AKTIF"];
if($l_gato == 'Y'){
	echo "EXIST_DEL";
	exit();
}

$cek_gati = "SELECT AKTIF
                FROM CONTAINER_RECEIVING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y' ORDER BY AKTIF DESC";
$d_gati = $db->query($cek_gati);
$r_gati = $d_gati->fetchRow();
$l_gati = $r_gati["AKTIF"];
if($l_gati == 'Y'){
	echo "EXIST_REC";
	exit();
}

$cek_stuf = "SELECT AKTIF
                FROM CONTAINER_STUFFING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$d_stuf = $db->query($cek_stuf);
$r_stuf = $d_stuf->fetchRow();
$l_stuf = $r_stuf["AKTIF"];
if($l_stuf == 'Y'){
	echo "EXIST_STUF";
	exit();
}

//added 26.04.14 06:44 pm - frenda
$cek_plan_strip = "SELECT AKTIF
                FROM CONTAINER_STRIPPING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$r_plan_strip = $db->query($cek_plan_strip);
$rwplan_strip = $r_plan_strip->fetchRow();
$l_strip = $rwplan_strip["AKTIF"];
if ($l_strip == 'Y') {
	echo "EXIST_STRIPPING";
	exit();
}

 
if($tgl_bongkar == NULL){
	echo 'TGL_BONGKAR';
	exit();
}
if($no_booking == NULL){
	$no_booking = "VESSEL_NOTHING";
}
$tgl_mulai		= $_POST["tgl_mulai"];
$tgl_selesai		= $_POST["tgl_selesai"];

$no_req_rec	= substr($no_req2,4);	
$no_req_rec	= "REC".$no_req_rec;

//HANYA YANG GATO YANG BISA STRIPPING
$flag = 1;
if($asal_cont != "DEPO" ) {
	$cek_locate = "SELECT LOCATION, MLO, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
	$r_locate = $db->query($cek_locate);
	$rw_locate = $r_locate->fetchRow();
	$count_hist = $db->query("SELECT COUNT(*) JUM FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont'");
	$r_counthi = $count_hist->fetchRow();
	if ($r_counthi["JUM"] > 1) {
		if ($rw_locate["LOCATION"] != "GATO") { 
			echo "EXIST_YARD";
			exit();
		}
	}
	$flag = 0;
}
//debug($_POST);
//die();
//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus di Lapangan dan sudah Gate In
//debug($_POST);
						
/*$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";			
				   
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];

*/
//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;

//if($location == "GATO")
//{
	//berarti GATI atau sudah placement
	//cek status aktif di CONTAINER_RECEIVING
	
	//asd
//---------------------------------------------------Interface Ke ICT----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------

	/*	$sql 			= "SELECT NO_REQ_DEL FROM PETIKEMAS_CABANG.TTM_DEL_REQ WHERE NO_REQ_DEL = '$no_req2'";
		$sqldb 			= $db->query($sql);
		$row_sql		= $sqldb->fetchRow();
		$no_req_del		= $row_sql["NO_REQ_DEL"];
		
		//echo $no_req_del;exit;
		
		//debug($no_req_del);die;
		if ($no_req_del != NULL)
		{
			$result_seq = "SELECT MAX(SEQ_DEL) as SEQ from PETIKEMAS_CABANG.TTD_DEL_REQ where  NO_REQ_DEL ='$no_req_del'";
			$rs 		= $db->query( $result_seq );
			$row		= $rs->FetchRow() ;			
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
		   '$berbahaya',
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
		   NO_SP2           ='$autono',
		   NO_REQ 		    ='$autobp',
		   NO_BL		    ='$no_bl',
		   NO_DO		    ='$no_do',
		   KD_PBM		    ='$kd_consignee',    
		   TUJUAN		    ='USTER'
		   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
		   //echo $sqlttd; //exit;
		   */
		//if($db->query($sqlttd))
		//{
				
//----------------------------------------------End Of Interface Ke ICT-------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------------------------------------------------------------------------				
	
		//Insert Uster
				/*$query_tgl_bongkar ="SELECT RTA AS TGL_BONGKAR
							FROM VOYAGE
							WHERE NO_BOOKING = '$no_booking'
							";
				$result_tgl_bongkar = $db->query($query_tgl_bongkar);
				$row_tgl_bongkar	= $result_tgl_bongkar->fetchRow();
				$tgl_bongkar_		= $row_tgl_bongkar["TGL_BONGKAR"];
			
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
														TO_DATE('$tgl_bongkar','dd-mm-yyyy'),
													   '$komoditi',
													   '$depo_tujuan')";
				//echo $query_insert;exit;			
				
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
																		LOCATION)
																 VALUES('$no_cont',
																		'$size',
																		'$type',
																		'GATO')
											";						
					$db->query($query_insert_mstr);
				}*/													   
		// ------------------------------------------------------------------------------
		
		//if($db->query($query_insert_rec))
		//{
			// Insert Plan Container Stripping ----------------------------------------------
			$q_cek_cont = "SELECT NO_CONTAINER 
							FROM PLAN_CONTAINER_STRIPPING 
							WHERE NO_CONTAINER = '$no_cont' 
							  AND NO_REQUEST = '$no_req'";
							  
			$r_cek_plan = $db->query($q_cek_cont);
			$row_cek_plan = $r_cek_plan->fetchRow();
			if($row_cek_plan["NO_CONTAINER"] == NULL){
			
			// [1] - Insert ke tabel PLAN_CONTAINER_STRIPPING
			
			$query_insert_strip	= "INSERT INTO PLAN_CONTAINER_STRIPPING(NO_CONTAINER, 
															   NO_REQUEST,
															   AKTIF,
															   TGL_BONGKAR,
															   HZ,
															   ID_YARD,
															   VOYAGE,
															   AFTER_STRIP,
															   COMMODITY,
															   UKURAN,
															   TYPE, ASAL_CONT, NO_BOOKING, TGL_MULAI, TGL_SELESAI, LOKASI_TPK
															  ) 
														VALUES('$no_cont', 
															   '$no_req',
															   'Y',
															   TO_DATE('$tgl_bongkar','dd-mm-yyyy'),
															   '$berbahaya',
															   '$depo_tujuan',
															   '$voyage',
															   '$after_strip',
															   '$komoditi',
															   '$size',
															   '$type',
															   '$asal_cont', 
															   '$no_booking',
															   TO_DATE('$tgl_mulai','dd-mm-yyyy'),
															   TO_DATE('$tgl_selesai','dd-mm-yyyy'),
															   '$lokasi'
															   )";
															   
			// echo $query_insert;
			// ----------------------------------------------------------------------------
			/* $q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
			$r_getcounter2 = $db->query($q_getcounter2);
			$rw_getcounter2 = $r_getcounter2->fetchRow();
			$cur_counter2 = $rw_getcounter2["COUNTER"];
			$cur_booking2 = $rw_getcounter2["NO_BOOKING"];	 */			
			$query_cek_cont			= "SELECT NO_CONTAINER 
										FROM  MASTER_CONTAINER
										WHERE NO_CONTAINER ='$no_cont'
										";
			$result_cek_cont = $db->query($query_cek_cont);
			$row_cek_cont	 = $result_cek_cont->fetchRow();
			$cek_cont		 = $row_cek_cont["NO_CONTAINER"];
			
			if($flag == 1){
				$loc_now = "IN_YARD";
			}
			else {
				$loc_now = "GATO";
			}
			
			if($cek_cont == NULL)
			{
			// [2] - Insert ke tabel MASTER_CONTAINER 
			// Dilakukan pengecekan dulu apakah container sudah pernah masuk atau belum
			// Bila belum, masukkan ke master, lalu tambahkan counter mulai dari nomor 1, bila sudah pernah, counter di-increment dari counter sebelumnya
				$query_insert_mstr	= "INSERT INTO MASTER_CONTAINER(NO_CONTAINER,
																	SIZE_,
																	TYPE_,
																	LOCATION, NO_BOOKING, COUNTER)
															 VALUES('$no_cont',
																	'$size',
																	'$type',
																	'$loc_now', '$no_booking', 1)
										";						
				$db->query($query_insert_mstr);
				
			}else{	
				$query_counter = "SELECT COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
				$r_counter = $db->query($query_counter);
				$rw_counter = $r_counter->fetchRow();
				$last_counter = $rw_counter["COUNTER"]+1;
				$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = '$no_booking', COUNTER = '$last_counter', LOCATION = '$loc_now', SIZE_ = '$size', TYPE_ = '$type' WHERE NO_CONTAINER = '$no_cont'";
				$db->query($q_update_book2);
			}
			$q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
			$r_getcounter2 = $db->query($q_getcounter2);
			$rw_getcounter2 = $r_getcounter2->fetchRow();
			$cur_counter2 = $rw_getcounter2["COUNTER"];
			$cur_booking2 = $rw_getcounter2["NO_BOOKING"];
			if($db->query($query_insert_strip))
			{																   
			
			// [3] - Insert ke tabel HISTORY_CONTAINER
			
					$history_del   =  "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER, 
																	 NO_REQUEST, 
																	 KEGIATAN, 
																	 TGL_UPDATE, 
																	 ID_USER, 
																	 ID_YARD, 
																	 NO_BOOKING, 
																	 COUNTER, 
																	 STATUS_CONT) 
															VALUES  ('$no_cont',
																	 '$no_req',
																	 'PLAN REQUEST STRIPPING',
																	 SYSDATE,
																	 '$id_user',
																	 '$id_yard', 
																	 '$cur_booking2', 
																	 '$cur_counter2',
																	 '$status')";
					
			// [4] - Update SUB_COUNTER ke tabel HISTORY_CONTAINER		
					if($db->query($history_del))
					{
						$query_cek_sub_counter = "SELECT SUB_COUNTER 
													FROM HISTORY_CONTAINER
													WHERE NO_CONTAINER = '$no_cont'
													AND COUNTER='$cur_counter2'
													ORDER BY SUB_COUNTER DESC";	
													
						$cek_sub_counter = $db->query($query_cek_sub_counter);
						$sub_counter = $cek_sub_counter->fetchRow();
						$sub_counter = $sub_counter['SUB_COUNTER'];
						
						if ($sub_counter == NULL)
						{
							$update_sub_counter = "UPDATE HISTORY_CONTAINER SET SUB_COUNTER = 1
													WHERE NO_CONTAINER = '$no_cont'
													AND COUNTER='$cur_counter2'";
							
							$db->query($update_sub_counter);
						}
						else
						{	
							$update_sub_counter = "UPDATE HISTORY_CONTAINER SET SUB_COUNTER = $sub_counter + 1
													WHERE NO_CONTAINER = '$no_cont'
													AND COUNTER='$cur_counter2'";
							
							$db->query($update_sub_counter);
						}
						
					}
					
					$q_pkk = $db->query("SELECT NO_UKK FROM V_PKK_CONT WHERE NO_BOOKING='$no_booking'");
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
							   TM.BP_ID NO_BOOKING
						  FROM    petikemas_cabang.v_pkk_cont A
							   JOIN
								  PETIKEMAS_CABANG.TTM_BP_CONT TM
							   ON A.NO_UKK = TM.NO_UKK AND TM.BP_ID = '$no_booking'";
						$db->query($q_insert);
						
					} 
					
				echo "OK";
			}
			}
else{
		echo "Maaf anda telah menambahakan container ".$no_cont." pada request ini";
	}
		//}
		//echo "XX";
	//}
	//echo "CC";
			//}
		//	echo "AA";
	//	}
	//echo "AS";
/*}
else 
{
	echo "OUTSIDE";	
}*/
?>