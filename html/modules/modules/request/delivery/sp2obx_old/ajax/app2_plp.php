<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$idplp = $_POST["ID_PLP"];
$custno = $_POST["CUST_NO"];
$custnm = $_POST["CUST_NAME"];
$custadr = $_POST["CUST_ADDR"];
$custtax = $_POST["CUST_TAX"];
$tgldel = $_POST["TGL_DEL"];
$line_cont = explode("&",$_POST['LIST_CONT']);
$jmlarr = count($line_cont);
//print_r($jmlarr);die;

$db = getDB();
$db5 = getDB('ora');

$list_param = $user."^".$idplp."^".$custno."^".$custnm."^".$custadr."^".$custtax."^".$tgldel;
$sql_apph = "begin proc_sp2obx_h('$list_param'); end;";
//print_r($sql_apph);die;
$db->query($sql_apph);

//=== integrasi header operational ict ===//
$getmax = "SELECT MAX(ID_DEL) MAX_ID FROM BIL_DELOB_H";
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
				   ID_PLP,
				   STS_DELOB,
				   APP_BY,
				   TO_CHAR(APP_DATE,'DD-MM-YYYY') APP_DATE,
				   BC_NUMB,
				   TO_CHAR(BC_DATE,'DD-MM-YYYY') BC_DATE,
				   NAME_YD,
				   TRIM(NO_UKK) NO_UKK
				FROM BIL_DELOB_H
				WHERE TRIM(ID_PLP) = TRIM('$idplp')";
$dataplph = $db->query($getplph)->fetchRow();
		
$param_ict_h = $maxdel['MAX_ID']."^".$dataplph['REQ_DATE']."^".$dataplph['FLAG']."^".$dataplph['CUST_NO']."^".$dataplph['CUST_NAME']."^".$dataplph['CUST_TAX_NO']."^".$dataplph['CUST_ADDR']."^".$dataplph['CALL_SIGN']."^".$dataplph['VESSEL']."^".$dataplph['VOY_IN']."^".$dataplph['VOY_OUT']."^".$dataplph['ATA']."^".$dataplph['ATD']."^".$dataplph['EXP_DATE']."^".$dataplph['ORIGIN_TERM']."^".$dataplph['ID_YD']."^".$dataplph['NO_REQUEST']."^".$dataplph['ID_PLP']."^".$dataplph['STS_DELOB']."^".$dataplph['APP_BY']."^".$dataplph['APP_DATE']."^".$dataplph['BC_NUMB']."^".$dataplph['BC_DATE']."^".$dataplph['NAME_YD']."^".$dataplph['NO_UKK'];
$list_paramhd = array("v_statements"=>"$param_ict_h",
						"v_rsp"=>"",
						"v_msg"=>"");
$ict_apph = "begin PETIKEMAS_CABANG.PRCINS_DELOBH(:v_statements,:v_rsp,:v_msg); end;";
$db5->query($ict_apph,$list_paramhd);
$rsph = $list_paramhd['v_rsp'];
$msgh = $list_paramhd['v_msg'];

//=== integrasi header operational ict ===//

for($i=0; $i<$jmlarr; $i++) 
{
	$list_no = $line_cont[$i];
	$sql_appd = "begin proc_sp2obx_d('$idplp','$list_no'); end;";
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
				FROM BIL_DELOB_D
				WHERE TRIM(ID_DEL) = TRIM('$iddel')
					AND LINE_NUMBER = '$list_no'";
	$dataplpd = $db->query($getplpd)->fetchRow();
	
	$ydloc = "YARD";
	$param_ict_d = $maxdel['MAX_ID']."^".$dataplpd['NO_CONT']."^".$dataplpd['SIZE_CONT']."^".$dataplpd['TYPE_CONT']."^".$dataplpd['STS_CONT']."^".$dataplpd['ISO_CODE']."^".$dataplpd['PLAN_STATUS']."^".$dataplpd['HZ_CONT']."^".$dataplpd['UN_NUMBER']."^".$dataplpd['IMO_CLASS']."^".$dataplpd['HEIGHT_CONT']."^".$ydloc."^".$dataplpd['IDPOD']."^".$dataplpd['IDPOL'];
	$list_params = array("v_statements"=>"$param_ict_d",
						"v_rsp"=>"",
						"v_msg"=>"");
	$ict_appd = "begin PETIKEMAS_CABANG.PRCINS_DELOBD(:v_statements,:v_rsp,:v_msg); end;";
	$db5->query($ict_appd,$list_params);
	$rspd = $list_params['v_rsp'];
	$msgd = $list_params['v_msg'];
	//=== integrasi header operational ict ===//
}

echo "OK";

?>