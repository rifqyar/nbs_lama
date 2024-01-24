<?php
	$ct=$_POST['NO_CONT'];
	$no_ukk_=$_POST['no_ukk'];
	$db=GETdB();
	$param_b_var= array(	
						"v_container"=>"$ct",
						"v_ukk"=>"$no_ukk_",
						"v_err"=>""
						);
	//print_r($param_b_var);die;
	$query 			= "begin ISWS.PACK_SYNC_CONT.get_header_anne(:v_container,:v_ukk,:v_err ); end;";
	$db->query($query,$param_b_var);
	$err_msg = $param_b_var['v_err'];


echo $err_msg;
//echo $query;

?>