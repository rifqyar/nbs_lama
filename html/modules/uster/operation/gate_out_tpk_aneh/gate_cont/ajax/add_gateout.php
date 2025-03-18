<?php

$db 			= getDB("storage");
$db2 			= getDB("ora");


//echo "dama";die;
$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$no_nota		= $_POST["NO_NOTA"];
$no_truck		= $_POST["NO_TRUCK"]; 
$kode_truck		= $_POST["KD_TRUCK"]; 
$no_seal		= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
$masa_berlaku   = $_POST["MASA_BERLAKU"]; 
$keterangan		= $_POST["KETERANGAN"];
$kd_pmb_dtl		= $_POST["KD_PMB_DTL"]; 
$gross			= $_POST["GROSS"];
$ht_op			= $_POST["HT_OP"];

//echo $kd_pmb_dtl;exit;
//debug($_POST);die;

if($_SESSION["LOGGED_STORAGE"] == NULL){
	echo "EXPIRED_SESSION";
	exit();
}


$qcek_gato = "SELECT COUNT(NO_CONTAINER) AS JUM
			  FROM BORDER_GATE_OUT
			  WHERE NO_CONTAINER = '$no_cont'
			  AND NO_REQUEST = '$no_req'";
$rcek_gato = $db->query($qcek_gato);
$rwc_gato = $rcek_gato->fetchRow();
$jum_gato = $rwc_gato["JUM"];
if($jum_gato > 0){
	echo "EXIST_GATO";
	exit();
}

$id_user		= $_SESSION["LOGGED_STORAGE"];
$nm_user		= $_SESSION["NAME"];
$id_yard        = $_SESSION["IDYARD_STORAGE"];

$selisih        = "SELECT TRUNC(TO_DATE('$masa_berlaku','DD/MM/YYYY') - SYSDATE) SELISIH FROM dual";
$result_cek		= $db->query($selisih);
$row_cek		= $result_cek->fetchRow();
$selisih_tgl    = 1;

$peralihan_     = "SELECT NO_REQ_ICT, DELIVERY_KE, PERALIHAN, STATUS FROM request_delivery WHERE NO_REQUEST = '$no_req'";
//echo $peralihan_;die;
$result_cek		= $db->query($peralihan_);
$row_cek		= $result_cek->fetchRow();
$peralihan		= $row_cek["PERALIHAN"];
$delivery_ke    = $row_cek["DELIVERY_KE"];
$no_req_ict    = $row_cek["NO_REQ_ICT"];
$stat_req    = $row_cek["STATUS"];


$peralihan_     = "SELECT LUNAS FROM nota_delivery WHERE NO_REQUEST = '$no_req'";
//echo $peralihan_;die;
$result_cek		= $db->query($peralihan_);
$row_cek		= $result_cek->fetchRow();
$lunas			= $row_cek["LUNAS"];
if($stat_req == "AUTO_REPO"){
	$cek_batal_muat = $db->query("SELECT * FROM REQUEST_BATAL_MUAT WHERE NO_REQ_BARU = '$no_req'");
	$rcek_bm =  $cek_batal_muat->fetchRow();
	$no_rqbm = $rcek_bm["NO_REQUEST"];
	if($rcek_bm["BIAYA"] == 'T'){
		$lunas = "YES";
		$no_nota = "AUTO";
	}
	else {
		$cek_lunas_bm = $db->query("select * from nota_batal_muat where no_request = '$no_rqbm'");
		$rlunas_bm = $cek_lunas_bm->fetchRow();
		if($rlunas_bm["LUNAS"] == "YES"){
			$lunas = "YES";
			$no_nota = "AUTO";
		}
		else {
			echo "BATAL_MUAT_BLM_LUNAS";
			exit();
		}
	}
	
}

$cek_gate       = "SELECT NO_CONTAINER FROM GATE_OUT WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
//echo $peralihan_;die;
$result_cek		= $db->query($cek_gate);
$row_cek		= $result_cek->fetchRow();
$cek_gate		= $row_cek["NO_CONTAINER"];

/*echo $selisih_tgl;
echo $peralihan;
echo $delivery_ke;
echo $no_nota;
echo $lunas;
*/
//echo "selisih tanggal :".$selisih_tanggal."<br/> peralihan :".$peralihan."delivery ke :".$delivery_ke."lunas: ".$lunas; die;

