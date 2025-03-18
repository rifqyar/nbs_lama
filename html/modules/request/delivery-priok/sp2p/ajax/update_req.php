<?php
	$idreqold=$_POST['IDREQOLD'];
	$iemkl=$_POST['IEMKL'];
	$emkl=$_POST['EMKL'];
	$vessel=$_POST['VESSEL'];
	$vin=$_POST['VOYAGE_IN'];
	$vout=$_POST['VOYAGE_OUT'];
	$almt=$_POST['ALMT'];
	$npwp=$_POST['NPWP'];
	$sppb=$_POST['SPPB'];
	$tglsppb=$_POST['TGLSPPB'];
	$tgldisch=$_POST['DISCH_DATE'];
	$ndo=$_POST['NDO'];
	$tgldo=$_POST['TGLDO'];
	$tgldel=$_POST['TGLDEL'];
	$tgldelp=$_POST['TGLDELP'];
	$ship=$_POST['SHIP'];
	$via=$_POST['VIA'];
	$ket=$_POST['KET'];
	$user=$_SESSION['PENGGUNA_ID'];
	$db=getDb();	
	
	//echo $emkl;echo $almt;echo $npwp;echo $sppb;echo $tglsppb;echo $ndo;echo $tgldo;echo $tgldel;echo $tgldelp;echo $ship;echo $via;echo $ket;echo $user;echo $idreqold;die;
	
	$param_b_var= array(										
							"v_vessel"=>"$vessel",
							"v_vin"=>"$vin",
							"v_vout"=>"$vout",
							"v_iemkl"=>"$iemkl",
							"v_emkl"=>"$emkl",
							"v_almt"=>"$almt",
							"v_npwp"=>"$npwp",
							"v_sppb"=>"$sppb",						
							"v_tglsppb"=>"$tglsppb",						
							"v_ndo"=>"$ndo",						
							"v_tgldo"=>"$tgldo",						
							"v_tgldel"=>"$tgldel",	
							"v_tgldelp"=>"$tgldelp",
							"v_tgldisch"=>"$tgldisch",							
							"v_ship"=>"$ship",						
							"v_via"=>"$via",						
							"v_ket"=>"$ket",
							"v_user"=>"$user",
							"v_no_req_old"=>"$idreqold",
							"v_no_req"=>"",
							"v_req_ke"=>"",
							"v_msg"=>""						
							);					
	
	
	$query = "declare begin proc_create_delivery_per(:v_vessel, :v_vin, :v_vout,:v_iemkl,:v_emkl,:v_almt, :v_npwp, :v_sppb,:v_tglsppb,:v_ndo,:v_tgldo,:v_tgldel,:v_tgldelp,:v_tgldisch,:v_ship,:v_via,:v_ket,:v_user,:v_no_req_old,:v_no_req,:v_req_ke,:v_msg); end;";
	
	$db->query($query,$param_b_var);	
	$no_req = $param_b_var['v_no_req'];		
	$msg = $param_b_var['v_msg'];
	$req_ke = $param_b_var['v_req_ke'];
	echo $msg.','.$no_req.','.$req_ke;
?>