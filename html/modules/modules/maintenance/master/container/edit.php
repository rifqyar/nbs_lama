<?php
	$tl = xliteTemplate('form_ubah.htm');
	$id = $_GET['id'];
	$db = getDB("manual");
	$query = "SELECT * FROM tb_container_master WHERE ID_CONT = $id";
	$result = $db->query($query);	
	$row = $result->getAll();
	$tl->assign('ubah',$row);
	$tl->renderToScreen(); 
?>