<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');
	
	$db = getDB("storage");

	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		
		
		
		/*$query = "SELECT NO_REQUEST_RECEIVING
					FROM PLAN_REQUEST_STRIPPING
					WHERE NO_REQUEST = '$no_req'";
					
           echo $query;die();
		$result 	= $db->query($query);
		$row_req2	= $result->fetchRow();
		$no_req_rec	= $row_req2["NO_REQUEST_RECEIVING"];
		
		$no_req2	= substr($no_req_rec,3);	
		$no_req2	= "UREC".$no_req2;
		
		$query_list		= "SELECT a.NO_CONTAINER, b.SIZE_ KD_SIZE, b.TYPE_ KD_TYPE, a.COMMODITY, a.TGL_APPROVE, a.TGL_APP_SELESAI
							FROM CONTAINER_STRIPPING a, MASTER_CONTAINER b
							WHERE a.NO_CONTAINER = b.NO_CONTAINER";
		$result_list	= $db->query($query_list);
		$row_list		= $result_list->getAll();
		$jum = count($row_list);	*/		
     
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
						   
	
	$query_request	= "/* Formatted on 1/1/2013 10:19:47 AM (QP5 v5.163.1008.3004) */
						SELECT REQUEST_STRIPPING.NO_REQUEST,
							   REQUEST_STRIPPING.NO_DO,
							   REQUEST_STRIPPING.NO_BL,
							   --PLAN_REQUEST_STRIPPING.NO_SPPB,
							   --TO_DATE (PLAN_REQUEST_STRIPPING.TGL_SPPB, 'dd/mm/rrrr') TGL_SPPB,
							   REQUEST_STRIPPING.TYPE_STRIPPING,
							   REQUEST_STRIPPING.KD_CONSIGNEE,
							   REQUEST_STRIPPING.CONSIGNEE_PERSONAL,
							   emkl.NM_PBM AS NAMA_PEMILIK,
                               REQUEST_STRIPPING.CLOSING,
                               REQUEST_STRIPPING.PERP_DARI,
                               REQUEST_STRIPPING.O_VESSEL,
                               REQUEST_STRIPPING.O_VOYIN,
                               REQUEST_STRIPPING.O_VOYOUT,
                               REQUEST_STRIPPING.O_REQNBS,
                               REQUEST_STRIPPING.O_IDVSB
						  FROM REQUEST_STRIPPING
							   INNER JOIN V_MST_PBM emkl
								  ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM
							   --JOIN PLAN_REQUEST_STRIPPING
								 --ON PLAN_REQUEST_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST_PLAN
						 WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	//debug($query_request);							
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	$count  		= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM CONTAINER_STRIPPING a WHERE a.NO_REQUEST = '$no_req' AND a.AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count		= $result_count->fetchRow();
	
	$get_tgl 		= "  SELECT a.NO_CONTAINER,
							 TO_DATE (a.TGL_SELESAI, 'dd/mm/rrrr') + 1 TGL_SELESAI
						FROM CONTAINER_STRIPPING a
					   WHERE a.NO_REQUEST = '$no_req' AND AKTIF = 'Y'
					ORDER BY a.NO_CONTAINER";
	$result_tgl		= $db->query($get_tgl);
	$row_tgl		= $result_tgl->getAll();
	

	$tl->assign("dla", $dl);
	//$tl->assign("dla", 'adad');
	$tl->assign("jum", $jum);
	$tl->assign("row_list", $row_list);
	$tl->assign("row_tgl", $row_tgl);
	$tl->assign("row_request", $row_request);
	$tl->assign("row_count", $row_count);
	$tl->assign("no_req2", $no_req2);
	$tl->assign("sp2", $sp2);	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
