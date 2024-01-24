<?php
	$db = getDB("storage");
	$no_container = $_POST["NO_CONT"];
	$que = "select no_booking from request_delivery, container_delivery 
			where request_delivery.no_request = container_delivery.no_request and delivery_ke = 'TPK' 
			and to_date(tgl_request,'dd/mm/yy') between to_date('09/04/2013','dd/mm/yy') and  to_date('11/04/2013','dd/mm/yy') and jn_repo = 'EMPTY'
			and no_container = '$no_container'";
	$rque = $db->query($que);
	$rwq = $rque->fetchRow();
	
	echo $rwq["NO_BOOKING"];
	exit();
?>