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
$tgl_gati         = $_POST["tgl_gati"];
//$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan	= $_POST["KETERANGAN"]; 
$id_yard	= $_POST["ID_YARD"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];

//=======================================  INTERFACE SIMOP ======================================================//

$sql 	= "UPDATE  PETIKEMAS_CABANG.TTD_BP_CONT SET
		   GATE_OUT_DATE			= TO_DATE('$tgl_gati','dd-mm-rrrr'), 
		   HT_NO				= '$no_truck' 
		   WHERE BP_ID  	    = '$bp_id' 
		   AND CONT_NO_BP		= '$no_cont'
		   AND NO_REQ			= '$no_req_tpk'
		   ";      
//echo $sql;	
$db2->query($sql);	 


//======================================= END INTERFACE SIMOP ======================================================/							
							
	$query_insert = "UPDATE BORDER_GATE_IN SET TGL_IN = TO_DATE('$tgl_gati','dd-mm-rrrr'), NOPOL = '$no_truck', KETERANGAN = '$keterangan', NO_SEAL = '$no_seal' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
    //$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
    if($db->query($query_insert))
	{
		
		//$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		$q_why = "UPDATE HISTORY_CONTAINER SET WHY = 'UPDATED_ADMIN' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND KEGIATAN = 'BORDER GATE IN'";
		if($db->query($q_why)){
			echo "OK";
		}
	
	}
	else 
	{	
		echo "GAGAL";
	}


?>