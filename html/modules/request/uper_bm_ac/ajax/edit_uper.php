<?php
require_lib('mzphplib.php');
include('auto_collection.php');
$no_uper = $_POST["NO_UPER"];
$no_ukk = $_POST['NO_UKK'];
$jum_detail = $_POST['JUM_DETAIL'];
$counter = explode("&",$_POST['COUNTER']);
$size = explode("&",$_POST['SIZE']);
$type = explode("&",$_POST['TYPE']);
$status = explode("&",$_POST['STATUS']);
$height = explode("&",$_POST['HEIGHT']);
$bahaya = explode("&",$_POST['BAHAYA']);
$bongkar = explode("&",$_POST['BONGKAR']);
$muat = explode("&",$_POST['MUAT']);
$perdagangan = $_POST['PERDAGANGAN'];
$keg = explode("&",$_POST['KEG']);
$subkeg = explode("&",$_POST['SUBKEG']);
$kemasan = explode("&",$_POST['KEMASAN']);
$satuan = explode("&",$_POST['SATUAN']);

$order_no = $_POST['heead']['ORDER_NO'];
$dorder = date('Y/m/d',strtotime($_POST['heead']['DORDER']));
$company_id = $_POST['heead']['COMPANY_ID'];
$deta = date('Y/m/d',strtotime($_POST['heead']['DETA']));
$detd = date('Y/m/d',strtotime($_POST['heead']['DETD']));
$detb = date('Y/m/d',strtotime($_POST['heead']['DETB']));
$kade = $_POST['heead']['KADE'];
$agent_id = $_POST['heead']['AGENT_ID'];
$pbm_id = $_POST['heead']['PBM_ID'];
$term = $_POST['heead']['TERM'];
$terminal = $_POST['heead']['TERMINAL'];
$orderby = $_POST['heead']['ORDERBY'];


//callAc($no_uper);die;

// echo $no_ukk."-".$jum_detail."-".$_POST['PERDAGANGAN']."-"; die;
if($no_ukk=="")
	echo "NO";
else
{
	$db=getDB();
	//delete detail terlbih dahulu, lalu insert ulang
	$sql = "DELETE UPER_D WHERE NO_UPER='".$no_uper."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper."','".$_SESSION["ID_USER"]."','UPER_D','UPDATE DETAIL UPER (DELETE THEN RE-INSERT)','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	if($perdagangan == 'O')	$val = 'USD';
	else if($perdagangan == 'I')	$val = 'IDR';
	for($i=0; $i<$jum_detail; $i++) {
		if($bongkar[$i]=="")	$bongkar[$i]=0;
		if($muat[$i]=="")	$muat[$i]=0;
		$sql = "INSERT INTO UPER_D (NO_UPER, NO_URUT, SIZE_, TYPE_, STATUS, HEIGHT_CONT, HZ, BONGKAR, MUAT, VALUTA, FLAG_OI, KEGIATAN, SUBKEG, KEMASAN, SATUAN) VALUES ('".$no_uper."',".$counter[$i].",".$size[$i].",'".$type[$i]."','".$status[$i]."','".$height[$i]."','".$bahaya[$i]."',".$bongkar[$i].",".$muat[$i].",'".$val."','".$perdagangan."','".$keg[$i]."','".$subkeg[$i]."','".$kemasan[$i]."','".$satuan[$i]."')";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper."~".$counter[$i]."','".$_SESSION["ID_USER"]."','UPER_D','UPDATE DETAIL UPER (DELETE THEN RE-INSERT)','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}
	
	$sql = "DECLARE PNOUPER VARCHAR2(20); P_ERRMSG VARCHAR2(32767); BEGIN PNOUPER := '".$no_uper."'; P_ERRMSG := NULL; UPER_BM.PERHITUNGAN (PNOUPER, P_ERRMSG); COMMIT; END;";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper."','".$_SESSION["ID_USER"]."','PACK: UPER_BM.PERHITUNGAN','PROCEDURE PERHITUNGAN (UPDATE)','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);

	callAc($no_uper);

	echo "OK";
}
?>