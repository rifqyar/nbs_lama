<?php$ukk=$_POST['UKK'];$iemkl=$_POST['IDEMKL'];$ipod=$_POST['IPOD'];$ipol=$_POST['IPOL'];$ship=$_POST['SHIP'];$npe=$_POST['NPE'];$peb=$_POST['PEB'];$ds=$_POST['DS'];$car=$_POST['CAR'];$start_shift=$_POST['START_SHIFT'];$end_shift=$_POST['END_SHIFT'];$jml_shift=$_POST['JML_SHIFT'];$fipod=$_POST['FIPOD'];$user=$_SESSION['PENGGUNA_ID'];$db=getDb();	$param_b_var= array(							"v_ukk"=>"$ukk",						"v_iemkl"=>"$iemkl",						"v_ipod"=>"$ipod",						"v_ipol"=>"$ipol",						"v_ship"=>"$ship",						"v_npe"=>"$npe",						"v_peb"=>"$peb",						"v_user"=>"$user",						"v_ds"=>"$ds",						"v_car"=>"$car",						"start_shift"=>"$start_shift",						"end_shift"=>"$end_shift",						"jml_shift"=>"$jml_shift",						"fipod"=>"$fipod",						"v_no_req"=>"",						"v_msg"=>""						);	//$query = "declare begin proc_create_anne($ukk,$iemkl,$ipod,$ipol,$ship,$npe,$peb,$user,$ds,$car,$start_shift,$end_shift,$jml_shift,$fipod,'',''); end;";	//print_r($query);die;		//print_r($param_b_var);die;		$query = "declare begin proc_create_anne(:v_ukk,:v_iemkl,:v_ipod,:v_ipol,:v_ship,:v_npe,:v_peb,:v_user,:v_ds,:v_car,:start_shift,:end_shift,:jml_shift,:fipod,:v_no_req,:v_msg); end;";		$db->query($query,$param_b_var);	$no_req = $param_b_var['v_no_req'];		$msg = $param_b_var['v_msg'];		if($msg=='POD') 	{		echo 'POD belum didaftarkan ke VVD, hubungi planner';	}	else	echo $msg.','.$no_req;?>