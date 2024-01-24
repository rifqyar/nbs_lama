<?php
$no_req = $_POST['NO_RENAME'];
$ei		= $_POST['EI'];
$no_ex_cont = $_POST['NO_EX_CONT'];
$no_cont = $_POST['NO_CONT'];
$ket = $_POST['REMARKS'];
$biaya = $_POST['BIAYA'];
$custname= $_POST['CUSTNAME'];
$custadd= $_POST['CUSTADDR'];
$custid= $_POST['CUSTID'];
$npwp=$_POST['NPWP'];

if($no_cont=="")
	echo "NO";
else
{
	$db=getDB();
	$sql = "UPDATE RENAME_CONTAINER SET NO_CONTAINER = '$no_cont', REMARKS= '$ket', BIAYA = '$biaya', PBM='$custname', COA='$custid', ALAMAT='$custadd', NPWP='$npwp' WHERE NO_RENAME = TRIM('$no_req') AND NO_EX_CONTAINER = TRIM('$no_ex_cont')";
	$db->query($sql);
	
	if($biaya == 'N'){
		$sql = "UPDATE ISWS_LIST_CONTAINER SET NO_CONTAINER = '$no_cont' WHERE NO_CONTAINER = '$no_ex_cont'";
		$db->query($sql);
		
		$sql = "UPDATE RENAME_CONTAINER SET STATUS = 'F' WHERE NO_EX_CONTAINER = '$no_ex_cont' AND NO_RENAME = '$no_req'";
		$db->query($sql);
	}
	
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','RENAME_CONTAINER','UPDATE RENAME_CONTAINER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);

	echo "OK";
}
?>