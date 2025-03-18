<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('preview_nota.htm');
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);	
	$tl->renderToScreen();
?>
