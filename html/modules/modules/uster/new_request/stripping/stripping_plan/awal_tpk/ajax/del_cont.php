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

$query_master	= "SELECT COUNTER, NO_BOOKING FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$result 		= $db->query($query_master);
$data			= $result->fetchRow();
$counter		= $data['COUNTER'];
$book			= $data['NO_BOOKING'];

$query_history	= "SELECT NO_REQUEST_APP_STRIPPING, NO_REQUEST_RECEIVING FROM PLAN_REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
$result_ 		= $db->query($query_history);
$row			= $result_->fetchRow();

//foreach ($data_ as $row){
	$req = $row['NO_REQUEST_APP_STRIPPING'];
	$req_rec = $row['NO_REQUEST_RECEIVING'];
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


$db2		= getDB("ora");
$cekdul = $db->query("SELECT NO_CONTAINER FROM CONTAINER_STRIPPING WHERE NO_REQUEST = REPLACE('$no_req','P','S')");
$rdul = $cekdul->fetchRow();
$apa = $rdul["NO_CONTAINER"];
//if($apa != NULL){
//==============================================================Interface Ke ICT==========================================================================//
//======================================================================================================================================================//

$sqlbp	= "SELECT NO_BP_ID FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE CONT_NO_BP = '$no_cont' AND NO_REQ_DEL = '$no_req2'";
$rsbp	= $db->query($sqlbp);
$rowbp	= $rsbp->FetchRow(); 
$bp_id	= $rowbp["NO_BP_ID"];

$sql= "DELETE FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE NO_REQ_DEL = '$no_req2' AND CONT_NO_BP = '$no_cont' AND NO_BP_ID = '$bp_id'  ";

$db2->query($sql);

$sql_u= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET STATUS_CONT='03' WHERE BP_ID ='$bp_id' AND CONT_NO_BP='$no_cont'";

$db2->query($sql_u);
//}
//==============================================================End Of Interface Ke ICT======================================================================//
//==========================================================================================================================================================//



//$db->query($query_del5);
//$db->query($query_del6);

	echo "OK";

?>