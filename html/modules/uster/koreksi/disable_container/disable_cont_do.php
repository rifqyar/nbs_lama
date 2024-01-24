<?php 
	$db = getDB("storage");
	$no_cont = $_POST["NO_CONT"];
	$no_req = $_POST["NO_REQ"];
	$Q_cek = $db->query("SELECT COUNT(*) JUM FROM (SELECT NO_CONTAINER FROM GATE_OUT WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'
	UNION SELECT NO_CONTAINER FROM BORDER_GATE_OUT WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req')");
	$r_cek = $Q_cek->fetchRow();
	if($r_cek["JUM"] == 0){
		$db->query("UPDATE CONTAINER_DELIVERY SET CONTAINER_DELIVERY.AKTIF='T' WHERE CONTAINER_DELIVERY.NO_CONTAINER = '$no_cont'");
		echo "OK";
		exit();
	}
	else {
		echo "GATO";
		exit();
	}
	
	
	
	//exit();
?>