<?php

$db 		= getDB("storage");

$no_cont	= $_GET["NO_CONT"]; 
$no_req		= $_GET["NO_REQ"]; 

$query_del	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";

if($db->query($query_del))
{
		header('Location: '.HOME.APPID.'/edit?no_req='.$no_req);		

}

?>