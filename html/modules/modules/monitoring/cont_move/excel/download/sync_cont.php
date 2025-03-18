<?php 
	$no_ukk=$_POST["NO_UKK"];	
	
	$db=getDB();
	$sql_xpi = "BEGIN ISWS.cont_movement.cont_mvmt_tr('$no_ukk'); END;";
	$db->query($sql_xpi);
	
	/*$sql_xpi2 = "BEGIN ISWS.gd_rbm_ict.fix_rbm_shift('$no_ukk'); END;";
	$db->query($sql_xpi2);
	*/
	
	echo "sukses";
?>