<?php

	$tl  =  xliteTemplate('preview_nota.htm');
	$db	 = getDB();
	//$nota	 = $_GET["n"];
	//$id_user = $_SESSION["LOGGED_STORAGE"]; 
	$req	 = $_GET["id"];
	$remarks = $_GET["remark"];
	
	$tl->assign('HOME',HOME);
	$tl->assign('APPID',APPID);
	$tl->assign('id_req',$req);
	$tl->assign('remark',$remarks);
	$tl->renderToScreen();

?>