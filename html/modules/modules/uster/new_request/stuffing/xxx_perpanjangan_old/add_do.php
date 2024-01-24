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
    $no_req	        = $_POST['NO_REQ'];
	$keterangan	    = $_POST['KETERANGAN'];
	
	//$tgl_perp		= $_POST['tgl_perpanjangan'];
	
	//debug($_POST);die;
	//$masa_berlaku 	= 1;
	//create request stripping perpanjangan baru dengan menunjuk ke nomor request yang lama, perp_ke + 1 dari yang lama
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_STUFFING
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
					   AND SUBSTR(request_stuffing.NO_REQUEST,0,3) = 'SFP'";
					   
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
                              PERP_KE)
			   SELECT '$no_req_s',
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
					  'T',
					  'PERP',
					  '$no_req',
					  NVL (PERP_KE + 1, 1)
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
	
		//insert container_stripping satu persatu	
		for($i=1;$i<=$jml;$i++)
		{
			
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
								  TYPE_STUFFING,
                                START_STACK,
                                ASAL_CONT,
                                NO_SEAL,
                                BERAT,
                                KETERANGAN,
                                'PERP',
                                TGL_APPROVE,
								'',
                                TGL_APPROVE+1,
								TO_DATE('$TGL_PERP[$i]','rrrr-mm-dd'),
                                TO_DATE('$TGL_PERP[$i]','rrrr-mm-dd')+1,
                                TO_DATE('$TGL_PERP[$i]','rrrr-mm-dd')+5
							 FROM CONTAINER_STUFFING
							WHERE NO_CONTAINER = '$NO_CONT[$i]'
								  AND NO_REQUEST = '$no_req'
								  AND AKTIF = 'Y'";
			
			
			    $db->query($query_ic);
			
			  
				//non aktifkan container_stuffing dengan nomor request lama
				$query_update	= "UPDATE CONTAINER_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
				$db->query($query_update);

				//non aktifkan status aktif kartu stuffing lama
				$query_update2	= "UPDATE KARTU_STUFFING SET AKTIF = 'T' WHERE NO_CONTAINER = '$NO_CONT[$i]' AND NO_REQUEST = '$no_req'";
				$db->query($query_update2);
			
			  $history = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD) 
                            VALUES ('$NO_CONT[$i]','$no_req_s','PERPANJANGAN STUFFING',SYSDATE,'$ID_USER','$id_yard')";
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