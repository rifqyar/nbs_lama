<?php 
	$id_nota=$_POST["ID_NOTA"];
	$no_req=$_POST["NO_REQ"];
	$user=$_SESSION["NAMA_PENGGUNA"];
	
	$db=getDB();
	$sql = "BEGIN PACK_NOTA_REEXPORT.RECALCULATE('$user','$id_nota','$no_req'); END;";
	$db->query($sql);
	
	$sql_h = "INSERT INTO HISTORY_ALL (ID, ID_REQUEST, ID_USER, NAMA_TABEL, KETERANGAN, IP_CLIENT, SQL) VALUES (GET_IDHIST,'".$no_req."','".$_SESSION["ID_USER"]."','BH_NOTA','RECALCULATE BEHANDLE','".$_SERVER['REMOTE_ADDR']."','".str_replace("'",'"',$sql)."')";
	$db->query($sql_h);
	
	echo "sukses";
?>