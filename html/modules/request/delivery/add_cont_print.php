<?php
$tl = xliteTemplate('add_print_sp2.htm');
$id=$_GET['id'];
//print_r($id);die;
$tl->assign("no",$id);
$tl->rendertoscreen();
?>