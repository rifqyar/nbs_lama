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
		$query_list = "SELECT MASTER_CONTAINER.*, CONTAINER_DELIVERY.*
						  FROM MASTER_CONTAINER
						       INNER JOIN
						          CONTAINER_DELIVERY
						       ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
						       INNER JOIN 
						            HISTORY_CONTAINER
						       ON HISTORY_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
						       AND HISTORY_CONTAINER.NO_REQUEST = CONTAINER_DELIVERY.NO_REQUEST
						       AND HISTORY_CONTAINER.KEGIATAN = 'REQUEST DELIVERY'
						 WHERE CONTAINER_DELIVERY.NO_REQUEST = '$no_req'
						 ORDER BY TGL_UPDATE ASC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
