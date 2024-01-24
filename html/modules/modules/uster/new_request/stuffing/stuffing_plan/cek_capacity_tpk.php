<?php
	echo 'OKE';
		exit();
	$NO_BOOKING = $_POST["no_booking"];
	$db = getDB('ora');
	$sqlb  = "select sum (jumlah_teus) as TEUS from V_dry_BOOKING_tlb where no_booking = '".$NO_BOOKING."'";
	$rsb = $db->query($sqlb);
	$rowb = $rsb->FetchRow();
	$booked = $rowb["TEUS"];		
	
	$sqlu = "SELECT count (no_container) as jml_counteiner,Sum( kd_size_teus) teus
			 FROM(
			 SELECT no_container,CASE WHEN kd_size ='3' THEN '2'
			 ELSE kd_size
			 END kd_size_teus,kd_size
			 from ttd_cont_exbspl a,tth_cont_exbspl b
			 where a.kd_pmb = b.kd_pmb and b.no_booking = '".$NO_BOOKING."') ";
	$rsu   = $db->query($sqlu);
	$rowu = $rsu->FetchRow();
	$used   = $rowu["JML"];
	
	$remained = $booked-$used;
	$remained = 0;
	if($remained > 0){
		echo 'OKE';
		exit();
	}
	else{
		//echo 'T'; validasi dibuka dulu buat ngetes2
		echo 'OKE';
		exit();
	}
?>