<?php
		$db 	= getDB("storage");
$no_peraturan	= $_POST["no_peraturan"]; 
$tgl_peraturan	= $_POST["from"]; 
$deposit		= $_POST["deposit"]; 
$id		= $_POST["id"];
$status = $_POST["status"];
	//print_r($id);die();

	
		//echo "UPDATE MASTER_TARIF SET TARIF = '$tarif' WHERE ID_GROUP_TARIF = '$id_group_tarif' AND ID_ISO = '$id_iso'";die;
		$query_list = "UPDATE MASTER_MATERAI
		SET NO_PERATURAN='SI-00153/SK/WPJ.19/KP.0403/2020', TGL_PERATURAN=TIMESTAMP '2020-10-15 00:00:00.000000', NOMINAL=54000000, STATUS='Y', SALDO=999999, TERPAKAI=64020000
		WHERE ID=101;";
		//print_r($query_list);die();
                
        
	
	$db->query($query_list);

	
    header('Location: '.HOME.APPID.'/index');
?>
