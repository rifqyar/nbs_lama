<?php

$db 		= getDB("storage");

$no_cont	= strtoupper($_POST["NO_CONT"]); 
$no_req		= $_POST["NO_REQ"]; 
$no_req2	= $_POST["NO_REQ2"]; 
$size		= $_POST["SIZE"]; 
$type		= $_POST["TYPE"]; 
$status		= $_POST["STATUS"]; 
$berbahaya	= $_POST["BERBAHAYA"];
$voyage		= $_POST["VOYAGE"];
$komoditi	= $_POST["KOMODITI"];
$kd_komoditi	= $_POST["KD_KOMODITI"];
$tgl_bongkar= $_POST["TGL_BONGKAR"];
$no_do		= $_POST["NO_DO"];
$via		= $_POST["VIA"];
$no_bl		= $_POST["NO_BL"];
$depo_tujuan = $_POST["DEPO_TUJUAN"];
$id_user        = $_SESSION["LOGGED_STORAGE"];


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

$cek_strip = "SELECT AKTIF
                FROM CONTAINER_STRIPPING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$d_strip = $db->query($cek_strip);
$r_strip = $d_strip->fetchRow();
$l_strip = $r_strip["AKTIF"];
if($l_strip == 'Y'){
	echo "EXIST_STRIP";
	exit(); 
}

//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus GATO dan belum direquest (belum aktif)
$query_cek2		= "SELECT COUNT(*) CEK 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";	

				   
				   
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$existing		= $row_cek2["CEK"];
if($existing == 0){
	$aktif = 'T';
} else {

	//HANYA YANG GATO
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

	$query_cek1		= " SELECT CONTAINER_RECEIVING.AKTIF
					   FROM CONTAINER_RECEIVING, REQUEST_RECEIVING
					   WHERE CONTAINER_RECEIVING.NO_REQUEST = REQUEST_RECEIVING.NO_REQUEST
					   AND CONTAINER_RECEIVING.NO_CONTAINER = '$no_cont' 
					   AND REQUEST_RECEIVING.TGL_REQUEST = (SELECT MAX(REQUEST_RECEIVING.TGL_REQUEST)
					   FROM CONTAINER_RECEIVING, REQUEST_RECEIVING
					   WHERE CONTAINER_RECEIVING.NO_REQUEST = REQUEST_RECEIVING.NO_REQUEST
					   AND CONTAINER_RECEIVING.NO_CONTAINER = '$no_cont')";

	$query_rec_dari ="SELECT RECEIVING_DARI
							FROM REQUEST_RECEIVING
							WHERE NO_REQUEST = '$no_req'";
		$result_rec_dari = $db->query($query_rec_dari);
		$row_rec_dari = $result_rec_dari->fetchRow();
		$rec_dari = $row_rec_dari["RECEIVING_DARI"];
					   
	$result_cek1	= $db->query($query_cek1);
	$row_cek1		= $result_cek1->fetchRow();
	//$jum			= $row_cek1["JUM"];
	$aktif			= $row_cek1["AKTIF"];
}

	
	if($berbahaya == NULL)
	{
		
			echo "BERBAHAYA";
		
	}
	else if($aktif == "Y")
	{
		echo "EXIST";	
	}
	else if($status == NULL)
	{
			echo "STATUS";
	}
	else if(($aktif != "Y") &&  ($berbahaya != NULL) && $status != NULL )
	{
		
		$ukk_ = $_POST['ID_VSB'];
		$ex_kapal = $_POST['VESSEL'];
		if ($ukk_=="") {
			$ukk_ = "NO";
			$ex_kapal="VESSEL_NOTHING";
		}

		$query_insert	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   AKTIF,
														   HZ,
														   NO_BOOKING,
														   KOMODITI,
														   DEPO_TUJUAN,
														   VIA,
														   EX_KAPAL
														   ) 
													VALUES('$no_cont', 
														   '$no_req',
														   '$status',
														   'Y',
														   '$berbahaya',
														   '$ukk_',
														   '$komoditi',
														   1,
														   '$via',
														   '$ex_kapal')";		
		   // echo $query_insert;
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
																			'GATO', 'VESSEL_NOTHING', 1)
												";						
						$db->query($query_insert_mstr);
					}
					else {
						$query_counter = "SELECT COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_counter = $db->query($query_counter);
						$rw_counter = $r_counter->fetchRow();
						$last_counter = $rw_counter["COUNTER"]+1;
						$q_update_book2 = "UPDATE MASTER_CONTAINER SET NO_BOOKING = 'VESSEL_NOTHING', COUNTER = '$last_counter', SIZE_ = '$size', TYPE_ = '$type', LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'";
						$db->query($q_update_book2);
					}
					//Insert ke history container
						$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_counter = $rw_getcounter["COUNTER"];
						$cur_booking = $rw_getcounter["NO_BOOKING"];
						$query_insert_history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER,
																			   NO_REQUEST,
																			   KEGIATAN,
																			   TGL_UPDATE,
																			   ID_USER,
																			   ID_YARD,
																			   STATUS_CONT, NO_BOOKING, COUNTER
																				)
																		VALUES('$no_cont',
																			   '$no_req',
																			   'REQUEST RECEIVING',
																			   SYSDATE,
																			   '$id_user',
																			   1,
																			   '$status', '$cur_booking', '$cur_counter'
																				)	
																				";	
						$db->query($query_insert_history);							
		if($db->query($query_insert))
		{
			echo "OK";
		}
	} else {
		echo "EXIST";
	
	}

	//echo "$aktif | $berbahaya | $status";

?>