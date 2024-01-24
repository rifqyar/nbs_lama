<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$no_ukk = $_POST['NO_UKK'];
$jum_detail = $_POST['JUM_DETAIL'];
$size = explode("&",$_POST['SIZE']);
$type = explode("&",$_POST['TYPE']);
$status = explode("&",$_POST['STATUS']);
$height = explode("&",$_POST['HEIGHT']);
$bahaya = explode("&",$_POST['BAHAYA']);
$bongkar = explode("&",$_POST['BONGKAR']);
$muat = explode("&",$_POST['MUAT']);
$perdagangan = explode("&",$_POST['PERDAGANGAN']);
$keg = explode("&",$_POST['KEG']);
$subkeg = explode("&",$_POST['SUBKEG']);

//echo $no_ukk."-".$jum_detail."-".$_POST['PERDAGANGAN']."-"; die;
if($no_ukk=="")
	echo "NO";
else
{
	$db=getDB();
	
	//cek data uper sudah ada atau belum
	if(strstr($_POST['PERDAGANGAN'],"O")) {
		$q_cek = "SELECT COUNT(1) AS JUM FROM UPER_H WHERE NO_UKK='".$no_ukk."' AND VALUTA='USD'";
		$row = $db->query($q_cek)->fetchRow();
		if($row['JUM']>0) {
			echo "KO";
			die;
		}
	}
	if(strstr($_POST['PERDAGANGAN'],"I")) {
		$q_cek = "SELECT COUNT(1) AS JUM FROM UPER_H WHERE NO_UKK='".$no_ukk."' AND VALUTA='IDR'";
		$row = $db->query($q_cek)->fetchRow();
		if($row['JUM']>0) {
			echo "KO";
			die;
		}
	}
	
	$ocean_going = $intersuler = false;
	// cek perdagangan Ocean Going atau Intersuler
	if(strstr($_POST['PERDAGANGAN'],"O")) {
		$q_max = "SELECT NVL(MAX(SUBSTR(NO_UPER,10,6)),0)+1 AS NO FROM UPER_H WHERE SUBSTR(NO_UPER,1,4)=TO_CHAR(SYSDATE,'YYYY')";
		$row = $db->query($q_max)->fetchRow();
		$no_uper1 = date("Ymd")."-".str_pad($row['NO'],6,0,STR_PAD_LEFT);
		$sql = "INSERT INTO UPER_H (NO_UPER, NO_UKK, VALUTA, USER_ENTRY, FLAG_OI) VALUES ('".$no_uper1."','".$no_ukk."','USD','".$user."','O')";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper1."','".$_SESSION["ID_USER"]."','UPER_H','ENTRY HEADER UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
		$ocean_going = true;
	}	
	if(strstr($_POST['PERDAGANGAN'],"I")) {
		$q_max = "SELECT NVL(MAX(SUBSTR(NO_UPER,10,6)),0)+1 AS NO FROM UPER_H WHERE SUBSTR(NO_UPER,1,4)=TO_CHAR(SYSDATE,'YYYY')";
		$row2 = $db->query($q_max)->fetchRow();
		$no_uper2 = date("Ymd")."-".str_pad($row2['NO'],6,0,STR_PAD_LEFT);
		$sql = "INSERT INTO UPER_H (NO_UPER, NO_UKK, VALUTA, USER_ENTRY, FLAG_OI) VALUES ('".$no_uper2."','".$no_ukk."','IDR','".$user."','I')";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper2."','".$_SESSION["ID_USER"]."','UPER_H','ENTRY HEADER UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
		$intersuler = true;
	}
	
	$urut1=$urut2=0;
	for($i=0; $i<$jum_detail; $i++) {
		if($perdagangan[$i] == 'O') {			
			$no_uper = $no_uper1;
			$urut1++;
			$no_urut = $urut1;
			$val = 'USD';
		}
		else if($perdagangan[$i] == 'I') {
			$no_uper = $no_uper2;
			$urut2++;
			$no_urut = $urut2;
			$val = 'IDR';
		}
		if($bongkar[$i]=="")	$bongkar[$i]=0;
		if($muat[$i]=="")	$muat[$i]=0;
		$sql = "INSERT INTO UPER_D (NO_UPER, NO_URUT, SIZE_, TYPE_, STATUS, HEIGHT_CONT, HZ, BONGKAR, MUAT, VALUTA, FLAG_OI, KEGIATAN, SUBKEG) VALUES ('".$no_uper."',".$no_urut.",".$size[$i].",'".$type[$i]."','".$status[$i]."','".$height[$i]."','".$bahaya[$i]."',".$bongkar[$i].",".$muat[$i].",'".$val."','".$perdagangan[$i]."','".$keg[$i]."','".$subkeg[$i]."')";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper."~".$no_urut."','".$_SESSION["ID_USER"]."','UPER_D','ENTRY DETAIL UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}
	
	if($ocean_going) {
		$sql = "DECLARE PNOUPER VARCHAR2(20); P_ERRMSG VARCHAR2(32767); BEGIN PNOUPER := '".$no_uper1."'; P_ERRMSG := NULL; ISWS_JAMBI.UPER_BM.PERHITUNGAN (PNOUPER, P_ERRMSG); COMMIT; END;";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper1."','".$_SESSION["ID_USER"]."','PACK: UPER_BM.PERHITUNGAN','PROCEDURE PERHITUNGAN','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}	
	if($intersuler) {
		$sql = "DECLARE PNOUPER VARCHAR2(20); P_ERRMSG VARCHAR2(32767); BEGIN PNOUPER := '".$no_uper2."'; P_ERRMSG := NULL; ISWS_JAMBI.UPER_BM.PERHITUNGAN (PNOUPER, P_ERRMSG); COMMIT; END;";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper2."','".$_SESSION["ID_USER"]."','PACK: UPER_BM.PERHITUNGAN','PROCEDURE PERHITUNGAN','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}
	
	$sql = "UPDATE RBM_H SET FLAG_UPER=1 WHERE NO_UKK='".$no_ukk."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_ukk."','".$_SESSION["ID_USER"]."','RBM_H','UPDATE FLAG UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);

	echo "OK";
}
?>