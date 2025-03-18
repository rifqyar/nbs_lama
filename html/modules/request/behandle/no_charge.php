<?php
		//die();
		$db=  getDB();
		$db2=  getDB('dbint');
		$no_req=$_POST['no_req'];
		
		$param_payment2= array(
						 "ID_NOTA"=>'TANPA_NOTA',
						 "ID_REQ"=>$no_req,
						 "OUT"=>'',
						 "OUT_MSG"=>''
						);
		$query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";
		$db2->query($query2,$param_payment2);
		
		$query="UPDATE REQ_BEHANDLE_H SET STATUS='F' WHERE ID_REQ='$no_req'";
		//echo($query);
		$db->query($query);
		echo("OK");
		die();
?>
