<?php$tipe_oi=$_POST['TIPE_OI'];$emkl=$_POST['EMKL'];$iemkl=$_POST['IDEMKL'];$inst=$_POST['INST'];$bc12=$_POST['BC12'];$oves=$_POST['OVES'];$ovoy=$_POST['OVOY'];$oukk=$_POST['OUKK'];$nves=$_POST['NVES'];$nvoy=$_POST['NVOY'];$nukk=$_POST['NUKK'];$ket=$_POST['KET'];$user=$_SESSION['PENGGUNA_ID'];$db=getDb();	$param_b_var= array(							"tipe_oi"=>"$tipe_oi",						"emkl"=>"$emkl",						"iemkl"=>"$iemkl",						"inst"=>"$inst",						"bc12"=>"$bc12",						"oves"=>"$oves",						"ovoy"=>"$ovoy",						"oukk"=>"$oukk",						"nves"=>"$nves",						"nvoy"=>"$nvoy",						"nukk"=>"$nukk",						"ket"=>"$ket",						"v_user"=>"$user",						"v_no_req"=>"",						"v_msg"=>""						);					$query = "declare begin proc_create_reexport(:tipe_oi,:emkl,:iemkl,:inst,:bc12,:oves,:ovoy,:oukk,:nves,:nvoy,:nukk,:ket,:v_user,:v_no_req,:v_msg); end;";	//echo "declare begin proc_create_reexport('$tipe_oi', '$emkl' ,'$iemkl', '$inst', '$bc12', '$oves', '$ovoy', '$oukk', '$nves', '$nvoy', '$nukk', '$ket', '$user', '$v_no_req', '$v_msg'); end;";	//die;		$db->query($query,$param_b_var);	$no_req = $param_b_var['v_no_req'];		$msg = $param_b_var['v_msg'];			echo $msg.','.$no_req;?>