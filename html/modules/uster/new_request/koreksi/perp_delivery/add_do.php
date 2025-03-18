<?php
        
	$db = getDB("storage");

   //    debug($_POST);
     //  echo count($_POST['select2']);die;
        $tgl_perp   = $_POST['tgl_perpanjangan'];
		$tgl_dev    = $_POST['tgl_dev'];
        $no_req     = $_POST['NO_REQ'];
        $data_sp2   = $_POST['select2'];
        $id_user    = $_SESSION["LOGGED_STORAGE"];
        $id_yard_   = $_SESSION["IDYARD_STORAGE"];
	
       // echo "SELECT TO_CHAR(TGL_REQUEST_DELIVERY+1,'dd/mm/yyyy') TGL_END, REQ_AWAL, NVL((PERP_KE+1),1) PERP_KE, ID_YARD, DELIVERY_KE, request_delivery.VESSEL, request_delivery.VOYAGE FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";die;
        $query  	= "SELECT TO_CHAR(TGL_REQUEST_DELIVERY+1, 'dd/mm/yyyy') TGL_END, REQ_AWAL, NVL((PERP_KE+1),1) PERP_KE, ID_YARD, DELIVERY_KE, request_delivery.VESSEL, request_delivery.VOYAGE FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";
	    $result_cek	= $db->query($query);
        $cek		= $result_cek->fetchRow();
	    $end_stack	= $cek["TGL_END"];
        $perp_ke     = $cek["PERP_KE"];
        $id_yard     = $cek["ID_YARD"];
        $req_awal    = $cek["REQ_AWAL"];
        $id_voyage   = $cek["VOYAGE"];
	    $id_vessel   = $cek["VESSEL"];
        $dev_ke      = $cek["DELIVERY_KE"];
        
        
	//	echo "SELECT LPAD((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM request_delivery WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";die;
	$query_cek	= "SELECT LPAD((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM request_delivery WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
        
	$no_req_dev     = 'DEL'.$month.$year.$jum;
        
        $query  	= "SELECT KD_EMKL FROM request_delivery where NO_REQUEST = '$no_req'";
	$result_cek	= $db->query($query);
        $cek		= $result_cek->fetchRow();
	$id_emkl	= $cek["KD_EMKL"];
        
//	echo "INSERT INTO request_delivery(NO_REQUEST, KD_EMKL, TGL_REQUEST, REQ_AWAL, TGL_REQUEST_DELIVERY, KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE,VESSEL, VOYAGE, PERALIHAN, ID_YARD, STATUS, PERP_KE, PERP_DARI) 
  //                                                    VALUES ('$no_req_dev', '$id_emkl', SYSDATE, '$req_awal',TO_DATE('".$tgl_perp."','yyyy/mm/dd'),'$KETERANGAN', '0', $id_user, '$dev_ke','$id_vessel','$id_voyage','T','$id_yard','EXT','$perp_ke','$no_req')";die;
        $query_req 	= "INSERT INTO request_delivery(NO_REQUEST, KD_EMKL, TGL_REQUEST, REQ_AWAL, TGL_REQUEST_DELIVERY, KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE,VESSEL, VOYAGE, PERALIHAN, ID_YARD, STATUS, PERP_KE, PERP_DARI, NOTA) 
                                                      VALUES ('$no_req_dev', '$id_emkl', SYSDATE, '$req_awal',TO_DATE('".$tgl_perp."','yyyy/mm/dd'),'$KETERANGAN', '0', $id_user, '$dev_ke','$id_vessel','$id_voyage','T','$id_yard','PERP','$perp_ke','$no_req','T')";
        //echo $query_req;die;
        $db->query($query_req);
        $query  	= "SELECT MAX(NO_REQUEST) NO_REQUEST FROM request_delivery";
	$result_cek	= $db->query($query);
        $cek		= $result_cek->fetchRow();
	$no_request_dev	= $cek["NO_REQUEST"];
       // echo 'before';die;
        foreach($data_sp2 as $item){
            //echo 'dama';die;
            $query  	= "SELECT a.STATUS, a.HZ, a.START_STACK FROM container_delivery a WHERE  a.NO_CONTAINER = '$item' AND NO_REQUEST = '$no_req'";
            $result_cek	= $db->query($query);
            $cek	= $result_cek->fetchRow();
            $status	= $cek["STATUS"];
            $hz 	= $cek["HZ"];
			$start_stack = $cek["START_STACK"];
			
          //  echo "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP) VALUES('$item', '$no_request_dev', '$status','Y','N','$hz','$start_stack', '$end_stack')";die;
            $query_insert           = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP) 
			VALUES('$item', '$no_request_dev', '$status','Y','N','$hz',TO_DATE('$start_stack','dd/mm/rrrr'), TO_DATE('$end_stack','dd/mm/rrrr'))";
           
		   $query_update           = "UPDATE container_delivery set AKTIF = 'T' where no_container = '$item' and no_request = '$no_req' ";
          
          //  echo $query_insert;die;
		    $db->query($query_update);
            $db->query($query_insert);
            
            $history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD) 
                                                      VALUES ('$item','$no_request_dev','PERP DELIVERY',SYSDATE,'$id_user','$id_yard_')";
           // echo $history;die;
            $db->query($history);
        }
	
            header('Location: '.HOME.APPID);		
   
?>