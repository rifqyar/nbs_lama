<?php
	$tl = xliteTemplate('form_add_edit.htm');
	$id = $_GET['id'];
	$db = getDB();
	$query = "SELECT * FROM tb_master_ship_line WHERE ID_SHIP = '$id'";
	$result = $db->query($query);	
	$row = $result->fetchRow();
	$tl->assign('ubah',$row);
	$stat = "ubah" ;
    $tl->assign('status',$stat);
	$tl->renderToScreen(); 
?>