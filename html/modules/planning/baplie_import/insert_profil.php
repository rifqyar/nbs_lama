<?php
 $u = $_GET['id'];
 $tl = xliteTemplate('insert_profil.htm');
 $tl->assign("req",$u);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

