<?php
$counter = explode("&",$_POST['COUNTER']);
$user = $_SESSION["ID_USER"];
$tipe_req = $_POST['TIPE'];
$vessel = $_POST['VESSEL'];
$voyage = $_POST['VOYAGE'];
$voyage2 = $_POST['VOYAGE_OUT'];
$no_ukk = $_POST['NO_UKK'];
$no_ukk_baru = $_POST['NO_UKK'];
$shipping = $_POST['SHIPPING'];
$fpod = $_POST['FPOD'];
$id_fpod = $_POST['ID_FPOD'];
$etd = $_POST['TGL_JAM_BERANGKAT'];
$stack = $_POST['OPEN_STACK'];
$emkl = $_POST['EMKL'];
$pbm = $_POST['KD_PELANGGAN'];
$alamat = $_POST['ALAMAT'];
$npwp = $_POST['NPWP'];
$ket = $_POST['KET'];
$jum_detail = $_POST['JUM_DETAIL'];
//id_req belum
$no_cont = explode("&",$_POST['NO_CONT']);
$id_brg = explode("&",$_POST['ID_BRG']);
$hz = explode("&",$_POST['HZ']);
$ukk = explode("&",$_POST['UKK']);
$jns_cont = explode("&",$_POST['JNS_CONT']);
$custom_number = $_POST['CUSTOM_NUMBER'];
$npe = $_POST['NPE'];
$peb = $_POST['PEB'];
$booking_numb = $_POST['BOOKING_NUMB'];


$msg = "";
if($tipe_req=="A") {
	$type_cancel = "CALAG";
} else {
	$type_cancel = "CALBG";
}

if($no_ukk=="")
	echo "NO";
else
{
	//echo '1';die;
	$db=getDB();	
	
	//memasukkan data ke tabel header
	$sql = "INSERT INTO TB_BATALMUAT_H 
		    (KODE_PBM, 
		     JENIS, 
		     VESSEL, 
		     VOYAGE,  
		     SHIPPING_LINE,
		     PENGGUNA, 
		     STATUS, 
		     TGL_BERANGKAT2, 
		     EMKL, 
		     ALAMAT, 
		     NPWP, 
		     KET, 
		     CUSTOM_NUMBER, 
		     NPE, 
		     PEB, 
		     BOOKING_NUMB,
		     FPOD,
		     ID_FPOD) 
			VALUES ('$pbm','$tipe_req', '$vessel', '$voyage', '$shipping', '$user', 'N', '$etd', '$emkl', '$alamat', '$npwp', '$ket', '$custom_number','$npe','$peb','$booking_numb','$fpod','$id_fpod')";
	$rq = $db->query($sql);
	
	if($rq){
		//mengambil nilai dari id requestnya
		$sql_id = "SELECT MAX(ID_BATALMUAT) as ID_BAT FROM TB_BATALMUAT_H";
		$res = $db->query($sql_id);
		$hasil = $res->fetchRow();
		$id_req = $hasil["ID_BAT"];
		
		//memasukkan ke history
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$id_req."','".$_SESSION["ID_USER"]."','BMAK_REQUEST','ENTRY HEADER BATALMUAT','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
		
		for($i=0; $i<$jum_detail; $i++) {
			$temp = explode("-", $jns_cont[$i]);
			$ukuran = $temp[0];
			$tipe = $temp[1];
			$status = $temp[2];
			$jns_cont[$i] = str_replace('FULL','FCL',$jns_cont[$i]);
			
			$sql = "declare v_msg varchar(20); begin proc_add_batal_muat_bg_dev( 
			".$counter[$i].",
			'".$user."',
			'".$_SERVER['REMOTE_ADDR']."',
			'".$vessel."',
			'".$voyage."',
			'".$voyage2."',
			'".$no_ukk."',
			'".$ukk[$i]."',
			'".$no_ukk_baru."',
			'".$shipping."',
			'".$etd."',
			'".$stack."',
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

			//print_r($sql);die;
			$db->query($sql);
		}
		
		
		echo "OK";
	} else {
		echo "NO";
	}
	
	
}
?>