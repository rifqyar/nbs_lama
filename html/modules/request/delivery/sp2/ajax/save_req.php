<?php
	
	$tipe_req_cont = $_POST['TIPE_REQ_CONT'];
	$jenis_sppb =$_POST['JENIS_SPPB'];
    $iemkl=$_POST['IDEMKL'];
	$sppb=$_POST['SPPB'];
	$tglsppb=$_POST['TGLSPPB'];
	$tglspcust=$_POST['TGLSPCUST'];
	$ndo=$_POST['NDO'];
	$tgldo=$_POST['TGLDO'];
	$tgldel=$_POST['TGLDEL'];
	$ship=$_POST['SHIP'];
	$via=$_POST['VIA'];
	$ket=$_POST['KET'];
	$user=$_SESSION['PENGGUNA_ID'];
	$id_ves_scd=$_POST['ID_VES_SCD'];
	$vin=$_POST['VIN'];
	$vout=$_POST['VOUT'];
	$ves=$_POST['VES'];
	$ddsc=$_POST['DDSC'];
	$callsign=$_POST['CALL_SIGN'];
	$spcust=$_POST['SPCUST'];
	$blnumb=$_POST['BLNUMB'];
	$cargow=$_POST['CARGOW'];
	$icargow=$_POST['ICARGOW'];
	$db=getDb();	
	
	//echo $iemkl;echo $sppb;echo $tglsppb;echo $ndo;echo $tgldo;echo $tgldel;echo $ship;echo $via;echo $ket;echo $user;die;
	
	$param_b_var= array(
							"v_tipe_req_cont"=>"$tipe_req_cont",
							"v_jenis_sppb"=>"$jenis_sppb",
                            "v_callsign"=>"$callsign",
							"v_idvesscd"=>"$id_ves_scd",
							"v_ves"=>"$ves",
							"v_vin"=>"$vin",
							"v_vout"=>"$vout",
							"v_ddsc"=>"$ddsc",
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
							"v_spcust"=>"$spcust",
							"v_tgl_spcust"=>"$tglspcust",
							"v_blnumb"=>"$blnumb",
							"v_cargow"=>"$cargow",
							"v_icargow"=>"$icargow",
							"v_no_req"=>"",						
							"v_msg"=>""						
							);					

	$query = "declare begin proc_create_delivery(:v_tipe_req_cont,:v_jenis_sppb, :v_callsign,:v_idvesscd,:v_ves,:v_vin,:v_vout,:v_ddsc,:v_iemkl,:v_sppb,:v_tglsppb,:v_ndo,:v_tgldo,:v_tgldel,:v_ship,:v_via,:v_ket,:v_user,:v_spcust,:v_tgl_spcust,:v_blnumb,:v_cargow,:v_icargow,:v_no_req,:v_msg); end;";		
	$db->query($query,$param_b_var);	
	$no_req = $param_b_var['v_no_req'];		
	$msg = $param_b_var['v_msg'];			
	echo $msg.','.$no_req;
?>