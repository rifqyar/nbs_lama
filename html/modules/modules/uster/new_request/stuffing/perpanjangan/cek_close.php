<?php
$db = getDB("storage");
$no_booking = $_POST["no_booking"];
$querys			= "select COUNT(*) cek
				from v_booking_stack_tpk a
				where a.NO_BOOKING = '".$no_booking."'
				and to_timestamp(sysdate) > to_timestamp (a.DOC_CLOSING_DATE_DRY)";
				
$results		= $db->query($querys);
$rows			= $results->fetchRow();
if($rows['CEK'] > 0){
	echo 'Y';
	exit();
}
else{
	echo 'A';
	exit();
}
?>