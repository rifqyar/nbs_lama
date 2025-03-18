<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit_cont_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");
	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
	
		$query_list ="select *
					from container_batal_muat cbm
					inner join master_container mc
					on cbm.no_container = mc.no_container where no_request = '$no_req'";
	}
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
?>
