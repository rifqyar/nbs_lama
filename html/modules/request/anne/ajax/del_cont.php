<?php
$db 		= getDB();
$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$vessel = $_POST["VESSEL"];
$voyage = $_POST["VOYAGE"];
$operatorId = $_POST["OPERATORID"];

$query_del	= "DELETE FROM req_receiving_d WHERE NO_CONTAINER = '$no_cont' AND ID_REQ = '$no_req'";
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