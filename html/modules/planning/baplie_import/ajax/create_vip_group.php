<?php
	
	echo "sukses lagi";
	
	$list_cont		= explode('&',$_SERVER["QUERY_STRING"]);
	
	$no_ukk				= $_GET["NO_UKK"]; 
	$id_pelanggan		= $_GET["id_pengguna"]; 
	$nama_pelanggan		= $_GET["pengguna_jasa"]; 
	$id_carrier		    = $_GET["id_carrier"]; 
	$carrier		    = $_GET["carrier"]; 
	$update_user 		= $_SESSION['NAMA_LENGKAP'];
	
	array_pop($list_cont);
	//echo $vip;
	echo "sukses lagi";
	
	$jml_cont = count($list_cont);
	
	echo $no_ukk;
	echo $id_pelanggan;
	echo $nama_pelanggan;
	echo $id_carrier;
	echo $carrier;
	
	echo $jml_cont;
	
	
	//echo $jml_cont;
	
	//echo $jml_cont;
//	die;

	for($i=0; $i<($jml_cont); $i++)
	{
		$db = getDb();
		$no_cont = substr($list_cont[$i],9);
		$box = substr($list_cont[$i],0,8);
		if($box=="box2View")
		{
			if ($id_pelanggan == ''){
				$query_request2 ="UPDATE ISWS_LIST_CONTAINER SET PEMILIK_VIP='$carrier', ID_VIP='$id_carrier', TYPE_VIP='CARRIER' WHERE NO_CONTAINER='$no_cont' AND NO_UKK='$no_ukk'";
				$db->query($query_request2);	
				$keg='UPDATE PEMILIK VIP';
				$query_request3 ="INSERT into ISWS_LIST_CONT_HIST (NO_UKK,NO_CONTAINER, KEGIATAN, E_I, DATE_STATUS, NM_USER) 
									VALUES ('$no_ukk','$no_cont','$keg','I',SYSDATE,'$update_user')";
				//echo $query_request3;die;
				$db->query($query_request3);	
			} else {
				$query_request2 ="UPDATE ISWS_LIST_CONTAINER SET PEMILIK_VIP='$nama_pelanggan', ID_VIP='$id_pelanggan', TYPE_VIP='PELANGGAN' WHERE NO_CONTAINER='$no_cont' AND NO_UKK='$no_ukk'";
				$db->query($query_request2);	
				$keg='UPDATE PEMILIK VIP';
				$query_request3 ="INSERT into ISWS_LIST_CONT_HIST (NO_UKK,NO_CONTAINER, KEGIATAN, E_I, DATE_STATUS, NM_USER) 
									VALUES ('$no_ukk','$no_cont','$keg','I',SYSDATE,'$update_user')";
				//echo $query_request3;die;
				$db->query($query_request3);	

			}
		}
	}
	
	header('Location: '.HOME.'planning.baplie_import/');
?>
