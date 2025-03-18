<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('placement_list.htm');

//-----------------paging
/*
	if(isset($_GET["page"]))
	{
		$page = $_GET["page"];	
	}
	else
	{
		$page = 1;	
	}
*/
//------------------------	
	$id_yard	= $_GET["yard"];
	$db 		= getDB("storage");

	$query_list		= "SELECT MASTER_CONTAINER.*, 
                        CONTAINER_RECEIVING.*
                    FROM CONTAINER_RECEIVING INNER JOIN MASTER_CONTAINER ON CONTAINER_RECEIVING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER 
                    AND CONTAINER_RECEIVING.AKTIF = 'Y' 
                    WHERE MASTER_CONTAINER.LOCATION = 'GATI' AND CONTAINER_RECEIVING.DEPO_TUJUAN = '$id_yard' AND rownum <= 20 ORDER BY CONTAINER_RECEIVING.NO_CONTAINER DESC";
	
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
