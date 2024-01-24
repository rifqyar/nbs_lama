<?php

$counter = explode("&",$_POST['COUNTER']);
$user = $_SESSION["ID_USER"];
$tipe_req = $_POST['TIPE'];
$tgl_delivery = $_POST['TGL_DELIVERY'];
$emkl = $_POST['EMKL'];
$pbm = $_POST['KD_PELANGGAN'];
$alamat = $_POST['ALAMAT'];
$npwp = $_POST['NPWP'];
$emkl_penumpukan = $_POST['EMKL_PENUMPUKAN'];
$pbm_penumpukan = $_POST['KD_PELANGGAN_PENUMPUKAN'];
$alamat_penumpukan = $_POST['ALAMAT_PENUMPUKAN'];
$npwp_penumpukan = $_POST['NPWP_PENUMPUKAN'];
$ket = $_POST['KET'];
$jum_detail = $_POST['JUM_DETAIL'];
$no_cont = explode("&",$_POST['NO_CONT']);
$id_brg = explode("&",$_POST['ID_BRG']);
$hz = explode("&",$_POST['HZ']);
$ukk = $_POST['UKK'];
$jns_cont = explode("&",$_POST['JNS_CONT']);
$custom_number = $_POST['CUSTOM_NUMBER'];
$type_cancel = "CALDG";
$vessel = $_POST['VESSEL'];
$voyage = $_POST['VOYAGE'];
$voyage_out = $_POST['VOYAGE_OUT'];
$tgl_del = $_POST['TGL_DEL'];
$ship = $_POST['SHIP'];
//print_r($vessel);die;

if($tgl_delivery=="")
	echo "NO";
else
{
	$db=getDB();
	$db3=getDB('dbint');	
	
	//mengambil data vessel code dan call sign
	$sql_vc = "SELECT VESSEL_CODE, CALL_SIGN FROM M_VSB_VOYAGE@DBINT_LINK WHERE VESSEL='$vessel' AND VOYAGE_IN='$voyage' AND VOYAGE_OUT='$voyage_out'";
		$res_vc = $db->query($sql_vc);
		$hasil_vc = $res_vc->fetchRow();
		
	$sql = "INSERT INTO REQ_BATALMUAT_H 
		    (KODE_PBM, JENIS, VESSEL, VOYAGE_IN,VOYAGE_OUT, PENGGUNA, STATUS, TGL_BERANGKAT2, EMKL, ALAMAT, NPWP, CUSTOM_NUMBER, KET,VESSEL_CODE,CALL_SIGN,D_I,KODE_PBM_PENUMPUKAN,EMKL_PENUMPUKAN, ALAMAT_PENUMPUKAN, NPWP_PENUMPUKAN, STATUS_PENUMPUKAN) 
			VALUES ('$pbm','D', '$vessel', '$voyage','$voyage_out', '$user', 'N', to_date('$tgl_delivery', 'dd/mm/yyyy'), '$emkl', '$alamat', '$npwp', '$custom_number', '$ket', '".$hasil_vc['VESSEL_CODE']."', '".$hasil_vc['CALL_SIGN']."','$ship','$pbm_penumpukan','$emkl_penumpukan','$alamat_penumpukan','$npwp_penumpukan','N')";
			
	$db->query($sql);	
	
	//mengambil nilai dari id requestnya
	$sql_id = "SELECT MAX(ID_REQ) as ID_BAT FROM REQ_BATALMUAT_H WHERE ID_REQ LIKE 'BM%'";
	$res = $db->query($sql_id);
	$hasil = $res->fetchRow();
	$id_req = $hasil["ID_BAT"];
	
	
	
	//edited by dendoy, cek tgl kapal lama
	$sql_vsb = "SELECT TO_DATE(ATD,'YYYYMMDDHH24MISS') AS ATD_DT FROM M_VSB_VOYAGE WHERE TRIM(VESSEL) = '$vessel' AND TRIM(VOYAGE_IN) = '$voyage' AND TRIM(VOYAGE_OUT) = '$voyage_out'";
	$res_vsb = $db3->query($sql_vsb);
	$hasil_vsb = $res_vsb->fetchRow();
	$vsbtgl = $hasil_vsb["ATD_DT"];

	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$id_req."','".$_SESSION["ID_USER"]."','BMD_REQUEST','ENTRY HEADER BATALMUAT DELIVERY','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	for($i=0; $i<$jum_detail; $i++) {
		$temp = explode("-", $jns_cont[$i]);
		$ukuran = $temp[0];
		$tipe = $temp[1];
		$status = $temp[2];
		$jns_cont[$i] = str_replace('FULL','FCL',$jns_cont[$i]);
		
		$sql = "declare v_msg varchar(20); 
		begin proc_add_batal_muat( 
		".$counter[$i].",
		'".$user."',
		'".$_SERVER['REMOTE_ADDR']."',
		'".$vessel."',
		'".$voyage."',
		'".$voyage_out."',
		'".$ukk."',
		'".$ukk."',
		'".$no_ukk_baru."',
		'".$shipping."',
		to_date('".$vsbtgl."', 'DD-Mon-YY'),		
		to_date('".$tgl_del."', 'DD/MM/YY'),		
		'".$id_req."',
		'".$no_cont[$i]."',
		'".$id_brg[$i]."',
		'".$hz[$i]."',
		'".$jns_cont[$i]."',
		'".$id_brg[$i]."',
		'".$tipe."',
		'".$status."',
		'".$type_cancel."',
		'".$custom_number."',
		'".$emkl."',
		v_msg); end;";
		$db->query($sql);
		
		/*$sql = "INSERT INTO REQ_BATALMUAT_D 
			    (ID_REQ, NO_CONTAINER, STATUS, HZ, NO_UKK, NO_UKK_NEW, JNS_CONT, ID_CONT, TGL_STACK, TGL_BERANGKAT) 
				VALUES ('".$id_req."','".$no_cont[$i]."','".$status[$i]."','".$hz[$i]."','".$ukk[$i]."','".$no_ukk."','".$jns_cont[$i]."','".$id_brg[$i]."','".$stack."',to_date('$tgl_delivery', 'dd/mm/yyyy'))";
		$db->query($sql);
		
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$id_req."~".$counter[$i]."','".$_SESSION["ID_USER"]."','BMD_DETAIL_REQUEST','ENTRY DETAIL BATALMUAT D','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);*/
	}
	echo "OK";
}


?>