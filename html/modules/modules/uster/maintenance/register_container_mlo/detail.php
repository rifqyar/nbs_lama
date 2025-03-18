<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');
        
	$db 	= getDB("storage");
	$id_group_tarif	= $_GET["id_group_tarif"]; 
	$id_iso			= $_GET["id_iso"];

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT a.ID_ISO, a.ID_GROUP_TARIF, b.KATEGORI_TARIF, a.TARIF FROM MASTER_TARIF a, GROUP_TARIF b WHERE a.ID_GROUP_TARIF = b.ID_GROUP_TARIF AND a.ID_GROUP_TARIF = '$id_group_tarif' AND ID_ISO = '$id_iso'";
                
        
	}
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->fetchRow(); 

	$tl->assign("row",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
