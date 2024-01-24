<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_kartu	= $_POST["NO_KARTU"]; 
$no_request	= $_POST["NO_REQ"]; 
$type		= substr($no_kartu,0,3);

$no_pol		= $_POST["NO_POL"]; 
$id_yard	= $_SESSION["IDYARD_STORAGE"];
$id_user	= $_SESSION["LOGGED_STORAGE"];

//cek apakah container yang ingin di stripping ada di yard atau tidak
$query_cek_cont		= "SELECT COUNT(1) AS JUM FROM MASTER_CONTAINER INNER JOIN PLACEMENT ON MASTER_CONTAINER.NO_CONTAINER = PLACEMENT.NO_CONTAINER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID WHERE MASTER_CONTAINER.NO_CONTAINER = '$no_cont' AND MASTER_CONTAINER.LOCATION = 'IN_YARD' AND BLOCKING_AREA.ID_YARD_AREA = '$id_yard'";
$result_cek_cont	= $db->query($query_cek_cont);
$row_cek_cont		= $result_cek_cont->fetchRow(); 

if($row_cek_cont["JUM"] > 0)
{
	//cek apakah sudah request stripping atau stuffing belum
	if($type == "STR")
	{
		$query_str 	= "SELECT COUNT(1) AS JUM FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_request' AND NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
	}
	else if($type == "STF")
	{
		$query_str 	= "SELECT COUNT(1) AS JUM FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_request' AND NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";		
	}
	
	$result_str	= $db->query($query_str);
	$row_str	= $result_str->fetchRow();
	//echo $no_request;die;
	if($row_str["JUM"] > 0)
	{
		// terdaftar di karu stripping
		
		//cek apakah karu masih berlaku atau tidak
		if($type == "STR")
		{
			$query_cek1	 	= "SELECT COUNT(1) AS JUM FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_request' AND NO_CONTAINER = '$no_cont' AND SYSDATE <= TGL_APP_SELESAI AND AKTIF = 'Y'";
		}
		else if($type == "STF")
		{
			$query_cek1	 	= "SELECT COUNT(1) AS JUM FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_request' AND NO_CONTAINER = '$no_cont' AND SYSDATE <= TGL_APPROVE+5 AND AKTIF = 'Y'";			
		}
		$result_cek1	= $db->query($query_cek1);
		$row_cek1		= $result_cek1->fetchRow();
		
		
		
		if($row_cek1["JUM"] > 0)
		{
			//insert di gate in
			if(substr($no_kartu,0,3) == "STR")
			{
				$aktif = "Y";	
			}
			else
			{
				$aktif = "T";	
			}
			
			$query_insert	= "INSERT INTO GATE_IN_TRUCK(NO_CONTAINER, NO_KARTU, NO_POL, ID_USER, TGL_IN, ID_YARD, AKTIF) VALUES('$no_cont', '$no_kartu', '$no_pol', '$id_user', SYSDATE, '$id_yard', '$aktif')";
			$result_insert	= $db->query($query_insert);
			
			//update kartu stripping
			 if($type == "STR")
			{
				$query_upd1		= "UPDATE KARTU_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_KARTU = '$no_kartu'";
			}
			else if($type == "STF")
			{
				$query_upd1		= "UPDATE KARTU_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_KARTU = '$no_kartu'";				
			}
			
			$db->query($query_upd1);
			
			/*
			//update status location di master container
			$query_upd2		= "UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'";
			$db->query($query_upd2);
			
			//update status AKTIF di container receiving
			$query_get_req_rec	= "SELECT REQUEST_STRIPPING.* FROM REQUEST_STRIPPING INNER JOIN KARTU_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = KARTU_STRIPPING.NO_REQUEST WHERE KARTU_STRIPPING.NO_KARTU LIKE '$no_kartu' AND KARTU_STRIPPING.NO_CONTAINER LIKE '$no_cont'";
			$result_req_rec		= $db->query($query_get_req_rec);
			$row_req_rec		= $result_req_rec->fetchRow();
			
			$no_req_rec			= $row_req_rec["NO_REQUEST_RECEIVING"];
			
			$query_upd	= "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_rec'";
			$db->query($query_upd);
			*/
			
			
			echo "OK";
		}
		else
		{
			echo "OVER";	
		}
		 
	}
	else
	{
		echo "NO_REQUEST";	
	}
}
else
{
	echo "NOT_IN_YARD";	
}
?>