<?php

$db 		= getDB("storage");
$db2 		= getDB("ora");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_truck	= $_POST["NO_TRUCK"]; 
$no_req_tpk		= $_POST["NO_REQ_TPK"];
$bp_id		= $_POST["BP_ID"];

$no_seal	= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
//$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan	= $_POST["KETERANGAN"]; 
$id_yard	= $_POST["ID_YARD"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];

//echo $no_req;die; 
//debug($_POST);die;
//Cek posisi container

$get_status = "SELECT STATUS FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$r_status = $db->query($get_status);
$v_status = $r_status->fetchRow();

$status = $v_status["STATUS"];
$query_gati = "SELECT LOCATION
			  FROM MASTER_CONTAINER
			  WHERE NO_CONTAINER = '$no_cont'
				";
$result_gati	= $db->query($query_gati);
$row_gati		= $result_gati->fetchRow();
$gati		= $row_gati["LOCATION"];

//=======================================  INTERFACE SIMOP ======================================================//

$sql 	= "UPDATE  PETIKEMAS_CABANG.TTD_BP_CONT SET
		   GATE_OUT				= '$nm_user',
		   GATE_OUT_DATE		= SYSDATE, 
		   HT_NO				= '$no_truck' 
		   WHERE BP_ID  	    = '$bp_id' 
		   AND CONT_NO_BP		= '$no_cont'
		   ";      
//echo $sql;	
$db2->query($sql);	 

$sql_d 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET
           STATUS_CONT		= '09'
		   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  
//echo $sql_d;	exit;	   
$db2->query($sql_d);



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
							KETERANGAN) 
				 VALUES(	'$no_req', 
							'$no_cont', 
							'$id_user', 
							SYSDATE, 
							'$no_truck', 
							'$status',
							'$no_seal',
							'$id_yard',
							'$keterangan')";
							
//=============flag gate di container stripping dan stuffing==============

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
				//tidak ada action
		} ELSE IF ($kegiatan == 'RECEIVING_TPK') {
				//tidak ada action
		} ELSE IF ($kegiatan == 'STUFFING') {
				$query_insert_	= "UPDATE CONTAINER_STUFFING SET TGL_GATE = SYSDATE WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
				$db->query($query_insert_);
		} ELSE IF ($kegiatan == 'STRIPPING') {
				$query_insert_	= "UPDATE CONTAINER_STRIPPING SET TGL_GATE = SYSDATE WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
				$db->query($query_insert_);
		}
		//echo $query_insert;

//=====================end flag===========================================
							
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