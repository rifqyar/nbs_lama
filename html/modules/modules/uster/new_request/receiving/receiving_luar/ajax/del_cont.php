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
$query_counter = "SELECT COUNTER,NO_BOOKING FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
$r_counter = $db->query($query_counter);
$rw_counter = $r_counter->fetchRow();
$counter = $rw_counter["COUNTER"];
$book = $rw_counter["NO_BOOKING"];

$db->query("DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND COUNTER = '$counter' AND NO_REQUEST = '$no_req'");
						
$query_del	= "DELETE FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";

if($db->query($query_del))
{
	echo "OK";
}

?>