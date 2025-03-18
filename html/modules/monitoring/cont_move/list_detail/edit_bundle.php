<?php
	$db = getDB();
	
	//$list_cont		= explode('&',$_SERVER["QUERY_STRING"]);
	//echo $list_cont[0]."<br />" ;die;
	//debug($list_cont);die;
	$no_cont		= $_GET["no_cont"]; 
	$no_bundle		= $_GET["no_bundle"]; 
	
	
	$query_dsr_bundle_lama 	="SELECT NO_CONTAINER,
								 BUNDLE,
								 NO_UKK
							 FROM RBM_LIST
							 WHERE DASAR_BUNDLE = 'Y'
							 AND	  BUNDLE	   = '$no_bundle'";
	$result_dsr_bundle_lama	= $db->query($query_dsr_bundle_lama);
	$dsr_bundle_lama		= $result_dsr_bundle_lama->fetchRow();
	$dsr_bundle_lama		= $dsr_bundle_lama['NO_CONTAINER'];
	//$dsr_bundle_lama_n	= $dsr_bundle_lama['NO_CONTAINER'];
	$no_ukk		= $dsr_bundle_lama['NO_UKK'];
	
	$query_dsr_bundle_baru 	="SELECT NO_CONTAINER,
								 BUNDLE
							FROM RBM_LIST
							WHERE NO_CONTAINER = '$no_cont'
							AND	  BUNDLE	   = '$no_bundle'";
	$result_dsr_bundle_baru	= $db->query($query_dsr_bundle_baru);
	$dsr_bundle_baru		= $result_dsr_bundle_baru->fetchRow();
	$dsr_bundle_baru		= $dsr_bundle_baru['NO_CONTAINER'];
	//$dsr_bundle_baru			= $dsr_bundle_baru['JUM'];
	//echo $no_bundle;die;
	//echo "tes";

	
	
				$query_update_lama ="UPDATE RBM_LIST
								   SET DASAR_BUNDLE = NULL,
                                       TYPE_CONT='FLT',
                                       STATUS_CONT='MTY'
								   WHERE BUNDLE = '$no_bundle'
									AND NO_CONTAINER = '$dsr_bundle_lama'
									";
				$db->query($query_update_lama);	
				
				
			
				$query_update_baru ="UPDATE RBM_LIST
								   SET DASAR_BUNDLE = 'Y',
                                       TYPE_CONT='DRY',
                                       STATUS_CONT='FCL'
								   WHERE BUNDLE = '$no_bundle'
									AND NO_CONTAINER = '$dsr_bundle_baru'
									";
				$db->query($query_update_baru);		
			
	header('Location: '.HOME.'billing.rbm.list_detail/list_detail?id_vessel='.$no_ukk);
  
	
?>
