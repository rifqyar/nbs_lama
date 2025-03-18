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
	//echo $no_req	
	$query_request	= "SELECT PLAN_REQUEST_STRIPPING.*, emkl.NM_PBM AS NAMA_PEMILIK, pnmt.NM_PBM AS NAMA_PENUMPUK FROM PLAN_REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM JOIN V_MST_PBM pnmt ON PLAN_REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM WHERE PLAN_REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	//debug($query_request);							
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
