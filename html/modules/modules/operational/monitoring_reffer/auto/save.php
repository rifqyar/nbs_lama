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
	$query = "declare begin ISWS.get_jobslip_position(:v_container,:v_req_anne,:v_berat,:v_nopol,:v_iso_code,:v_remark,:v_blok,:v_slot,:v_row,:v_tier,:v_id_user,:v_seal,:v_err ); end;";
	/*printf($no_cont);
	printf($no_req);
	printf($berat);
	printf($no_polis);
	printf($iso_code);
	printf($rm);
	printf($user);
	printf($seal);
	printf('<BR>'.$query);
	die;
	*/
	$db->query($query,$param_b_var);
	//======= PATCH INHOUSE =======//

	//========= PATCH ICT ============//
	//---get kd_pmb_dtl--
	$pmb_dtl = "select dtl.kd_pmb_dtl as PMB_DTL 
				from tth_cont_exbspl@prodlinkx hd, ttd_cont_exbspl@prodlinkx dtl
				where trim(hd.kd_pmb) = trim('$no_req')
						and trim(hd.kd_pmb) = trim(dtl.kd_pmb)
						and trim(dtl.no_container) = trim('$no_cont')";
		$dtl_pmb = $db->query($pmb_dtl);
		$hasil_pmb = $dtl_pmb->fetchRow();
		$kd_pmb_dtl = $hasil_pmb['PMB_DTL'];

	//---update gate in container	
	$update_ict = "update ttd_cont_exbspl@prodlinkx set
					tgl_gate = sysdate,
					no_gate = '001',
					no_seal = '$seal',
					gross = '$berat',
					truck_no = '$no_polis',
					truck_op = '$opr',
					keterangan = '$rm',
					status_pmb_dtl = '1',
					user_id = '$user'
					where trim(kd_pmb_dtl) = trim('$kd_pmb_dtl')";
	$db->query($update_ict);

	/*$sql_ict = "begin gatein_receiving_ict('$no_req','$berat','$seal','$no_polis','$rm','$opr','$no_cont'); end;";
	$db->query($sql_ict);*/
	//========= PATCH ICT ============//

	$blok = $param_b_var['v_blok'];	
	$slot = $param_b_var['v_slot'];	
	$row = $param_b_var['v_row'];	
	$tier = $param_b_var['v_tier'];	
	$err_msg = $param_b_var['v_err'];
		

	echo $blok.','.$slot.','.$row.','.$tier.','.$err_msg;

	//ECHO $query;
}

?>