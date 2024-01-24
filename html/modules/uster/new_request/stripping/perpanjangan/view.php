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
		
		$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, PLAN_CONTAINER_STRIPPING.COMMODITY COMMO, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_
                           FROM PLAN_CONTAINER_STRIPPING INNER JOIN 
						   MASTER_CONTAINER ON PLAN_CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                           WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req'";
		$result_list	= $db->query($query_list);
		$row_list		= $result_list->getAll();
		$jum = count($row_list);			
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
				
	//$query_request	= "SELECT REQUEST_STRIPPING.*, emkl.NM_PBM AS NAMA_PEMILIK, pnmt.NM_PBM AS NAMA_PENUMPUK FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	//debug($query_request);							
	$q_cekperp = "select STATUS_REQ from request_stripping where no_request = '$no_req'";
	$resperp = $db->query($q_cekperp);
	$rowperp = $resperp->fetchRow();
	$rcperp = $rowperp["STATUS_REQ"];
	
	if($rcperp == 'PERP'){
		$query_request = "SELECT REQUEST_STRIPPING.*,
						   emkl.NM_PBM AS NAMA_PEMILIK,
						   pnmt.NM_PBM AS NAMA_PENUMPUK
					  FROM REQUEST_STRIPPING
						   INNER JOIN V_MST_PBM emkl
							  ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM and emkl.KD_CABANG = '05'
						   JOIN V_MST_PBM pnmt
							  ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM and pnmt.KD_CABANG = '05'
						   --JOIN PLAN_REQUEST_STRIPPING PL
							  --ON REQUEST_STRIPPING.NO_REQUEST = REPLACE(PL.NO_REQUEST,'P','S')
					 WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	}
	else {
	$query_request = "SELECT REQUEST_STRIPPING.*, PL.NO_SPPB, PL.TGL_SPPB,
						   emkl.NM_PBM AS NAMA_PEMILIK,
						   pnmt.NM_PBM AS NAMA_PENUMPUK
					  FROM REQUEST_STRIPPING
						   INNER JOIN V_MST_PBM emkl
							  ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM and emkl.KD_CABANG = '05'
						   JOIN V_MST_PBM pnmt
							  ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM and pnmt.KD_CABANG = '05'
						   JOIN PLAN_REQUEST_STRIPPING PL
							  ON REQUEST_STRIPPING.NO_REQUEST = PL.NO_REQUEST_APP_STRIPPING
					 WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	}
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request)
	
	$count  		= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM CONTAINER_STRIPPING a WHERE a.NO_REQUEST = '$no_req' AND a.AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count		= $result_count->fetchRow();
	
	if($rcperp == 'PERP'){
		$get_tgl 		= " SELECT a.NO_CONTAINER,
								a.END_STACK_PNKN+1 TGL_SELESAI
                        FROM CONTAINER_STRIPPING a
                       WHERE a.NO_REQUEST = '$no_req' AND AKTIF = 'Y' AND a.NO_CONTAINER IS NOT NULL
                    ORDER BY a.NO_CONTAINER";
	}
	else {
	$get_tgl 		= "  SELECT a.NO_CONTAINER,
							 CASE WHEN a.TGL_SELESAI  IS NULL
                             THEN TO_DATE (a.TGL_BONGKAR + 4, 'dd/mm/rrrr')
                             ELSE
                              TO_DATE (a.TGL_SELESAI, 'dd/mm/rrrr')
                             END AS TGL_SELESAI
						FROM CONTAINER_STRIPPING a
					   WHERE a.NO_REQUEST = '$no_req' AND AKTIF = 'Y'
					ORDER BY a.NO_CONTAINER";
	}
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
