<?php

if($_SESSION["ID_ROLE"] != NULL){
	if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
	{
		echo "UNAUTORHIZED";
		exit();
	}
}
else {
	exit();
}
$db 			= getDB("storage");

$no_cont		= $_POST["NO_CONT"]; 
$no_req			= $_POST["NO_REQ"]; 
$tgl_real		= $_POST["TGL_REAL"]; 
$id_user        = $_SESSION["LOGGED_STORAGE"];
$id_yard		= $_SESSION["IDYARD_STORAGE"];

$nota_lunas 	= $db->query("select case when lunas = 'YES' THEN 'OK'
				        else 'NO'
				         end  STATUS_NOTA      
				 from nota_stripping where no_request = '$no_req' AND STATUS <> 'BATAL'");
$rnota_lunas 	= $nota_lunas->fetchRow();

if ($rnota_lunas["STATUS_NOTA"] != 'OK') {
	echo "NOTA_FAIL";
	exit();
}

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
		//$row_cek1		= "OK";
		//cek apakah container perpanjangan 
		$q_cek_perp = "SELECT STATUS_REQ FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
		$rc_perp = $db->query($q_cek_perp);
		$rpep = $rc_perp->fetchRow();
		if($rpep["STATUS_REQ"] == "PERP"){
			$query_cek1 = "SELECT END_STACK_PNKN, sysdate, 
                                    CASE 
                                    WHEN TO_DATE('$tgl_real','dd/mm/rrrr') <= END_STACK_PNKN THEN 'OK'
                                    ELSE 'NO'
                                    END AS STATUS,
                                    CASE 
                                    WHEN TO_DATE('$tgl_real','dd/mm/rrrr') = END_STACK_PNKN-1 THEN 'OK'
                                    ELSE 'NO'
                                    END AS WARNING
                            FROM CONTAINER_STRIPPING
                            WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
		} else {
		//cek apakah container tersebut masa strippingnya masih berlaku
		$query_cek1		= "SELECT   TGL_BONGKAR+4, sysdate, 
                                   CASE WHEN CONTAINER_STRIPPING.TGL_SELESAI IS NULL
                                        THEN    CASE 
                                                    WHEN TO_DATE('$tgl_real','dd/mm/rrrr') <= TGL_BONGKAR+4 THEN 'OK'
                                                    ELSE 'NO'
                                                    END                                                  
										ELSE       CASE 
													WHEN TO_DATE('$tgl_real','dd/mm/rrrr') <= TGL_SELESAI THEN 'OK'
													ELSE 'NO'
													END
                                    END  STATUS                                     
                            FROM CONTAINER_STRIPPING                          
                            WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
		}
		$result_cek1	= $db->query($query_cek1);
		$row_cek1		= $result_cek1->fetchRow();
		$row_cek1		= $row_cek1["STATUS"];
	}
	else {
		$row_cek1		= "NOT_OK";
	}
	
	
	
	if($row_cek1 == "OK")
	{
		
		//update status aktif
		$query_update	= "UPDATE CONTAINER_STRIPPING SET AKTIF = 'T', TGL_REALISASI = TO_DATE('$tgl_real','dd/mm/rrrr'),ID_USER_REALISASI = '$id_user' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
		if($db->query($query_update))
		{
			//update status aktif kartu yang masih Y
			$query_update2	= "UPDATE KARTU_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
			$db->query($query_update2);
			/*$qcekpl = $db->query("SELECT NO_BOOKING, COUNTER, LOCATION FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'");
			$rcekpl = $qcekpl->fetchRow();
			$no_booking = $rcekpl['NO_BOOKING'];
			$counter = $rcekpl['COUNTER'];*/

			$qbook = "SELECT NO_BOOKING, COUNTER, TO_CHAR (TGL_UPDATE + interval '10' minute, 'MM/DD/YYYY HH:MI:SS AM') TGL_UPDATE FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req' ORDER BY TGL_UPDATE DESC";
			$rbook = $db->query($qbook);
			$rwbook = $rbook->fetchRow();
			$cur_booking1 = $rwbook["NO_BOOKING"];
			$cur_counter1 = $rwbook["COUNTER"];
			$tgl_update = $rwbook["TGL_UPDATE"];
						
			$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, NO_BOOKING, COUNTER,STATUS_CONT, ID_YARD) 
														  VALUES ('$no_cont','$no_req','REALISASI STRIPPING',SYSDATE,'$id_user','$cur_booking1','$cur_counter1','MTY', '$id_yard')";
			//echo $history;
			//die;
			$db->query($history);
			
			echo "OK";
		}
	}
	else if($row_cek1 == "NO")
	{
		echo "OVER";	
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