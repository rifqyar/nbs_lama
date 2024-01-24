<?php

//print_R("coba");die;
$tl = xliteTemplate('edit.htm');
$id=$_GET['id'];
$db=getDB();
$query="SELECT * from TB_REQ_DELIVERY_H WHERE ID_REQ='$id'";
$result_q=$db->query($query);
$row=$result_q->fetchRow();
$tl->assign("detail",$row);
$tl->assign("no",$id);
$tl->renderToScreen();
?>