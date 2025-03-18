<?php

	$tl =  xliteTemplate('list_cont.htm');
	$no_request	= $_GET["no_req"];
	
	$db				= getDB("storage");
	$query_cont		= "SELECT * FROM CONTAINER_STUFFING WHERE NO_REQUEST = '$no_request' AND NO_CONTAINER NOT LIKE '%rename%'";
	$result_cont	= $db->query($query_cont);
	$row_cont		= $result_cont->getAll();
	
	$tl->assign("row_cont",$row_cont);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
