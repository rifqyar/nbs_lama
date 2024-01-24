<?php
	$plx=$_POST['plx'];
	$id=$_POST['id'];
	$id_req=$_GET['id_req'];
	$plo=$_POST['plo'];
	//ECHO $plo;die;
	$db=getdb();
	$sql = "SELECT CEIL((((86400*(TO_DATE('". $_POST["plx"] ."','DD-MM-YYYY HH24:MI') - TO_DATE('".$_POST["plo"]."','DD-MM-YYYY HH24:MI')))/60)/60)/ 8) AS JML FROM DUAL";
	$rs = $db->query($sql);
	$row = $rs->FetchRow();
	
	$query="update req_delivery_d set plug_out_ext=to_date('$plx','dd-mm-yyyy hh24:mi'), JML_SHIFT=".$row['JML']." where no_container='$id' and no_req_dev=$id_req";
	
	$db->query($query);
?>