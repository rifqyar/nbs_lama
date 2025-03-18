<?php
//print_r('test');die;
$db = getDB();
$no_cont 	= $_POST['NO_CONTAINER'];
$no_ukk		= $_POST['no_ukk'];
$size		= $_POST['size'];
$tipe		= $_POST['type'];
$status		= $_POST['status'];
$berat		= $_POST['berat'];
$hz			= $_POST['hz'];
$carrier	= $_POST['carrier'];
$iso		= $_POST['iso_code'];
$vessel		= $_POST['vessel'];
$voyage		= $_POST['voyage'];
$voyage_out	= $_POST['VOYAGE_OUT'];
$pel_asal	= $_POST['pel_asal'];
$pel_tuj	= $_POST['pel_tujuan'];
$seal_id	= $_POST['seal'];
$user		=$_SESSION['ID_USER'];
$user2		=$_SESSION['NAMA_PENGGUNA'];
$rm			= $_POST['remark'];
$date		=date('d-m-Y H:i:s');
$tgl_status = $_POST['date_status'];
		
				
		/*print_r("INSERT INTO ISWS_LIST_CONT_HIST (NO_UKK, NO_CONTAINER, KEGIATAN, E_I, KODE_STATUS, DATE_STATUS, NM_USER)
				   VALUES ('$no_ukk','$no_cont', 'Bongkar','I', '02', $date, '$user')
				   	");die;
		*/
		
		
		$query	= " UPDATE ISWS_LIST_CONTAINER
					  SET STATUS_CONFIRM = 'Y',
						  DATE_CONFIRM = SYSDATE,
						  KODE_STATUS = '02',
						  USER_CONFIRM = user,
						  DISCHARGE_CONFIRM = SYSDATE
						  HH = 'N'
					WHERE NO_CONTAINER = '$no_cont' AND NO_UKK = '$no_ukk_'";
		$db->query($query);
			
		$cek_hist	= "SELECT COUNT(1) JML FROM ISWS_LIST_CONT_HIST  WHERE NO_UKK = '$no_ukk' AND NO_CONTAINER = '$no_cont' AND KEGIATAN = 'BONGKAR'";
		$tes        = $db->query($cek_hist);	
		$hasil      = $tes->fetchRow();
		$jml		= $hasil['JML'];
		
		if ($jml <= 0){
			$query_hist	= "INSERT INTO ISWS_LIST_CONT_HIST (NO_UKK, NO_CONTAINER, KEGIATAN, E_I, KODE_STATUS, DATE_STATUS, NM_USER)
					   VALUES ('$no_ukk','$no_cont', 'BONGKAR','I', '02', SYSDATE, '$user')
						";
			$db->query($query_hist);	
		}
		
		$cek_hist2	= "SELECT COUNT(1) JML FROM CONFIRM_DISC WHERE NO_UKK = '$no_ukk' AND NO_CONTAINER = '$no_cont'";
		$tes2       = $db->query($cek_hist2);	
		$hasil2     = $tes2->fetchRow();
		$jml2		= $hasil2['JML'];
		
		if ($jml2 <= 0){
			$query_hist2 = "select substr(lokasi_bp,0,3) BAY_BAY, substr(lokasi_bp,-4,2) ROW_BAY, substr(lokasi_bp,-2,2) TIER_BAY, size_, type_, status, hz, height  from isws_list_container where no_COntainer = '$no_cont' AND NO_UKK = '$no_ukk'";
			$result      = $db->query($query_hist2);
			$data 		 = $result->fetchRow();
			$bay_bay	 = $data['BAY_BAY'];
			$row_bay	 = $data['ROW_BAY'];
			$tier_bay	 = $data['TIER_BAY'];
			$size2	 	 = $data['SIZE_'];
			$type2	     = $data['TYPE_'];
			$status2 	 = $data['STATUS'];
			$hz2         = $data['HZ'];
			$height2     = $data['HEIGHT'];
	   
			$quer  = "INSERT INTO CONFIRM_DISC (NO_CONTAINER,
								 NO_UKK,
								 DATE_CONFIRM,
								 USER_CONFIRM,
								 ID_USER,
								 OP_ALAT,
								 OP_HT,
								 HT,
								 ALAT,
								 SOA,
								 WOA,
								 HH,
								 BAY_,
								 ROW_,
								 TIER_,
								 NO_TRUCK,
								 SIZE_,
								 TYPE_,
								 STATUS_,
								 HZ,
								 HEIGHT)
						VALUES ('$no_cont',
								'$no_ukk',
								SYSDATE,
								'$user2',
								'$user',
								'OP.OJA',
								0,
								'HEAD TRUCK',
								'CC01',
								'BONGKAR MUAT',
								'BONGKAR MUAT',
								'Y',
								'$bay_bay',
								'$row_bay',
								'$tier_bay',
								'',
								'$size2', 
								'$type2', 
								'$status2;', 
								'$hz2', 
								'$height2')";
				$db->query($quer);	
		}
		
		$cek 		= " BEGIN
						discharge_confirm_ict('$no_cont','$no_ukk','$user2');
						END;";
		$db->$query($cek);	   
		
		$query_stw="DELETE FROM STW_PLACEMENT_BAY
					WHERE ID_VS='$no_ukk' AND NO_CONTAINER='$no_cont' AND ACTIVITY ='BONGKAR'
					";	
			$db->query($query_stw);
			
	
	
		header('Location: '.HOME.'operational.discharge');		 
			
	
?>
