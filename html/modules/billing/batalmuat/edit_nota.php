<?php
 $tl = xliteTemplate('edit_nota.htm');
 $no_req = $_GET['no_req'];
 
 $tl->assign('id_req',$no_req);
 $tl->renderToScreen();
?>