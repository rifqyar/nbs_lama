<?php

$db 		= getDB();

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];

$query_del	= "DELETE FROM REQ_TRANSHIPMENT_D WHERE NO_CONTAINER = '$no_cont' AND ID_REQ = '$no_req'";

if($db->query($query_del))
{
		echo "OK";
}

?>