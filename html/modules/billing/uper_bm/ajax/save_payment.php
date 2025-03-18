<?php
$no_uper=$_POST['NO_UPER'];
$no_ukk=$_POST['NO_UKK'];
$via=$_POST['VIA'];
//$payment_date=$_POST['DATE'];
$user=$_SESSION['PENGGUNA_ID'];

// echo "'$no_uper','$no_ukk','$via','$user'"; die;
/*$sql="UPDATE UPER_H SET LUNAS='Y', TGL_LUNAS=TO_DATE('".$payment_date."','DD-MM-YYYY'), USER_LUNAS='".$user."', PAYMENT_VIA='".$via."' WHERE NO_UPER='".$no_uper."'";
$db->query($sql);
$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper."','".$_SESSION["ID_USER"]."','UPER_H','UPDATE PELUNASAN UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
$db->query($sql_h);

$sql = "UPDATE RBM_H SET FLAG_UPER=2 WHERE NO_UKK='".$no_ukk."'";
$db->query($sql);
$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_ukk."','".$_SESSION["ID_USER"]."','RBM_H','UPDATE FLAG UPER','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
$db->query($sql_h);
echo "sukses";*/

$db=getDb();
$db->startTransaction();
$param_payment= array(
					 "NO_UPER"=>$no_uper,
					 "VIA"=>$via,
					 "USER"=>$user,
					 "OUT"=>'',
					 "OUT_MSG"=>''
					);
//$query="declare begin pack_ar.proc_receipt2('$no_uper','$via','$user','$out','$out_msg'); end;";
//echo $query;die;
$query="declare begin pack_ar.proc_receipt2(:NO_UPER,:VIA,:USER,:OUT,:OUT_MSG); end;";
$db->query($query,$param_payment);
if ($db->endTransaction()) {
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_uper."','".$_SESSION["ID_USER"]."','UPER_H','TRANSFER PAYMENT UPER BM','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	if($param_payment["OUT"]=='S')
		echo "sukses";
	else
		echo "failed - ".$param_payment["OUT"]." - ".$param_payment["OUT_MSG"];
}
?>