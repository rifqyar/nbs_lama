<?php
	$ct=$_POST['NO_CONT'];
	$no_ukk_=$_POST['no_ukk'];
	$db=GETdB();
	$param_b_var= array(	
						"v_container"=>"$ct",
						"v_ukk"=>"$no_ukk_"
						);
	//print_r($param_b_var);die;
	
	$query2 			= "SELECT COUNT(1) JML from isws_list_container where no_container = '$ct' and no_ukk = '$no_ukk'";
	$result             = $db->query($query2);
	$jml 				= $result->fetchRow();
	$jm					= $jml['JML'];

	if ($jm >0){
		$query 			= "begin ISWS.proc_sync_sp2_per_cont(:v_container,:v_ukk); end;";
		$db->query($query,$param_b_var);
		$err_msg = 'sukses';
	} else {
		$err_msg = 'Vessel name and container number not match';
	}


echo $err_msg;
//echo $query;

?>