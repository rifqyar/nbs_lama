<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_truck	= $_POST["NO_TRUCK"]; 
$no_req_tpk		= $_POST["NO_REQ_TPK"];
$bp_id		= $_POST["BP_ID"];

$no_seal	= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
$tgl_gati         = $_POST["tgl_gati"];
//$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan	= $_POST["KETERANGAN"]; 
$id_yard	= $_POST["ID_YARD"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];

$qcek_gati = "SELECT COUNT(NO_CONTAINER) AS JUM
			  FROM BORDER_GATE_IN
			  WHERE NO_CONTAINER = '$no_cont'
			  AND NO_REQUEST = '$no_req'";
$rcek_gati = $db->query($qcek_gati);
$rwc_gati = $rcek_gati->fetchRow();
$jum_gati = $rwc_gati["JUM"];
if($jum_gati > 0){
	echo "EXIST_GATI";
	exit();
}

//echo $no_req;die; 
//debug($_POST);die;
//Cek posisi container
$query_gati = "SELECT LOCATION
			  FROM MASTER_CONTAINER
			  WHERE NO_CONTAINER = '$no_cont'
				";
$result_gati	= $db->query($query_gati);
$row_gati		= $result_gati->fetchRow();
$gati		= $row_gati["LOCATION"];

//=======================================  INTERFACE SIMOP ======================================================//




//======================================= END INTERFACE SIMOP ======================================================//


$query_insert	= "INSERT INTO BORDER_GATE_IN
						( 	NO_REQUEST, 
							NO_CONTAINER, 
							ID_USER, 
							TGL_IN, 
							NOPOL, 
							STATUS, 
							NO_SEAL, 
							ID_YARD,
							KETERANGAN,
							ENTRY_DATE) 
				 VALUES(	'$no_req', 
							'$no_cont', 
							'$id_user', 
							TO_DATE('$tgl_gati','dd-mm-rrrr'), 
							'$no_truck', 
							'$status',
							'$no_seal',
							'$id_yard',
							'$keterangan',
							SYSDATE)";
							
//Insert ke handling piutang
/*					
$query_insert_piutang = "INSERT INTO HANDLING_PIUTANG(NO_CONTAINER,
													  KEGIATAN,
													  STATUS_CONT,
													  TANGGAL,
													  KETERANGAN,
													  NO_REQUEST,
													  ID_YARD
													  )
											   VALUES('$no_cont',
													  'RECEIVING',
													  '$status',
													  SYSDATE,
													  '',
													  '$no_req',
													  '$depo_tujuan'
													  )";
*/

		
							
							


//echo $query_insert;exit;							
							
	
    //$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
    if($no_truck == NULL)
    {
		echo "TRUCK";
	
    }
	else if($gati == "GATI")
	{
		echo "EXIST";
	}
	else if( ($db->query($query_insert)) /* && ($db->query($query_insert_piutang)) */ )
	{
		$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'");
		
		$id_yard_	= $_SESSION["IDYARD_STORAGE"];
		
		$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
		$r_getcounter1 = $db->query($q_getcounter1);
		$rw_getcounter1 = $r_getcounter1->fetchRow();
		$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
		$cur_counter1  = $rw_getcounter1["COUNTER"]; 
		
		$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
                           VALUES ('$no_cont','$no_req','BORDER GATE IN',SYSDATE,'$id_user','$id_yard_','$status','$cur_booking1','$cur_counter1')";
       
        $db->query($history);
		
		$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'");
		
		//$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		echo "OK";
	}
	else 
	{	
		echo "GAGAL";
	}


?>