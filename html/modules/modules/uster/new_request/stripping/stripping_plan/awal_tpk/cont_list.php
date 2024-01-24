<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, PLAN_CONTAINER_STRIPPING.COMMODITY COMMO, ukuran kd_size, type KD_TYPE, PLAN_CONTAINER_STRIPPING.REMARK REMARK
                           FROM PLAN_CONTAINER_STRIPPING
                           WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req'";
	}
	
		$cek_save = "SELECT CLOSING FROM PLAN_REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
		$e_cek = $db->query($cek_save);
		$r_ce = $e_cek->fetchRow();
		$close = $r_ce["CLOSING"];
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	//echo $jumlah;
	$tl->assign("close",$close);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
