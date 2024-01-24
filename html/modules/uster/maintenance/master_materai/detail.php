<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');
        
	$db 	= getDB("storage");
	//$id_group_tarif	= $_GET["from"]; 
	$id_iso			= $_GET["id_iso"];
	//sprint_r($id_iso);die();


	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT * FROM MASTER_MATERAI WHERE ID = '$id_iso'";
                
        
	}
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->fetchRow(); 

	$tl->assign("row",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
