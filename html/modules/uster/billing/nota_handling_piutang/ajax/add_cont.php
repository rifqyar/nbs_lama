<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$status		= $_POST["STATUS"]; 

$query_cek		= "SELECT COUNT(1) AS JUM FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$jum			= $row_cek["JUM"];

if($jum <= 0)
{
	$query_insert	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, NO_REQUEST, STATUS) VALUES('$no_cont', '$no_req', '$status')";

	if($db->query($query_insert))
	{
		echo "OK";
	}
}
else
{
	echo "EXIST";	
}
?>