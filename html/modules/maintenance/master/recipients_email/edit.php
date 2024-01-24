<?php
	$tl = xliteTemplate('form_add_edit.htm');
	$id = $_GET['kode'];
	
	$db = getDB();
	$query = "SELECT * FROM MASTER_VESSEL WHERE KODE_KAPAL = '$id'";
	$result = $db->query($query);	
	$row = $result->fetchRow();
	$tl->assign('ubah',$row);
	$stat = "ubah" ;
    $tl->assign('status',$stat);
	$tl->renderToScreen(); 
?>