<?php
$tl = xliteTemplate('tes.htm','_site');
$tl->assign('s',$sql);
$tl->renderToScreen();
