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
		
	
		// $query_list		= "SELECT DISTINCT PLAN_CONTAINER_STUFFING.*, PLAN_CONTAINER_STUFFING.COMMODITY COMMO, M.SIZE_ KD_SIZE, M.TYPE_ KD_TYPE
                           // FROM PLAN_CONTAINER_STUFFING LEFT JOIN MASTER_CONTAINER M        
                           // ON PLAN_CONTAINER_STUFFING.NO_CONTAINER = M.NO_CONTAINER
                           // WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req'";
						   
		$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STUFFING.*, 										
										    TO_CHAR(PLAN_CONTAINER_STUFFING.TGL_APPROVE,'dd-mm-yyyy') APPROVE,
											TO_CHAR(PLAN_CONTAINER_STUFFING.START_STACK,'dd-mm-yyyy') STACK,
										   PLAN_CONTAINER_STUFFING.COMMODITY COMMO, 
										   M.SIZE_ KD_SIZE, M.TYPE_ KD_TYPE
                           FROM PLAN_CONTAINER_STUFFING LEFT JOIN MASTER_CONTAINER M        
                           ON PLAN_CONTAINER_STUFFING.NO_CONTAINER = M.NO_CONTAINER
                           WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = '$no_req'";
		
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
