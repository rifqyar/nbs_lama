<?php
$no_req = $_POST["ID_REQUEST"];
$paid_thru = $_POST['PAID_THRU'];
$tipe = $_POST['TIPE'];
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
	if($tipe=='Import')
	{
		$ei='I';
	}
	else
		$ei='E';
	$db=getDB();
	$sql = "UPDATE REQ_BEHANDLE_H SET NOMOR_INSTRUKSI='".$no_bc."', VOYAGE_IN='".$voyage."', VESSEL='".$vessel."', 
			EMKL='".$emkl."', COA='".$coa."', ALAMAT_EMKL='".$alamat."', NPWP='".$npwp."', 
			TIPE_REQ='".$tipe."', SHIPPING_LINE='".$shipping."', 
			KET='".$ket."', NO_UKK='".$no_ukk."', PAID_THRU=to_date('".$paid_thru."','DD-MM-YYYY'), TGL_SPJM=to_date('".$tgl_spjm."','DD-MM-YYYY') ,TYPE_SPJM='".$type_spjm."'
			WHERE ID_REQ='".$no_req."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','BH_REQUEST','UPDATE HEADER BEHANDLE','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	//delete detail terlebih dahulu, lalu insert ulang
	$sql = "DELETE REQ_BEHANDLE_D WHERE ID_REQ='".$no_req."'";
	$db->query($sql);
	
	
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','BH_DETAIL_REQUEST','UPDATE DETAIL BEHANDLE (DELETE THEN RE-INSERT)','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	$sql = "DELETE M_BILLING WHERE NO_REQUEST='".$no_req."' AND FLAG='BHD'";
	$db2=getDB('dbint');
	$db2->query($sql);
	
	
	for($i=0; $i<$jum_detail; $i++) {
	/*	$sql = "INSERT INTO BH_DETAIL_REQUEST (ID, ID_REQUEST, ID_BARANG, NO_CONTAINER, HAZZARD, JNS_CONT, NO_UKK) 
	VALUES ('".$counter[$i]."','".$no_req."','".$id_brg[$i]."',UPPER('".$no_cont[$i]."'),'".$hz[$i]."','".$jns_cont[$i]."','".$no_ukk."')";		
		$db->query($sql);		
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."~".$counter[$i]."','".$_SESSION["ID_USER"]."','BH_DETAIL_REQUEST','ENTRY DETAIL BEHANDLE','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	*/
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
		//$sql="declare begin proc_add_cont_bhd('$v_user','$v_ipaddr','$vessel','$voyage','$voyage_out','$ei','$inst','$paid_thru','$v_id','$no_req','$v_idbarang', '$v_nocont','$v_hz','$v_jenis_cont', '$v_ukk','$v_response','$v_msg'); end;";
		//echo $sql;die;
		$sql="declare begin proc_add_cont_bhd(:v_user,:v_ipaddr,:v_vessel,:v_voy,:v_voy2,:ei,:inst,:paid_thru,:v_id,:v_req,:v_idbarang, :v_nocont,:v_hz,:v_jenis_cont, :v_ukk,:v_response,:v_msg,:ops_response); end;";
		$db->query($sql,$param);
		$resp = $param['v_response'];	
		$msg = $param['v_msg'];	
		$ops_msg = $param['ops_response'];
		
	}

	echo $ops_msg;
}
?>