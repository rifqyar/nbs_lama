<?php 
	$no_ukk=$_POST["NO_UKK"];	
	
	$db=getDB();
	$sql_xpi = "BEGIN ISWS.cont_movement.cont_mvmt_h('$no_ukk'); END;";
	$db->query($sql_xpi);
	
	echo "sukses";
?>