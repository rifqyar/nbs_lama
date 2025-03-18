<?php
 $tl = xliteTemplate('home.htm'); 
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>