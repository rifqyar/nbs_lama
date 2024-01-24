<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$status		= $_POST["STATUS"]; 
$end_perp	= $_POST["TGL_PERP"]; 
$start_perp	= $_POST["TGL_DEV"]; 


$query_cek		= "SELECT a.NO_CONTAINER, b.LOCATION, a.START_STACK, NVL((SELECT NO_CONTAINER FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'), 0) as STATUS FROM CONTAINER_DELIVERY a, MASTER_CONTAINER b WHERE a.NO_CONTAINER = b.NO_CONTAINER AND a.NO_CONTAINER = '$no_cont'";
$result_cek		= $db->query($query_cek);
$row_cek		= $result_cek->fetchRow();
$no_cont		= $row_cek["NO_CONTAINER"];
$location		= $row_cek["LOCATION"];
$start_stack	= $row_cek["START_STACK"];
$req_dev        = $row_cek["STATUS"];

//ECHO $query_cek;
if(($no_cont <> NULL) && ($location == 'YARD') && ($req_dev == 0))
{
	$query_insert	= "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS) VALUES('$no_cont', '$no_req', '$status', '$start_stack','$start_perp')";
        $update         = "UPDATE MASTER_CONTAINER SET LOCATION = 'REQ DELIVERY' WHERE NO_CONTAINER = '$no_cont'";
       // echo $query_insert;
        $db->query($update);
        
	if($db->query($query_insert))
	{
		echo "OK";
	}
} else if (($no_cont <> NULL) && ($location == 'GATI') && ($req_dev == 0))
{
	echo "BLM_PLACEMENT";	
} else if (($no_cont <> NULL) && ($location == 'REQ_DELIVERY') && ($req_dev > 0))
{
        echo "SDH_REQUEST";
} else if (($no_cont <> null) && ($location == 'GATO') && ($req_dev > 0))
{
        echo "NOT_EXIST";
}

        
?>