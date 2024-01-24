<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit_cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT MASTER_CONTAINER.*,
					       CONTAINER_DELIVERY.*,
					       TO_CHAR (CONTAINER_DELIVERY.TGL_DELIVERY, 'YYYY-MM-DD') TGL_DELIVERY
					  FROM MASTER_CONTAINER
					       LEFT JOIN CONTAINER_DELIVERY
					          ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
					       LEFT JOIN HISTORY_CONTAINER
					          ON CONTAINER_DELIVERY.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
					          AND CONTAINER_DELIVERY.NO_REQUEST = HISTORY_CONTAINER.NO_REQUEST
					          AND HISTORY_CONTAINER.KEGIATAN = 'REQUEST_DELIVERY'
					 WHERE CONTAINER_DELIVERY.NO_REQUEST = '$no_req'
					 ORDER BY HISTORY_CONTAINER.TGL_UPDATE ASC"; 
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID); 
	
	$tl->renderToScreen();
?>
