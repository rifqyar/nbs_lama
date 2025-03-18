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
			 $query_update           = "UPDATE request_delivery set TGL_REQUEST_DELIVERY = TO_DATE('$tgl_perp','yyyy-mm-dd') WHERE NO_REQUEST = '$no_req_new'";
		     $db->query($query_update);
		}
        foreach($data_sp2 as $item){
            //echo 'dama';die;
            $query  	= "SELECT a.NO_CONTAINER FROM container_delivery a WHERE  a.NO_CONTAINER = '$item' AND NO_REQUEST = '$no_req_new'";
            $result_cek	= $db->query($query);
            $cek		= $result_cek->fetchRow();
            $no_cont_   = $cek["NO_CONTAINER"];
			
			if ($no_cont_ == NULL){
			
			$query  	= "SELECT a.STATUS, a.HZ, a.START_STACK FROM container_delivery a WHERE  a.NO_CONTAINER = '$item' AND NO_REQUEST = '$no_req'";
            $result_cek	= $db->query($query);
            $cek	= $result_cek->fetchRow();
            $status	= $cek["STATUS"];
            $hz 	= $cek["HZ"];
			$start_stack = $cek["START_STACK"];


			 $query  	= "SELECT TO_CHAR(TGL_REQUEST_DELIVERY+1, 'dd/mm/yyyy') TGL_END FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";
		    $result_cek	= $db->query($query);
	        $cek		= $result_cek->fetchRow();
		    $end_stack	= $cek["TGL_END"];

			//  echo "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP) VALUES('$item', '$no_request_dev', '$status','Y','N','$hz','$start_stack', '$end_stack')";die;
            $query_insert           = "INSERT INTO CONTAINER_DELIVERY(NO_CONTAINER, NO_REQUEST, STATUS, AKTIF, KELUAR,HZ, START_STACK, START_PERP) 
			VALUES('$item', '$no_req_new', '$status','Y','N','$hz',TO_DATE('$start_stack','dd/mm/rrrr'), TO_DATE('$end_stack','dd/mm/rrrr'))";
           
		   $query_update           = "UPDATE container_delivery set AKTIF = 'T' where no_container = '$item' and no_request = '$no_req' ";
          
          //  echo $query_insert;die;
		    $db->query($query_update);
            $db->query($query_insert);
            
            $history                = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD) 
                                                      VALUES ('$item','$no_req_new','PERP DELIVERY',SYSDATE,'$id_user','$id_yard_')";
           // echo $history;die;
            $db->query($history);
        }
	}
            header('Location: '.HOME.APPID);		
   
?>