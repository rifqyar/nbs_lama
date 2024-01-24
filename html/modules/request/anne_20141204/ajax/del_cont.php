<?php

$db 		= getDB();

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$vessel = $_POST["VESSEL"];
$voyage = $_POST["VOYAGE"];
$operatorId = $_POST["OPERATORID"];

$query_del	= "DELETE FROM req_receiving_d WHERE NO_CONTAINER = '$no_cont' AND NO_REQ_ANNE = '$no_req'";
//$db->query($query_del);

/*$query_del2	= "delete m_billing where no_container=TRIM('$no_cont') and no_request=TRIM('$no_req')";

$param_b_var= array(	
							"v_nocont"=>"$no_cont",
							"flag"=>"DEL",
							"vessel"=>"$vessel",
							"voyage"=>"$voyage",
							"operatorId"=>"$operatorId",
							"v_response"=>"",
							"v_msg"=>""
							);

$db2 		= getDB('dbint');							
$query = "declare begin PROC_DEL_CONT(:v_nocont, :flag, :vessel, :voyage, :operatorId, :v_response, :v_msg); end;";
$db2->query($query,$param_b_var);

*/

$param_b_var= array(	
							"v_nocont"=>TRIM($no_cont),
							"v_req"=>TRIM($no_req),
							"flag"=>"REC",
							"vessel"=>"$vessel",
							"voyage"=>"$voyage",
							"operatorId"=>"$operatorId",
							"v_response"=>"",
							"v_msg"=>""
							);
$query = "declare begin proc_delete_cont(:v_nocont, :v_req, :flag, :vessel, :voyage, :operatorId, :v_response, :v_msg); end;";

							
//harusnya ditambahkan untuk delete ke billing_ops
if($db->query($query_del))
{
	$db->query($query,$param_b_var);
	echo "OK ".$param_b_var[v_response];
}

?>