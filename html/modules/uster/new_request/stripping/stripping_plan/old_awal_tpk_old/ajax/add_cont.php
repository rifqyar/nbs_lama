<?php

$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req_strip	= $_POST["NO_REQ_STRIP"];
$no_req_rec		= $_POST["NO_REQ_REC"]; 
$hz				= $_POST["BERBAHAYA"]; 
$via			= $_POST["VIA"];
$voy			= $_POST["VOY"]; 
$tgl			= $_POST["TGL"]; 
//print_r($status);die;
//Cek status kontainer, yg bisa direquest hanya yg berstatus di Lapangan dan sudah Gate In



						
$query_cek2		= "SELECT LOCATION 
				   FROM MASTER_CONTAINER 
				   WHERE NO_CONTAINER = '$no_cont' ";			
				   
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
$location		= $row_cek2["LOCATION"];


//$aktif			= $row_cek["AKTIF"];
//print_r($jum.$location);die;

if($location == "GATO")
{
	//berarti sekarang ada di luar, belum gate in
	//cek status aktif di CONTAINER_RECEIVING
	
	$query_cek		= "SELECT COUNT(1) AS CEK FROM CONTAINER_RECEIVING FULL OUTER JOIN CONTAINER_STRIPPING ON CONTAINER_RECEIVING.NO_CONTAINER = CONTAINER_STRIPPING.NO_CONTAINER WHERE CONTAINER_RECEIVING.NO_CONTAINER = '$no_cont' AND (CONTAINER_RECEIVING.AKTIF = 'Y' OR CONTAINER_STRIPPING.AKTIF = 'Y')";
	$result_cek		= $db->query($query_cek);
	$row_cek 		= $result_cek->fetchRow();
	
	if($row_cek["CEK"] == 0)
	{	
	
		//-----------------------------------------------------------INSERT KE DETIL DELIVERY SIMOP
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//-------------------------------------------------------------------------------------------
		
		
		$query_insert_rec	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   HZ,
														   AKTIF
														  ) 
													VALUES('$no_cont', 
														   '$no_req_rec',
														   'FULL',
														   '$hz',
														   'Y'
														   )";
		// echo $query_insert;
								
		if($db->query($query_insert_rec))
		{
			$query_insert_strip	= "INSERT INTO CONTAINER_STRIPPING(NO_CONTAINER, 
															   NO_REQUEST,
															   AKTIF,
															   VIA,
															   HZ,
															   VOYAGE,
															   TGL_BONGKAR
															  ) 
														VALUES('$no_cont', 
															   '$no_req_strip',
															   'Y',
															   '$via',
															   '$hz',
															   '$voy',
															   TO_DATE('$tgl','dd-mm-yyyy')
															   )";
			// echo $query_insert;
									
			if($db->query($query_insert_strip))
			{
				echo "OK";
			}
		}
	}
	else
	{
		echo "AKTIF_RECEIVING";	
	}
}
else 
{
	echo "EXIST";	
}
?>