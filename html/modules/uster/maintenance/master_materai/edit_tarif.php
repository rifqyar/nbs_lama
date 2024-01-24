<?php
		$db 	= getDB("storage");
$no_peraturan	= $_POST["no_peraturan"]; 
$tgl_peraturan	= $_POST["from"]; 
$deposit		= $_POST["deposit"]; 
$id		= $_POST["id"];
$status = $_POST["status"];
	//print_r($id);die();

	
		//echo "UPDATE MASTER_TARIF SET TARIF = '$tarif' WHERE ID_GROUP_TARIF = '$id_group_tarif' AND ID_ISO = '$id_iso'";die;
		$query_list = "UPDATE MASTER_MATERAI SET NO_PERATURAN = '$no_peraturan', TGL_PERATURAN=TO_DATE('$tgl_peraturan', 'YYYY-MM-DD HH24:MI:SS'), NOMINAL='$deposit',SALDO='$deposit' WHERE ID = '$id' AND SALDO=NOMINAL";
		//print_r($query_list);die();
                
        
	
	$db->query($query_list);

	
    header('Location: '.HOME.APPID.'/index');
?>
