<?php
		$db 	= getDB("storage");
	$id_group_tarif	= $_POST["id_group_tarif"]; 
	$id_iso			= $_POST["id_iso"];
	$tarif			= $_POST["tarif"];

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		//echo "UPDATE MASTER_TARIF SET TARIF = '$tarif' WHERE ID_GROUP_TARIF = '$id_group_tarif' AND ID_ISO = '$id_iso'";die;
		$query_list = "UPDATE MASTER_TARIF SET TARIF = '$tarif' WHERE ID_GROUP_TARIF = '$id_group_tarif' AND ID_ISO = '$id_iso'";
                
        
	}
	$db->query($query_list);

	
    header('Location: '.HOME.APPID.'/index');
?>
