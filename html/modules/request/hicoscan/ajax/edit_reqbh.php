<?php
$db=getDB();
$no_req = $_POST["ID_REQUEST"];
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
$kdp = $_POST['KD_PELANGGAN'];
$no_do = $_POST['NO_DO'];
$jum_detail = $_POST['JUM_DETAIL'];
$counter = explode("&",$_POST['COUNTER']);
$no_cont = explode("&",$_POST['NO_CONT']);
$id_brg = explode("&",$_POST['ID_BRG']);
$hz = explode("&",$_POST['HZ']);
$jns_cont = explode("&",$_POST['JNS_CONT']);

//echo "EXIST"; die();
for($j=0; $j<$jum_detail; $j++) {
	$v_nocont=$no_cont[$j];
	$q_cek = "SELECT COUNT(*) CEK FROM REQ_HICOSCAN_D WHERE NO_CONTAINER = '$v_nocont' AND NO_UKK = '$no_ukk'";
	//echo $q_cek; die();
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
	
	$sql = "UPDATE REQ_HICOSCAN SET KD_PELANGGAN='$kdp', NOMOR_INSTRUKSI='".$no_bc."', VOYAGE='".$voyage."', VESSEL='".$vessel."', 
			EMKL='".$emkl."', ALAMAT_EMKL='".$alamat."', NPWP='".$npwp."', 
			TIPE_REQ='".$tipe."', SHIPPING_LINE='".$shipping."', 
			KET='".$ket."', NO_UKK='".$no_ukk."', PAID_THRU=to_date('$paid_thru','dd-mm-yyyy'), NO_DO='$no_do'  WHERE ID_REQUEST='".$no_req."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','REQ_HICOSCAN','UPDATE HEADER HICOSCAN','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	//delete detail terlebih dahulu, lalu insert ulang
	$sql = "DELETE REQ_HICOSCAN_D WHERE ID_REQUEST='".$no_req."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','REQ_HICOSCAN_D','UPDATE DETAIL HICOSCAN (DELETE THEN RE-INSERT)','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	$sql = "DELETE M_BILLING WHERE NO_REQUEST='".$no_req."' and FLAG='HICO'";
	$db2=getDB('dbint');
	$db2->query($sql);

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
		//$sql="declare begin proc_add_cont_hico('$v_user','$v_ipaddr','$vessel','$voyage','$voyage_out','$ei','$inst','$paid_thru','$v_id','$no_req','$v_idbarang', '$v_nocont','$v_hz','$v_jenis_cont', '$v_ukk','$v_response','$v_msg'); end;";
		//echo $sql;die;
		$sql="declare begin proc_add_cont_hico(:v_user,:v_ipaddr,:v_vessel,:v_voy,:v_voy2,:ei,:inst,:paid_thru,:v_id,:v_req,:v_idbarang, :v_nocont,:v_hz,:v_jenis_cont, :v_ukk,:v_response,:v_msg); end;";
		$db->query($sql,$param);
		$resp = $param['v_response'];	
		$msg = $param['v_msg'];	
	}

	echo "OK";
}
?>