<?php
	$prn=$_GET['id'];
	$user=$_SESSION['PENGGUNA_ID'];
	$db=getDb();
	$param = array 
			(
				"v_id"=>"$prn",
				"v_user"=>"$user",
				"v_msg"=>""
			);
	$qry="begin bil_prc_ntstv(:v_id,:v_user,:v_msg); end;";
	//print_r($qry);die;
	$db->query($qry,$param);
	
	
	
	echo $param["v_msg"];
    die();
	
?>