<?php 
	$u=$_GET['id'];
	
	$tl =  xliteTemplate('list_container.htm');
	$db=  getDB();
	$query="SELECT B.UKURAN,B.TYPE,B.STATUS,A.HZ,A.JUMLAH,A.ID_REQ,A.ID_CONT,A.DATE_IN,A.NO from TB_REQ_DELIVERY_D A, MASTER_BARANG B WHERE A.ID_CONT=B.KODE_BARANG AND A.ID_REQ='$u' order by A.NO";
	$result_d=$db->query($query);
	$rowd=$result_d->getAll();
	$tl->assign("list",$rowd);
	$tl->assign("req",$u);
	$tl->renderToScreen();	
?>
	