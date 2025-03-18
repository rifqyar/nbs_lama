<?php
$tl = xliteTemplate('edit.htm');
//$id=$_GET['id'];
$id=$_GET['no_req'];

$db=getDB();
$query="SELECT * from REQ_DELIVERY_H WHERE ID_REQ='$id'";
// echo $query; die;
$result_q=$db->query($query);
$row=$result_q->fetchRow();

$tl->assign("detail",$row);
$tl->assign("no",$id);
$tl->assign("tgl",date('d-m-Y H:i'));
$tl->renderToScreen();
?>