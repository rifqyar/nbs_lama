<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");

	//2... Cek Apakah No Request Sebelumnya Merupakan  request awal atau perpanjangan
	
	$query_cek_perp = "SELECT PERP_DARI
						FROM REQUEST_STUFFING
						WHERE NO_REQUEST='$no_req'";
			
	
			
	$result_perp	= $db->query($query_cek_perp);
	$req_lama		= $result_perp->fetchRow(); 
	$no_req_lama	= $req_lama['PERP_DARI'];
	
	//echo "tes5";die;
	//print_r($no_req_lama);die;
	
	
		if($no_req_lama == NULL) //Ini berarti merupakan perpanjangan pertama
		{
			//echo "perpanjangan pertama";
			//print_r($no_req_lama);die;
		
		
			$query_list		= "SELECT DISTINCT CONTAINER_STUFFING.NO_CONTAINER,
											   CONTAINER_STUFFING.HZ, 
											   CONTAINER_STUFFING.START_PERP_PNKN+1 TGL_MULAI, 
											   CONTAINER_STUFFING.COMMODITY, M.SIZE_ KD_SIZE, M.TYPE_ KD_TYPE
							   FROM CONTAINER_STUFFING LEFT JOIN MASTER_CONTAINER M        
							   ON CONTAINER_STUFFING.NO_CONTAINER = M.NO_CONTAINER
							   WHERE CONTAINER_STUFFING.NO_REQUEST = '$no_req'
							   AND AKTIF = 'Y'";
		}
		else
		{
			// echo "perpanjangan kedua";
			
			$query_list		= "SELECT DISTINCT CB.NO_REQUEST NO_BARU,
							   CL.NO_REQUEST NO_LAMA,
							   CB.NO_CONTAINER,
							   CB .HZ, 
							   CB.END_STACK_PNKN+1 TGL_MULAI, 
							   CB.COMMODITY, M.SIZE_ KD_SIZE, M.TYPE_ KD_TYPE
							   FROM CONTAINER_STUFFING CB
											INNER JOIN CONTAINER_STUFFING CL
										ON CB.NO_CONTAINER = CL.NO_CONTAINER
											LEFT JOIN MASTER_CONTAINER M        
										ON CB.NO_CONTAINER = M.NO_CONTAINER
							   WHERE CB.NO_REQUEST = '$no_req'
							   AND CL.NO_REQUEST = '$no_req_lama'
							   AND CB.AKTIF = 'Y'";
		}
	
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
