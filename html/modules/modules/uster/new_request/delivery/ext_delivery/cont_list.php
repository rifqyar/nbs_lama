<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT MASTER_CONTAINER.*, CONTAINER_DELIVERY.* FROM MASTER_CONTAINER INNER JOIN CONTAINER_DELIVERY ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER WHERE 
		CONTAINER_DELIVERY.NO_REQUEST = '$no_req' AND CONTAINER_DELIVERY.AKTIF = 'Y' ORDER BY CONTAINER_DELIVERY.NO_CONTAINER";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
