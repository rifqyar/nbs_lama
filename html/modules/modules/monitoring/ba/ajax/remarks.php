<?php
$id_user = $_SESSION["ID_USER"];
$remarks = $_POST['REMARK_BA'];
$id_ba = $_POST['ID_BA'];

$datex = date('Y-m-d H:i:s');
$db=getDb();

$cek_ba = "SELECT NO_REF_HUMAS,
				  ACCOUNT_KEU,
				  SIMOP,
				  TERMINAL
		   FROM BERITA_ACARA 
			WHERE ID = '$id_ba'";
$result15 = $db->query($cek_ba);
$ba_cek = $result15->fetchRow();
$ref_hms = $ba_cek['NO_REF_HUMAS'];
$ac_keu = $ba_cek['ACCOUNT_KEU'];
$simop = $ba_cek['SIMOP'];
$terminal = $ba_cek['TERMINAL'];

$db->query("update BERITA_ACARA set REMARKS = '$remarks' where ID = '$id_ba'");
$db->query("INSERT INTO HIST_BERITA_ACARA 
			(NO_REF_HUMAS,ACCOUNT_KEU,STATUS,SIMOP,TERMINAL,USER_UPDATE) 
			VALUES 
			('$ref_hms','$ac_keu','REMARKS','$simop','$terminal','$id_user')");
			
echo "OK";

?>