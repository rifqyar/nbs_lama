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
	
	$query_request	= "SELECT REQUEST_STRIPPING.*, 
							  emkl.NAMA AS NAMA_EMKL, 
							  pnmt.NAMA AS NAMA_PEMILIK
					   FROM REQUEST_STRIPPING 
					   JOIN MASTER_PBM emkl ON REQUEST_STRIPPING.ID_PEMILIK = emkl.ID  
					   JOIN MASTER_PBM pnmt ON REQUEST_STRIPPING.ID_PEMILIK = pnmt.ID 
					   WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
								
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
