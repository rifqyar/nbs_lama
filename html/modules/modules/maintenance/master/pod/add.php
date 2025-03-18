<?php
 $tl = xliteTemplate('form_add_edit.htm');
 $stat = "tambah" ;
 $tl->assign('status',$stat);
 $tl->renderToScreen();
?>