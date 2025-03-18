<?php
 $db = getDB('storage');
 $no_req = $_POST["NO_REQ"];
 $tgl_dev = $_POST["tgl_dev"];
 $keterangan = $_POST["keterangan"];
 $no_cont = $_POST["NO_CONT"]; 
 $no_cont_old = $_POST["NO_CONT_OLD"];
 $type_ = $_POST["TYPE_"];
 $komoditi = $_POST["KOMODITI"];
 $no_seal = $_POST["NO_SEAL"];
 $berat = $_POST["BERAT"];
 $via = $_POST["VIA"];
 $hz = $_POST["HZ"];
 //$query_cont = "UPDATE CONTAINER_DELIVERY SET NO_CONTAINER = '$no_cont', KOMODITI = '$komoditi', NO_SEAL = '$no_seal', BERAT = '$berat', VIA = '$via', HZ='$hz' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
 //echo $query_cont;die;
 $db->query("UPDATE REQUEST_DELIVERY SET TGL_REQUEST_DELIVERY = TO_DATE('$tgl_dev','yyyy-mm-dd'), KETERANGAN = '$keterangan' WHERE NO_REQUEST = '$no_req'");
  for($i=0; $i<count($no_cont); $i++){
	$db->query("UPDATE CONTAINER_DELIVERY SET NO_CONTAINER = '".$no_cont[$i]."', KOMODITI = '".$komoditi[$i]."', NO_SEAL = '".$no_seal[$i]."', BERAT = '".$berat[$i]."', VIA = '".$via[$i]."', HZ='".$hz[$i]."' WHERE NO_CONTAINER = '".$no_cont_old[$i]."' AND NO_REQUEST = '$no_req'");
 }
 header('Location:'.HOME.APPID.'/view?no_req='.$no_req."&keg=DELIVERY");
?>