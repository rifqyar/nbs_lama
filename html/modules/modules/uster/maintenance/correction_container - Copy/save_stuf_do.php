<?php
 $db = getDB('storage');
 $no_req = $_POST["NO_REQ"];
 $keterangan = $_POST["KETERANGAN"];
 $no_cont = $_POST["NO_CONT"];
 $no_cont_old = $_POST["NO_CONT_OLD"];
 //$type_ = $_POST["TYPE_"];
 $tgl_sppb = $_POST["TGL_SPPB"];
 $keterangan = $_POST["KETERANGAN"];
 $no_doc = $_POST["NO_DOC"];
 $no_jpb = $_POST["NO_JPB"];
 $bprp = $_POST["BPRP"];
 $no_npe = $_POST["NO_NPE"];
 $no_peb = $_POST["NO_PEB"];
 $no_do = $_POST["NO_DO"];
 $no_bl = $_POST["NO_BL"];
 $no_sppb = $_POST["NO_SPPB"];
 $hz = $_POST["HZ"];
 $komoditi = $_POST["KOMODITI"];
 
 $db->query("UPDATE REQUEST_STUFFING SET NO_DO = '$no_do', NO_BL = '$no_bl', NO_NPE = '$no_npe', NO_PEB = ' $no_peb', BPRP = '$bprp', NO_DOKUMEN = '$no_doc',
 KETERANGAN = '$keterangan', NO_SPPB = '$no_sppb', NO_JPB = '$no_jpb', TGL_SPPB = TO_DATE('$tgl_sppb','yyyy-mm-dd') WHERE NO_REQUEST = '$no_req'");
 $db->query("UPDATE PLAN_REQUEST_STUFFING SET NO_DO = '$no_do', NO_BL = '$no_bl', NO_NPE = '$no_npe', NO_PEB = ' $no_peb', BPRP = '$bprp', NO_DOKUMEN = '$no_doc',
 KETERANGAN = '$keterangan', NO_SPPB = '$no_sppb', NO_JPB = '$no_jpb', TGL_SPPB = TO_DATE('$tgl_sppb','yyyy-mm-dd') WHERE NO_REQUEST = REPLACE('$no_req','S','P')");
 
 for($i=0; $i<count($no_cont); $i++){	
	$db->query("UPDATE CONTAINER_STUFFING SET NO_CONTAINER = '".$no_cont[$i]."', COMMODITY = '".$komoditi[$i]."', HZ='".$hz[$i]."' WHERE NO_CONTAINER = '".$no_cont_old[$i]."' AND NO_REQUEST = '$no_req'");
	$db->query("UPDATE PLAN_CONTAINER_STUFFING SET NO_CONTAINER = '".$no_cont[$i]."', COMMODITY = '".$komoditi[$i]."', HZ='".$hz[$i]."' WHERE NO_CONTAINER = '".$no_cont_old[$i]."' AND NO_REQUEST = REPLACE('$no_req', 'S', 'P')");
 }
 header('Location:'.HOME.APPID.'/view?no_req='.$no_req."&keg=STUFFING");
?>