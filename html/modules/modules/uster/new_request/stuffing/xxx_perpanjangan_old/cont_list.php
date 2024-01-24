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
		$query_list		= "SELECT DISTINCT CONTAINER_STUFFING.NO_CONTAINER,CONTAINER_STUFFING.HZ, CONTAINER_STUFFING.TGL_APPROVE, CONTAINER_STUFFING.TGL_APPROVE+1 TGL_MULAI, CONTAINER_STUFFING.COMMODITY, M.SIZE_ KD_SIZE, M.TYPE_ KD_TYPE
                           FROM CONTAINER_STUFFING LEFT JOIN MASTER_CONTAINER M        
                           ON CONTAINER_STUFFING.NO_CONTAINER = M.NO_CONTAINER
                           WHERE CONTAINER_STUFFING.NO_REQUEST = '$no_req'
						   AND AKTIF = 'Y'";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
