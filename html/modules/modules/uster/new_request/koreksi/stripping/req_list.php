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

	$from		= $_POST["FROM"]; 
	$to			= $_POST["TO"];
	$no_req		= $_POST["NO_REQ"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"]; 	
	
	if(isset($_POST["CARI"]))
	{
		
	}
	else if(isset($_POST["NO_REQ"]))
	{
	}
	else
	{
		$query_list		= "SELECT REQUEST_STRIPPING.*, 
							emkl.NM_PBM AS NAMA_PEMILIK, 
							pnmt.NM_PBM AS NAMA_PENUMPUK FROM REQUEST_STRIPPING 
							INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
							JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM 
							WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') 
							AND LAST_DAY(SYSDATE) AND STRIPPING_DARI = 'TPK' 
							and REQUEST_STRIPPING.KOREKSI = 'Y'
							ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	
?>
