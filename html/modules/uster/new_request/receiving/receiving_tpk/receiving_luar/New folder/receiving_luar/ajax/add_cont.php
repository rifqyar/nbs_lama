<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$status		= $_POST["STATUS"]; 
$berbahaya	= $_POST["BERBAHAYA"];
//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus GATO dan belum direquest (belum aktif)
$query_cek1		= "SELECT AKTIF
				   FROM CONTAINER_RECEIVING 
				   WHERE NO_CONTAINER = '$no_cont' 
				   ";
						
$result_cek1	= $db->query($query_cek1);
$row_cek1		= $result_cek1->fetchRow();
//$jum			= $row_cek1["JUM"];
$aktif			= $row_cek1["AKTIF"];
			

$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";			
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];


//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;

if($status == NULL)
{
	
		echo "STATUS";
	
}
else if($berbahaya == NULL)
{
	
		echo "BERBAHAYA";
	
}
else if(($aktif != "Y") && ($status != NULL) && ($location == "GATO") && ($berbahaya != NULL))
{
	$query_insert	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
													   NO_REQUEST,
													   STATUS,
													   AKTIF,
													   HZ) 
												VALUES('$no_cont', 
													   '$no_req',
													   '$status',
													   'Y',
													   '$berbahaya')";
       // echo $query_insert;
     						
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