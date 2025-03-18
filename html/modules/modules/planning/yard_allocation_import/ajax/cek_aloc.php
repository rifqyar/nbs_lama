<?php
	$db=getDb();
	$gb=$_POST['BLOK'];
	$rr=$db->query("select POSISI from yd_blocking_area where ID='$gb'");
	$rt=$rr->fetchrow();

	echo $rt['POSISI'];
	
?>