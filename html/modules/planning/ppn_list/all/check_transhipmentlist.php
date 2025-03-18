<?php
	$db=  getDB(ibis);
	$vessel=$_POST['vessel'];
	$voyage_in=$_POST['voyage_in'];
	$voyage_out=$_POST['voyage_out'];
	$call_sign=$_POST['call_sign'];
	$action=$_POST['action'];
	
	if ($action == "check") {
		$query="SELECT count(*) as TOTAL FROM TRANS_CODECO_LIST_H WHERE trim(vessel) = trim('$vessel') and trim(voyage_in) = trim('$voyage_in') and trim(voyage_out) = trim('$voyage_out') and trim(call_sign) = trim('$call_sign') ";
		
		$res = $db->query($query)->fetchRow();
		echo $res[TOTAL];
	} else if ($action == "create_h") {
		$eta=$_POST['eta'];
		$user_create=$_POST['user_create'];
		$id_vsb_voyage=$_POST['id_vsb_voyage'];
	
		$param_b_var= array(
							"v_vessel"=>"$vessel",
							"voyage_in"=>"$voyage_in",
							"voyage_out"=>"$voyage_out",
							"v_eta"=>"$eta",
							"v_call_sign"=>"$call_sign",
							"v_user_create"=>"$user_create",
							"v_id_vsb_voyage"=>"$id_vsb_voyage",
							"v_no_req"=>"",						
							"v_msg"=>""						
							);					

		$query = "declare begin codeco_create_trans_list_h(:v_vessel,:voyage_in,:voyage_out,:v_eta,:v_call_sign,:v_user_create,:v_id_vsb_voyage,:v_no_req,:v_msg); end;";		
		$db->query($query,$param_b_var);	
		$no_req = $param_b_var['v_no_req'];		
		$msg = $param_b_var['v_msg'];			
		echo $msg.','.$no_req;
	} else if ($action == "check_det") {
		$id_trans = $_POST['ID_TRANS'];

		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS 
				FROM trans_list_d
				where trim(id_trans) = trim('$id_trans')";

		$res = $db->query($query)->fetchRow();
		echo $res[NUMBER_OF_ROWS];
	}
	die;
?>
