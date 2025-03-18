<?php
        
		$db = getDB("storage");
		
		//$KETERANGAN		= $_POST["keterangan"].' ';
		$ID_USER		= $_SESSION["LOGGED_STORAGE"];
		$id_yard		= $_SESSION["IDYARD_STORAGE"];
		
		
	   // $perp_ke     	= $_POST['PERP_KE'];
		$no_cont     	= $_POST['NO_CONT'];
		$no_req	        = $_POST['NO_REQ'];
		$keterangan	    = $_POST['KETERANGAN'];
	
	
		$get_jumlah		= "SELECT COUNT(NO_CONTAINER) COUNT FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
		$result_cont_ 	= $db->query($get_jumlah);
		$count			= $result_cont_->fetchRow();
		
		$jml 			= $count['COUNT'];
		
		for($i=1;$i<=$jml;$i++){
			if($_POST['TGL_PERP_'.$i] != NULL){
			$NO_CONT[$i] = $_POST['NO_CONT_'.$i];
			$TGL_PERP[$i] = $_POST['TGL_PERP_'.$i];
			
			// echo $NO_CONT[$i]."--";
			//echo $TGL_PERP[$i]."<br/>";
			}
		}
	
		//insert container_stripping satu persatu	
		for($i=1;$i<=$jml;$i++)
		{
			$query_cek		= "SELECT NVL(COUNT(NO_CONTAINER),0) JML FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$NO_CONT[$i]'";
			$result_cek		= $db->query($query_cek);
			$row_cek		= $result_cek->fetchRow();
			
			$jum			= $row_cek['JML'];
			
			if ($jum == 0){
					$query_select		= "SELECT HZ, VIA, VOYAGE, TO_DATE(TGL_BONGKAR, 'dd/mm/yyyy') TGL_BONGKAR, AFTER_STRIP, TO_DATE(TGL_APPROVE, 'dd/mm/yyyy') TGL_APPROVE,TO_DATE(TGL_APP_SELESAI, 'dd/mm/yyyy')+1 START_PERP, COMMODITY  FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
					$result_cont		= $db->query($query_select);
					$row_cont			= $result_cont->fetchRow();
					
					//debug($row_cont);die;
					$hz					= $row_cont["HZ"];
					$via				= $row_cont["VIA"];
					$voy				= $row_cont["VOYAGE"];
					$tgl_bongkar		= $row_cont["TGL_BONGKAR"];
					$tgl_approve		= $row_cont["TGL_APPROVE"];
					$after_strip		= $row_cont["AFTER_STRIP"];
					$start_perp			= $row_cont["START_PERP"];
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
																	   AFTER_STRIP,
																	   TGL_APPROVE,
																	   START_PERP_PNKN,
																	   END_STACK_PNKN,
																	   COMMODITY
																	  ) 
																VALUES('$NO_CONT[$i]', 
																	   '$no_req',
																	   'Y',
																	   '$via',
																	   '$hz',
																	   '$voy',
																	   TO_DATE('$tgl_bongkar','dd-mm-rrrr'),
																	   '$after_strip',
																	   TO_DATE('$tgl_approve','dd-mm-rrrr'),
																		TO_DATE('$start_perp','dd-mm-rrrr'),
																	   TO_DATE('$TGL_PERP[$i]','rrrr-mm-dd'),
																	   '$commodity'
																	   )";
					
					
					
					$db->query($query_insert_strip);
					  $history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD) 
															  VALUES ('$item','$no_req_s','PERPANJANGAN STRIPPING',SYSDATE,'$ID_USER','$id_yard')";
				   // echo $history;die;
					$db->query($history);
				} else {
					$query_insert_strip	= "UPDATE CONTAINER_STRIPPING SET END_STACK_PNKN = TO_DATE('$TGL_PERP[$i]','rrrr-mm-dd')
												WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
					
					$db->query($query_insert_strip);
				}
		}
		
		header('Location: '.HOME.APPID);	
	
?>