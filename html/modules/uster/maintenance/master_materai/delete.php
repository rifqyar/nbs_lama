<?php
	//header('Location: '.HOME .'static/error.htm');		
        
	$db 	= getDB("storage");
	//$id_group_tarif	= $_GET["from"]; 
	$id_iso			= $_GET["id_iso"];
	//sprint_r($id_iso);die();


	$query_list = "DELETE FROM MASTER_MATERAI WHERE ID = '$id_iso' and STATUS='N' and SALDO = NOMINAL";
                
        
	$result_list	= $db->query($query_list);
	header('Location: '.HOME.APPID.'/index');
?>
