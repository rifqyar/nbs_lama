<?php
	$cont=$_POST['NO_CONT'];
	$size_s=$_POST['SIZE_S'];
	$type_s=$_POST['TYPE_S'];
	
	$query="INSERT INTO MASTER_CONTAINER (NO_CONTAINER,UKURAN,TYPE_) VALUES ('$cont','$size_s','$type_s')";
	$db= getDb();
	$db->query($query);
	
	echo "container sukses di add";
?>