<?php
 $db = getDB('storage');
 $no_req = $_POST["NO_REQ"];
 $keterangan = $_POST["KETERANGAN"];
 $no_cont = $_POST["NO_CONT"];
 $no_cont_old = $_POST["NO_CONT_OLD"];
 $type_s = $_POST["TYPE_S"];
 //$type_ = $_POST["TYPE_"];
 $tgl_sppb = $_POST["TGL_SPPB"];
 $keterangan = $_POST["KETERANGAN"];
 $no_do = $_POST["NO_DO"];
 $no_bl = $_POST["NO_BL"];
 $no_sppb = $_POST["NO_SPPB"];
 $after = $_POST["AFTER"];
 $hz = $_POST["HZ"];
 
 $db->query("UPDATE REQUEST_STRIPPING SET NO_DO = '$no_do', NO_BL = '$no_bl', TYPE_STRIPPING = '$type_s', KETERANGAN = '$keterangan' WHERE NO_REQUEST = '$no_req'");
 $db->query("UPDATE PLAN_REQUEST_STRIPPING SET NO_DO = '$no_do', NO_BL = '$no_bl', NO_SPPB = '$no_sppb', TGL_SPPB = TO_DATE('$tgl_sppb','yyyy-mm-dd'), TYPE_STRIPPING = '$type_s', KETERANGAN = '$keterangan' WHERE NO_REQUEST =  REPLACE('$no_req', 'S', 'P')");
 
 for($i=0; $i<count($no_cont); $i++){	
	$db->query("UPDATE CONTAINER_STRIPPING SET NO_CONTAINER = '".$no_cont[$i]."', AFTER_STRIP = '".$after[$i]."', HZ='".$hz[$i]."' WHERE NO_CONTAINER = '".$no_cont_old[$i]."' AND NO_REQUEST = '$no_req'");
	$db->query("UPDATE PLAN_CONTAINER_STRIPPING SET NO_CONTAINER = '".$no_cont[$i]."', AFTER_STRIP = '".$after[$i]."', HZ='".$hz[$i]."' WHERE NO_CONTAINER = '".$no_cont_old[$i]."' AND NO_REQUEST = REPLACE('$no_req', 'S', 'P')");
 }
 header('Location:'.HOME.APPID.'/view?no_req='.$no_req."&keg=STRIPPING");
?>