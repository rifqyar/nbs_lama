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
                                  CONTAINER_RECEIVING.* ,
                                   YARD_AREA.NAMA_YARD AS NAMA_YARD
                           FROM MASTER_CONTAINER 
                           INNER JOIN CONTAINER_RECEIVING  ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_RECEIVING.NO_CONTAINER 
                             JOIN USTER.YARD_AREA YARD_AREA ON CONTAINER_RECEIVING.DEPO_TUJUAN = YARD_AREA.ID
                           WHERE CONTAINER_RECEIVING.NO_REQUEST = '$no_req'";
						   
		/*
		$query_list		= "SELECT REQ_DEL.KD_SIZE AS SIZE_, REQ_DEL.KD_TYPE AS TYPE_, CONTAINER_RECEIVING.*
						   FROM PETIKEMAS_CABANG.TTD_DEL_REQ REQ_DEL 
						   INNER JOIN USTER.CONTAINER_RECEIVING CONTAINER_RECEIVING 
						   ON REQ_DEL.CONT_NO_BP = CONTAINER_RECEIVING.NO_CONTAINER 
						   WHERE CONTAINER_RECEIVING.NO_REQUEST = '$no_req'";
						   */
	}
	

	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
