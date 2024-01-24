<?php
 $u = $_GET['bay'];
 $tl = xliteTemplate('stowage_bay.htm');
 $tl->assign("bay",$u);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

