<?php
	$db=getDb();
	
	$no_req=$_POST['noreq_p'];
	$old_req=$_POST['old_noreq_p'];
	$ke=$_POST['v_reqke_p'];
	$kes = $ke + 1;
	$cont = $_POST['choosen'];
	$jum = count($cont);
	print_r($cont);
	//die();
	/*echo "string";
	print_r($no_req);
	echo "string";
	print_r($old_req);
	echo "string";
	print_r($ke);
	die();*/
	for ($i=0; $i < $jum; $i++) { 
		$filter .= "'".$cont[$i]."'";
		if ($i != $jum-1) {
			$filter .= ",";
		}
	}
	/*echo $filter;
	die();*/
   $query_filter = '';
   if (trim($filter) != ''){
      $query_filter = " AND NO_CONTAINER NOT IN (".$filter.")";
   }
	$get_not = $db->query("SELECT NO_CONTAINER FROM REQ_DELIVERY_D WHERE NO_REQ_DEV = '$no_req' $query_filter");
   
	$r_not 	= $get_not->getAll();
	foreach ($r_not as $key) {
		$delete = "DELETE FROM REQ_DELIVERY_D WHERE NO_REQ_DEV = '$no_req' AND NO_CONTAINER = '".$key[NO_CONTAINER]."'";
		$db->query($delete);
	}

	
	$query = "begin proc_delivery_d('$no_req','$old_req', '$kes'); end;";
	
	if ($db->query($query)) {
		header("Location: ".HOME."request.delivery.sp2pnew/");
	}
		

	
?>