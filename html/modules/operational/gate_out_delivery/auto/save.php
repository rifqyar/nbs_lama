
<?php
//print_r('test');die;
$db = getDB();
$no_req		= $_POST['REQ_DEV'];
$no_cont 	= $_POST['NO_CONT'];
$no_ukk		= $_POST['NO_UKK'];
$no_pols	= $_POST['NO_POL'];
$no_seal	= $_POST['SEAL'];  
$user		= $_SESSION['ID_USER'];
$nm_user	= $_SESSION["NAMA_PENGGUNA"];
$rm			= $_POST['REMARK'];		
		
	if(($user==NULL)||($nm_user==NULL))
	{
		header('Location: '.HOME.'login/');
	}
	else
	{
		//========= PATCH INHOUSE ============//
					
		$sql_erp = "begin gateout_delivery_erp('$no_ukk','$no_cont','$user','$no_seal','$nm_user'); end;";
		$db->query($sql_erp);	
			
        //========= PATCH INHOUSE ============//

		
		//========= PATCH ICT ============//		
		$cek_bm = "select KETERANGAN from ISWS_LIST_CONTAINER where trim(NO_UKK) = trim('$no_ukk') and trim(NO_CONTAINER) = trim('$no_cont')";
		$resultbm = $db->query($cek_bm);
		$bm_cek = $resultbm->fetchRow();
		$bm = $bm_cek['KETERANGAN'];
		
		if($bm=='BATAL MUAT DELIVERY')
		{
			$cek_bp_id = "select BP_ID from ttm_bp_cont@prodlinkx where trim(no_ukk) = trim('$no_ukk') and trim(status_bp) = '2'";
			$resultid = $db->query($cek_bp_id);
			$id_cek = $resultid->fetchRow();
			$bp_id = $id_cek['BP_ID'];
		}
		else
		{
			$cek_bp_id = "select BP_ID from ttm_bp_cont@prodlinkx where trim(no_ukk) = trim('$no_ukk') and trim(status_bp) = '1'";
			$resultid = $db->query($cek_bp_id);
			$id_cek = $resultid->fetchRow();
			$bp_id = $id_cek['BP_ID'];
		}

		$update_ict	= "update ttd_bp_cont@prodlinkx set
						gate_out = '$nm_user',
						gate_out_date = sysdate,
						ht_no = '$no_pols',
						status_cont = '09'
						where trim(bp_id) = trim('$bp_id') and trim(cont_no_bp) = trim('$no_cont')";
		$db->query($update_ict);		
		
		/*$sql_ict = "begin gateout_delivery_ict('$no_ukk','$no_cont','$nm_user'); end;";
		$db->query($sql_ict);*/
		//========= PATCH ICT ============//
		
		echo "OK";				
	
		//header('Location: '.HOME.'operational.gate_in_delivery');	
	}	
?>