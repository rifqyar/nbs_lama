<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$tipe = $_POST['TIPE'];
$emkl = $_POST['EMKL'];
$coa = $_POST['COA'];
$alamat = $_POST['ALAMAT'];
$npwp = $_POST['NPWP'];
$no_instruksi = $_POST['NO_INSTRUKSI'];
$ket = $_POST['KET'];
$jum_detail = $_POST['JUM_DETAIL'];
$counter = explode("&",$_POST['COUNTER']);
$no_cont = explode("&",$_POST['NO_CONT']);
$id_brg = explode("&",$_POST['ID_BRG']);
$size = explode("&",$_POST['SIZE']);
$type = explode("&",$_POST['TYPE']);
$stat = explode("&",$_POST['STAT']);
$hz = explode("&",$_POST['HZ']);
$vessel = explode("&",$_POST['VESSEL']);
$voyage = explode("&",$_POST['VOYAGE']);
$voyage_out = explode("&",$_POST['VOYAGE_OUT']);
$ukk = explode("&",$_POST['UKK']);
$lift_on = explode("&",$_POST['LIFT_ON']);
$lift_off = explode("&",$_POST['LIFT_OFF']);
$ex_mo = explode("&",$_POST['EX_MO']);

if($emkl=="")
	echo "NO";
else
{
	$ket_id = "EXMO";	
	$db=getDB();
	$q_max = "SELECT NVL(MAX(SUBSTR(ID_REQUEST,11,6)),0)+1 AS NO FROM EXMO_REQUEST WHERE SUBSTR(ID_REQUEST,5,4)=TO_CHAR(SYSDATE,'YYYY')";
	$row = $db->query($q_max)->fetchRow();
	$no_req = $ket_id.date("Ym").str_pad($row['NO'],6,0,STR_PAD_LEFT);
	
	$sql = "INSERT INTO EXMO_REQUEST (ID_REQUEST, NOMOR_INSTRUKSI, ID_USER, EMKL, ALAMAT, NPWP, KETERANGAN, OI, COA, VESSEL, VOYAGE_IN, VOYAGE_OUT) 
			VALUES ('".$no_req."','".$no_instruksi."','".$_SESSION["ID_USER"]."','".$emkl."','".$alamat."','".$npwp."','".$ket."','".$tipe."','".$coa."','$vessel','$voyage_in','$voyage_out')";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','EXMO_REQUEST','ENTRY HEADER EXTRA MOVEMENT','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	for($i=0; $i<$jum_detail; $i++) {
		$sql = "INSERT INTO EXMO_DETAIL_REQUEST (ID_REQUEST, ID_CONT, NO_CONTAINER, STATUS, HZ, VESSEL, VOYAGE, SIZE_, TYPE, NO_UKK, ID, LIFT_ON, LIFT_OFF, EX_MO) VALUES ('".$no_req."','".$id_brg[$i]."',UPPER('".$no_cont[$i]."'),'".$stat[$i]."','".$hz[$i]."','".$vessel[$i]."','".$voyage[$i]."','".$size[$i]."','".$type[$i]."','".$ukk[$i]."','".$counter[$i]."','".$lift_on[$i]."','".$lift_off[$i]."','".$ex_mo[$i]."')";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."~".$counter[$i]."','".$_SESSION["ID_USER"]."','EXMO_DETAIL_REQUEST','ENTRY DETAIL EXTRA MOVEMENT','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}

	echo "OK";
}
?>