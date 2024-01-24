<?php
        
	$db = getDB("storage");

   //    debug($_POST);
     //  echo count($_POST['select2']);die;
        $tgl_perp   = $_POST['tgl_perpanjangan'];
		$tgl_dev    = $_POST['tgl_dev'];
		$tgl_approve = $_POST['tgl_approve'];
        $no_req     = $_POST['NO_REQ'];
        $data_perp   = $_POST['select2'];
        $id_user    = $_SESSION["LOGGED_STORAGE"];
        $id_yard_   = $_SESSION["IDYARD_STORAGE"];
	
       // echo "SELECT TO_CHAR(TGL_REQUEST_DELIVERY+1,'dd/mm/yyyy') TGL_END, REQ_AWAL, NVL((PERP_KE+1),1) PERP_KE, ID_YARD, DELIVERY_KE, request_delivery.VESSEL, request_delivery.VOYAGE FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";die;
        $query  	= "SELECT NO_REQUEST_RECEIVING, NO_DO, NO_BL, TYPE_STRIPPING, KD_CONSIGNEE, NVL(PERP_KE_PNKN+1,1) PERP_KE  FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
	    $result_cek	= $db->query($query);
        $cek		= $result_cek->fetchRow();
	    $no_req_rec	= $cek["NO_REQUEST_RECEIVING"];
        $no_do      = $cek["NO_DO"];
        $no_bl      = $cek["NO_BL"];
        $type       = $cek["TYPE_STRIPPING"];
		$kd_cons       = $cek["KD_CONSIGNEE"];
		$perp_ke       = $cek["PERP_KE"];
        
        
	//	echo "SELECT LPAD((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM request_delivery WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";die;
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM,
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_STRIPPING 
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
        
	$no_req_str     = 'STR'.$month.$year.$jum;
        
//	echo "INSERT INTO request_delivery(NO_REQUEST, KD_EMKL, TGL_REQUEST, REQ_AWAL, TGL_REQUEST_DELIVERY, KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE,VESSEL, VOYAGE, PERALIHAN, ID_YARD, STATUS, PERP_KE, PERP_DARI) 
  //                                                    VALUES ('$no_req_dev', '$id_emkl', SYSDATE, '$req_awal',TO_DATE('".$tgl_perp."','yyyy/mm/dd'),'$KETERANGAN', '0', $id_user, '$dev_ke','$id_vessel','$id_voyage','T','$id_yard','EXT','$perp_ke','$no_req')";die;
        $query_req 	= "INSERT INTO request_stripping(NO_REQUEST, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, TGL_REQUEST, NO_DO, NO_BL, TYPE_STRIPPING, NO_REQUEST_RECEIVING, PERP_DARI, ID_YARD, ID_USER,STATUS_REQ, PERP_KE_PNKN, NOTA) 
                                                      VALUES ('$no_req_str', '$kd_cons', '$kd_cons',SYSDATE, '$no_do','$no_bl', '$type_stripping','$no_req_rec', '$no_req','$id_yard_','$id_user','PERP PNKN', '$perp_ke','T')";
        $db->query($query_req);
  
        foreach($data_perp as $item){
            //echo 'dama';die;
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
															   '$no_req_str',
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
	
            header('Location: '.HOME.APPID);		
   
?>