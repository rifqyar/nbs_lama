<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage"); 
	
	$query  = "SELECT a.ID, a.NAME, a.YARD, a.SLOT_, a.ROW_, a.TIER_, a.GROUND_SLOT, a.CAPACITY, COUNT(b.NO_CONTAINER) USED, (a.CAPACITY-COUNT(b.NO_CONTAINER)) AVA FROM BLOCKING_AREA a, PLACEMENT b WHERE a.ID_YARD_AREA = '46' and a.id = b.id_blocking_area(+) and a.AKTIF='Y' GROUP BY a.ID, a.NAME, a.YARD, a.SLOT_, a.ROW_, a.TIER_, a.GROUND_SLOT, a.CAPACITY";
	$start	=  $db->query($query);
	$data	= $start->getAll();
	$tl->assign("row_list",$data);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
