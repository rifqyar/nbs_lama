<?php
        
	$db = getDB("storage");
	
	$KETERANGAN		= $_POST["keterangan"].' ';
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
        
    $perp_ke     	= $_POST['PERP_KE'];
    $no_req     	= $_POST['NO_REQ'];
    $con_perp	    = $_POST['select2'];
	
	$ID_PEMILIK		= $_POST['ID_PNMT'];
	$ID_EMKL		= $_POST['ID_EMKL'];
	
	
	//create request stripping baru dengan menunjuk ke nomor request yang lama, perp_ke + 1 dari yang lama
	$query_select ="SELECT LPAD(COUNT(1)+1,4,0) AS NO_REQUEST,
							   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							   TO_CHAR(SYSDATE, 'YY') AS YEAR 
						FROM REQUEST_STRIPPING
						WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
							";
					   
	$result_select 	= $db->query($query_select);
	$row_select 	= $result_select->fetchRow();
	$no_req_s		= $row_select["NO_REQUEST"];
	$month			= $row_select["MONTH"];
	$year			= $row_select["YEAR"];
	$perp			= $perp_ke + 1;
	
	$no_req_str		= "STR".$month.$year.$no_req_s;
	
	
	$query_req 	= "INSERT INTO REQUEST_STRIPPING(NO_REQUEST, 
												 ID_PEMILIK, 
												 TGL_REQUEST, 
												 KETERANGAN, 
												 ID_USER,
												 ID_YARD,
												 ID_EMKL,
												 CETAK_KARTU_SPPS,
												 PERP_KE,
												 PERP_DARI) 
										VALUES(	'$no_req_str', 
												$ID_PEMILIK, 
												SYSDATE, 
												'$KETERANGAN',
												$ID_USER,
												$id_yard,
												$ID_EMKL,
												0,
												$perp,
												'$no_req' ) ";
	
	if($db->query($query_req))
	{
		
		//insert container_stripping satu persatu	
		foreach($con_perp as $cont)
		{
			$query_select		= "SELECT * FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$cont' AND NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
			$result_cont		= $db->query($query_select);
			$row_cont			= $result_cont->fetchRow();
			
			$via			= $row_cont["VIA"];
			$voy			= $row_cont["VOYAGE"];
			$tgl_bongkar	= $row_cont["TGL_BONGKAR"];
			
					
			//non aktifkan container_stripping dengan nomor request lama
			$query_update	= "UPDATE CONTAINER_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$cont' AND NO_REQUEST = '$no_req'";
			$db->query($query_update);

			//non aktifkan status aktif kartu stripping lama
			$query_update2	= "UPDATE KARTU_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$cont' AND NO_REQUEST = '$no_req'";
			$db->query($query_update2);
			
			$query_insert_strip	= "INSERT INTO CONTAINER_STRIPPING(NO_CONTAINER, 
															   NO_REQUEST,
															   AKTIF,
															   VIA,
															   VOYAGE,
															   TGL_BONGKAR
															  ) 
														VALUES('$cont', 
															   '$no_req_str',
															   'Y',
															   '$via',
															   '$voy',
															   TO_DATE('$tgl_bongkar','dd-mm-yyyy')
															   )";
			$db->query($query_insert_strip);
		}
		
		header('Location: '.HOME.APPID);	
	}
	
?>