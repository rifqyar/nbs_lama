<?php
	$db=getDB();	
$user = $_SESSION["NAMA_PENGGUNA"];
$tipe = $_POST['TIPE'];
$vessel = $_POST['VESSEL'];
$voyage = $_POST['VOYAGE'];
$voyage_out= $_POST['VOYAGE_OUT'];
$paid_thru= $_POST['PAID_THRU'];
$no_ukk = $_POST['NO_UKK'];
$shipping = $_POST['SHIPPING'];
$emkl = $_POST['EMKL'];
$alamat = $_POST['ALAMAT'];
$npwp = $_POST['NPWP'];
$no_bc = $_POST['NO_BC'];
$ket = $_POST['KET'];
$kdp= $_POST['KD_PELANGGAN'];
$no_do = $_POST['NO_DO'];
$jum_detail = $_POST['JUM_DETAIL'];
$counter = explode("&",$_POST['COUNTER']);
$no_cont = explode("&",$_POST['NO_CONT']);
$id_brg = explode("&",$_POST['ID_BRG']);
$hz = explode("&",$_POST['HZ']);
$jns_cont = explode("&",$_POST['JNS_CONT']);
//validate
for($i=0; $i<$jum_detail; $i++) {
	$v_nocont=$no_cont[$i];
	$q_cek = "SELECT COUNT(*) CEK FROM REQ_HICOSCAN_D WHERE NO_CONTAINER = '$v_nocont' AND NO_UKK = '$no_ukk'";
	$r_cek = $db->query($q_cek)->fetchRow();
	if ($r_cek['CEK'] > 0) {
		echo "EXIST";
		die();
	}
}

if($no_ukk=="")
	echo "NO";
else
{
	if($tipe=='Import')
	{
		$ei='I';
	}
	else
		$ei='E';
	
	$ket_id = "HCS";
	

	
	$q_max = "SELECT NVL(MAX(SUBSTR(ID_REQUEST,10,6)),0)+1 AS NO FROM REQ_HICOSCAN WHERE SUBSTR(ID_REQUEST,4,4)=TO_CHAR(SYSDATE,'YYYY')";
	$row = $db->query($q_max)->fetchRow();
	$no_req = $ket_id.date("Ym").str_pad($row['NO'],6,0,STR_PAD_LEFT);
	
	$sql = "INSERT INTO REQ_HICOSCAN (ID_REQUEST, TGL_REQUEST, NOMOR_INSTRUKSI, ID_USER, VOYAGE, VESSEL, EMKL, ALAMAT_EMKL, NPWP, TIPE_REQ, SHIPPING_LINE, KET, NO_UKK, KD_PELANGGAN, VOYAGE_OUT, PAID_THRU,NO_DO) 
			VALUES ('".$no_req."',SYSDATE,'".$no_bc."','".$_SESSION["ID_USER"]."','".$voyage."','".$vessel."','".$emkl."','".$alamat."','".$npwp."','".$tipe."','".$shipping."','".$ket."','".$no_ukk."','$kdp', '$voyage_out',TO_DATE('$paid_thru','DD-MM-YYYY'),'$no_do')";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','REQ_HICOSCAN','ENTRY HEADER HICOSCAN','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	for($i=0; $i<$jum_detail; $i++) {
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
						"v_msg"=>""
						);
		//print_r($param);die;
		$sql="declare begin proc_add_cont_hico(:v_user,:v_ipaddr,:v_vessel,:v_voy,:v_voy2,:ei,:inst,:paid_thru,:v_id,:v_req,:v_idbarang, :v_nocont,:v_hz,:v_jenis_cont, :v_ukk,:v_response,:v_msg); end;";
		$db->query($sql,$param);
		$resp = $param['v_response'];	
		$msg = $param['v_msg'];	
	}

	echo "OK";
}
?>