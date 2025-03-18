<?php
	 $db=  getDB();
 
	 $id_vessel 	= $_POST['no_ukk'];
	 
	 $query			= "UPDATE RBM_H SET STATUS = 'OPEN', KOREKSI = 'Y', EMAIL = 'N' WHERE NO_UKK = '$id_vessel'";
	 
	  
	$query_i 	= "SELECT NVL(MAX(COUNTER),0) COUNT FROM LOG_RBM where NO_UKK='$id_vessel' AND KETERANGAN = 'RBM_ICT'";
	$result_i   = $db->query($query_i);
	$count_     = $result_i->fetchrow();
	$count 		= $count_['COUNT'];
	
	
	 if ($db->query($query)) {
	 
		 $query2	= "INSERT INTO LOG_RBM(TGL_UPDATE,STATUS_RBM, KETERANGAN,NAMA_FILE,NO_UKK,STATUS, COUNTER) VALUES (SYSDATE,'KOREKSI','RBM_ICT','','$id_vessel','KOREKSI','$count')";
		 $query3	= "INSERT INTO LOG_RBM(TGL_UPDATE,STATUS_RBM, KETERANGAN,NAMA_FILE,NO_UKK,STATUS, COUNTER) VALUES (SYSDATE,'KOREKSI','RBM_IPC','','$id_vessel','KOREKSI','$count')";
		 
		 $db->query($query2);
		 $db->query($query3);
		 
		echo 'OK';
	 } else {
		echo 'NOT';
	 }
?>
