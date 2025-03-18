<?php
 $db = getDB('storage');
 $no_req = $_POST["NO_REQ_REL"];
 $keterangan = $_POST["KETERANGAN"];
 $no_cont = $_POST["NO_CONT"];
 $no_cont_old = $_POST["NO_CONT_OLD"];
 
 $db->query("UPDATE REQUEST_RELOKASI SET KETERANGAN = '$keterangan' WHERE NO_REQUEST = '$no_req'");
 
 for($i=0; $i<count($no_cont); $i++){	
	$db->query("UPDATE CONTAINER_RELOKASI SET NO_CONTAINER = '".$no_cont[$i]."' WHERE NO_CONTAINER = '".$no_cont_old[$i]."' AND NO_REQUEST = '$no_req'");
 }
 header('Location:'.HOME.APPID.'/view?no_req='.$no_req."&keg=RELOKASI");
?>