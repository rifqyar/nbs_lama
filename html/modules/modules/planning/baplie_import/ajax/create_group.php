<?php
echo "sukses";

	$db = getDB();
	$list_cont		= explode('&',$_SERVER["QUERY_STRING"]);
	
	$no_ukk		= $_GET["NO_UKK"]; 
	$vip		= $_GET["vip"]; 
	$update_user = $_SESSION['NAMA_LENGKAP']
	array_pop($list_cont);
	//print_r($vip);
	
	$jml_cont = count($list_cont);

	for($i=0; $i<($jml_cont); $i++)
	{
		$no_cont = substr($list_cont[$i],9);
		$box = substr($list_cont[$i],0,8);
		if($box=="box2View")
		{
			$query_request2 ="UPDATE ISWS_LIST_CONTAINER SET PEMILIK_VIP='$vip' WHERE NO_CONTAINER='$no_cont' AND NO_UKK='$no_ukk'";
			$db->query($query_request2);	
			$query_request3 ="INSERT ISWS_LIST_CONT_HIST (NO_UKK,NO_CONTAINER, KEGIATAN, E_I, DATE_STATUS, NM_USER) 
								VALUES ('$no_ukk','$no_cont','UPDATE PEMILIK VIP $vip','I',SYSDATE,'$update_user')";
			$db->query($query_request3);	
		}
	}
	header('Location: '.HOME.'planning.baplie_import/');
?>