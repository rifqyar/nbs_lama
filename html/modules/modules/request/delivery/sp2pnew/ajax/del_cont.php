<?php

$db 		= getDB();

$no_cont	= $_POST["CONT"]; 
$no_req		= $_POST["REQ"];

$query_del	= "DELETE FROM req_delivery_d WHERE (TRIM(NO_CONTAINER) = TRIM('$no_cont')) AND (TRIM(NO_REQ_DEV) = TRIM('$no_req'))";


if($db->query($query_del))
{
		echo "OK";
}

?>