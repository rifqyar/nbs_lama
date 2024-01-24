<?php
		$db=  getDB();
 
		 $id_vessel 	= $_POST['no_ukk'];
		 
		 $query			= "UPDATE RBM_H SET STATUS = 'CLOSED' WHERE NO_UKK = '$id_vessel'";
		
		$db->query($query);
		//$i=1;

		
		//require_once('cetak/save_rbm2.php');

		//include("cetak/save_rbm.php");  

		//include("cetak/save_rbm2.php");  

		
		 $query2		= "SELECT KOREKSI, NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK = '$id_vessel'";
		 $result_q2		= $db->query($query2);
		 $data2			= $result_q2->fetchROw();
		 
		 $koreksi 		= $data2['KOREKSI'];
		 $nm_kapal 		= $data2['NM_KAPAL'];
		 $voy_in 		= $data2['VOYAGE_IN'];
		 $voy_out 		= $data2['VOYAGE_OUT'];
		 
		 if ($koreksi == 'Y'){
			$kor = 'KOREKSI';
		 } else {
			$kor = 'NEW';
		 }
		 
		$query_i 	= "SELECT NVL(MAX(COUNTER),0)+1 COUNT FROM LOG_RBM where NO_UKK='$id_vessel' AND KETERANGAN = 'RBM_ICT'";
		$result_i   = $db->query($query_i);
		$count_     = $result_i->fetchrow();
		$count 		= $count_['COUNT'];
				 
		 $nama_file = 'RBM_ICT_'.$nm_kapal.'_'.$voy_in.$voy_out.'_'.$count.'.pdf';
		 
		// $nama_file = 'dama';
		 // $nama_file_ = 'dama_';
		 
		 //$query_3	= "INSERT INTO LOG_RBM(TGL_UPDATE,STATUS_RBM, KETERANGAN,NAMA_FILE,NO_UKK,STATUS) VALUES (SYSDATE,'$kor','RBM_ICT','$nama_file','$id_vessel','SAVE')";
		 
		  $query_4	= "INSERT INTO LOG_RBM(TGL_UPDATE,STATUS_RBM, KETERANGAN,NAMA_FILE,NO_UKK,STATUS, COUNTER) VALUES (SYSDATE,'$kor','RBM_ICT','$nama_file','$id_vessel','SAVE','$count')";
		 
		 
		 if ($db->query($query_4)){
		// exec("convert report/pdf/sample.pdf report/images/sample.jpg");
		//$pdf_file = escapeshellarg( "report/pdf/sample.pdf" );
		//$jpg_file = escapeshellarg( "report/images/sample.jpg" );

		//$result = 0;
		//exec( "convert -density 300 {$pdf_file} {$jpg_file}", null, $result );
		require_once('cetak/save_rbm.php');
		echo 'OK'; } else {
		echo 'NOT';
		}

?>
