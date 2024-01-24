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
$tipe_oi = $_POST['TIPE_OI'];
$emkl_penumpukan = $_POST['EMKL_PENUMPUKAN'];
$etd_lama = $_POST['ETD_LAMA'];

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
	
	//mengambil data vessel code dan call sign
	$sql_vc = "SELECT VESSEL_CODE, CALL_SIGN FROM M_VSB_VOYAGE@DBINT_LINK WHERE VESSEL='$vessel' AND VOYAGE_IN='$voyage' AND VOYAGE_OUT='$voyage2'";
		$res_vc = $db->query($sql_vc);
		$hasil_vc = $res_vc->fetchRow();
		
	
	//memasukkan data ke tabel header
	$sql = "INSERT INTO REQ_BATALMUAT_H 
		    (KODE_PBM, 
		     JENIS, 
		     VESSEL, 
		     VOYAGE_IN,
                     VOYAGE_OUT,
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
		     ID_FPOD, 
                     D_I, 
                     EMKL_PENUMPUKAN,VESSEL_CODE,CALL_SIGN) 
		VALUES 
                    ('$pbm','$tipe_req', '$vessel', '$voyage','$voyage2','$shipping', '$user', 'N', '$etd', '$emkl', '$alamat', '$npwp', '$ket', '$custom_number','$npe','$peb','$booking_numb','$fpod','$id_fpod', '$tipe_oi', '$emkl_penumpukan','".$hasil_vc['VESSEL_CODE']."','".$hasil_vc['CALL_SIGN']."')";
	$rq = $db->query($sql);
	//echo $sql; die();
	if($rq){
		//mengambil nilai maximum dari id requestnya
		$sql_id = "SELECT ID_REQ as ID_BAT
					  FROM (  SELECT ID_REQ
								FROM REQ_BATALMUAT_H
							ORDER BY TGL_REQ DESC)
					 WHERE ROWNUM = 1";
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
                        '".$etd_lama."',    
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