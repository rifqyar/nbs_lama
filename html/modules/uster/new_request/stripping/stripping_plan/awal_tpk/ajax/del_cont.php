<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$no_req2	= $_POST["NO_REQ2"]; 

//added 26.04.14 06:44 pm - frenda
if ($no_req == NULL) {
	echo "DATA_NOT_FOUND";
	exit();
}

$q_get = "SELECT O_IDVSB FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
$rget  = $db->query($q_get)->fetchRow();
$idvsb = $rget['O_IDVSB'];


if($no_req2 != NULL){
//==============================================================Interface to OPUS==========================================================================//
//======================================================================================================================================================//
$db2 = getDB();
$query_del	= "DELETE FROM req_delivery_d WHERE (TRIM(NO_CONTAINER) = TRIM('$no_cont')) AND (TRIM(ID_REQ) = TRIM('$no_req2'))";
$qparam = "select a.vessel_code, a.voyage_in, b.carrier from m_vsb_voyage@dbint_link a, m_cyc_container@dbint_link b
        where a.vessel_code = b.vessel_code and a.voyage = b.voyage
        and b.no_container = TRIM('$no_cont') and a.ID_VSB_VOYAGE = '$idvsb'";
$rparam = $db2->query($qparam)->fetchRow(); 
$vessel = $rparam['VESSEL_CODE'];
$voyage = $rparam['VOYAGE'];
$operatorId = $rparam['CARRIER'];
    
$param_b_var= array(	
							"v_nocont"=>TRIM($no_cont),
							"v_req"=>TRIM($no_req2),
							"flag"=>"DEL",
							"vessel"=>"$vessel",
							"voyage"=>"$voyage",
							"operatorId"=>"$operatorId",
							"v_response"=>"",
							"v_msg"=>""
							);
$query = "declare begin proc_delete_cont(:v_nocont, :v_req, :flag, :vessel, :voyage, :operatorId, :v_response, :v_msg); end;";

							
//harusnya ditambahkan untuk delete ke billing_ops
if($db2->query($query_del))
{
	$db2->query($query,$param_b_var);
	echo "OK ".$param_b_var[v_response];
		
}


//==============================================================End Of Interface to OPUS======================================================================//
//==========================================================================================================================================================//
}

$query_master	= "SELECT COUNTER, NO_BOOKING FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$result 		= $db->query($query_master);
$data			= $result->fetchRow();
$counter		= $data['COUNTER'];
$book			= $data['NO_BOOKING'];

$query_history	= "SELECT NO_REQUEST_APP_STRIPPING, NO_REQUEST_RECEIVING FROM PLAN_REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
$result_ 		= $db->query($query_history);
$row			= $result_->fetchRow();

$qrec = "SELECT NO_REQUEST FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_BOOKING = '$book' AND KEGIATAN = 'REQUEST RECEIVING'";
$rreq = $db->query($qrec)->fetchRow();
//foreach ($data_ as $row){
	$req = $row['NO_REQUEST_APP_STRIPPING'];
	$req_rec = $rreq['NO_REQUEST'];
	$query_del2	= "DELETE FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	$query_del3	= "DELETE FROM PLAN_CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
	$query_del4	= "DELETE FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req_rec'";
	//$query_del5	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	
	$db->query($query_del2);
	$db->query($query_del3);
	$db->query($query_del4);
//}
//foreach ($data_ as $row){
	$req = $row['NO_REQUEST_APP_STRIPPING'];
	$query_del6	= "DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	$query_del7	= "DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req_rec'";
	$query_del8	= "DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
	$db->query($query_del6);
	$db->query($query_del7);
	$db->query($query_del8);
//}
if($counter > 0) {
	$new_counter = $counter-1;
}
else {
	$new_counter = $counter;
}

//$db->query("DELETE FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_BOOKING = '$book'");


//$db2		= getDB("ora");




//$db->query($query_del5);
//$db->query($query_del6);

	echo "OK";

?>