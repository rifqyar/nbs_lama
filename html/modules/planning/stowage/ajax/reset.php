<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_POST["ID_VS"];
$no_cont = $_POST["NO_CONTAINER"];

if($id_vs==NULL)
{
	echo "NO";
}
else
{
	$param_b_var = array(	
						"v_id_vs"=>"$id_vs",
						"v_nocont"=>"$no_cont",
						"v_iduser"=>"$id_user",
						"v_msg"=>""
						);
	$reset_bayplan = "begin reset_bayplan(:v_id_vs,:v_nocont,:v_msg,:v_iduser); end;";
	$db->query($reset_bayplan,$param_b_var);
	
	$msg_error = $param_b_var['v_msg'];
	echo $msg_error;

		$status = "RESET STOWAGE PLAN ".$no_cont;
		$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','$status',SYSDATE,'$id_user')";
				
		if($db->query($insert_history))
		{				
			echo "OK";
		}
		else
		{
			echo "NO";
		}
}
?>