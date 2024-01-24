<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('pranota.htm');
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);	
	$tl->renderToScreen();
?>
