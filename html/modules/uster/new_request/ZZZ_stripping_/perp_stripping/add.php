<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('add.htm');
	
	//echo HOME.APPID;
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	print_r (date());
?>