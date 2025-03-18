<?php
// --Model Dokumentasi
// -- Daftar Isi
// 1 - 
	// 1.1 -
	// 		1.1.2 - 
	// 		1.1.3 - 
	// 		1.1.4 - 
	
	// 1.2 - 
	// 		1.2.1 - 
	// 		1.2.2 - 
// 2 - 

        
	$db = getDB("storage");
	
	//$KETERANGAN		= $_POST["keterangan"].' ';
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
    
    
   // $perp_ke     	= $_POST['PERP_KE'];
     $no_cont     	= $_POST['NO_CONT'];
    $no_req	        = $_POST['NO_REQ']; //No Request Sebelumnya
	$keterangan	    = $_POST['KETERANGAN'];
	
	
	//$tgl_perp		= $_POST['tgl_perpanjangan'];
	
	//debug($_POST);die;
	//create request stuffing perpanjangan baru dengan menunjuk ke nomor request yang lama, perp_ke + 1 dari yang lama
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_STUFFING
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
					   AND request_stuffing.NO_REQUEST LIKE '%SFP%'";
					   
	//echo $query_cek;die;

	
	$result_select 	= $db->query($query_cek);
	
	$row_select 	= $result_select->fetchRow();
	
	
	$no_req_		= $row_select["JUM"];
	$month			= $row_select["MONTH"];
	$year			= $row_select["YEAR"];
	$no_req_s		= "SFP".$month.$year.$no_req_;
	
	//debug($row_old);die;
	$query_ir = "INSERT INTO REQUEST_STUFFING (NO_REQUEST,
							  ID_YARD,
							  CETAK_KARTU_SPPS,
							  KETERANGAN,
							  NO_BOOKING,
							  TGL_REQUEST,
							  NO_DOKUMEN,
							  NO_JPB,
							  BPRP,
							  ID_PEMILIK,
							  ID_EMKL,
							  NO_REQUEST_RECEIVING,
							  ID_USER,
							  NO_REQUEST_DELIVERY,
							  KD_CONSIGNEE,
							  KD_PENUMPUKAN_OLEH,
							  NM_KAPAL,
							  NO_PEB,
							  NO_NPE,
							  VOYAGE,
							  STUFFING_DARI,
							  NOTA,
							  STATUS_REQ,
							  PERP_DARI,
							  PERP_KE,
							  ID_PENUMPUKAN,
                              O_VESSEL,
                              O_VOYIN,
                              O_VOYOUT,
                              O_IDVSB,
                              O_REQNBS,
                              DI)
			   SELECT '$no_req_s',
					  ID_YARD,
					  CETAK_KARTU_SPPS,
					  KETERANGAN,
					  NO_BOOKING,
					  SYSDATE,
					  NO_DOKUMEN,
					  NO_JPB,
					  BPRP,
					  ID_PEMILIK,
					  ID_EMKL,
					  NO_REQUEST_RECEIVING,
					  ID_USER,
					  NO_REQUEST_DELIVERY,
					  CASE ID_PENUMPUKAN
						  WHEN NULL THEN KD_CONSIGNEE
						  ELSE ID_PENUMPUKAN
					  END,
				      CASE ID_PENUMPUKAN
						  WHEN NULL THEN KD_CONSIGNEE
						  ELSE ID_PENUMPUKAN
					  END,
					  NM_KAPAL,
					  NO_PEB,
					  NO_NPE,
					  VOYAGE,
					  STUFFING_DARI,
					  'T',
					  'PERP',
					  '$no_req',
					  NVL (PERP_KE + 1, 1),
					  ID_PENUMPUKAN,
                      O_VESSEL,
                      O_VOYIN,
                      O_VOYOUT,
                      O_IDVSB,
                      O_REQNBS,
                      DI
				 FROM REQUEST_STUFFING
				WHERE NO_REQUEST = '$no_req'";
	
	
	if($db->query($query_ir))
	{
		
		$get_jumlah		= "SELECT COUNT(NO_CONTAINER) COUNT FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_req' AND AKTIF = 'Y'";
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
		
		
		
		//2... Cek Apakah No Request Sebelumnya Merupakan request awal atau perpanjangan
		
		$query_cek_perp = "SELECT PERP_DARI
						FROM REQUEST_STUFFING
						WHERE NO_REQUEST='$no_req'";
			
	
			
		$result_perp	= $db->query($query_cek_perp);
		$req_lama		= $result_perp->fetchRow(); 
		$no_req_lama	= $req_lama['PERP_DARI'];
		
		
		
		//echo "tes5";die;
		//print_r($no_req_lama);die;
		  $db->startTransaction();
			if($no_req_lama == NULL) //Ini berarti merupakan perpanjangan pertama
			{
				//echo "perpanjangan pertama ";die;
				//insert container_stripping satu persatu	
				for($i=1;$i<=$jml;$i++)
				{
					if($_POST['TGL_PERP_'.$i] != NULL){
					//CEK TGL GATE
                    
					$tes 			= "select TO_CHAR(TGL_UPDATE,'dd/mm/rrrr') TGL_GATE from history_container where no_container = '$no_cont' AND KEGIATAN = 'BORDER GATE IN' AND TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM history_container WHERE NO_CONTAINER = '$no_cont')";
					$result_tes 	= $db->query($tes);
					$gate			= $result_tes->fetchRow();
					$tgl_gate 		= $gate['TGL_GATE'];
					
					$query_ic	= "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER,
										NO_REQUEST,
										AKTIF,
										HZ,
										COMMODITY,
										KD_COMMODITY,
										TYPE_STUFFING,
										START_STACK,
										ASAL_CONT,
										NO_SEAL,
										BERAT,
										KETERANGAN,
										STATUS_REQ,									
										TGL_APPROVE,
										TGL_GATE,									
										START_PERP_PNKN,
										END_STACK_PNKN,
										TGL_MULAI_FULL,
										TGL_SELESAI_FULL)
								   SELECT NO_CONTAINER,
										  '$no_req_s',
										  'Y',
										  HZ,
										  COMMODITY,
										  KD_COMMODITY,
										  TYPE_STUFFING,
										START_STACK,
										ASAL_CONT,
										NO_SEAL,
										BERAT,
										KETERANGAN,
										'PERP',									
										TGL_APPROVE,
										'',									
										START_PERP_PNKN+1,
										TO_DATE('$TGL_PERP[$i]','dd-mm-rrrr'),
										TGL_MULAI_FULL,
										TGL_SELESAI_FULL
									 FROM CONTAINER_STUFFING
									WHERE NO_CONTAINER = '$NO_CONT[$i]'
										  AND NO_REQUEST = '$no_req'
										  AND AKTIF = 'Y'";
					
					
						$db->query($query_ic);
					
					  
						//non aktifkan container_stuffing dengan nomor request lama
						$query_update	= "UPDATE CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
						$db->query($query_update);

						//non aktifkan status aktif kartu stuffing lama
						// $query_update2	= "UPDATE KARTU_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
						// $db->query($query_update2);
						//cek status terakhir container pada kegiatan sebelumnya
						$q_getstatus = "SELECT STATUS_CONT FROM HISTORY_CONTAINER
											WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
						$r_getstatus = $db->query($q_getstatus);
						$rw_getstatus = $r_getstatus->fetchRow();
						$cur_status  = $rw_getstatus["STATUS_CONT"];
						//cek counter dan status history container
						$q_getcounter = "SELECT NO_BOOKING, COUNTER
											FROM MASTER_CONTAINER 
											WHERE NO_CONTAINER = '$NO_CONT[$i]' ORDER BY COUNTER DESC";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_booking  = $rw_getcounter["NO_BOOKING"];
						$cur_counter  = $rw_getcounter["COUNTER"];
						//cek s
					  
						$history = "INSERT INTO history_container
									(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, COUNTER, NO_BOOKING, STATUS_CONT ) 
							 VALUES ('$NO_CONT[$i]','$no_req_s','PERPANJANGAN STUFFING',SYSDATE,'$ID_USER','$id_yard','$cur_counter','$cur_booking','$cur_status')";
				   // echo $history;die;
					$db->query($history);
				}
            }
			}
			else //Ini berarti merupakan perpanjangan kedua dan seterusnya
			{
				//echo "perpanjangan kedua dan seterusnya ";die;
				for($i=1;$i<=$jml;$i++)
				{
					if($_POST['TGL_PERP_'.$i] != NULL){
					//CEK TGL GATE
					$tes 			= "select TO_CHAR(TGL_UPDATE,'dd/mm/rrrr') TGL_GATE from history_container where no_container = '$no_cont' AND KEGIATAN = 'BORDER GATE IN' AND TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM history_container WHERE NO_CONTAINER = '$no_cont')";
					$result_tes 	= $db->query($tes);
					$gate			= $result_tes->fetchRow();
					$tgl_gate 		= $gate['TGL_GATE'];
					
					$query_ic	= "INSERT INTO CONTAINER_STUFFING (NO_CONTAINER,
										NO_REQUEST,
										AKTIF,
										HZ,
										COMMODITY,
										KD_COMMODITY,
										TYPE_STUFFING,
										START_STACK,
										ASAL_CONT,
										NO_SEAL,
										BERAT,
										KETERANGAN,
										STATUS_REQ,									
										TGL_APPROVE,
										TGL_GATE,									
										START_PERP_PNKN,
										END_STACK_PNKN,
										TGL_MULAI_FULL,
										TGL_SELESAI_FULL)
								   SELECT NO_CONTAINER,
										  '$no_req_s',
										  'Y',
										  HZ,
										  COMMODITY,
										  KD_COMMODITY,
										  TYPE_STUFFING,
										START_STACK,
										ASAL_CONT,
										NO_SEAL,
										BERAT,
										KETERANGAN,
										'PERP',									
										TGL_APPROVE,
										'',								
										END_STACK_PNKN+1,
										TO_DATE('$TGL_PERP[$i]','dd-mm-rrrr'),
										TGL_MULAI_FULL,
										TGL_SELESAI_FULL
									 FROM CONTAINER_STUFFING
									WHERE NO_CONTAINER = '$NO_CONT[$i]'
										  AND NO_REQUEST = '$no_req'
										  AND AKTIF = 'Y'";
					
					
						$db->query($query_ic);
					
					  
						//non aktifkan container_stuffing dengan nomor request lama
						$query_update	= "UPDATE CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
						$db->query($query_update);
						$no_req_plan = str_replace('S', 'P', $no_req);
						$query_update_plan	= "UPDATE PLAN_CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req_plan'";
						$db->query($query_update_plan);

						//non aktifkan status aktif kartu stuffing lama
						// $query_update2	= "UPDATE KARTU_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
						// $db->query($query_update2);
						
						
						
					  //cek status terakhir container pada kegiatan sebelumnya
						$q_getstatus = "SELECT STATUS_CONT FROM HISTORY_CONTAINER
											WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
						$r_getstatus = $db->query($q_getstatus);
						$rw_getstatus = $r_getstatus->fetchRow();
						$cur_status  = $rw_getstatus["STATUS_CONT"];
						//cek counter dan status history container
						$q_getcounter = "SELECT NO_BOOKING, COUNTER
											FROM MASTER_CONTAINER 
											WHERE NO_CONTAINER = '$NO_CONT[$i]' ORDER BY COUNTER DESC";
						$r_getcounter = $db->query($q_getcounter);
						$rw_getcounter = $r_getcounter->fetchRow();
						$cur_booking  = $rw_getcounter["NO_BOOKING"];
						$cur_counter  = $rw_getcounter["COUNTER"];
					  
						$history = "INSERT INTO history_container
									(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, COUNTER, NO_BOOKING, STATUS_CONT ) 
							 VALUES ('$NO_CONT[$i]','$no_req_s','PERPANJANGAN STUFFING',SYSDATE,'$ID_USER','$id_yard','$cur_counter','$cur_booking','$cur_status')";
				   // echo $history;die;
					$db->query($history);
				}
            }
				
			}
            $db->endTransaction();
        /*================================opus-interface===========================================================*/
            
            $qparam = array("in_req_old"=>$no_req,
                            "in_req_new"=>$no_req_s,
                            "in_iduser"=>$ID_USER,
                            "in_ket"=>$KETERANGAN,
                            "p_ErrMsg"=>"",
                           );
            $queryif = "declare begin pack_create_req_stuffing.perpanjangan_stuffing(:in_req_old,:in_req_new,:in_iduser,:in_ket,:p_ErrMsg); end;";
            $db->query($queryif,$qparam);
            $msg = $qparam["p_ErrMsg"];
        /*================================opus-interface===========================================================*/
        if($msg == 'OK'){
            header('Location: '.HOME.APPID);    
        }
        else {
            header('Location: '.HOME.APPID.'?ps=error');
        }
		
		
	}
	else
	{
		echo "gagal insert req strip";
	}
	
	
?>