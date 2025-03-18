<?php
        // echo "dama";die;
	     $db = getDB("storage");

    
     //  echo count($_POST['select2']);die;
        $tgl_perp   = $_POST['tgl_perpanjangan'];
		$tgl_dev    = $_POST['tgl_dev'];
        $no_req     = $_POST['NO_REQ'];
		$no_req_new     = $_POST['NO_REQ_NEW'];
        $data_sp2   = $_POST['select2'];
        $id_user    = $_SESSION["LOGGED_STORAGE"];
        $id_yard_   = $_SESSION["IDYARD_STORAGE"];
	
		//echo $tgl_perp;die;
		if ($tgl_perp <> NULL){
			//echo "UPDATE request_delivery set TGL_REQUEST_DELIVERY = TO_DATE('$tgl_perp','rrrr/mm/dd') WHERE NO_REQUEST = '$no_req_new'";die;
			 $query_update           = "UPDATE container_stripping set END_STACK_PNKN = TO_DATE('$tgl_perp','yyyy-mm-dd') WHERE NO_REQUEST = '$no_req_new'";
		     $db->query($query_update);
		}
        foreach($data_sp2 as $item){
            //echo 'dama';die;
            $query  	= "SELECT a.NO_CONTAINER FROM container_stripping a WHERE  a.NO_CONTAINER = '$item' AND NO_REQUEST = '$no_req_new'";
            $result_cek	= $db->query($query);
            $cek		= $result_cek->fetchRow();
            $no_cont_   = $cek["NO_CONTAINER"];
			
			if ($no_cont_ == NULL){
			
 $query  	= "SELECT TO_CHAR(a.TGL_BONGKAR,'dd/mm/yyyy') TGL_BONGKAR, NVL(TO_DATE(a.END_STACK_PNKN,'dd/mm/rrrr')+1,TO_DATE(a.TGL_APPROVE,'dd/mm/rrrr')+3) START_PERP, a.AFTER_STRIP, TO_CHAR(a.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, a.HZ, a.VOYAGE FROM container_stripping a WHERE  a.NO_CONTAINER = '$item' AND NO_REQUEST = '$no_req'";
            $result_cek	= $db->query($query);
            $cek	= $result_cek->fetchRow();
            $tgl_bongkar	= $cek["TGL_BONGKAR"];
            $after_strip 	= $cek["AFTER_STRIP"];
			$tgl_approve    = $cek["TGL_APPROVE"];
			$hz				= $cek['HZ'];
			$voyage			= $cek['VOYAGE'];
			$start_perp			= $cek['START_PERP'];
			//non aktifkan container_stripping dengan nomor request lama
			$query_update	= "UPDATE CONTAINER_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$item' AND NO_REQUEST = '$no_req'";
			$db->query($query_update);

		//	//non aktifkan status aktif kartu stripping lama
		//	$query_update2	= "UPDATE KARTU_STRIPPING SET AKTIF = 'T' WHERE NO_CONTAINER = '$item' AND NO_REQUEST = '$no_req'";
		//	$db->query($query_update2);

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
														VALUES('$item', 
															   '$no_req_new',
															   'Y',
															   'DARAT',
															   '$hz',
															   '$voyage',
															   TO_DATE('$tgl_bongkar','dd-mm-rrrr'),
															   '$after_strip',
															   TO_DATE('$tgl_approve','dd-mm-rrrr'),
															   TO_DATE('$start_perp','dd-mon-rr'),
															   TO_DATE('$tgl_perp','rrrr-mm-dd')
															   )";
			
			
			
			$db->query($query_insert_strip);
            
            $history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD) 
                                                      VALUES ('$item','$no_req_str','PERP PNKN STRIPPING',SYSDATE,'$id_user','$id_yard_')";
           // echo $history;die;
            $db->query($history);        
			
			}
	}
            header('Location: '.HOME.APPID);		
   
?>