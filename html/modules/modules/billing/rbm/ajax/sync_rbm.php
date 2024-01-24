<?php 
	$no_ukk=$_POST["NO_UKK"];	
	
	$db=getDB();
	
	// update table opus_repo.m_hatch_move
	/*$db2=getDB('dbint');

	$get_vessel = "select VESSEL_CODE, VOYAGE from bil_stv_h where id_vsb_voyage = '$no_ukk'";
	$row_vessel = $db->query($get_vessel);
	$result     = $row_vessel->fetchRow();
	
	$vessel_code = $result['VESSEL_CODE'];
	$voyage 	 = $result['VOYAGE'];
	
	$sql_palka = "DECLARE
					BEGIN
						PNOADM.PRO_IF_DEPARTURE3('$vessel_code', '$voyage');
					END;";
	$db2->query($sql_palka);
	*/
	// end update hatch move
	
	$param_b_var= array(	
							"v_vsb_vyg"=>"$no_ukk",
							"v_rsp"=>"",
							"v_msg"=>""
							);
	$sql_xpi = "BEGIN prc_billstvh(:v_vsb_vyg,:v_rsp,:v_msg); END;";
	$db->query($sql_xpi,$param_b_var);
	$msg = $param_b_var['v_msg'];	
	
	echo $msg;
?>