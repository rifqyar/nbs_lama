<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('req_list.htm');

//-----------------paging
/*
	if(isset($_GET["page"]))
	{
		$page = $_GET["page"];	
	}
	else
	{
		$page = 1;	
	}
*/
//------------------------	
	
	$db = getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT REQUEST_DELIVERY.*, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT, NOTA_DELIVERY.LUNAS FROM REQUEST_DELIVERY INNER JOIN MASTER_PBM emkl ON REQUEST_DELIVERY.ID_EMKL = emkl.ID JOIN MASTER_PBM pnmt ON REQUEST_DELIVERY.ID_PEMILIK = pnmt.ID  JOIN NOTA_DELIVERY ON REQUEST_DELIVERY.NO_REQUEST = NOTA_DELIVERY.NO_REQUEST WHERE REQUEST_DELIVERY.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
