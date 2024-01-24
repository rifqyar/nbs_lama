<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$tipe = $_POST['TIPE'];
$vessel = $_POST['VESSEL'];
$voyage = $_POST['VOYAGE'];
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

if($no_ukk=="")
	echo "NO";
else
{
	// if($tipe=="Export")
		// $ket_id = "BHEX";
	// else
		// $ket_id = "BHIM";
	$ket_id = "BHD";
	
	$db=getDB();	
	// $q_max = "SELECT NVL(MAX(SUBSTR(ID_REQUEST,11,6)),0)+1 AS NO FROM BH_REQUEST WHERE SUBSTR(ID_REQUEST,5,4)=TO_CHAR(SYSDATE,'YYYY') AND SUBSTR(ID_REQUEST,1,4)='".$ket_id."'";
	$q_max = "SELECT NVL(MAX(SUBSTR(ID_REQUEST,10,6)),0)+1 AS NO FROM BH_REQUEST WHERE SUBSTR(ID_REQUEST,4,4)=TO_CHAR(SYSDATE,'YYYY')";
	$row = $db->query($q_max)->fetchRow();
	$no_req = $ket_id.date("Ym").str_pad($row['NO'],6,0,STR_PAD_LEFT);
	
	$sql = "INSERT INTO BH_REQUEST (ID_REQUEST, TGL_REQUEST, NOMOR_INSTRUKSI, ID_USER, VOYAGE, VESSEL, EMKL, COA, ALAMAT_EMKL, NPWP, TIPE_REQ, SHIPPING_LINE, KET, NO_UKK) VALUES ('".$no_req."',SYSDATE,'".$no_bc."','".$_SESSION["ID_USER"]."','".$voyage."','".$vessel."','".$emkl."','".$coa."','".$alamat."','".$npwp."','".$tipe."','".$shipping."','".$ket."','".$no_ukk."')";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','BH_REQUEST','ENTRY HEADER BEHANDLE','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	for($i=0; $i<$jum_detail; $i++) {
		$sql = "INSERT INTO BH_DETAIL_REQUEST (ID, ID_REQUEST, ID_BARANG, NO_CONTAINER, HAZZARD, JNS_CONT, NO_UKK) VALUES ('".$counter[$i]."','".$no_req."','".$id_brg[$i]."',UPPER('".$no_cont[$i]."'),'".$hz[$i]."','".$jns_cont[$i]."','".$no_ukk."')";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."~".$counter[$i]."','".$_SESSION["ID_USER"]."','BH_DETAIL_REQUEST','ENTRY DETAIL BEHANDLE','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}

	echo "OK";
}
?>