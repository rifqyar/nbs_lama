<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl	=  xliteTemplate('cetak_rbm.html');
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);	
	$tl->renderToScreen();
?>
