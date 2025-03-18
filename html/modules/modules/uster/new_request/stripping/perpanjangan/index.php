<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage"); 
	
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
