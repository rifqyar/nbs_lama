<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_POST["ID_VS"];
$id_bay = $_POST["BAY_AREA"];
$bay_no = $_POST["NO_BAY"];
$posisi_stack = $_POST["POSISI"];
$sz = $_POST["SZ_CONT"];
$act = $_POST["ACTIVITY"];
if($act=='IMPORT')
{
	$activity = "I";
}
else
{
	$activity = "E";
}

$posisi = strtoupper($posisi_stack);
$alat = $_POST["ALAT"];

if(($id_vs==NULL)||($alat==NULL)||($activity==NULL))
{
	echo "NO";
}
else
{
	$param_b_var= array(	
				"v_sz"=>"$sz",
				"v_no_ukk"=>"$id_vs",
				"v_bay_id"=>"$id_bay",
				"v_bay_no"=>"$bay_no",
				"v_bay_pss"=>"$posisi_stack",
				"v_act"=>"$activity",
				"v_alat"=>"$alat",
				"v_id_user"=>"$id_user",
				"v_msg"=>""
						);
	//print_r($param_b_var);die;	
	$csl_allo = "declare begin csl_allocation(:v_sz,:v_no_ukk,:v_bay_id,:v_bay_no,:v_bay_pss,:v_act,:v_alat,:v_id_user,:v_msg); end;";
	$db->query($csl_allo,$param_b_var);
								
	$msg_error = $param_b_var['v_msg'];
	echo $msg_error;

}
?>