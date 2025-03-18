<?php
        
	$db = getDB("storage");

      // debug($_POST);die;
     //  echo count($_POST['select2']);die;
        $tgl_perp   = $_POST['tgl_perpanjangan'];
		$tgl_dev    = $_POST['tgl_dev'];
        $no_req     = $_POST['NO_REQ'];
        //$data_sp2   = $_POST['select2'];
		$total = $_POST["total"];
		//echo $total;die;
		//$NO_CONT = 'dada';
		//$i=0;
		//echo $_POST['NO_CONT_1']."--";die;
		for($i=1;$i<=$total;$i++){
			if($_POST['TGL_PERP_'.$i] != NULL){
			$NO_CONT[$i] = $_POST['NO_CONT_'.$i];
			$TGL_PERP[$i] = $_POST['TGL_PERP_'.$i];
			
			// echo $NO_CONT[$i]."--";
			// echo $TGL_PERP[$i]."<br/>";
			}
		}
		
		// die;
		
		
		//die;
		
        $id_user    = $_SESSION["LOGGED_STORAGE"];
        $id_yard_   = $_SESSION["ID_YARD_STORAGE"];
	
       // echo "SELECT TO_CHAR(TGL_REQUEST_DELIVERY+1,'dd/mm/yyyy') TGL_END, REQ_AWAL, NVL((PERP_KE+1),1) PERP_KE, ID_YARD, DELIVERY_KE, request_delivery.VESSEL, request_delivery.VOYAGE FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";die;
        $query  	= "SELECT REQ_AWAL, NVL((PERP_KE+1),1) PERP_KE, ID_YARD, DELIVERY_KE, request_delivery.VESSEL, request_delivery.VOYAGE FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";
	    $result_cek	= $db->query($query);
        $cek		= $result_cek->fetchRow();
	    //$end_stack	= $cek["TGL_END"];
        $perp_ke     = $cek["PERP_KE"];
        $id_yard     = $cek["ID_YARD"];
        $req_awal    = $cek["REQ_AWAL"];
        $id_voyage   = $cek["VOYAGE"];
	    $id_vessel   = $cek["VESSEL"];
        $dev_ke      = $cek["DELIVERY_KE"];
        
        
	//	echo "SELECT LPAD((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM request_delivery WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)";die;
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
                              TO_CHAR(SYSDATE, 'MM') AS MONTH, 
                              TO_CHAR(SYSDATE, 'YY') AS YEAR 
                       FROM REQUEST_DELIVERY
                       WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
                       AND SUBSTR(request_delivery.NO_REQUEST,0,3) = 'DEL'";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
        
	$no_req_dev     = 'DEL'.$month.$year.$jum;
        
        $query  	= "SELECT CASE WHEN KD_AGEN IS NOT NULL THEN KD_AGEN ELSE KD_EMKL END KD_AGEN FROM request_delivery where NO_REQUEST = '$no_req'";
	$result_cek		= $db->query($query);
        $cek		= $result_cek->fetchRow();
	$id_emkl		= $cek["KD_AGEN"];
        
//	echo "INSERT INTO request_delivery(NO_REQUEST, KD_EMKL, TGL_REQUEST, REQ_AWAL, TGL_REQUEST_DELIVERY, KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE,VESSEL, VOYAGE, PERALIHAN, ID_YARD, STATUS, PERP_KE, PERP_DARI) 
  //                                                    VALUES ('$no_req_dev', '$id_emkl', SYSDATE, '$req_awal',TO_DATE('".$tgl_perp."','yyyy/mm/dd'),'$KETERANGAN', '0', $id_user, '$dev_ke','$id_vessel','$id_voyage','T','$id_yard','EXT','$perp_ke','$no_req')";die;
        $query_req 	= "INSERT INTO request_delivery(NO_REQUEST, KD_EMKL, TGL_REQUEST, REQ_AWAL, KETERANGAN, CETAK_KARTU, ID_USER, DELIVERY_KE,VESSEL, VOYAGE, PERALIHAN, ID_YARD, STATUS, PERP_KE, PERP_DARI, NOTA) 
                                                      VALUES ('$no_req_dev', '$id_emkl', SYSDATE, '$req_awal','$KETERANGAN', '0', $id_user, '$dev_ke','$id_vessel','$id_voyage','T','$id_yard','PERP','$perp_ke','$no_req','T')";
      
        $db->query($query_req); 
		
	   $jum_cont = count($TGL_PERP);
	   //echo $jum_cont;die;
	   //print_r($NO_CONT);
	   for($g=1;$g<=$total;$g++){	    
			if($_POST['TGL_PERP_'.$g] != NULL){
            // echo 'dama'.$g;
            $query  	= "SELECT a.STATUS, a.HZ, a.START_STACK, a.VIA, a.ID_YARD, a.NOREQ_PERALIHAN, a.ASAL_CONT, a.TGL_DELIVERY FROM container_delivery a WHERE  a.NO_CONTAINER = '$NO_CONT[$g]' AND NO_REQUEST = '$no_req'";
            $result_cek	= $db->query($query);
            $cek	= $result_cek->fetchRow();
            $status	= $cek["STATUS"];
            $hz 	= $cek["HZ"];
			$start_stack = $cek["START_STACK"];
			$via 	= $cek["VIA"];
			$id_yard_ = $cek["ID_YARD"];
			$noreq_per 	= $cek["NOREQ_PERALIHAN"];
			$asal_cont = $cek["ASAL_CONT"];
			$endstack = $cek["TGL_DELIVERY"];
			$komoditi = $cek["KOMODITI"];
		
			
			
			
			
          //  echo "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP) VALUES('$item', '$no_request_dev', '$status','Y','N','$hz','$start_stack', '$end_stack')";die;
            $query_insert           = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP, TGL_DELIVERY, VIA, NOREQ_PERALIHAN, ID_YARD, ASAL_CONT, KOMODITI) 
			VALUES('$NO_CONT[$g]', '$no_req_dev', '$status','Y','N','$hz',TO_DATE('$start_stack','dd/mm/rrrr'), TO_DATE('$endstack','dd/mm/rrrr'), TO_DATE('$TGL_PERP[$g]','yyyy/mm/dd'), '$via','$noreq_per','$id_yard_','$asal_cont','$komoditi')";
           // echo $g.$query_insert.'<br/>';
		   $query_update           = "UPDATE container_delivery set AKTIF = 'T' where no_container = '$NO_CONT[$g]' and no_request = '$no_req' ";
          //echo $query_update.'<br/>';
          //  echo $query_insert;die;
		  
		    if($db->query($query_insert)){
				$q_getc = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$NO_CONT[$g]'";
						$r_getc = $db->query($q_getc);
						$rw_getc = $r_getc->fetchRow();
						$cur_book = $rw_getc["NO_BOOKING"];
						$cur_c = $rw_getc["COUNTER"];
						
				echo "insert new container ok";
				$history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
                                                      VALUES ('$NO_CONT[$g]','$no_req_dev','PERP DELIVERY',SYSDATE,'$id_user','$id_yard_', '$status', 'VESSEL_NOTHING', '$cur_c')"; 
				$db->query($history);
			}
			
			if($db->query($query_update)){
				echo "update old container OK";
			}
            
            
            
			}
		}
		// debug($cek);die;
		
		// die;
            header('Location: '.HOME.APPID);		
   
?>