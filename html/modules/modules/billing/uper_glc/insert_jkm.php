<?php
 $u = $_GET['id'];
 $tl = xliteTemplate('insert_jkm.htm');
 $tl->assign("req",$u);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

