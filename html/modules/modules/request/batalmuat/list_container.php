<?php

//print_R("coba");die;
$tl = xliteTemplate('list_container.htm');
$id=$_GET['no_req'];
$db=getDB();
$query1="SELECT a.*, b.UKURAN, b.TYPE_ FROM tb_req_receiving_d a, master_container b WHERE a.NO_CONTAINER = b.NO_CONTAINER AND a.ID_REQ = '$id' ";
$result_l=$db->query($query1);
$row1=$result_l->getAll();

$tl->assign("list",$row1);
$tl->assign("no",$id);
$tl->renderToScreen();
?>