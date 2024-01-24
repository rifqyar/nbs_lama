<?php
	$tl	=  xliteTemplate('home1.htm');
	
	$no_ukk=$_GET["id_vessel"];
	//debug($no_ukk);die;
	
	$tl->assign("no_ukk",$no_ukk);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);	
	$tl->renderToScreen();
?>
