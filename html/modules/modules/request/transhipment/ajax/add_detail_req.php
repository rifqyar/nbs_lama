<?php
//debug ($_POST);die;
$db 			= getDB();
$arr_cont		= $_POST["LIST_CONT"]; 
$no_req			= $_POST["REQ"]; 
$oukk			= $_POST["OUKK"]; 
$nukk           = $_POST["NUKK"];

//delete dulu baru entry ulang
//$query = "delete REQ_TRANSHIPMENT_D where ID_REQ='".$no_req."'";
//$query = "declare begin proc_del_cont_trans('$no_req','$oukk','$nukk'); end;";
//$db->query($query);
for($i=0; $i < count($arr_cont); $i++) {
	$det_cont=explode("^^",$arr_cont[$i]);
	$param_b_var= array(	
							"v_nc"=>$det_cont[0],
							"v_size"=>$det_cont[1],
							"v_type"=>$det_cont[2],
							"v_status"=>$det_cont[3],
							"v_hz"=>$det_cont[4],
							"v_height"=>$det_cont[5],
							"v_ei"=>$det_cont[6],
							"v_id_cont"=>$det_cont[7],
							"v_req"=>$no_req,
							"v_oukk"=>$oukk,
							"v_nukk"=>$nukk,
							"v_urut"=>($i+1),
							"v_msg"=>""
						);
					
	$query = "declare begin proc_add_cont_trans(:v_nc, :v_size, :v_type, :v_status, :v_hz, :v_height, :v_ei,:v_id_cont, :v_req, :v_oukk, :v_nukk, :v_urut, :v_msg); end;";
	//print_r($param_b_var);
	//echo($query);
	$db->query($query,$param_b_var);
	$msg = $param_b_var['v_msg'];
}	

echo $msg;  
?>