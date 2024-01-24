<?php

$db 			= getDB("storage");
$db2 			= getDB("ora");

$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$stripping_dari	= $_POST["STRIPPING_DARI"];
$no_req_rec		= $_POST["NO_REQUEST_RECEIVING"];
$id_user        = $_SESSION["LOGGED_STORAGE"];

$no_req_del_ict	= "U".$no_req_rec;

//echo $no_req_del_ict;die; 
//echo $stripping_dari;die;
//debug($_POST);die;

$q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_counter = $rw_getcounter["COUNTER"];
						$cur_booking = $rw_getcounter["NO_BOOKING"];

if($stripping_dari == "TPK" )
{
//==============================================================Interface Ke ICT=============================================================================//
//==============================================================================================================================================================================//

	$sqlbp	= "SELECT NO_BP_ID 
				FROM PETIKEMAS_CABANG.TTD_DEL_REQ 
				WHERE CONT_NO_BP = '$no_cont' 
				AND NO_REQ_DEL = '$no_req_del_ict'";
				
	//debug($sqlbp);die;	
	
	$rsbp	= $db2->query($sqlbp);
	$rowbp	= $rsbp->FetchRow(); 
	$bp_id	= $rowbp["NO_BP_ID"];
	
	//echo $bp_id;die;

	$sql= "DELETE FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE NO_REQ_DEL = '$no_req_del_ict' AND CONT_NO_BP = '$no_cont' AND NO_BP_ID = '$bp_id'  ";

	$db2->query($sql);

	$sql_u= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET STATUS_CONT='03' WHERE BP_ID ='$bp_id' AND CONT_NO_BP='$no_cont'";

	$db2->query($sql_u);

//==============================================================End Of Interface Ke ICT======================================================================//
//==============================================================================================================================================================================//
	


	$update_batal_rec= "UPDATE CONTAINER_RECEIVING SET STATUS_REQ='BATAL' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'";
	$db->query($update_batal_rec);
	
	$update_batal_strip= "UPDATE CONTAINER_STRIPPING SET STATUS_REQ='BATAL' WHERE NO_REQUEST ='$no_req' AND NO_CONTAINER='$no_cont'";
	$db->query($update_batal_strip);
	
	$update_batal_plan_strip= "UPDATE PLAN_CONTAINER_STRIPPING SET AKTIF='T' WHERE NO_REQUEST = REPLACE('$no_req','S','P') AND NO_CONTAINER='$no_cont'";
	$db->query($update_batal_plan_strip);
	
	$db->query("UPDATE CONTAINER_STRIPPING SET AKTIF='T' WHERE NO_REQUEST ='$no_req' AND NO_CONTAINER='$no_cont'");
	$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF='T' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'");
	
	$query_insert_history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER,
																			   NO_REQUEST,
																			   KEGIATAN,
																			   TGL_UPDATE,
																			   ID_USER, NO_BOOKING, COUNTER, STATUS_CONT
																				)
																		VALUES('$no_cont',
																			   '$no_req',
																			   'BATAL STRIPPING',
																			   SYSDATE,
																			   '$id_user', '$cur_booking', '$cur_counter', 'FCL'
																				)	
																				";	
	$db->query($query_insert_history);
	
	$q_ifperp = $db->query("SELECT * FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'");
	$r_ifperp = $q_ifperp->fetchRow();
	if($r_ifperp["STATUS_REQ"] == 'PERP'){
		$no_req_awal = $r_ifperp["PERP_DARI"];
		$update_batal_strip_= "UPDATE CONTAINER_STRIPPING SET STATUS_REQ='BATAL' WHERE NO_REQUEST ='$no_req_awal' AND NO_CONTAINER='$no_cont'";
		$db->query($update_batal_strip_);
	}

	//update gato
	$q_gato = "UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'";
	if ($db->query($q_gato)) {
		echo "OK";
	}
	
	

}
else if($stripping_dari == "DEPO")
{
	$update_batal_rec= "UPDATE CONTAINER_RECEIVING SET STATUS_REQ='BATAL' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'";
	$db->query($update_batal_rec);
	
	$update_batal_strip= "UPDATE CONTAINER_STRIPPING SET STATUS_REQ='BATAL' WHERE NO_REQUEST ='$no_req' AND NO_CONTAINER='$no_cont'";
	$db->query($update_batal_strip);
	
	$update_batal_plan_strip= "UPDATE PLAN_CONTAINER_STRIPPING SET AKTIF='T' WHERE NO_REQUEST = REPLACE('$no_req','S','P') AND NO_CONTAINER='$no_cont'";
	$db->query($update_batal_plan_strip);
	
	$db->query("UPDATE CONTAINER_STRIPPING SET AKTIF='T' WHERE NO_REQUEST ='$no_req' AND NO_CONTAINER='$no_cont'");
	$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF='T' WHERE NO_REQUEST ='$no_req_rec' AND NO_CONTAINER='$no_cont'");
	
	$query_insert_history = "INSERT INTO HISTORY_CONTAINER(NO_CONTAINER,
																			   NO_REQUEST,
																			   KEGIATAN,
																			   TGL_UPDATE,
																			   ID_USER, NO_BOOKING, COUNTER, STATUS_CONT
																				)
																		VALUES('$no_cont',
																			   '$no_req',
																			   'BATAL STRIPPING',
																			   SYSDATE,
																			   '$id_user','$cur_booking', '$cur_counter', 'FCL'
																				)	
																				";	
	$db->query($query_insert_history);
	
	echo "OK";
}

?>