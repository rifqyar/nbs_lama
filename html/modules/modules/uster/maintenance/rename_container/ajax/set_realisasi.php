<?php

$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$tgl_real		= $_POST["TGL_REAL"]; 
$id_user        = $_SESSION["LOGGED_STORAGE"];

//cek apakah container tersebut status strippingnya aktif
$query_cek2		= "SELECT COUNT(1) AS JUM FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
$result_cek2	= $db->query($query_cek2);
$row_cek2		= $result_cek2->fetchRow();
if($row_cek2["JUM"] > 0)
{ 
	//cek apakah container tersebut masa strippingnya masih berlaku
	$query_cek1		= "SELECT LOCATION
						FROM MASTER_CONTAINER
						WHERE NO_CONTAINER = '$no_cont'";
	
	$result_cek1	= $db->query($query_cek1);
	$row_cek		= $result_cek1->fetchRow();
	$row_cek1		= $row_cek["LOCATION"];
	if($row_cek1 == 'IN_YARD'){
		$row_cek1		= "OK";
	}
	else {
		$row_cek1		= "NOT_OK";
	}
	
	
	
	if($row_cek1 == "OK")
	{
		
		//update status aktif
		$query_update	= "UPDATE CONTAINER_STRIPPING SET AKTIF = 'T', TGL_REALISASI = TO_DATE('$tgl_real','yyyy/mm/dd'),ID_USER_REALISASI = '$id_user' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
		if($db->query($query_update))
		{
			//update status aktif kartu yang masih Y
			$query_update2	= "UPDATE KARTU_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
			$db->query($query_update2);
			$qcekpl = $db->query("SELECT NO_BOOKING, COUNTER, LOCATION FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'");
			$rcekpl = $qcekpl->fetchRow();
			$no_booking = $rcekpl['NO_BOOKING'];
			$counter = $rcekpl['COUNTER'];
						
			$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER,STATUS_CONT) 
														  VALUES ('$no_cont','$no_req','REALISASI STRIPPING',SYSDATE,'$id_user','$no_booking','$counter','MTY')";
			//echo $history;
			//die;
			$db->query($history);
			
			echo "OK";
		}
	}
	else
	{
		echo "NOT_OK";	
	}
}
else
{
	echo "NOT_AKTIF";	
}
?>