<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage"); 
	
	$query  = "SELECT a.NO_CONTAINER, b.SIZE_, b.TYPE_, a.NO_REQUEST, TO_CHAR(a.TGL_APPROVE,'dd/mm/yyyy') TGL_APPROVE, (TO_DATE(SYSDATE,'dd/mm/yyyy')-TO_DATE(a.TGL_APPROVE,'dd/mm/yyyy')+1) LAMA, 'Belum realisasi' STATUS
FROM container_stripping a, master_container b
where a.no_container = b.no_container
and (TO_DATE(SYSDATE,'dd/mm/yyyy')-TO_DATE(a.TGL_APPROVE,'dd/mm/yyyy')+1) > 3	";
	$start	=  $db->query($query);
	$data	= $start->getAll();
	$tl->assign("row_list",$data);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
