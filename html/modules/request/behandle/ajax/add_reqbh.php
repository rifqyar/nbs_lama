<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$tipe = $_POST['TIPE'];
$paid_thru = $_POST['PAID_THRU'];
$vessel = $_POST['VESSEL'];
$voyage = $_POST['VOYAGE'];
$voyage_out = $_POST['VOYAGE_OUT'];
$no_ukk = $_POST['NO_UKK'];
$shipping = $_POST['SHIPPING'];
$emkl = $_POST['EMKL'];
$coa = $_POST['COA'];
$alamat = $_POST['ALAMAT'];
$npwp = $_POST['NPWP'];
$no_bc = $_POST['NO_BC'];
$ket = $_POST['KET'];
$jum_detail = $_POST['JUM_DETAIL'];
$counter = explode("&",$_POST['COUNTER']);
$no_cont = explode("&",$_POST['NO_CONT']);
$id_brg = explode("&",$_POST['ID_BRG']);
$hz = explode("&",$_POST['HZ']);
$jns_cont = explode("&",$_POST['JNS_CONT']);
$type_spjm = $_POST['TYPE_SPJM'];
$tgl_spjm = $_POST['TGL_SPJM'];



if($no_ukk=="")
	echo "NO";
else
{
	$ket_id = "BHD";
	
	$db=getDB();	

	$q_max = "SELECT NVL(MAX(SUBSTR(ID_REQ,10,6)),0)+1 AS NO FROM REQ_BEHANDLE_H WHERE SUBSTR(ID_REQ,4,4)=TO_CHAR(SYSDATE,'YYYY')";
	$row = $db->query($q_max)->fetchRow();
	$no_req = $ket_id.date("Ym").str_pad($row['NO'],6,0,STR_PAD_LEFT);
	
	$query_vescode="SELECT VESSEL_CODE, CALL_SIGN FROM M_VSB_VOYAGE@dbint_link WHERE VESSEL='".$vessel."' AND VOYAGE_IN='".$voyage."' AND VOYAGE_OUT='".$voyage_out."'";
	$row_vescode = $db->query($query_vescode)->fetchRow();
	
	$sql = "INSERT INTO REQ_BEHANDLE_H (ID_REQ, TGL_REQUEST, NOMOR_INSTRUKSI, ID_USER, VOYAGE_IN, VESSEL, EMKL, COA, ALAMAT_EMKL, NPWP, TIPE_REQ, SHIPPING_LINE, KET, NO_UKK, VOYAGE_OUT, PAID_THRU, TYPE_SPJM,TGL_SPJM,VESSEL_CODE,CALL_SIGN) 
	VALUES ('".$no_req."',SYSDATE,'".$no_bc."','".$_SESSION["ID_USER"]."','".$voyage."','".$vessel."','".$emkl."','".$coa."','".$alamat."','".$npwp."','".$tipe."','".$shipping."','".$ket."','".$no_ukk."','".$voyage_out."',to_date('".$paid_thru."','DD-MM-YYYY'),'".$type_spjm."', to_date('".$tgl_spjm."','dd-mm-yyyy'),'".$row_vescode['VESSEL_CODE']."','".$row_vescode['CALL_SIGN']."')";
	//print_r($sql);die;
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','BH_REQUEST','ENTRY HEADER BEHANDLE','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	if($tipe=='Import')
	{
		$ei='I';
	}
	else
		$ei='E';
	
	//echo $jum_detail;
	
	for($i=0; $i<$jum_detail; $i++) 
	{
		//echo "coba";
		$v_id=$counter[$i];
		$v_idbarang=$id_brg[$i];
		$v_nocont=$no_cont[$i];
		$v_hz=$hz[$i];
		$v_jenis_cont=$jns_cont[$i];
		$v_user=$_SESSION['ID_USER'];
		$v_ipaddr=$_SERVER['REMOTE_ADDR'];		
		$param =array(	
						"v_user"=>"$v_user",
						"v_ipaddr"=>"$v_ipaddr",
						"v_vessel"=>"$vessel",
						"v_voy"=>"$voyage",
						"v_voy2"=>"$voyage_out",
						"ei"=>"$ei",
						"inst"=>"$no_bc",
						"paid_thru"=>"$paid_thru",
						"v_id"=>"$v_id",
						"v_req"=>"$no_req",
						"v_idbarang"=>"$v_idbarang",
						"v_nocont"=>"$v_nocont",
						"v_hz"=>"$v_hz",
						"v_jenis_cont"=>"$v_jenis_cont",
						"v_ukk"=>"$no_ukk",
						"v_response"=>"",
						"v_msg"=>"",
						"ops_response"=>""
						);
		
		//print_r($param);die;
		$sql="declare begin proc_add_cont_bhd(:v_user,:v_ipaddr,:v_vessel,:v_voy,:v_voy2,:ei,:inst,:paid_thru,:v_id,:v_req,:v_idbarang, :v_nocont,:v_hz,:v_jenis_cont, :v_ukk,:v_response,:v_msg,:ops_response); end;";
		$db->query($sql,$param);
		$resp = $param['v_response'];	
		$msg = $param['v_msg'];
		$ops_msg = $param['ops_response'];
	}
	
	echo $ops_msg;
}
?>