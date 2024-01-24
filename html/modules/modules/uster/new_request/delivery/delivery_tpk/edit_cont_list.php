<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit_cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");
	//$no_req2	= substr($no_req,3);	
	$no_req2	= $_GET["no_req2"];	
	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
	/*
		$query_list = "SELECT MASTER_CONTAINER.*, CONTAINER_DELIVERY.*, YARD_AREA.NAMA_YARD 
FROM MASTER_CONTAINER INNER JOIN CONTAINER_DELIVERY ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER 
INNER JOIN YARD_AREA ON CONTAINER_DELIVERY.ID_YARD= YARD_AREA.ID
WHERE CONTAINER_DELIVERY.NO_REQUEST = '$no_req'";*/

$query_list =" SELECT DISTINCT MASTER_CONTAINER.*, CONTAINER_DELIVERY.*, YARD_AREA.NAMA_YARD, HISTORY_CONTAINER.TGL_UPDATE
				  FROM MASTER_CONTAINER
				       RIGHT JOIN CONTAINER_DELIVERY
				          ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
				       INNER JOIN YARD_AREA
				          ON CONTAINER_DELIVERY.ID_YARD = YARD_AREA.ID
				       INNER JOIN HISTORY_CONTAINER
				            ON HISTORY_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
				       AND HISTORY_CONTAINER.NO_REQUEST = CONTAINER_DELIVERY.NO_REQUEST
				       AND HISTORY_CONTAINER.KEGIATAN = 'REQUEST DELIVERY'
				       LEFT JOIN PETIKEMAS_CABANG.TTD_CONT_EXBSPL TD
				          ON TRIM (TD.NO_CONTAINER) = TRIM (CONTAINER_DELIVERY.NO_CONTAINER)
				 WHERE CONTAINER_DELIVERY.NO_REQUEST = '$no_req'
				       AND TD.KD_PMB = '$no_req2'
				       ORDER BY HISTORY_CONTAINER.TGL_UPDATE ASC";  
	}
	//echo $query_list;exit;
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
?>
