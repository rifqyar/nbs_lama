<?php
$userid = $_SESSION["ID_USER"];
$no_req = $_POST["ID_REQUEST"];

if($no_req=="")
	echo "NO";
else
{	
	$db=getDB();
	$sql = "DECLARE P_REQID VARCHAR2(20); P_USERID NUMBER; BEGIN P_REQID := '".$no_req."'; P_USERID := ".$userid."; ISWS_JAMBI.PACK_TRANSFER.TRANSFER_EXMO (P_REQID, P_USERID); COMMIT; END;";
	//echo $sql."<br>";
	$db->query($sql);
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$userid."','EXMO_REQUEST','TRANSFER FINAL REQUEST','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	//echo $sql_h."<br>";die;
	$db->query($sql_h);

	echo "OK";
}
?>