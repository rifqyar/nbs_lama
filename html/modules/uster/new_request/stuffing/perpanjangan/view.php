<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view.htm');
	
	$db = getDB("storage");
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		
		$query = "SELECT NO_REQUEST_RECEIVING
					FROM PLAN_REQUEST_STRIPPING
					WHERE NO_REQUEST = '$no_req'";
					
		$result 	= $db->query($query);
		$row_req2	= $result->fetchRow();
		$no_req_rec	= $row_req2["NO_REQUEST_RECEIVING"];
		
		$no_req2	= substr($no_req_rec,3);	
		$no_req2	= "UREC".$no_req2;
		
		$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, PLAN_CONTAINER_STRIPPING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
                           FROM PLAN_CONTAINER_STRIPPING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A            
                           ON PLAN_CONTAINER_STRIPPING.NO_CONTAINER = A.CONT_NO_BP
                           WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req'";
		$result_list	= $db->query($query_list);
		$row_list		= $result_list->getAll();
		$jum = count($row_list);			
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
				
	$query_request	= "SELECT REQUEST_STRIPPING.*, emkl.NM_PBM AS NAMA_PEMILIK, pnmt.NM_PBM AS NAMA_PENUMPUK FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	//debug($query_request);							
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	$sql_no  		= "select AUTO_NO+1 AS AUTO_NO  from PETIKEMAS_CABANG.MST_AUTO_NO WHERE CODE='7'";
	$rs 	 		= $db->query( $sql_no );
	$row	 		= $rs->FetchRow();
	$sp2		 	= $row['AUTO_NO'];	
	
	$count  		= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM CONTAINER_STRIPPING a WHERE a.NO_REQUEST = '$no_req' AND a.AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count		= $result_count->fetchRow();
	
	$get_tgl 		= "  SELECT a.NO_CONTAINER,
							 TO_DATE (a.TGL_APP_SELESAI, 'dd/mm/rrrr') + 1 TGL_APPROVE
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