// IF untuk kondisi RELOKASI
if (($no_nota == NULL) && ($peralihan == 'RELOKASI') && ($selisih_tgl < 0) && ($delivery_ke == NULL) && ($lunas == NULL)) {
    echo "EXPIRED";
} else if (($no_nota == NULL) && ($peralihan == 'RELOKASI') && ($selisih_tgl >= 0) && ($delivery_ke == 'LUAR') && ($lunas == NULL)){ 



//================================= GATE IN SIMOP ============================================//
	/*
	$sql 	= "UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL 
	SET TGL_GATE=SYSDATE,
	NO_GATE='001', 
	NO_SEAL='$no_seal', 
	GROSS='".$GROSS."',
	TRUCK_NO='$no_truck',
	TRUCK_OP='".$TRUCK_OP."',
	KETERANGAN='$keterangan',
	STATUS_PMB_DTL='1', 
	USER_ID='$nm_user'  
	WHERE KD_PMB_DTL='$kd_pmb_dtl'";
	
	*/
	
	
	
	
	
	
	
	
	
	
//==================================gate uster==============================================================================================================================	
	
	
	
    $query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN, ID_YARD) 
								     VALUES('$no_req', '$no_cont', '$id_user', SYSDATE, '$no_truck', '$status','$no_seal','$kode_truck','$keterangan','$id_yard')";
   // echo $query_insert;
    $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
    
    $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, STATUS_CONT) 
                                                      VALUES ('$no_cont','$no_req','GATE OUT',SYSDATE,'$id_user','$status')";
           // echo $history;die;
    $db->query($history);
    
   // $selisih        = "SELECT a.NO_REQUEST, a.PERALIHAN, a.RECEIVING_DARI FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING b WHERE a.NO_REQUEST = b.NO_REQUEST AND b.NO_CONTAINER = '$no_cont' AND b.AKTIF = 'Y'";
    //$result_cek     = $db->query($selisih);
    //$row_cek        = $result_cek->fetchRow();
    //$no_req_rec     = $row_cek["NO_REQUEST"];
    //$peralihan      = $row_cek["PERALIHAN"];
    //$rec_dari       = $row_cek["RECEIVING_DARI"];

   
    
    if ($peralihan == 'RELOKASI'){
        $data = "INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST, ID_YARD) VALUES 
            ('$no_cont','LIFT OFF','$status',SYSDATE,'$no_req', '$id_yard')";
        $db->query($data);
        $data2 ="INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST, ID_YARD) VALUES 
            ('$no_cont','HAULAGE','$status',SYSDATE,'$no_req', '$id_yard')";
        $db->query($data2);
       // echo $data; echo $data2; die;
    }    
    
    $db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    $db->query("UPDATE CONTAINER_DELIVERY SET KELUAR = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    if($db->query($query_insert))
    {
	echo "OK";
    }
	
	//==================================gate uster==============================================================================================================================
	
// IF untuk kondisi DELIVERY KE  LUAR DEPO (bukan TPK)
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl < 0) && ($delivery_ke == 'LUAR') && ($lunas == 'NO')){ 
    echo "BLM LUNAS DAN EXPIRED";
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl >= 0) && ($delivery_ke == 'LUAR') && ($lunas == 'NO')){ 
    echo "BLM LUNAS";
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl < 0) && ($delivery_ke == 'LUAR') && ($lunas == 'YES')){ 
    echo "EXPIRED";
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl >= 0) && ($delivery_ke == 'LUAR') && ($lunas == 'YES')){ 
   
   //==================================gate uster==============================================================================================================================
	
    $query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN, ID_YARD) VALUES('$no_req', '$no_cont', '$id_user', SYSDATE, '$no_truck', '$status','$no_seal','$kode_truck','$keterangan','$id_yard')";
   // echo $query_insert;die;
    $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
    
    $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, STATUS_CONT) 
                                                      VALUES ('$no_cont','$no_req','GATE OUT',SYSDATE,'$id_user','$status')";
           // echo $history;die;
              $db->query($history);
    
   // $selisih        = "SELECT a.NO_REQUEST, a.PERALIHAN, a.RECEIVING_DARI FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING b WHERE a.NO_REQUEST = b.NO_REQUEST AND b.NO_CONTAINER = '$no_cont' AND b.AKTIF = 'Y'";
   // $result_cek     = $db->query($selisih);
   // $row_cek        = $result_cek->fetchRow();
    //$no_req_rec     = $row_cek["NO_REQUEST"];
    //$peralihan      = $row_cek["PERALIHAN"];
    //$rec_dari       = $row_cek["RECEIVING_DARI"];

    
    
    if ($peralihan == 'RELOKASI'){
        $data = "INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST, ID_YARD) VALUES 
            ('$no_cont','LIFT OFF','$status',SYSDATE,'$no_req','$id_yard')";
        $db->query($data);
        $data2 ="INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST, ID_YARD) VALUES 
            ('$no_cont','HAULAGE','$status',SYSDATE,'$no_req','$id_yard')";
        $db->query($data2);
       // echo $data; echo $data2; die;
    }    
    
    $db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    $db->query("UPDATE CONTAINER_DELIVERY SET KELUAR = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
	
	$cek_placement = "SELECT * FROM PLACEMENT INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID WHERE BLOCKING_AREA.ID_YARD_AREA = '$id_yard' AND PLACEMENT.NO_CONTAINER = '$no_cont'";
	$r_cek_place = $db->query($cek_placement);
	$r_b = $r_cek_place->fetchRow();
	$block_ = $r_b["ID_BLOCKING_AREA"];		
	$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont' AND ID_BLOCKING_AREA = '$block_'");
    if($db->query($query_insert))
    {
	echo "OK";
    }
	
	//==================================gate uster==============================================================================================================================
	
// IF UNTUK DELIVERY KE TPK


} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl == NULL) && ($delivery_ke == 'TPK') && ($lunas == 'NO')){ 
    echo "BLM LUNAS DAN EXPIRED";
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl == NULL) && ($delivery_ke == 'TPK') && ($lunas == 'NO')){ 
    echo "BLM LUNAS";
}
/* 
else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl == NULL) && ($delivery_ke == 'TPK') && ($lunas == 'YES')){ 
    echo "EXPIRED";
}*/ 
else if ( ($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($delivery_ke == 'TPK') && ($selisih_tgl >= 0) && ($lunas == 'YES')){ 


//================================= GATE IN SIMOP ============================================//

	if ($komoditi == '' || $komoditi == NULL)
	{$kd_komoditi = 'C000000293';}	 
	
	if ($gross == '' || $gross == NULL)
	{$gross = '0';}	 
   	
   	$nm_user = "Darmawi";
   	$id_user = "287";
   
	$sql1 	= "UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL 
	SET TGL_GATE=TO_DATE('09/07/2013','DD/MM/RRRR'),
	NO_GATE='001', 
	NO_SEAL='$no_seal', 
	GROSS='$gross',
	TRUCK_NO='$no_truck',
	TRUCK_OP='$ht_op',
	KETERANGAN='$keterangan', 
	USER_ID='$nm_user'  
	WHERE NO_CONTAINER='$no_cont' AND KD_PMB = '$no_req_ict'";
	
	//$db->query("UPDATE PETIKEMAS_CABANG.TTD_CONT_EXBSPL SET STATUS_PMB_DTL='1U' WHERE NO_CONTAINER='$no_cont' AND KD_PMB = '$no_req_ict'");
   
   //==================================gate uster==============================================================================================================================
	
	if($db->query($sql1))
	{
		
		
		$query_insert = "INSERT INTO BORDER_GATE_OUT
						( 	NO_REQUEST, 
							NO_CONTAINER, 
							ID_USER, 
							TGL_IN, 
							NOPOL, 
							STATUS, 
							NO_SEAL,
							TRUCKING,
							ID_YARD,
							KETERANGAN) 
				   VALUES(	'$no_req', 
							'$no_cont', 
							'$id_user', 
							TO_DATE('09/07/2013','DD/MM/RRRR'), 
							'$no_truck', 
							'$status',
							'$no_seal',
							'$kode_truck',
							'$id_yard',
							'$keterangan')";
		
		$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
		
		//Masih pakai data dummy
		$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
		$r_getcounter1 = $db->query($q_getcounter1);
		$rw_getcounter1 = $r_getcounter1->fetchRow();
		$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
		$cur_counter1  = $rw_getcounter1["COUNTER"];
		
		$history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
														  VALUES ('$no_cont','$no_req','BORDER GATE OUT',SYSDATE,'$id_user','$id_yard','$status','$cur_booking1','$cur_counter1')";
			   // echo $history;die;
		
		$db->query($history);
		
		$selisih        = "SELECT a.NO_REQUEST, a.PERALIHAN, a.RECEIVING_DARI FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING b WHERE a.NO_REQUEST = b.NO_REQUEST AND b.NO_CONTAINER = '$no_cont' AND b.AKTIF = 'Y'";
		$result_cek     = $db->query($selisih);
		$row_cek        = $result_cek->fetchRow();
		$no_req_rec     = $row_cek["NO_REQUEST"];
		//$peralihan      = $row_cek["PERALIHAN"];
		//$rec_dari       = $row_cek["RECEIVING_DARI"];

		

		
		if ($peralihan == 'RELOKASI'){
			$data = "INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST, ID_YARD) VALUES 
				('$no_cont','LIFT OFF','$status',SYSDATE,'$no_req','$id_yard')";
			$db->query($data);
			$data2 ="INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST, ID_YARD) VALUES 
				('$no_cont','HAULAGE','$status',SYSDATE,'$no_req','$id_yard')";
			$db->query($data2);
		   // echo $data; echo $data2; die;
		}

		
		
		$db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		$db->query("UPDATE CONTAINER_DELIVERY SET KELUAR = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		
		/* $cek_placement = "SELECT * FROM PLACEMENT INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID WHERE BLOCKING_AREA.ID_YARD_AREA = '$id_yard' AND PLACEMENT.NO_CONTAINER = '$no_cont'";
		$r_cek_place = $db->query($cek_placement);
		$r_b = $r_cek_place->fetchRow();
		$block_ = $r_b["ID_BLOCKING_AREA"];	 */
		
		$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
		
		if($db->query($query_insert))
		{
				$sql = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_TRUCKOUT( 
					KD_PMB_DTL, 
					TGL, 
					KD_TT_OP_ALAT, 
					NM_OPERATOR, 
					CATATAN, 
					STATUS_CONT_TALLY, 
					YOA, 
					USER_ID ) 
			VALUES (
					'$kd_pmb_dtl',
					TO_DATE('09/07/2013','DD/MM/RRRR'),
					'$fdtt_no',
					'$fdtt_op',
					'$keterangan',
					'0',
					'$yoa',
					'$nm_user'
					)";


				//============================END SIMOP GATE OUT ==================//
				//echo $sql;exit;
				
				if($db2->query($sql))
				{
					
					$query_insert	= "INSERT INTO border_gate_in( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN, ID_YARD) 
						VALUES('$no_req', '$no_cont', '$id_user', TO_DATE('09/07/2013','DD/MM/RRRR'), '$no_truck', '$status','$no_seal','$kode_truck','$keterangan','$id_yard')";
					
					$db->query($query_insert);
					
				}
			
			$qcek_gato = "SELECT COUNT(NO_CONTAINER) AS JUM
			  FROM BORDER_GATE_OUT
			  WHERE NO_CONTAINER = '$no_cont'
			  AND NO_REQUEST = '$no_req'";
			$rcek_gato = $db->query($qcek_gato);
			$rwc_gato = $rcek_gato->fetchRow();
			$jum_gato = $rwc_gato["JUM"];
			if($jum_gato > 0){
				echo "OK";
				exit();
			}
			else {
				echo "FAIL";
				exit();
			}
		}
		
	}
	else
	{
		echo "gagal query simop";exit;
	}
    
	
	//==================================gate uster==============================================================================================================================
	
}
else if ( ($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($delivery_ke == 'TPK') && ($selisih_tgl <= 0) && ($lunas == 'YES')){ 
	echo "EXPIRED"; exit();
}
else
{
	debug($_POST);	
}

?>