<?php
$db = getDB();
$no_cont = $_POST['NO_CONT'];
$no_req= $_POST['REQ_ANNE'];
$berat= $_POST['BERAT'];
$iso_code= $_POST['ISO_CODE'];
$no_polis= $_POST['NO_POL'];
$opr= $_POST['OPR'];
$rm= $_POST['REMARK'];
$seal= $_POST['SEAL'];
$user=$_SESSION['NAMA_PENGGUNA'];

if($user==NULL)
{
	header('Location: '.HOME.'login/');
}
else
{
	$param_b_var= array(	
						"v_container"=>"$no_cont",
						"v_req_anne"=>"$no_req",
						"v_berat"=>"$berat",
						"v_nopol"=>"$no_polis",
						"v_iso_code"=>"$iso_code",
						"v_remark"=>"$rm",
						"v_blok"=>"",
						"v_slot"=>"",
						"v_row"=>"",
						"v_tier"=>"",
						"v_id_user"=>"$user",
						"v_seal"=>"$seal",
						"v_err"=>""
						);

	//======= PATCH INHOUSE =======//						
	$query = "declare begin get_jobslip_position(:v_container,:v_req_anne,:v_berat,:v_nopol,:v_iso_code,:v_remark,:v_blok,:v_slot,:v_row,:v_tier,:v_id_user,:v_seal,:v_err ); end;";
	
	$db->query($query,$param_b_var);
	//======= PATCH INHOUSE =======//

	$blok = $param_b_var['v_blok'];	
	$slot = $param_b_var['v_slot'];	
	$row = $param_b_var['v_row'];	
	$tier = $param_b_var['v_tier'];	
	$err_msg = $param_b_var['v_err'];
		

	echo $blok.','.$slot.','.$row.','.$tier.','.$err_msg;

	//ECHO $query;
}

?>