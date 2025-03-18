<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage"); 
	
	$nama_yard	= $_SESSION["NAMAYARD_STORAGE"];
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	$tl->assign("nama_yard",$nama_yard);
	
	$tl->renderToScreen();
?>
