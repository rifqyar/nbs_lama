<?php
	 $db=  getDB();
	 	 
	 $id_vessel 	= $_POST['no_ukk'];
		//sync rbm	------
		$sql_xpi = "BEGIN ISWS.gd_rbm_ict.fix_rbm_tarif('$id_vessel'); END;";
		$db->query($sql_xpi);
	
		$sql_xpi2 = "BEGIN ISWS.gd_rbm_ict.fix_rbm_shift('$id_vessel'); END;";
		$db->query($sql_xpi2);
		//sync rbm ----	
	 $query			= "UPDATE RBM_H SET STATUS = 'FINAL', PRANOTA = 'Y', TGL_FINAL = SYSDATE, NOTA='N' WHERE NO_UKK = '$id_vessel'";

	if ($db->query($query)){
		 $query2		= "SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK = '$id_vessel'";
		 $result_q2		= $db->query($query2);
		 $data2			= $result_q2->fetchROw();
		 
		$query_i 	= "SELECT NVL(MAX(COUNTER),0) COUNT FROM LOG_RBM where NO_UKK='$id_vessel' AND KETERANGAN = 'RBM_IPC'";
		$result_i   = $db->query($query_i);
		$count_     = $result_i->fetchrow();
		$count 		= $count_['COUNT'];
		
		 $nm_kapal 		= $data2['NM_KAPAL'];
		 $voy_in 		= $data2['VOYAGE_IN'];
		 $voy_out 		= $data2['VOYAGE_OUT'];
		 
		 $nama_file = 'RBM_ICT_'.$nm_kapal.'_'.$voy_in.$voy_out.'_'.$count.'.pdf';
		  $nama_file_ = 'RBM_IPC_'.$nm_kapal.'_'.$voy_in.$voy_out.'_'.$count.'.pdf';
		 
		 $query3	= "INSERT INTO LOG_RBM(TGL_UPDATE,STATUS_RBM, KETERANGAN,NAMA_FILE,NO_UKK,STATUS, COUNTER) VALUES (SYSDATE,'FINAL','RBM_ICT','$nama_file','$id_vessel','SAVE','$count')";
		 $db->query($query3);
		  $query4	= "INSERT INTO LOG_RBM(TGL_UPDATE,STATUS_RBM, KETERANGAN,NAMA_FILE,NO_UKK,STATUS, COUNTER) VALUES (SYSDATE,'FINAL','RBM_IPC','$nama_file_','$id_vessel','SAVE','$count')";
		 $db->query($query4);
		 
		  
		$query_k 	= "SELECT NVL(MAX(COUNTER),0)+1 COUNT FROM LOG_RBM where NO_UKK='$id_vessel' AND KETERANGAN = 'PRANOTA'";
		$result_k   = $db->query($query_k);
		$count_k    = $result_k->fetchrow();
		$counter 	= $count_k['COUNT'];
		
		 $nama_file_pranota = 'PRANOTA_'.$nm_kapal.'_'.$voy_in.$voy_out.'_'.$counter.'.pdf';
		 
		 $query3	= "INSERT INTO LOG_RBM(TGL_UPDATE,STATUS_RBM, KETERANGAN,NAMA_FILE,NO_UKK,STATUS, COUNTER) VALUES (SYSDATE,'NEW','PRANOTA','$nama_file_pranota','$id_vessel','SAVE', '$counter')";
		 $db->query($query3);
		 
		$id_vessel 	= $_POST['no_ukk'];
		require_once('cetak/print_pranota.php');		 
		echo 'OK';
	 } else {
		echo 'NOT';
	 }
?>
