<?php
	$id_vs   = $_POST['ID_VS'];
	$date_op = $_POST['DATE_OP'];
	$name 	 = $_SESSION["NAMA_PENGGUNA"];
	
	$db=getDb();
	$header=$db->query("UPDATE RBM_H SET OPEN_STACK=TO_DATE('$date_op','DD/MM/YYYY HH24:MI') WHERE NO_UKK='$id_vs'");
	
	$query_log = "INSERT INTO LOG_CLOPEN_STACK (TYPE_UPDATE_, TIME_SET_, USER_ID, ID_VS, TIME_UPDATE_) 
				VALUES ('OS',TO_DATE('$date_op','DD/MM/YYYY HH24:MI'),'$name','$id_vs',SYSDATE)";
				
	$db->query($query_log);			
	
?>