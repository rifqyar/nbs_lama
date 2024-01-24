<?php
	$iemkl=$_POST['IDEMKL'];
	$sppb=$_POST['SPPB'];
	$tglsppb=$_POST['TGLSPPB'];
	$ndo=$_POST['NDO'];
	$tgldo=$_POST['TGLDO'];
	$tgldel=$_POST['TGLDEL'];
	$ship=$_POST['SHIP'];
	$via=$_POST['VIA'];
	$ket=$_POST['KET'];
	$user=$_SESSION['PENGGUNA_ID'];
	$db=getDb();	

	$param_b_var= array(										
							"v_iemkl"=>"$iemkl",						
							"v_sppb"=>"$sppb",						
							"v_tglsppb"=>"$tglsppb",						
							"v_ndo"=>"$ndo",						
							"v_tgldo"=>"$tgldo",						
							"v_tgldel"=>"$tgldel",						
							"v_ship"=>"$ship",						
							"v_via"=>"$via",						
							"v_ket"=>"$ket",
							"v_user"=>"$user",
							"v_no_req"=>"",						
							"v_msg"=>""						
							);					
	
	$query = "declare begin proc_create_delivery(:v_iemkl,:v_sppb,:v_tglsppb,:v_ndo,:v_tgldo,:v_tgldel,:v_ship,:v_via,:v_ket,:v_user,:v_no_req,:v_msg); end;";		
	$db->query($query,$param_b_var);	
	$no_req = $param_b_var['v_no_req'];		
	$msg = $param_b_var['v_msg'];			
	echo $msg.','.$no_req;
?>