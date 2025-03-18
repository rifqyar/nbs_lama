<?php 
	$u=$_GET['id'];
	$tl =  xliteTemplate('list_cont_print.htm');
	$db=  getDB();
	$query="SELECT B.UKURAN,B.TYPE,B.STATUS,A.HZ,A.ID_CONTAINER from TB_REQ_DELIVERY_CONT A, MASTER_BARANG B WHERE A.ID_BARANG=B.KODE_BARANG AND A.ID_REQ='$u' order by A.ID_CONTAINER";
	$result_d=$db->query($query);
	$rowd=$result_d->getAll();
	$tl->assign("list",$rowd);
	$tl->assign("req",$u);
	$tl->renderToScreen();	
?>
	