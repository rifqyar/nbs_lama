<?php
	header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage"); 
	
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();

	/*$db = getDB('storage');

	if(isset($_GET['table'])) {
		$table_name = $_GET['table'];
	$q = "SELECT column_name
FROM  USER_TAB_COLUMNS
WHERE  table_name = '$table_name'";
	$r = $db->query($q)->getAll();
	echo count($r)."<br/>";
	foreach ($r as $key) {
		echo $key['COLUMN_NAME'].",";
	}
	}
	die();*/
?>
