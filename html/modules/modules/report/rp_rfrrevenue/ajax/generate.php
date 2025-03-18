<?php
	$period=$_POST['period'];
	$periodtype=$_POST['periodtype'];

	$param=array(
			"v_period"=>"$periodtype",
			"v_periodparam"=>"$period",
			"v_out"=>""
		);
	//echo $param;die;
	$query="begin prc_stgreportrfr(:v_period, :v_periodparam, :v_out); end;";
	$db=getDb();
	$exec=$db->query($query,$param);
	echo $param['v_out'];

?>