<?php
	$tl = xliteTemplate('form_add_edit.htm');
	$id = $_GET['ID'];
	
	$db = getDB();
	$query = "SELECT * FROM TB_USER WHERE ID = '$id'";
	$result = $db->query($query);	
	$row = $result->fetchRow();
	$tl->assign('ubah',$row);
	$stat = "ubah" ;
    $tl->assign('status',$stat);
	$tl->renderToScreen(); 
?>