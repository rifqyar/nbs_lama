<?php
	$db=getDb();
	
	$no_req=$_POST['REQ'];
	$old_req=$_POST['OLDREQ'];
	$ke=$_POST['KE'];
	$kes = $ke + 1;
	
	
	$query = "begin proc_delivery_d('$no_req','$old_req', '$kes'); end;";
	
	print_r($query);die;
	$db->query($query);
	echo "OK";
?>