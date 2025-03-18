<?php
$counter = explode("&",$_POST['COUNTER']);
$user = $_SESSION["ID_USER"];
$tipe_req = $_POST['TIPE'];
$tgl_delivery = $_POST['TGL_JAM_BERANGKAT'];
$vessel = $_POST['VESSEL'];
$voyage = $_POST['VOYAGE'];
$no_ukk = $_POST['NO_UKK'];
$shipping = $_POST['SHIPPING'];
$etd = $_POST['TGL_JAM_BERANGKAT'];
$stack = $_POST['TGL_JAM_BERANGKAT'];
$emkl = $_POST['EMKL'];
$pbm = $_POST['KD_PELANGGAN'];
$alamat = $_POST['ALAMAT'];
$npwp = $_POST['NPWP'];
$ket = $_POST['KET'];
$jum_detail = $_POST['JUM_DETAIL'];
$no_cont = explode("&",$_POST['NO_CONT']);
$id_brg = explode("&",$_POST['ID_BRG']);
$hz = explode("&",$_POST['HZ']);
$ukk = explode("&",$_POST['UKK']);
$jns_cont = explode("&",$_POST['JNS_CONT']);
$custom_number = $_POST['CUSTOM_NUMBER'];  
$type_cancel = "CALDG";
$no_req = $_POST["ID_BATALMUAT"];

if($tgl_delivery=="")
	echo "NO";
else
{
	$db=getDB();
	$sql = "UPDATE TB_BATALMUAT_H SET KODE_PBM='".$pbm."', JENIS='D', VESSEL='".$vessel."', VOYAGE='".$voyage."', PENGGUNA='".$user."', TGL_BERANGKAT2=to_date('$etd', 'dd/mm/yyyy'), EMKL='".$emkl."', ALAMAT='".$alamat."', NPWP='".$npwp."', KET='".$ket."', SHIPPING_LINE='".$shipping."', CUSTOM_NUMBER='".$custom_number."' WHERE ID_BATALMUAT='".$no_req."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','TB_BATALMUAT_D','UPDATE HEADER BATALMUAT','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	//delete detail terlebih dahulu, lalu insert ulang
	$sql = "DELETE TB_BATALMUAT_D WHERE ID_BATALMUAT='".$no_req."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','TB_BATALMUAT_D','UPDATE DETAIL BATALMUAT (DELETE THEN RE-INSERT)','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	$sql = "DELETE ops_billing@dbint_link WHERE NO_REQUEST='".$no_req."'";
	$db->query($sql);
	
	for($i=0; $i<$jum_detail; $i++) {
		/*$sql = "INSERT INTO TB_BATALMUAT_D 
			    (ID_BATALMUAT, NO_CONTAINER, STATUS, HZ, NO_UKK, NO_UKK_NEW, JNS_CONT, ID_CONT, TGL_STACK, TGL_BERANGKAT) 
				VALUES ('".$no_req."','".$no_cont[$i]."','".$status[$i]."','".$hz[$i]."','".$ukk[$i]."','".$no_ukk."','".$jns_cont[$i]."','".$id_brg[$i]."','".$stack."',to_date('$etd', 'dd/mm/yyyy'))";		
		$db->query($sql);		
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."~".$counter[$i]."','".$_SESSION["ID_USER"]."','TB_BATALMUAT_D','ENTRY DETAIL BATALMUAT','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);*/
		
		$temp = explode("-", $jns_cont[$i]);
		$ukuran = $temp[0];
		$tipe = $temp[1];
		$status = $temp[2];
		$jns_cont[$i] = str_replace('FULL','FCL',$jns_cont[$i]);

		$sql = "declare v_msg varchar(20); begin proc_add_batal_muat( 
		".$counter[$i].",
		'".$user."',
		'".$_SERVER['REMOTE_ADDR']."',
		'".$vessel."',
		'".$vin."',
		'".$vout."',
		'".$no_ukk."',
		'".$ukk[$i]."',
		'".$no_ukk_baru."',
		'".$shipping."',
		'".$etd."',
		'".$stack."',
		'".$no_req."',
		'".$no_cont[$i]."',
		'".$id_brg[$i]."',
		'".$hz[$i]."',
		'".$jns_cont[$i]."',
		'".$id_brg[$i]."',
		'".$tipe."',
		'".$status."',
		'".$type_cancel."',
		'".$custom_number."',
		v_msg); end;";
		$db->query($sql);
	}

	echo "OK";
}
?>