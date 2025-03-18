<?php
        
	$db = getDB("storage");
	
	//$KETERANGAN		= $_POST["keterangan"].' ';
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
    
    
   // $perp_ke     	= $_POST['PERP_KE'];
     $no_cont     	= $_POST['NO_CONT'];
    $no_req	        = $_POST['NO_REQ'];
	$keterangan	    = $_POST['KETERANGAN'];
	
	//$tgl_perp		= $_POST['tgl_perpanjangan'];
	/* for($i=1;$i<=$_POST['total'];$i++){
			if($_POST['TGL_PERP_'.$i] != NULL){
			$NO_CONT[$i] = $_POST['NO_CONT_'.$i];
			$TGL_PERP[$i] = $_POST['TGL_PERP_'.$i];
			
			 echo $NO_CONT[$i]."--";
			echo $TGL_PERP[$i]."<br/>";
			}
		} */
		
		 //$jml_new = count($TGL_PERP); 
	//debug($_POST);die;
	//$masa_berlaku 	= 1;
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
	
	$query_get_old	= "SELECT a.KD_CONSIGNEE, a.CONSIGNEE_PERSONAL, a.NO_REQUEST_PLAN,a.TYPE_STRIPPING, a.NO_DO, a.NO_BL, NVL(a.PERP_KE,1) PERP_KE, a.NO_REQUEST_RECEIVING FROM REQUEST_STRIPPING a WHERE NO_REQUEST = '$no_req'";
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
												 CONSIGNEE_PERSONAL
												 ) 
										VALUES(	'$no_req_s', 
												'".$row_old["KD_CONSIGNEE"]."',
												'".$row_old["KD_CONSIGNEE"]."',
												'$keterangan',
												SYSDATE, 
												SYSDATE,
												'$ID_USER',
												'".$row_old["TYPE_STRIPPING"]."',
												'".$row_old["NO_DO"]."',
												'".$row_old["NO_BL"]."',
												'T',
												'',
												'".$row_old["NO_REQUEST_PLAN"]."',
												'".$row_old["NO_REQUEST_RECEIVING"]."',
												".$row_old["PERP_KE"]."+1,
												'$no_req',
												'$id_yard',
												'PERP',
												'".$row_old["CONSIGNEE_PERSONAL"]."') ";
	
	
	if($db->query($query_req))
	{
		$get_jumlah		= "SELECT COUNT(NO_CONTAINER) COUNT FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
		$result_cont_ 	= $db->query($get_jumlah);
		$count			= $result_cont_->fetchRow();
		
		$jml 			= $count['COUNT'];
		
		for($i=1;$i<=$jml;$i++){
			if($_POST['TGL_PERP_'.$i] != NULL){
			$NO_CONT[$i] = $_POST['NO_CONT_'.$i];
			$TGL_PERP[$i] = $_POST['TGL_PERP_'.$i];
			$START_PERP_PNKN[$i] = $_POST['START_PERP_PNKN'.$i];
			
			// echo $NO_CONT[$i]."--";
			//echo $TGL_PERP[$i]."<br/>";
			}
		}
	
		//insert container_stripping satu persatu	
		for($i=1;$i<=$jml;$i++)
		{
			$query_select		= "SELECT HZ, VIA, VOYAGE, TO_DATE(TGL_BONGKAR, 'dd/mm/yyyy') TGL_BONGKAR, TO_DATE(TGL_SELESAI, 'dd/mm/yyyy') TGL_SELESAI, AFTER_STRIP, TO_DATE(TGL_APPROVE, 'dd/mm/yyyy') TGL_APPROVE,
			 CASE WHEN CONTAINER_STRIPPING.TGL_SELESAI  IS NULL
                             THEN TO_DATE (CONTAINER_STRIPPING.TGL_BONGKAR + 5, 'dd/mm/rrrr')
                             ELSE
                              TO_DATE (CONTAINER_STRIPPING.TGL_SELESAI + 1, 'dd/mm/rrrr')
                             END AS START_PERP, 
			TO_DATE(END_STACK_PNKN, 'dd/mm/yyyy')+1 START_PERP_I,
			COMMODITY  FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
			$result_cont		= $db->query($query_select);
			$row_cont			= $result_cont->fetchRow();
			
			//debug($row_cont);die;
			$hz					= $row_cont["HZ"];
			$via				= $row_cont["VIA"];
			$voy				= $row_cont["VOYAGE"];
			$tgl_bongkar		= $row_cont["TGL_BONGKAR"];
			$tgl_selesai		= $row_cont["TGL_SELESAI"];
			$tgl_approve		= $row_cont["TGL_APPROVE"];
			$after_strip		= $row_cont["AFTER_STRIP"];
			$start_perp			= $row_cont["START_PERP"];
			if($row_old["PERP_KE"] > 1){
				$start_perp			= $row_cont["START_PERP_I"];				
			}
			else {
				$start_perp			= $row_cont["START_PERP"];
			}
			$commodity			= $row_cont["COMMODITY"];
			
					
			//non aktifkan container_stripping dengan nomor request lama
			$query_update	= "UPDATE CONTAINER_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
			$db->query($query_update);

			//non aktifkan status aktif kartu stripping lama
			$query_update2	= "UPDATE KARTU_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
			$db->query($query_update2);
			
			$query_insert_strip	= "INSERT INTO CONTAINER_STRIPPING(NO_CONTAINER, 
															   NO_REQUEST,
															   AKTIF,
															   VIA,
															   HZ,
															   VOYAGE,
															   TGL_BONGKAR,
																TGL_SELESAI,
															   AFTER_STRIP,
															   TGL_APPROVE,
															   START_PERP_PNKN,
															   END_STACK_PNKN,
															   COMMODITY
															  ) 
														VALUES('$NO_CONT[$i]', 
															   '$no_req_s',
															   'Y',
															   '$via',
															   '$hz',
															   '$voy',
															   TO_DATE('$tgl_bongkar','dd-mm-rrrr'),
																TO_DATE('$tgl_selesai','dd-mm-rrrr'),
															   '$after_strip',
															   TO_DATE('$tgl_approve','dd-mm-rrrr'),
															    TO_DATE('$start_perp','dd-mm-rrrr'),
															   TO_DATE('$TGL_PERP[$i]','rrrr-mm-dd'),
															   '$commodity'
															   )";
			
			
			
			$db->query($query_insert_strip);
			$q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$NO_CONT[$i]'";
			$r_getcounter2 = $db->query($q_getcounter2);
			$rw_getcounter2 = $r_getcounter2->fetchRow();
			$cur_counter2 = $rw_getcounter2["COUNTER"];
			$cur_booking2 = $rw_getcounter2["NO_BOOKING"];
			  $history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD,STATUS_CONT, NO_BOOKING, COUNTER) 
                                                      VALUES ('$NO_CONT[$i]','$no_req_s','PERPANJANGAN STRIPPING',SYSDATE,'$ID_USER','$id_yard','FCL','$cur_booking2','$cur_counter2')";
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