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
$db5 = getDB('dbint');
$db3 = getDB('dbportal');

$q_cek = "SELECT count(*) AS JML FROM BIL_DELSPJM_H where trim(NO_PIB) like trim('$idplp')";
$res_cek=$db->query($q_cek);
$row_cek=$res_cek->fetchRow();

if($row_cek['JML']>0){
	echo('EXIST');
	die;
} else {
	$q_pkk = "SELECT * FROM M_VSB_VOYAGE where trim(ID_VSB_VOYAGE) like trim('$no_ukk')";
	$res_pkk=$db5->query($q_pkk);
	$row_pkk=$res_pkk->fetchRow();
	
	$q_pib = "SELECT * FROM SPPUD where trim(PIB_NO) like trim('$idplp')";
	$res_pib=$db3->query($q_pib);
	$row_pib=$res_pib->fetchRow();

	$list_param = $user."^".$idplp."^".$custno."^".$custnm."^".$custadr."^".$custtax."^".$tgldel."^".$row_pkk['CALL_SIGN']."^".$row_pkk['VESSEL']."^".$row_pkk['VOYAGE_IN']."^".$row_pkk['VOYAGE_OUT']."^".$row_pkk['ATA']."^".$row_pkk['ATD']."^".$no_ukk."^".$row_pib['PIB_NO']."^".$row_pib['PIB_DATE'];
	
	$sql_apph = "begin proc_sp2spjm_opus_h('$list_param'); end;";
	$db->query($sql_apph);

	//=== integrasi DETAIL ===//
	
	$getmax = "SELECT MAX(ID_DEL) MAX_ID FROM BIL_DELSPJM_H";
	$maxdel = $db->query($getmax)->fetchRow();
	$iddel = 'SPJ'.$maxdel['MAX_ID'];
	
	for($i=0; $i<$jmlarr; $i++) 
	{
		$list_no = $line_cont[$i];
		
		$list_params = array("v_nc"=>"$list_no",
							"v_req"=>"$iddel",
							"v_ukk"=>"$no_ukk",
							"v_msg"=>"");
		$ict_appd = "begin proc_add_cont_spjm_opus(:v_nc,:v_req,:v_ukk, :v_msg); end;";
		$db->query($ict_appd,$list_params);
		$msgd = $list_params['v_msg'];
        if($msgd=='OK'){
			$q_cont = "SELECT A.NO_CONTAINER,
						   A.SIZE_CONT,
						   A.TYPE_CONT,
						   CASE WHEN A.STATUS='FULL' THEN 'FCL' ELSE 'MTY' END STATUS_CONT,
						   A.ISO_CODE,
						   A.HZ,
						   A.UN_NUMBER,
						   A.IMO,
						   A.HEIGHT,
						   A.POD,
						   A.POL 
							FROM M_CYC_CONTAINER A INNER JOIN M_VSB_VOYAGE B ON (A.VESSEL = B.VESSEL AND A.VOYAGE_IN = B.VOYAGE_IN AND A.VOYAGE_OUT = B.VOYAGE_OUT)
						WHERE A.CONT_LOCATION = 'Yard'
							AND 
							B.ID_VSB_VOYAGE LIKE '$no_ukk'
							AND A.NO_CONTAINER LIKE '$list_no'";
			$res_cont=$db5->query($q_cont);
			$row_cont=$res_cont->fetchRow();
			
			$list_param2=$row_cont['NO_CONTAINER']."^".$row_cont['SIZE_CONT']."^".$row_cont['TYPE_CONT']."^".$row_cont['STATUS_CONT']."^".$row_cont['ISO_CODE']."^".$row_cont['HZ']."^".$row_cont['UN_NUMBER']."^".$row_cont['IMO']."^".$row_cont['HEIGHT']."^".$row_cont['POD']."^".$row_cont['POL'];
			
			$sql_appd = "begin proc_sp2spjm_d('$list_param2',".($i+1)."); end;";
			//print_r($sql_appd);
			$db->query($sql_appd);
		} else {
			echo("Failed");
			die();
		}
	}
	
	$param_payment2= array(
					 "ID_NOTA"=>'TPFT',
					 "ID_REQ"=>$iddel,
					 "OUT"=>'',
					 "OUT_MSG"=>''
					);
	$query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";
	$db5->query($query2,$param_payment2);

	echo "OK";

}

?>