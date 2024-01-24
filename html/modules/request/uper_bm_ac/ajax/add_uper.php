<?php
//print_r($_POST);die;
require_lib('mzphplib.php');
include('auto_collection.php'); 
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
$kemasan = explode("&",$_POST['KEMASAN']);
$satuan = explode("&",$_POST['SATUAN']);
$via = explode("&",$_POST['VIA']);

$order_no = $_POST['heead']['ORDER_NO'];
$dorder = date('Y/m/d',strtotime($_POST['heead']['DORDER']));
$company_id = $_POST['heead']['COMPANY_ID'];
$deta = date('Y/m/d',strtotime($_POST['heead']['DETA']));
$detd = date('Y/m/d',strtotime($_POST['heead']['DETD']));
//$detb = date('Y/m/d',strtotime($_POST['heead']['DETB']));
//$kade = $_POST['heead']['KADE'];
//$agent_id = $_POST['heead']['AGENT_ID'];
$pbm_id = $_POST['heead']['PBM_ID'];
//$term = $_POST['heead']['TERM'];
$terminal = $_POST['heead']['TERMINAL'];
$orderby = $_POST['heead']['ORDERBY'];
$TRD_TYPE_ID = $_POST['heead']['TRD_TYPE_ID'];

//print_r($orderby);
//print_r(" ");
//print_r($TRD_TYPE_ID);
//die;

//OraDate
// print_r(OraDate($dorder));die;

//echo $no_ukk."-".$jum_detail."-".$_POST['PERDAGANGAN']."-"; die;
 
function f_date($date){
	return "to_date('".$date."','YYYY-MM-DD HH24:MI:SS')";
}

//print_r(f_date($dorder));die;


if($no_ukk=="")
	echo "NO";
