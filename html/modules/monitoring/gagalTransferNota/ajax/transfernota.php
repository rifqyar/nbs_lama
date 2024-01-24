<?php

	$nonota = $_POST['NO_NOTA'];
	$param = array(
			'no_nota'=>$nonota,
			'msg'=>''
	);
	
	$sql="declare
	begin
    nbs_transferaritpk_bynota(:no_nota,:msg);
	end;";
	 
	$db=getDb();
	$rs=$db->query($sql,$param);
	
	echo $param['msg'];
	die();

	
?>