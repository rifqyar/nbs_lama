<?php
        // echo "dama";die;
	     $db = getDB("storage");

    
     //  echo count($_POST['select2']);die;
        $tgl_perp   = $_POST['tgl_perpanjangan'];
		$tgl_dev    = $_POST['tgl_dev'];
        $no_req     = $_POST['NO_REQ'];
		$no_req_new     = $_POST['NO_REQ_NEW'];
        //$data_sp2   = $_POST['select2'];
		$total = $_POST["total"];
        $id_user    = $_SESSION["LOGGED_STORAGE"];
        $id_yard_   = $_SESSION["IDYARD_STORAGE"];
		
		for($i=1;$i<=$total;$i++){
			if($_POST['TGL_PERP_'.$i] != NULL){
			$NO_CONT[$i] = $_POST['NO_CONT_'.$i];
			$TGL_PERP[$i] = $_POST['TGL_PERP_'.$i];
			
			// echo $NO_CONT[$i]."--";
			//echo $TGL_PERP[$i]."<br/>";
			}
		}
		//echo $tgl_perp;die;
		/* if ($tgl_perp <> NULL){
			//echo "UPDATE request_delivery set TGL_REQUEST_DELIVERY = TO_DATE('$tgl_perp','rrrr/mm/dd') WHERE NO_REQUEST = '$no_req_new'";die;
			 $query_update           = "UPDATE request_delivery set TGL_REQUEST_DELIVERY = TO_DATE('$tgl_perp','yyyy-mm-dd') WHERE NO_REQUEST = '$no_req_new'";
		     $db->query($query_update);
		} */
        for($g=1;$g<=$total;$g++){
			if($_POST['TGL_PERP_'.$g] != NULL){
            //echo 'dama';die;
            $query  	= "SELECT a.NO_CONTAINER FROM container_delivery a WHERE  a.NO_CONTAINER = '$NO_CONT[$g]' AND NO_REQUEST = '$no_req_new'";
            $result_cek	= $db->query($query);
            $cek		= $result_cek->fetchRow();
            $no_cont_   = $cek["NO_CONTAINER"];
			
			if ($no_cont_ == NULL){
			
			$query  	= "SELECT * FROM container_delivery a WHERE a.NO_CONTAINER = '$NO_CONT[$g]' AND NO_REQUEST = '$no_req'";
            $result_cek	= $db->query($query);
            $cek		= $result_cek->fetchRow();
            $status		= $cek["STATUS"];
            $hz 		= $cek["HZ"];
			$start_stack = $cek["START_STACK"];
			$via 		= $cek["VIA"];
			$id_yard_ 	= $cek["ID_YARD"];
			$asal_cont = $cek["ASAL_CONT"];
			$noreq_per = $cek["NOREQ_PERALIHAN"];


			$query  	= "SELECT TO_CHAR(TGL_DELIVERY+1, 'dd/mm/yyyy') TGL_END FROM CONTAINER_DELIVERY WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$NO_CONT[$g]'";
		    $result_cek	= $db->query($query);
	        $cek		= $result_cek->fetchRow();
		    $end_stack	= $cek["TGL_END"];

			//  echo "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP) VALUES('$item', '$no_request_dev', '$status','Y','N','$hz','$start_stack', '$end_stack')";die;
            $query_insert           = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP, TGL_DELIVERY, VIA, NOREQ_PERALIHAN, ID_YARD, ASAL_CONT) 
			VALUES('$NO_CONT[$g]', '$no_req_new', '$status','Y','N','$hz',TO_DATE('$start_stack','dd/mm/rrrr'), TO_DATE('$end_stack','dd/mm/rrrr'), TO_DATE('$TGL_PERP[$g]','yyyy/mm/dd'), '$via','$noreq_per','$id_yard_','$asal_cont')";
           
		   $query_update           = "UPDATE container_delivery set AKTIF = 'T' where no_container = '$NO_CONT[$g]' and no_request = '$no_req' ";
          
          //  echo $query_insert;die;
		     if($db->query($query_insert)){
				echo "insert new container ok";
			}
			
			if($db->query($query_update)){
				echo "update old container OK";
			}
            
            $history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT) 
                                                      VALUES ('$NO_CONT[$g]','$no_req_new','PERP DELIVERY',SYSDATE,'$id_user','$id_yard_','$status')";
           // echo $history;die;
            $db->query($history);
        }
		}
	}
            header('Location: '.HOME.APPID);		
   
?>