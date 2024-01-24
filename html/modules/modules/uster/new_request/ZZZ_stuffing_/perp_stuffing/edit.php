<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');
	
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	
	$query_request	= "SELECT REQUEST_DELIVERY.KETERANGAN, REQUEST_DELIVERY.DELIVERY_KE, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'yyyy/mm/dd') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VESSEL NAMA_VESSEL, request_delivery.VOYAGE
        FROM REQUEST_DELIVERY INNER JOIN v_mst_pbm emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM 
        WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
