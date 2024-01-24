<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('cont_list_strp.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, PLAN_CONTAINER_STRIPPING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
                           FROM PLAN_CONTAINER_STRIPPING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A            
                           ON PLAN_CONTAINER_STRIPPING.NO_CONTAINER = A.CONT_NO_BP
                           WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = REPLACE('$no_req','S','P')";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	//echo $jumlah;
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
