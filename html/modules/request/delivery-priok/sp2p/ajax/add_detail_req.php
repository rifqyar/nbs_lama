<?php
//debug ($_POST);die;
$db 			= getDB();

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
//query cek tabel master container

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
							"v_msg"=>""
							);
	//print($param_b_var);die;	
	$query = "declare begin proc_add_cont_delivery(:v_nc,:v_req,:v_sc,:v_tc,:v_stc,:v_hc,:v_comm,:v_imo,:v_iso,:v_hgc,:v_ship,:v_car,:v_tmp,:v_ukk,:v_msg); end;";
	
	$db->query($query,$param_b_var);
	$msg = $param_b_var['v_msg'];	
	
	$query_kpl = "SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK = '$ukk'";
	$hasil 	   = $db->query($query_kpl);
	$kapal 	   = $hasil->fetchRow();
	$vessel	   = $kapal['NM_KAPAL'];
	$voyage	   = $kapal['VOYAGE_IN'].'-'.$kapal['VOYAGE_OUT'];
	
	$query_update = "UPDATE REQ_DELIVERY_H SET VESSEL = '$vessel', VOYAGE = '$voyage', NO_UKK = '$ukk' WHERE ID_REQ='$no_req'";
	$db->query($query_update);
		

	echo $msg;
    
?>