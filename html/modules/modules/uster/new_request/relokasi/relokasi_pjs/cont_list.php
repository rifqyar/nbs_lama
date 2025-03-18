<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$nota	= $_GET["nota"]; 
	$db 	= getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT MASTER_CONTAINER.*, CONTAINER_RELOKASI.* 
		FROM MASTER_CONTAINER INNER JOIN CONTAINER_RELOKASI ON 
		MASTER_CONTAINER.NO_CONTAINER = CONTAINER_RELOKASI.NO_CONTAINER 
		WHERE CONTAINER_RELOKASI.NO_REQUEST = '$no_req'";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("nota",$nota);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
