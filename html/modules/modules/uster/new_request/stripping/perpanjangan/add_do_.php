<?php
        
	$db = getDB("storage");
	
	$KETERANGAN		= $_POST["keterangan"].' ';
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
        
    $perp_ke     	= $_POST['PERP_KE'];
    $no_req     	= $_POST['NO_REQ'];
    $con_perp	    = $_POST['select2'];
	
//	$ID_PEMILIK		= $_POST['ID_PNMT'];
//	$ID_EMKL		= $_POST['ID_EMKL'];
	
	$KD_EMKL		= $_POST['ID_PNMT'];
	$KD_PNMT		= $_POST['ID_EMKL'];
	
	$tgl_perp		= $_POST['tgl_perpanjangan'];
	
	//debug($_POST);die;
	$masa_berlaku 	= 1;
	//create request stripping baru dengan menunjuk ke nomor request yang lama, perp_ke + 1 dari yang lama
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_STRIPPING
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
					   AND SUBSTR(request_stripping.NO_REQUEST,0,3) = 'STP'";
					   
	//echo $query_cek;die;
	
	$result_select 	= $db->query($query_cek);
	
	$row_select 	= $result_select->fetchRow();
	
	
	$no_req_		= $row_select["JUM"];
	$month			= $row_select["MONTH"];
	$year			= $row_select["YEAR"];
	$no_req_s		= "STP".$month.$year.$no_req_;
	
	//echo $no_req_s;die;
	/*
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
	*/
	
	$query_get_old	= "SELECT a.TYPE_STRIPPING, a.NO_DO, a.NO_BL, NVL(a.PERP_KE,1) PERP_KE, a.NO_REQUEST_RECEIVING FROM REQUEST_STRIPPING a WHERE NO_REQUEST = '$no_req'";
	$result_get 	= $db->query($query_get_old);
	
	//echo $query_get_old;die;
	$row_old 		= $result_get->fetchRow();
	
	//debug($row_old);die;
	$query_req 	= "INSERT INTO REQUEST_STRIPPING(NO_REQUEST, 
												 KD_CONSIGNEE, 
												 KD_PENUMPUKAN_OLEH,
												 KETERANGAN,
												 TGL_REQUEST, 
												 TGL_APPROVE,
												 ID_USER,
												 TYPE_STRIPPING,
												 NO_DO,
												 NO_BL,
												 NOTA,
												 STRIPPING_DARI,
												 NO_REQUEST_PLAN,
												 NO_REQUEST_RECEIVING,
												 PERP_KE,
												 PERP_DARI,
												 ID_YARD,
												 STATUS_REQ,
												 PERP_SD
												 ) 
										VALUES(	'$no_req_s', 
												'$KD_PNMT', 
												'$KD_PNMT', 
												'$KETERANGAN',
												SYSDATE, 
												SYSDATE,
												'$ID_USER',
												'".$row_old["TYPE_STRIPPING"]."',
												'".$row_old["NO_DO"]."',
												'".$row_old["NO_BL"]."',
												'T',
												'',
												'$no_req',
												'".$row_old["NO_REQUEST_RECEIVING"]."',
												'".$row_old["PERP_KE"]."'+1,
												'$no_req',
												'$id_yard',
												'PERP',
												TO_DATE('$tgl_perp','rrrr/mm/dd')) ";
	
	
	if($db->query($query_req))
	{
		
		//insert container_stripping satu persatu	
		foreach($con_perp as $cont)
		{
			$query_select		= "SELECT HZ, VIA, VOYAGE, TO_DATE(TGL_BONGKAR, 'dd/mm/yyyy') TGL_BONGKAR, AFTER_STRIP, TO_DATE(TGL_APPROVE, 'dd/mm/yyyy') TGL_APPROVE,TO_DATE(TGL_APP_SELESAI, 'dd/mm/yyyy')+1 START_PERP  FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$cont' AND NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
			$result_cont		= $db->query($query_select);
			$row_cont			= $result_cont->fetchRow();
			
			//debug($row_cont);die;
			$hz				= $row_cont["HZ"];
			$via			= $row_cont["VIA"];
			$voy			= $row_cont["VOYAGE"];
			$tgl_bongkar	= $row_cont["TGL_BONGKAR"];
			$tgl_approve	= $row_cont["TGL_APPROVE"];
			$after_strip	= $row_cont["AFTER_STRIP"];
			$start_perp		= $row_cont["START_PERP"];
			
					
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
															   HZ,
															   VOYAGE,
															   TGL_BONGKAR,
															   AFTER_STRIP,
															   TGL_APPROVE,
															   START_PERP_PNKN,
															   END_STACK_PNKN
															  ) 
														VALUES('$cont', 
															   '$no_req_s',
															   'Y',
															   '$via',
															   '$hz',
															   '$voy',
															   TO_DATE('$tgl_bongkar','dd-mm-rrrr'),
															   '$after_strip',
															   TO_DATE('$tgl_approve','dd-mm-rrrr'),
															    TO_DATE('$start_perp','dd-mm-rrrr'),
															   TO_DATE('$tgl_perp','rrrr-mm-dd')
															   )";
			
			
			
			$db->query($query_insert_strip);
			  $history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD) 
                                                      VALUES ('$item','$no_req_s','PERP PNKN STRIPPING',SYSDATE,'$ID_USER','$id_yard')";
           // echo $history;die;
            $db->query($history);
		}
		
		header('Location: '.HOME.APPID);	
	}
	else
	{
		echo "gagal insert req strip";
	}
	
?>