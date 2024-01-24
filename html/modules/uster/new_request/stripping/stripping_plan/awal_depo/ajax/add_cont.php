<?php

$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req_strip	= $_POST["NO_REQ_STRIP"];
$no_req_rec		= $_POST["NO_REQ_REC"]; 
$hz				= $_POST["BERBAHAYA"]; 
$via			= $_POST["VIA"];
$asal_yard		= $_POST["ID_YARD"]; 
$tgl			= $_POST["TGL"]; 
$after_strip    = $_POST["after_strip"]; 

//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus di Lapangan dan sudah Gate In
//debug($_POST);
						
$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";			
				   
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];


//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;

if($location != "GATO")
{
	//berarti GATI atau sudah placement
	//cek status aktif di CONTAINER_RECEIVING
	
	//asd
	$query_get_yard	= "";
	
	$query_insert_strip	= "INSERT INTO PLAN_CONTAINER_STRIPPING(NO_CONTAINER, 
													   NO_REQUEST,
													   AKTIF,
													   VIA,
													   TGL_BONGKAR,
													   HZ,
													   ID_YARD,
													   AFTER_STRIP
													  ) 
												VALUES('$no_cont', 
													   '$no_req_strip',
													   'Y',
													   '$via',
													   TO_DATE('$tgl','dd-mm-yyyy'),
													   '$hz',
													   $asal_yard,
													   '$after_strip'
													   )";
	// echo $query_insert;
							
	if($db->query($query_insert_strip))
	{
		echo "OK";
	}
}
else 
{
	echo "OUTSIDE";	
}
?>