<?php
//debug ($_POST);die;
$db 			= getDB();

$vin		= $_POST["VIN"]; 
$ves		= $_POST["VESX"]; 
$no_cont		= $_POST["NC"]; 
$no_req			= $_POST["REQ"]; 
$status			= $_POST["STC"]; 
$hz             = $_POST["HC"]; 
$size			= $_POST["SC"];
$tipe			= $_POST["TC"];
$comm			= $_POST["COMM"];
$imo			= $_POST["IMO"];
$iso			= $_POST["ISO"];
$hgc			= $_POST['HGC'];
$ship			= $_POST['SHIP'];
$car			= $_POST['CAR'];
$tmp			= $_POST['TEMP'];
$ukk			= $_POST['UKK'];
$bookingno			= $_POST['BOOKINGSL'];
$ow			= $_POST['OW'];
$ol			= $_POST['OL'];
$oh			= $_POST['OH'];
$unnumber			= $_POST['UNNUMBER'];
$pod			= $_POST['POD'];
$pol			= $_POST['POL'];
$pli			= $_POST['PLI'];
$plo			= $_POST['PLO'];
//query cek tabel master container

$q_cek_ob = "select count(*) jum  from obx_approval_h a, obx_approval_d b where a.id_plp = b.id_plp and  trim(b.id_barang) = trim('$no_cont')
and trim(a.vessel) like trim('%$ves%') and trim(a.voyage_in) LIKE trim('%$vin%') and a.created_by = 'EDII' and a.created_date is not null";
$r_cek_ob = $db->query($q_cek_ob);
$rwcek_ob = $r_cek_ob->fetchRow();

if ($rwcek_ob['JUM'] == 0) {
	
	$param_b_var= array(	
							"v_nc"=>"$no_cont",
							"v_req"=>"$no_req",
							"v_stc"=>"$status",
							"v_hc"=>"$hz",
							"v_sc"=>"$size",
							"v_tc"=>"$tipe",
							"v_comm"=>"$comm",
							"v_imo"=>"$imo",
							"v_iso"=>"$iso",
							"v_hgc"=>"$hgc",
							"v_ship"=>"$ship",
							"v_car"=>"$car",
							"v_tmp"=>"$tmp",
							"v_ukk"=>"$ukk",
							"v_booking_no"=>"$bookingno",
							"v_ow"=>"$ow",
							"v_ol"=>"$ol",
							"v_oh"=>"$oh",
							"v_unnumber"=>"$unnumber",
							"v_pod"=>"$pod",
							"v_pol"=>"$pol",
							"v_pli"=>"$pli",
							"v_plo"=>"$plo",
							"v_msg"=>""
							
							);
	/*$query = "declare v_msg varchar2(1000);begin proc_add_cont_delivery('$no_cont','$no_req','$size','$tipe','$status','$hz','$comm','$imo','$iso','$hgc','$ship','$car','$tmp','$ukk','$booking_no','$ow','$ol','$oh','$unnumber','$pod','$pol','$pli','$plo',v_msg); end;";
	*/
	//print_r($query);die;
	//print_r($param_b_var);die;
	
	$query = "declare begin proc_add_cont_delivery2(:v_nc,:v_req,:v_sc,:v_tc,:v_stc,:v_hc,:v_comm,:v_imo,:v_iso,:v_hgc,:v_ship,:v_car,:v_tmp,:v_ukk,:v_booking_no,
	:v_ow,:v_ol,:v_oh,:v_unnumber,:v_pod,:v_pol,:v_pli,:v_plo,:v_msg); end;";
	
	$db->query($query,$param_b_var);
	
	//$q2 = "SELECT * FROM M_CYC CONTAINER WHERE NO_CONTAINER='' AND ";
	$msg = $param_b_var['v_msg'];	
	
	echo $msg;
} else {
	echo "CONTAINER TELAH DI REQUEST OB";
}
?>