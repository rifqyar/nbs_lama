<?php
	$ukk=$_POST['NO_UKK'];
	$db=getDb();
	$db->query("begin ISWS.PROC_SYNC_MAP('$ukk','SYNC');end;");
	echo "sukses";
?>