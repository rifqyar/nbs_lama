<?php
 $db = getDB('storage');
 $no_req = $_POST["NO_REQ"];
 $keterangan = $_POST["KETERANGAN"];
 $no_cont = $_POST["NO_CONT"];
 //$type_ = $_POST["TYPE_"];
 $no_cont_old = $_POST["NO_CONT_OLD"];
 $komoditi = $_POST["KOMODITI"];
 $hz = $_POST["HZ"];
 
 $db->query("UPDATE REQUEST_RECEIVING SET TGL_SPPB = TO_DATE('$tgl_sppb','yyyy-mm-dd'), KETERANGAN = '$keterangan' WHERE NO_REQUEST = '$no_req'");
 for($i=0; $i<count($no_cont); $i++){	
	$db->query("UPDATE CONTAINER_RECEIVING SET NO_CONTAINER = '".$no_cont[$i]."', KOMODITI = '".$komoditi[$i]."', HZ='".$hz[$i]."' WHERE NO_CONTAINER = '".$no_cont_old[$i]."' AND NO_REQUEST = '$no_req'");
 }
 header('Location:'.HOME.APPID.'/view?no_req='.$no_req."&keg=RECEIVING");
?>