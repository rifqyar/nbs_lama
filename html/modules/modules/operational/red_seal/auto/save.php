
<?php
//print_r('test');die;
$db = getDB();
$no_req		= $_POST['REQ_DEV'];
$no_cont 	= $_POST['NO_CONT'];
$no_ukk		= $_POST['NO_UKK'];
$no_pols		= $_POST['NO_POL'];
$user		=$_SESSION['ID_USER'];
$rm			= $_POST['REMARK'];		

		$query	= "UPDATE ISWS_LIST_CONTAINER 
					SET KODE_STATUS='10', STATUS_GATE_IN='Y', TGL_GATE_IN=SYSDATE, USER_CONFIRM='$user', NO_TRUCK='$no_pols'
				   WHERE  NO_UKK='$no_ukk' AND E_I='I' AND NO_CONTAINER ='$no_cont' AND TGL_GATE_IN IS NULL and NO_REQUEST='$no_req'";
			$db->query($query);
			
		$query_hist	= "INSERT INTO ISWS_LIST_CONT_HIST (NO_UKK, NO_CONTAINER, KEGIATAN, E_I, KODE_STATUS, DATE_STATUS, NM_USER)
				   VALUES ('$no_ukk','$no_cont', 'GATE IN','I', '10', SYSDATE, '$user')
				   	";
			$db->query($query_hist);		
		
		//echo $query;
		echo "sukses";
				
	
		//header('Location: '.HOME.'operational.gate_in_delivery');	
?>