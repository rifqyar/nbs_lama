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

$query_list ="  SELECT MASTER_CONTAINER.*, CONTAINER_DELIVERY.*,TD.KD_PMB_DTL , YARD_AREA.NAMA_YARD
                    FROM MASTER_CONTAINER INNER JOIN CONTAINER_DELIVERY ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
                         INNER JOIN YARD_AREA ON CONTAINER_DELIVERY.ID_YARD= YARD_AREA.ID,
                         PETIKEMAS_CABANG.TTD_CONT_EXBSPL TD
                    WHERE CONTAINER_DELIVERY.NO_REQUEST = '$no_req'
                          AND TD.KD_PMB = '$no_req2'
                          AND TD.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER ";
	}
	//echo $query_list;exit;
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
?>
