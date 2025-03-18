<?php
 $db = getDB('storage');
 $no_req = $_POST["NO_REQ"];
 $tgl_dev = $_POST["tgl_dev"];
 $keterangan = $_POST["keterangan"];
 $peb = $_POST["peb"];
 $npe = $_POST["npe"];
 $no_cont = $_POST["NO_CONT"];
 $type_ = $_POST["TYPE_"];
 $komoditi = $_POST["KOMODITI"];
 $no_seal = $_POST["NO_SEAL"];
 $berat = $_POST["BERAT"];
 $via = $_POST["VIA"];
 $hz = $_POST["HZ"];
 $etd = $_POST["TGL_DELIVERY"];
 $id_user = $_SESSION["LOGGED_STORAGE"];
 //$query_cont = "UPDATE CONTAINER_DELIVERY SET NO_CONTAINER = '$no_cont', KOMODITI = '$komoditi', NO_SEAL = '$no_seal', BERAT = '$berat', VIA = '$via', HZ='$hz' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
 //echo $query_cont;die;
 
 $jumlah_cont = count($no_cont);
 
 // print_r($no_cont);die;
 
 $db->query("UPDATE REQUEST_DELIVERY SET TGL_REQUEST_DELIVERY = TO_DATE('$tgl_dev','dd-mm-rrrr'), KETERANGAN = '$keterangan'  WHERE NO_REQUEST = '$no_req'");
 for($i=0; $i<$jumlah_cont; $i++){
	$db->query("UPDATE CONTAINER_DELIVERY SET KOMODITI = '".$komoditi[$i]."', BERAT = '".$berat[$i]."', VIA = '".$via[$i]."', HZ='".$hz[$i]."', TGL_DELIVERY=TO_DATE('".$etd[$i]."','dd-mm-rrrr') WHERE NO_CONTAINER = '".$no_cont[$i]."' AND NO_REQUEST = '$no_req'");
 }

 $db->query("INSERT INTO CORRECTION_CONTAINER_LOG(NO_REQUEST, CREATED_BY, CREATED_DATE, NEW_RECORD) VALUES('$no_req','$id_user',SYSDATE,TO_DATE('$tgl_dev','dd-mm-rrrr'))");
 header('Location:'.HOME.APPID.'/view?no_req='.$no_req."&keg=DELIVERY");
?>