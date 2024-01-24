<?php
	//header('Location: '.HOME .'static/error.htm');		
	 $tl 			=  xliteTemplate('sent_email.htm');
	
	 $db			=  getDB();
 
	 $no_ukk 		= $_GET['id_vessel'];
	 
	 $query			= "SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK = '$no_ukk'";
	 $result_q		= $db->query($query);
	 $data			= $result_q->fetchROw();
	 
	 $kapal 		= $data['NM_KAPAL'];
	 $voy_in 		= $data['VOYAGE_IN'];
	 $voy_out 		= $data['VOYAGE_OUT'];
	
	 $query1		= "SELECT a.NAMA_FILE RBM_ICT, b.NAMA_FILE RBM_IPC
					  FROM (SELECT DISTINCT(NAMA_FILE)
							  FROM LOG_RBM
							 WHERE TGL_UPDATE =
									  (SELECT MAX (TGL_UPDATE)
										 FROM LOG_RBM
										WHERE NO_UKK = '$no_ukk' AND KETERANGAN = 'RBM_ICT') AND KETERANGAN = 'RBM_ICT') a,
						   (SELECT DISTINCT(NAMA_FILE)
							  FROM LOG_RBM
							 WHERE TGL_UPDATE =
									  (SELECT MAX (TGL_UPDATE)
										 FROM LOG_RBM
										WHERE NO_UKK = '$no_ukk' AND KETERANGAN = 'RBM_IPC') AND KETERANGAN = 'RBM_IPC' ) b";
	 $result_q1		= $db->query($query1);
	 $data1			= $result_q1->fetchRow();
	 
	 $rbm_ict     = $data1['RBM_ICT'];
	  $rbm_jict     = $data1['RBM_IPC'];
	/* $count 		= count($data);
	 $email = "";
	 $i = 1;
	 foreach ($data as $item){
		if ($i < $count){
			$email .= $email.'"'.$item['NAMA'].'" <'.$item['EMAIL'].'>,';
		} else {
			$email .= $email.'"'.$item['NAMA'].'" <'.$item['EMAIL'].'>';
		}
	$i++;
	 }*/
	 
	//echo $email;
	
	 $tl->assign("rbm_jict",$rbm_jict);
	 $tl->assign("rbm_ict",$rbm_ict);
	 $tl->assign("voy_in",$voy_in);
	 $tl->assign("voy_out",$voy_out);
	 $tl->assign("kapal",$kapal);
	 $tl->assign("data",$data);
	 $tl->assign("no_ukk",$no_ukk);
	 $tl->assign("email",$email);

	 $tl->renderToScreen();
?>
