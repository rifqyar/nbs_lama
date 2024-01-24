<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view.htm');
	
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	
	$query_request	= "SELECT REQUEST_STRIPPING.NO_REQUEST, REQUEST_STRIPPING.PERP_KE, TO_CHAR(REQUEST_STRIPPING.TGL_REQUEST+3,'dd/mm/yyyy') TGL_REQUEST_END, emkl.NAMA AS NAMA_EMKL, emkl.ID AS ID_EMKL, pnmt.NAMA AS NAMA_PNMT, pnmt.ID AS ID_PNMT FROM REQUEST_STRIPPING INNER JOIN MASTER_PBM emkl ON REQUEST_STRIPPING.ID_EMKL = emkl.ID JOIN MASTER_PBM pnmt ON REQUEST_STRIPPING.ID_PEMILIK = pnmt.ID WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
        
    $detail_cont	= "SELECT a.NO_REQUEST, a.NO_CONTAINER, b.SIZE_, b.TYPE_ FROM CONTAINER_STRIPPING a, MASTER_CONTAINER b WHERE a.NO_CONTAINER = b.NO_CONTAINER AND a.NO_REQUEST = '$no_req' AND a.AKTIF = 'Y'";
	$result_detail	= $db->query($detail_cont);
	$row_detail		= $result_detail->getAll();
        //debug($row_detail);

    $count  		= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM CONTAINER_STRIPPING a WHERE a.NO_REQUEST = '$no_req' AND a.AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count		= $result_count->fetchRow();
	
	//debug($row_request); 
	
	$tl->assign("row_request", $row_request);
    $tl->assign("row_detail", $row_detail);
    $tl->assign("row_count", $row_count);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
