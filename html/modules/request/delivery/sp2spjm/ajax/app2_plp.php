<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$idplp = $_POST["ID_PLP"];
$custno = $_POST["CUST_NO"];
$custnm = $_POST["CUST_NAME"];
$custadr = $_POST["CUST_ADDR"];
$custtax = $_POST["CUST_TAX"];
$tgldel = $_POST["TGL_DEL"];
$no_ukk = $_POST["NO_UKK"];
$line_cont = explode("&",$_POST['LIST_CONT']);
$jmlarr = count($line_cont);
//print_r($jmlarr);die;

$db = getDB();
$db5 = getDB('ora');
$db3 = getDB('dbportal');

$q_cek = "SELECT count(*) AS JML FROM BIL_DELSPJM_H where trim(NO_PIB) like trim('$idplp')";
$res_cek=$db->query($q_cek);
$row_cek=$res_cek->fetchRow();

if($row_cek['JML']>0){
	echo('EXIST');
	die;
} else {
	$q_pkk = "SELECT * FROM v_pkk_cont where trim(NO_UKK) like trim('$no_ukk')";
	$res_pkk=$db5->query($q_pkk);
	$row_pkk=$res_pkk->fetchRow();
	
	$q_pib = "SELECT * FROM SPPUD where trim(PIB_NO) like trim('$idplp')";
	$res_pib=$db3->query($q_pib);
	$row_pib=$res_pib->fetchRow();

	$list_param = $user."^".$idplp."^".$custno."^".$custnm."^".$custadr."^".$custtax."^".$tgldel."^".$row_pkk['CALL_SIGN']."^".$row_pkk['NM_KAPAL']."^".$row_pkk['VOYAGE_IN']."^".$row_pkk['VOYAGE_OUT']."^".$row_pkk['TGL_JAM_TIBA']."^".$row_pkk['TGL_JAM_BERANGKAT']."^".$no_ukk."^".$row_pib['PIB_NO']."^".$row_pib['PIB_DATE'];
	/*
	 v2_callsign:= TRIM(if_split(v_param, '^', 8));
	   v2_vessel:= TRIM(if_split(v_param, '^', 9));
	   v2_voy_in:= TRIM(if_split(v_param, '^', 10));
	   v2_voy_out:= TRIM(if_split(v_param, '^', 11));
	   v2_ata:= TRIM(if_split(v_param, '^', 12));
	   v2_atd:= TRIM(if_split(v_param, '^', 13));
	   v2_ukk:= TRIM(if_split(v_param, '^', 14));
	*/
	$sql_apph = "begin proc_sp2spjm_h('$list_param'); end;";
	//print_r($sql_apph);
	$db->query($sql_apph);

	//=== integrasi header operational ict ===//
	$getmax = "SELECT MAX(ID_DEL) MAX_ID FROM BIL_DELSPJM_H";
	$maxdel = $db->query($getmax)->fetchRow();
	$iddel = $maxdel['MAX_ID'];
	$getplph = "SELECT ID_DEL,
					   TO_CHAR(REQ_DATE,'DD-MM-YYYY') REQ_DATE,
					   FLAG,
					   CUST_NO,
					   CUST_NAME,
					   CUST_TAX_NO,
					   CUST_ADDR,
					   CALL_SIGN,
					   VESSEL,
					   VOY_IN,
					   VOY_OUT,
					   TO_CHAR(ATA,'DD-MM-YYYY') ATA,
					   TO_CHAR(ATD,'DD-MM-YYYY') ATD,
					   TO_CHAR(EXP_DATE,'DD-MM-YYYY') EXP_DATE,
					   ORIGIN_TERM,
					   ID_YD,
					   NO_REQUEST,
					   NO_PIB,
					   STS_DELSPJM,
					   APP_BY,
					   TO_CHAR(APP_DATE,'DD-MM-YYYY') APP_DATE,
					   BC_NUMB,
					   TO_CHAR(BC_DATE,'DD-MM-YYYY') BC_DATE,
					   NAME_YD,
					   TRIM(NO_UKK) NO_UKK
					FROM BIL_DELSPJM_H
					WHERE TRIM(NO_PIB) = TRIM('$idplp')";
	$dataplph = $db->query($getplph)->fetchRow();
			
	$param_ict_h = $maxdel['MAX_ID']."^".$dataplph['REQ_DATE']."^".$dataplph['FLAG']."^".$dataplph['CUST_NO']."^".$dataplph['CUST_NAME']."^".$dataplph['CUST_TAX_NO']."^".$dataplph['CUST_ADDR']."^".$dataplph['CALL_SIGN']."^".$dataplph['VESSEL']."^".$dataplph['VOY_IN']."^".$dataplph['VOY_OUT']."^".$dataplph['ATA']."^".$dataplph['ATD']."^".$dataplph['EXP_DATE']."^".$dataplph['ORIGIN_TERM']."^".$dataplph['ID_YD']."^".$dataplph['NO_REQUEST']."^".$dataplph['NO_PIB']."^".$dataplph['STS_DELSPJM']."^".$dataplph['APP_BY']."^".$dataplph['APP_DATE']."^".$dataplph['BC_NUMB']."^".$dataplph['BC_DATE']."^".$dataplph['NAME_YD']."^".$dataplph['NO_UKK'];
	$list_paramhd = array("v_statements"=>"$param_ict_h",
							"v_rsp"=>"",
							"v_msg"=>"");
	$ict_apph = "begin PETIKEMAS_CABANG.PRCINS_DELSPJMH(:v_statements,:v_rsp,:v_msg); end;";
	//echo($param_ict_h);
	//echo($ict_apph);
	$db5->query($ict_apph,$list_paramhd);
	$rsph = $list_paramhd['v_rsp'];
	$msgh = $list_paramhd['v_msg'];

	//=== integrasi header operational ict ===//
	
	for($i=0; $i<$jmlarr; $i++) 
	{
		$list_no = $line_cont[$i];
		
		$q_cont = "SELECT A.CONT_NO_BP,
					   A.KD_SIZE,
					   A.KD_TYPE,
					   A.KD_STATUS_CONT,
					   A.CONT_TYPE,
					   CASE WHEN CLASS IS NULL THEN 'N' ELSE 'Y' END HZ,
					   '' UN,
					   CLASS AS IMO,
					   A.HEIGHT,
					   A.DISC_PORT,
					   A.LOAD_PORT
				  FROM TTD_BP_CONT A INNER JOIN TTM_BP_CONT B ON (A.BP_ID = B.BP_ID)
				 WHERE B.KD_CABANG = '01'
					 AND no_ukk LIKE '$no_ukk'
					 AND A.CONT_NO_BP like '$list_no'";
		$res_cont=$db5->query($q_cont);
		$row_cont=$res_cont->fetchRow();
		
		$list_param2=$row_cont['CONT_NO_BP']."^".$row_cont['KD_SIZE']."^".$row_cont['KD_TYPE']."^".$row_cont['KD_STATUS_CONT']."^".$row_cont['CONT_TYPE']."^".$row_cont['HZ']."^".$row_cont['UN']."^".$row_cont['IMO']."^".$row_cont['HEIGHT']."^".$row_cont['DISC_PORT']."^".$row_cont['LOAD_PORT'];
		
		/*v_nocont,
					   v_size,
					   v_type,
					   v_status,
					   v_isocode,
					   v_hz,
					   v_un,
					   v_imo,
					   v_height,
					   v_pod, 
					   v_pol*/
		$sql_appd = "begin proc_sp2spjm_d('$list_param2',".($i+1)."); end;";
		//print_r($sql_appd);
		$db->query($sql_appd);
		
		//=== integrasi detail operational ict ===//
		$getplpd = "SELECT ID_DEL,
						   NO_CONT,
						   SIZE_CONT,
						   TYPE_CONT,
						   STS_CONT,
						   ISO_CODE,
						   PLAN_STATUS,
						   HZ_CONT,
						   UN_NUMBER,
						   IMO_CLASS,
						   HEIGHT_CONT,
						   IDPOD,
						   IDPOL
					FROM BIL_DELSPJM_D
					WHERE TRIM(ID_DEL) = TRIM('$iddel')
						AND LINE_NUMBER = '".($i+1)."'";
		$dataplpd = $db->query($getplpd)->fetchRow();
		
		$ydloc = "YARD";
		$param_ict_d = $maxdel['MAX_ID']."^".$dataplpd['NO_CONT']."^".$dataplpd['SIZE_CONT']."^".$dataplpd['TYPE_CONT']."^".$dataplpd['STS_CONT']."^".$dataplpd['ISO_CODE']."^".$dataplpd['PLAN_STATUS']."^".$dataplpd['HZ_CONT']."^".$dataplpd['UN_NUMBER']."^".$dataplpd['IMO_CLASS']."^".$dataplpd['HEIGHT_CONT']."^".$ydloc."^".$dataplpd['IDPOD']."^".$dataplpd['IDPOL'];
		$list_params = array("v_statements"=>"$param_ict_d",
							"v_rsp"=>"",
							"v_msg"=>"");
		$ict_appd = "begin PETIKEMAS_CABANG.PRCINS_DELSPJMD(:v_statements,:v_rsp,:v_msg); end;";
		//echo($param_ict_d);
		//echo($ict_appd);
		$db5->query($ict_appd,$list_params);
		$rspd = $list_params['v_rsp'];
		$msgd = $list_params['v_msg'];
		//=== integrasi header operational ict ===//
	}

	echo "OK";

}

?>