else
{
	$db=getDB();

	if(isset($_POST['NO_UPER'])){
		//cek data uper sudah ada atau belum
		//if(strstr($_POST['PERDAGANGAN'],"I")) {
			$q_cek = "SELECT COUNT(1) AS JUM FROM UPER_H WHERE NO_UKK='".$no_ukk."' AND NO_UPER !='".$_POST['NO_UPER']."' AND (USER_LUNAS <> 'X' OR USER_LUNAS IS NULL)";
			$row = $db->query($q_cek)->fetchRow();
			if($row['JUM']>0) {
				echo "KO";
				die;
			}
		//}
	}
	
	$ocean_going = $intersuler = false;
	// cek perdagangan Ocean Going atau Intersuler

	if(isset($_POST['NO_UPER'])){
		//delete detail terlbih dahulu, lalu insert ulang
		$sql = "DELETE UPER_D WHERE NO_UPER='".$_POST['NO_UPER']."'";
		$db->query($sql);
		$no_uper2 = $_POST['NO_UPER'];
		
		if($TRD_TYPE_ID == 'I') {
			$val = 'IDR';
		}
		else
		{
			$val = 'USD';
		}
		
		$sql = "UPDATE UPER_H SET PBM_ID='".$pbm_id."' ,VALUTA='".$val."',FLAG_OI='".$TRD_TYPE_ID."' WHERE NO_UPER='".$_POST['NO_UPER']."'";
		$db->query($sql);
		
		//$intersuler = true;
	}else{
		$q_max = "SELECT NVL(MAX(SUBSTR(NO_UPER,13,6)),0)+1 AS NO FROM UPER_H WHERE SUBSTR(NO_UPER,5,4)=TO_CHAR(SYSDATE,'YYYY')";
		$row2 = $db->query($q_max)->fetchRow();
		$no_uper2 = '1001'.date("Ymd")."".str_pad($row2['NO'],6,0,STR_PAD_LEFT);
		
		if($TRD_TYPE_ID == "I") {
			
			//print_r("a");die;
			$sql = "INSERT INTO UPER_H (NO_UPER, NO_UKK, VALUTA, USER_ENTRY, FLAG_OI, LUNAS,ORDER_NO,DORDER,COMPANY_ID,DETA,DETD,DETB,KADE,AGENT_ID,PBM_ID,TERM,TERMINAL,TGL_ENTRY, ORDERBY) 
					VALUES ('".$no_uper2."','".$no_ukk."','IDR','".$user."','".$TRD_TYPE_ID."','T','".$order_no."',".f_date($dorder).",'".$company_id."',".f_date($deta).",".f_date($detd).",".f_date($detb).",'".$kade."','".$agent_id."','".$pbm_id."','".$term."','".$terminal."',".f_date($dorder).",'".$orderby."')";
			$db->query($sql);
			
			$intersuler = true;
		}
		else
		{
			//print_r("b");die;
			$sql = "INSERT INTO UPER_H (NO_UPER, NO_UKK, VALUTA, USER_ENTRY, FLAG_OI, LUNAS,ORDER_NO,DORDER,COMPANY_ID,DETA,DETD,DETB,KADE,AGENT_ID,PBM_ID,TERM,TERMINAL,TGL_ENTRY, ORDERBY) 
					VALUES ('".$no_uper2."','".$no_ukk."','USD','".$user."','".$TRD_TYPE_ID."','T','".$order_no."',".f_date($dorder).",'".$company_id."',".f_date($deta).",".f_date($detd).",".f_date($detb).",'".$kade."','".$agent_id."','".$pbm_id."','".$term."','".$terminal."',".f_date($dorder).",'".$orderby."')";
			$db->query($sql);
			
			$intersuler = false;
		}
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper2."','".$_SESSION["ID_USER"]."','UPER_H','ENTRY HEADER UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
		
	}

	
	//print_r($via);
	$urut1=$urut2=0;
	for($i=0; $i<$jum_detail; $i++) {
		
		$no_uper = $no_uper2;
		$urut2++;
		$no_urut = $urut2;
		
		if($TRD_TYPE_ID == 'I') {
			$val = 'IDR';
		}
		else
		{
			$val = 'USD';
		}
				
		
		if($bongkar[$i]=="")	$bongkar[$i]=0;
		if($muat[$i]=="")	$muat[$i]=0;

		if($via[$i] == 'TL'){
			$vias = 'Y';
		}elseif($via[$i] == 'LAP'){
			$vias = 'N';
		}else{
			$vias = $via[$i]; 
		}

		//echo "</br>VIA : ".$i." : ".$vias."</br>";

		$sql = "INSERT INTO UPER_D (NO_UPER, NO_URUT, SIZE_, TYPE_, STATUS, HEIGHT_CONT, HZ, BONGKAR, MUAT, VALUTA, FLAG_OI, KEGIATAN, SUBKEG, KEMASAN, SATUAN,TL_FLAG) VALUES ('".$no_uper."',".$no_urut.",".$size[$i].",'".$type[$i]."','".$status[$i]."','".$height[$i]."','".$bahaya[$i]."',".$bongkar[$i].",".$muat[$i].",'".$val."','".$TRD_TYPE_ID."','".$keg[$i]."','".$subkeg[$i]."','".$kemasan[$i]."','".$satuan[$i]."','".$vias."')";
		//print_r($sql);
		$db->query($sql);
		
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper."~".$no_urut."','".$_SESSION["ID_USER"]."','UPER_D','ENTRY DETAIL UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	}
	
	//if($intersuler) {
		// $sql = "DECLARE PNOUPER VARCHAR2(20); P_ERRMSG VARCHAR2(32767); BEGIN PNOUPER := '".$no_uper2."'; P_ERRMSG := NULL; ISWS_JAMBI.UPER_BM.PERHITUNGAN (PNOUPER, P_ERRMSG); COMMIT; END;";
		$sql = "DECLARE PNOUPER VARCHAR2(20); P_ERRMSG VARCHAR2(32767); BEGIN PNOUPER := '".$no_uper2."'; P_ERRMSG := NULL;
				UPER_BM.PERHITUNGAN (PNOUPER, P_ERRMSG); END;";
		$db->query($sql);
		$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper2."','".$_SESSION["ID_USER"]."','PACK: UPER_BM.PERHITUNGAN','PROCEDURE PERHITUNGAN','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
		$db->query($sql_h);
	//}

	//die;
	/*add kondisi untuk insert alat dermaga dan kebersihan*/
	

	$cek_ac = cek_cust($company_id);

	if($cek_ac){
		/*ac*/
		//callAc($no_uper2);
		//echo "1";
		echo "OK";
	}else{
		/*non ac*/
		//echo "0";
		echo "OK";
	}

	
	$sql = "UPDATE RBM_H SET FLAG_UPER=1 WHERE NO_UKK='".$no_ukk."'";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_ukk."','".$_SESSION["ID_USER"]."','RBM_H','UPDATE FLAG UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);

	//echo "OK";
}
?>