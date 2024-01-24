<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('home.htm');
	
	// assignment variabel
	/*
		$tl->assign("variable1", "PELINDO II");
		$tl->assign("variable2", "TELKOM");
		$tl->assign("username", "HUGO");
	*/
	// block aplikasi
	/*
	$db = getDB("storage");
	
	$query_news = "select * from MASTER_CONTAINER";
	$result = $db->query($query_news);
	$row = $result->getAll();
	*/
	//debug($row);
	//debug($_SESSION);
	$tl->renderToScreen();
?>
