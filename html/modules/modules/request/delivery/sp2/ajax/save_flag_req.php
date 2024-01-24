<?php
	$noreq=$_POST['noreq'];
	$db=getDb();
	$param_b_var= array(	
		"v_noreq"=>"$noreq"
	);
	
	$query = "UPDATE REQ_DELIVERY_H SET IS_EDIT=0 WHERE ID_REQ = :v_noreq";
	$db->query($query,$param_b_var);
?>