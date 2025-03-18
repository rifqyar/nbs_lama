<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$no_req2	= $_POST["NO_REQ2"]; 
 

$query_master	= "SELECT COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$result 		= $db->query($query_master);
$data			= $result->fetchRow();
$counter		= $data['COUNTER'];

$query_history	= "SELECT NO_REQUEST FROM history_container WHERE no_container = '$no_cont' and counter = '$counter'";
$result_ 		= $db->query($query_history);
$data_			= $result_->getAll();

foreach ($data_ as $row){
	$req = $row['NO_REQUEST'];
	$query_del2	= "DELETE FROM PLAN_CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	if($db->query($query_del2)){echo "yes"; }
	$query_del3	= "DELETE FROM CONTAINER_STUFFING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	if($db->query($query_del3)){echo "yes2"; }
	$query_del4	= "DELETE FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	if($db->query($query_del4)){echo "yes3"; }
	$query_del5	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$req'";
	if($db->query($query_del5)){echo "yes4"; }
}

$query_del6	= "DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND COUNTER = '$counter'";

//$db2		= getDB("ora");
//==============================================================Interface Ke ICT==========================================================================//
//======================================================================================================================================================//

$sqlbp	= "SELECT NO_BP_ID FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE CONT_NO_BP = '$no_cont' AND NO_REQ_DEL = '$no_req2'";
$rsbp	= $db2->query($sqlbp);
$rowbp	= $rsbp->FetchRow(); 
$bp_id	= $rowbp["NO_BP_ID"];

$sql= "DELETE FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE NO_REQ_DEL = '$no_req2' AND CONT_NO_BP = '$no_cont' AND NO_BP_ID = '$bp_id'  ";

$db2->query($sql);

$sql_u= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET STATUS_CONT='03' WHERE BP_ID ='$bp_id' AND CONT_NO_BP='$no_cont'";

$db2->query($sql_u);

//==============================================================End Of Interface Ke ICT======================================================================//
//==========================================================================================================================================================//
//$db->query($query_del6);
if($db->query($query_del6))
{
	echo "OK";
}

?>