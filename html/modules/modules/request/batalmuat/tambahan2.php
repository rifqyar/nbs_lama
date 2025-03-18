<?php 
	$u=$_GET['id'];
	
	$tl =  xliteTemplate('list_container.htm');
	$db=  getDB();
	$query="SELECT B.NO_CONTAINER, 
	               A.ID_BATALMUAT, 
				   B.UKURAN, 
				   B.TYPE_, 
				   A.STATUS, 
				   A.HZ,
				   A.ID_DETAIL
				   FROM TB_BATALMUAT_D A, MASTER_CONTAINER B 
				   WHERE A.NO_CONTAINER=B.NO_CONTAINER 
				   AND A.ID_BATALMUAT='$u'
				   ORDER BY A.ID_DETAIL DESC";
				   
	$result_d=$db->query($query);
	$rowd=$result_d->getAll();
	$tl->assign("list",$rowd);
	$tl->assign("req",$u);
	$tl->renderToScreen();	
?>
	