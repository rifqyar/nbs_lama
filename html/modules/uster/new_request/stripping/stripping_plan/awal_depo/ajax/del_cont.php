<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 

//echo "DELETE FROM PLAN_CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";die;
$query_del	= "DELETE FROM PLAN_CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";

if($db->query($query_del))
{
	echo "OK";
}

?>