<?php

$db 			= getDB("storage");
$no_req_rel     = $_POST["NO_REQ"];
$no_cont		= $_POST["NO_CONT"]; 
$no_req_del		= $_POST["NO_REQ_DEL"];
$no_req_rec		= $_POST["NO_REQ_REC"];
$yard_asal		= $_POST["YARD_ASAL"];
$yard_tujuan	= $_POST["YARD_TUJUAN"];

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

if($location != "GATO")
{
	//container ada di dalam
	//cek status aktif di CONTAINER_RECEIVING
	
	$query_cek		= "SELECT COUNT(1) AS CEK FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y' ";
	$result_cek		= $db->query($query_cek);
	$row_cek 		= $result_cek->fetchRow();
	
	if($row_cek["CEK"] == 0)
	{	
		//get status container terakhir
		$query_get_cont		= "SELECT * FROM CONTAINER_RECEIVING WHERE NO_CONTAINER = '$no_cont' ORDER BY NO_REQUEST DESC";
		$result_get_cont	= $db->query($query_get_cont);
		$row_cont			= $result_get_cont->fetchRow();
		
		$status	= $row_cont["STATUS"];
		$hz		= $row_cont["HZ"];
		
		$query_insert_del	= "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   HZ,
														   AKTIF,
														   ID_YARD
														  ) 
													VALUES('$no_cont', 
														   '$no_req_del',
														   '$status',
														   '$hz',
														   'Y',
														   '$yard_asal'
														   )";
		// echo $query_insert;
								
		if($db->query($query_insert_del))
		{
			$query_insert_rec	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
															   NO_REQUEST,
															   STATUS,
															   HZ,
															   AKTIF,
															   DEPO_TUJUAN
															  ) 
														VALUES('$no_cont', 
															   '$no_req_rec',
															   '$status',
															   '$hz',
															   'Y',
															   '$yard_tujuan'
															   )";
			// echo $query_insert;
									
			if($db->query($query_insert_rec))
			{
				$query_insert_rel	= "INSERT INTO CONTAINER_RELOKASI(NO_CONTAINER, 
															   NO_REQUEST,
															   STATUS,
															   AKTIF 
															  ) 
														VALUES('$no_cont', 
															   '$no_req_rel',
															   '$status',
															   'Y'
															   )";
															   
				$db->query($query_insert_rel);
				echo "OK";
			}
		}
	}
	else
	{
		echo "EXIST";	
	}
}
else 
{
	echo "OUTSIDE";	
}
?>