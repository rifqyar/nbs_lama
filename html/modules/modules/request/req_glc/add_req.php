<?php
 $tl = xliteTemplate('form_tambah_edit.htm');
 $stat = "tambah" ;
 $remarks = $_GET['remarks'];
 $maxreq = $_GET['id_req'];
 if(isset($maxreq))
 {
	$tl->assign('max_req',$maxreq);
 }
 
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->assign('remark',$remarks);
 $tl->assign('status',$stat);
 $tl->renderToScreen();
?>