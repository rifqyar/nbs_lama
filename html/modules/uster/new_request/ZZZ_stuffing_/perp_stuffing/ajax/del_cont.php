<?php

$db 		= getDB("storage");

$no_cont	= $_GET["no_cont"]; 
$no_req		= $_GET["no_req"]; 

$query_del	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";

if($db->query($query_del))
{
	echo "OK";
}

?>