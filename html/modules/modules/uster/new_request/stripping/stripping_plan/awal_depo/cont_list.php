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
		$query_list		= "SELECT MASTER_CONTAINER.*, 
								  PLAN_CONTAINER_STRIPPING.* 
						   FROM MASTER_CONTAINER 
						   INNER JOIN PLAN_CONTAINER_STRIPPING 
						   ON MASTER_CONTAINER.NO_CONTAINER = PLAN_CONTAINER_STRIPPING.NO_CONTAINER 
						   WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req'";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
