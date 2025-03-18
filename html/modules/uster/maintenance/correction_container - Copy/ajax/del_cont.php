<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 

/*
$sql= "DELETE FROM PETIKEMAS_CABANG.TTD_DEL_REQ WHERE NO_SP2 ='".$_POST['SP2']."'";

$db->query( _sql($sql) );



$sql_u= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET STATUS_CONT='03' WHERE BP_ID ='".$_POST['BP_ID']."' AND CONT_NO_BP='".$_POST['CONT_NO_BP']."'";

$db->query($sql_u);
*/
$query_del	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$history        = "DELETE FROM history_container WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$db->query($history);
if($db->query($query_del))
{
	echo "OK";
}

?>