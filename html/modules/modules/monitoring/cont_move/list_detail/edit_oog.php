<?php
	$db = getDB();
	
	$no_ukk		= $_POST["NO_UKK"]; 
	$no_cont	= $_POST["NO_CONT"]; 
	$oog		= $_POST["OOG"];
	
	//$no_ukk=$_GET["no_ukk"];
	//$no_cont=$_GET["no_cont"];
	//$oog=$_GET["oog"];
	
	$query_request	= "UPDATE RBM_LIST
					   SET HEIGHT_CONT = '$oog'
					   WHERE NO_UKK = '$no_ukk'
						 AND NO_CONTAINER = '$no_cont'
						";
	$result_request	= $db->query($query_request);	
	
	//debug($result_request);die;
	
	//die;
	//header('Location: '.HOME'/billing.rbm.list_detail/list_detail?id_vessel='.$no_ukk);
?>
