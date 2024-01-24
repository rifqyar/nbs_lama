<?php
	$plx=$_POST['deli'];
	$id=$_POST['id'];
	$id_req=$_GET['id_req'];
	$plo=$_POST['plo'];
	//ECHO $plo;die;
	$db=getdb();
	//$sql = "SELECT CEIL((((86400*(TO_DATE('". $_POST["plx"] ."','DD-MM-YYYY HH24:MI') - TO_DATE('".$_POST["plo"]."','DD-MM-YYYY HH24:MI')))/60)/60)/ 8) AS JML FROM DUAL";
	//echo $sql; die();
	//$rs = $db->query($sql);
	//$row = $rs->FetchRow();
	
	$query="update req_delivery_d set tgl_delivery=to_date('$plx','dd-mm-rrrr') where no_container='$id' and ID_REQ=$id_req";
	
	$db->query($query);
?>