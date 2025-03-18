<?php 
	$u=$_GET['id'];
	
	$tl =  xliteTemplate('list_container.htm');
	$db=  getDB();
	$query="SELECT B.UKURAN,B.TYPE,B.STATUS,A.HAZZARD,A.ID_REQUEST,A.NO_CONTAINER from BH_DETAIL_REQUEST A, MASTER_BARANG B WHERE A.ID_BARANG=B.KODE_BARANG AND A.ID_REQUEST='$u'";
	$result_d=$db->query($query);
	$rowd=$result_d->getAll();
	$tl->assign("list",$rowd);
	$tl->assign("req",$u);
	$tl->renderToScreen();	
?>
	