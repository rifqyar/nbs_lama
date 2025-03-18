<?php
	$ukk=$_POST['NO_UKK'];
	$db=getDb();
	//$db->query("begin ISWS.PROC_SYNC_MAP('$ukk','SYNC');end;");
	
	$db->query("begin proc_sync_map_new('$ukk');end;");
	
	echo "sukses";
?>