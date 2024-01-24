<?php

$db 			= getDB("storage");
$no_req_rel     = $_POST["NO_REQ"];
$no_cont		= $_POST["NO_CONT"]; 
$no_req_del		= $_POST["NO_REQ_DEL"];
$no_req_rec		= $_POST["NO_REQ_REC"];
$yard_asal		= $_POST["YARD_ASAL"];
$yard_tujuan	= $_POST["YARD_TUJUAN"];
$id_user	= $_SESSION["LOGGED_STORAGE"];
//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus di Lapangan dan sudah Gate In

						
$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";			
				   
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];

//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;
$query_vel_real		= "SELECT tes.NO_REQUEST, 
										CASE SUBSTR(KEGIATAN,9)
											WHEN 'RECEIVING' THEN (SELECT CONCAT('RECEIVING_',a.RECEIVING_DARI) FROM request_receiving a WHERE a.NO_REQUEST = tes.NO_REQUEST)
											ELSE SUBSTR(KEGIATAN,9)
										END KEGIATAN FROM (SELECT TGL_UPDATE, NO_REQUEST,KEGIATAN FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI')) tes
										WHERE tes.TGL_UPDATE=(SELECT MAX(TGL_UPDATE) FROM history_container WHERE no_container = '$no_cont' and kegiatan IN ('REQUEST RECEIVING','REQUEST STRIPPING','REQUEST STUFFING','REQUEST RELOKASI'))";
			$result_val		= $db->query($query_vel_real);
			$row_val		= $result_val->fetchRow();
			$no_request_val	= $row_val["NO_REQUEST"];
			$kegiatan_val	= $row_val["KEGIATAN"];
if ($kegiatan_val == 'STRIPPING'){
	$q_cek = "SELECT TGL_REALISASI FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_val'";
	$res_cek_real = $db->query($q_cek);
	$row_cek_real = $res_cek_real->fetchRow();
	$tgl_realisasi = $row_cek_real["TGL_REALISASI"];
	if($tgl_realisasi == NULL){
		echo "Maaf, Anda belum melakukan realisasi stripping pada container ini";
		exit();
	}
}
else if($kegiatan_val == 'STUFFING'){
	$q_cek = "SELECT COUNT(1) JUM, TGL_REALISASI FROM CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_request_val'
	GROUP BY TGL_REALISASI";
	$res_cek_real = $db->query($q_cek);
	$row_cek_real = $res_cek_real->fetchRow();
	$tgl_realisasi = $row_cek_real["TGL_REALISASI"];
	$jum = $row_cek_real["JUM"];
	if($tgl_realisasi == NULL && $jum != 0){
		echo "Maaf, Anda belum melakukan realisasi stuffing pada container ini";
		exit();
	}
}

if($location != "GATO")
{
	//container ada di dalam
	//cek status aktif di CONTAINER_RECEIVING
	
	$query_cek		= "SELECT COUNT(1) AS CEK FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y' ";
	$result_cek		= $db->query($query_cek);
	$row_cek 		= $result_cek->fetchRow();
	
	if($row_cek["CEK"] == 0)
	{	
		//get status container terakhir
		$query_get_cont		= "SELECT * FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' ORDER BY NO_REQUEST DESC";
		$result_get_cont	= $db->query($query_get_cont);
		$row_cont			= $result_get_cont->fetchRow();
		
		$status	= $row_cont["STATUS"];
		$hz		= $row_cont["HZ"];
		
		$query_insert_del	= "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   HZ,
														   AKTIF,
														   ID_YARD
														  ) 
													VALUES('$no_cont', 
														   '$no_req_del',
														   '$status',
														   '$hz',
														   'Y',
														   '$yard_asal'
														   )";
		// echo $query_insert;
								
		if($db->query($query_insert_del))
		{
			
			
			$query_insert_rec	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
															   NO_REQUEST,
															   STATUS,
															   HZ,
															   AKTIF,
															   DEPO_TUJUAN
															  ) 
														VALUES('$no_cont', 
															   '$no_req_rec',
															   '$status',
															   '$hz',
															   'Y',
															   '$yard_tujuan'
															   )";
			// echo $query_insert;
									
			if($db->query($query_insert_rec))
			{
				$q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
				$r_getcounter2 = $db->query($q_getcounter2);
				$rw_getcounter2 = $r_getcounter2->fetchRow();
				$cur_booking2  = $rw_getcounter2["NO_BOOKING"];
				$cur_counter2  = $rw_getcounter2["COUNTER"]+1;
				
				$history_rec        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
																  VALUES ('$no_cont','$no_req_rec','REQUEST RECEIVING',SYSDATE,'$id_user','$yard_tujuan','$status','$cur_booking2','$cur_counter2')";
				$db->query($history_rec);
				
				$db->query("UPDATE MASTER_CONTAINER SET COUNTER = '$cur_counter2', NO_BOOKING = '$cur_booking2' WHERE NO_CONTAINER = '$no_cont'");
				
				$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
				$r_getcounter1 = $db->query($q_getcounter1);
				$rw_getcounter1 = $r_getcounter1->fetchRow();
				$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
				$cur_counter1  = $rw_getcounter1["COUNTER"];
				
				$history_del        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
																  VALUES ('$no_cont','$no_req_del','REQUEST DELIVERY',SYSDATE,'$id_user', '$yard_asal','$status','$cur_booking1','$cur_counter1')";
				$db->query($history_del);
				
				$query_insert_rel	= "INSERT INTO CONTAINER_RELOKASI(NO_CONTAINER, 
															   NO_REQUEST,
															   STATUS,
															   AKTIF 
															  ) 
														VALUES('$no_cont', 
															   '$no_req_rel',
															   '$status',
															   'Y'
															   )";
															   
				$db->query($query_insert_rel);
				
				$q_getcounter3 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
				$r_getcounter3 = $db->query($q_getcounter3);
				$rw_getcounter3 = $r_getcounter3->fetchRow();
				$cur_booking3  = $rw_getcounter3["NO_BOOKING"];
				$cur_counter3  = $rw_getcounter3["COUNTER"];
				
				$history_rel        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
																  VALUES ('$no_cont','$no_req_rel','REQUEST RELOKASI',SYSDATE,'$id_user','$yard_tujuan','$status','$cur_booking3','$cur_counter3')";
				$db->query($history_rel);
				
				echo "OK";
			}
		}
	}
	else
	{
		echo "EXIST";	
	}
}
else 
{
	echo "OUTSIDE";	
}
?>