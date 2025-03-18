<?php
$db = getDB("storage");
$no_booking = $_POST["no_booking"];
$querys			= "select count(*) cek from m_vsb_voyage@dbint_link where 'BS'||vessel_code||id_vsb_voyage =  '$no_booking' and sysdate > to_date(clossing_time,'yyyymmddhh24miss')";
				
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