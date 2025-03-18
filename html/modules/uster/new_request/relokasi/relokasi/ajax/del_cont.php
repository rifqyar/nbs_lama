<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req	= $_POST["NO_REQ"]; 
$no_req_del	= $_POST["no_req_del"]; 
$no_req_rec	= $_POST["no_req_rec"]; 

$query_del	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_del'";

if($db->query($query_del))
{
	$query_rec	= "DELETE FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_rec'";
	$query_rel	= "DELETE FROM CONTAINER_RELOKASI WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
	$db->query($query_rec);
	$db->query($query_rel);
	
	$q_master = $db->query("SELECT * FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'");
	$res_master = $q_master->fetchRow();
	$q_book = $res_master["COUNTER"];
	$query_del6	= "DELETE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND COUNTER = '$q_book'";
	if($db->query($query_del6)){
		echo "OK";
	}
	
}

?>