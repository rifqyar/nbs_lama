<?php
	$db=  getDB();
	$kd_pelanggan=$_POST['kd_pelanggan'];
	$remark=$_POST['remark'];
	
	$user=$_POST['user'];
	
	$query="SELECT COUNT(*) AS TOTAL FROM MST_PELANGGAN_PPN WHERE KD_PELANGGAN = '$kd_pelanggan'";
	
	$res = $db->query($query)->fetchRow();
	if($res["TOTAL"] > 0){
		echo "NOT OK||Kode pelanggan sudah terdaftar";
	return;
	}else{
		$query="INSERT INTO MST_PELANGGAN_PPN(KD_PELANGGAN,CREATED_BY,CREATED_DATE,REMARK)
		VALUES
		('$kd_pelanggan','$user',SYSDATE,'$remark')";
		$db->query($query);
		echo "OK||$kd_pelanggan berhasil tersimpan";
	return;
	}	
?>
