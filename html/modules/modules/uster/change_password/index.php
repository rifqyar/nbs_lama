<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('home.htm');
	$id_user = $_SESSION["LOGGED_STORAGE"];
	$db = getDB("storage");
	$q_user = "SELECT * FROM MASTER_USER WHERE ID = '$id_user'";
	$r_user = $db->query($q_user);
	$row_u = $r_user->fetchRow();
	$tl->assign("row_u",$row_u);
	// assignment variabel
	/*
		$tl->assign("variable1", "PELINDO II");
		$tl->assign("variable2", "TELKOM");
		$tl->assign("username", "HUGO");
	*/
	// block aplikasi
	/*
	
	
	$query_news = "select * from MASTER_CONTAINER";
	$result = $db->query($query_news);
	$row = $result->getAll();
	*/
	//debug($row);
	//debug($_SESSION);
	$tl->renderToScreen();
?>
