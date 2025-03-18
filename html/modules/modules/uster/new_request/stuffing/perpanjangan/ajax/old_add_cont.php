<?php

$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req_stuf	= $_POST["NO_REQ_STUF"];
$no_req_rec		= $_POST["NO_REQ_REC"]; 
$no_req_del		= $_POST["NO_REQ_DEL"]; 
$hz				= $_POST["BERBAHAYA"]; 
$commodity		= $_POST["COMMODITY"];
$jenis		= $_POST["JENIS"];
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
	//berarti sekarang ada di luar, belum gate in, dapat di masukkan ke detil container receiving
	//cek status aktif di CONTAINER_RECEIVING
	
	$query_cek		= "SELECT COUNT(1) AS CEK FROM CONTAINER_RECEIVING FULL OUTER JOIN CONTAINER_STUFFING ON CONTAINER_RECEIVING.NO_CONTAINER = CONTAINER_STUFFING.NO_CONTAINER WHERE CONTAINER_RECEIVING.NO_CONTAINER = '$no_cont' AND (CONTAINER_RECEIVING.AKTIF = 'Y' OR CONTAINER_STUFFING.AKTIF = 'Y')";
	$result_cek		= $db->query($query_cek);
	$row_cek 		= $result_cek->fetchRow();
	
	if($row_cek["CEK"] == 0)
	{	
	
		//-----------------------------------------------------------INSERT KE DETIL DELIVERY SIMOP
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//-------------------------------------------------------------------------------------------
		
		//-----------------------------------------------------------INSERT KE DETIL MUAT SIMOP
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		//-------------------------------------------------------------------------------------------
		
		// insert ke tabel container receiving uster
		
		$query_insert_rec	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   HZ,
														   AKTIF
														  ) 
													VALUES('$no_cont', 
														   '$no_req_rec',
														   'EMPTY',
														   'N',
														   'Y'
														   )";
		// echo $query_insert;
		//-------------------------------------------------------------------------------------------
								
		if($db->query($query_insert_rec))
		{
			// insert ke container stuffing uster
			$query_insert_strip	= "INSERT INTO CONTAINER_STUFFING(NO_CONTAINER, 
															   NO_REQUEST,
															   AKTIF,
															   HZ,
															   COMMODITY,
															   TYPE_STUFFING
															  ) 
														VALUES('$no_cont', 
															   '$no_req_stuf',
															   'Y',
															   '$hz',
															   '$commodity',
															   $jenis
															   )";
			// echo $query_insert;
			//----------------------------------------------------------------------------------------						
			if($db->query($query_insert_strip))
			{
				//insert ke container delivery uster
				
				$query_insert_del	= "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, 
														   NO_REQUEST,
														   STATUS,
														   HZ,
														   AKTIF,
														   HAULAGE_REC,
														   HAULAGE_DEV
														  ) 
													VALUES('$no_cont', 
														   '$no_req_del',
														   'FULL',
														   '$hz',
														   'Y',
														   'Y',
														   'N'
														   )";
				
				//-----------------------------------------------------------------------------------------------
				
				if($db->query($query_insert_del))
				{
					echo "OK";
				}
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