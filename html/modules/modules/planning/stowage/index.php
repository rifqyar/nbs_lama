<?php
 $tl = xliteTemplate('view_yard.htm');
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>