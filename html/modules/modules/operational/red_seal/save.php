
<?php
//print_r('test');die;
$db = getDB();
$no_req		= $_POST['no_req'];
$no_cont 	= $_POST['NO_CONTAINER'];
$no_ukk		= $_POST['no_ukk'];
$size		= $_POST['size'];
$tipe		= $_POST['type'];
$status		= $_POST['status'];
$gross		= $_POST['gross'];
$height_c	= $_POST['height_c'];
$hz			= $_POST['hz'];
$iso		= $_POST['iso_code'];
$vessel		= $_POST['vessel'];
$voyage_in	= $_POST['voyage_in'];
$voyage_out	= $_POST['VOYAGE_OUT'];
$pel_asal	= $_POST['pel_asal'];
$pel_tuj	= $_POST['pel_tujuan'];
$seal_id	= $_POST['seal'];
$user		=$_SESSION['ID_USER'];
$rm			= $_POST['remark'];
$date		=date('d-m-Y H:i:s');
$tgl_status = $_POST['date_status'];
		
				
		/*print_r("INSERT INTO ISWS_LIST_CONT_HIST (NO_UKK, NO_CONTAINER, KEGIATAN, E_I, KODE_STATUS, DATE_STATUS, NM_USER)
				   VALUES ('$no_ukk','$no_cont', 'GATE IN','I', '10', SYSDATE, '$user')
				   	");die;
		*/
		$query	= "UPDATE ISWS_LIST_CONTAINER 
					SET KODE_STATUS='10', STATUS_GATE_IN='Y', TGL_GATE_IN=SYSDATE, USER_CONFIRM='$user'
				   WHERE  NO_UKK='$no_ukk' AND E_I='I' AND NO_CONTAINER ='$no_cont' AND STATUS_GATE_IN IS NULL";
			$db->query($query);
			
		$query_hist	= "INSERT INTO ISWS_LIST_CONT_HIST (NO_UKK, NO_CONTAINER, KEGIATAN, E_I, KODE_STATUS, DATE_STATUS, NM_USER)
				   VALUES ('$no_ukk','$no_cont', 'GATE IN','I', '10', SYSDATE, '$user')
				   	";
			$db->query($query_hist);		
		
				
	
		header('Location: '.HOME.'operational.gate_in_delivery');	
?>