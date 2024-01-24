<?php 
	$status = $_POST['status']; 
	$db = getDB();
	
	$sql_xpi_h = "BEGIN ISWS.diskon_nota_lolo.header_nota_dev; END;";
	$db->query($sql_xpi_h);
	
	$sql_xpi_dtl = "BEGIN ISWS.diskon_nota_lolo.detail_nota_dev; END;";
	$db->query($sql_xpi_dtl);
	
	echo $status;
?>