<?php
$ID_USER = $_SESSION["LOGGED_STORAGE"];
if($ID_USER == NULL){
	echo "LOGIN";
	exit();
}
$db = getDB("storage");
$no_cont_old = strtoupper($_POST['NO_CONT_OLD']);
$no_cont_new = strtoupper($_POST['NO_CONT_NEW']);
$size_new = $_POST["SIZE_NEW"];
$type_new = $_POST["TYPE_NEW"];

$q_cek_new = "SELECT NO_CONTAINER, NO_BOOKING, COUNTER, LOCATION FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont_new'";
$rcek_new = $db->query($q_cek_new);
$rc_new = $rcek_new->fetchRow();
if($rc_new["NO_CONTAINER"] != NULL){
	echo "Z";
	exit();
}

$q_cek = "SELECT NO_CONTAINER, NO_BOOKING, COUNTER, LOCATION FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont_old'";
$rcek = $db->query($q_cek);
$rc = $rcek->fetchRow();
$no_cont = $rc["NO_CONTAINER"];
$no_booking = $rc["NO_BOOKING"];
$counter = $rc["COUNTER"];
$location = $rc["LOCATION"];
if($no_cont != NULL){
	$gethistory = "SELECT NO_CONTAINER,NO_REQUEST,KEGIATAN FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont_old'";
	$rhist = $db->query($gethistory);
	$rh = $rhist->getAll();
	foreach($rh as $rha){
		$no_req = $rha["NO_REQUEST"];
		$keg 	= $rha["KEGIATAN"];
		if($keg == "REQUEST RECEIVING"){
			$qupdrec = "UPDATE CONTAINER_RECEIVING SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdrec);
		}
		else if($keg == "GATE IN"){
			$qupdgati = "UPDATE GATE_IN SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdgati);
		}
		else if($keg == "GATE OUT"){
			$qupdgato = "UPDATE GATE_OUT SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdgato);
		}
		else if($keg == "BORDER GATE IN" || $keg == "BORDER GATE OUT"){
			$qupdgatib = "UPDATE BORDER_GATE_IN SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdgatib);
			$qupdgatob = "UPDATE BORDER_GATE_OUT SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdgatob);
		}
		else if($keg == "PERPANJANGAN STRIPPING" || $keg == "REQUEST STRIPPING"){
			$qupdstrip = "UPDATE CONTAINER_STRIPPING SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdstrip);
		}
		else if($keg == "REQUEST STUFFING" || $keg == "PERPANJANGAN STUFFING"){
			$qupdstuf = "UPDATE CONTAINER_STUFFING SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdstuf);
		}
		else if($keg == "PLAN REQUEST STRIPPING"){
			$qupdplst = "UPDATE PLAN_CONTAINER_STRIPPING SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdplst);
		}
		else if($keg == "PLAN REQUEST STUFFING"){
			$qupdplstf = "UPDATE PLAN_CONTAINER_STUFFING SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdplstf);
		}
		else if($keg == "REQUEST BATALMUAT"){
			$qupdbm = "UPDATE CONTAINER_BATAL_MUAT SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdbm);
		}
		else if($keg == "REQUEST DELIVERY" || $keg == "PERP DELIVERY"){
			$qupdbm = "UPDATE CONTAINER_DELIVERY SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont_old'";
			$db->query($qupdbm);
		}
	}
	
	$get_history_ = "SELECT NO_REQUEST, NO_CONTAINER, NO_BOOKING FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont_old'";
	$exhist		= $db->query($get_history_);
	$rxhist 	= $exhist->getAll();
	foreach($rxhist as $rxh){
		$noreq_hist = $rxh["NO_REQUEST"];
		$nobook_hist = $rxh["NO_BOOKING"];
		
		$update_history = "UPDATE HISTORY_CONTAINER SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST = '$noreq_hist' AND NO_BOOKING = '$nobook_hist' AND NO_CONTAINER = '$no_cont_old'";
		$db->query($update_history);
	}
	
		$update_master = "INSERT INTO MASTER_CONTAINER (NO_CONTAINER, SIZE_, TYPE_, LOCATION, NO_BOOKING, COUNTER) VALUES ('$no_cont_new', '$size_new' , '$type_new', '$location', '$no_booking', '$counter')";
		$db->query($update_master);
		$update_old = "UPDATE MASTER_CONTAINER SET MLO = '-' WHERE NO_CONTAINER = '$no_cont_old'";
		$db->query($update_old);
		
		//update placement
		$cek_placement = "SELECT NO_CONTAINER, NO_REQUEST_RECEIVING FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont_old'";
		$r_cek_placement = $db->query($cek_placement);
		$rwcek = $r_cek_placement->fetchRow();
		$contplace = $rwcek["NO_REQUEST_RECEIVING"];
		$db->query("UPDATE PLACEMENT SET NO_CONTAINER = '$no_cont_new' WHERE NO_REQUEST_RECEIVING = '$contplace' AND NO_CONTAINER = '$no_cont_old'");
		
		//update history placement
		$cek_hplace = "SELECT NO_REQUEST FROM HISTORY_PLACEMENT WHERE NO_CONTAINER = '$no_cont_old'";
		$rhplace = $db->query($cek_hplace);
		$rhwplace = $rhplace->getAll();
		foreach($rhwplace as $rhw){
			$reqh = $rhw["NO_REQUEST"];
			$updhsplace = "UPDATE HISTORY_PLACEMENT SET NO_CONTAINER = '$no_cont_new' WHERE NO_CONTAINER = '$no_cont_old'";
			$db->query("");
		}
	
	echo "Y";
	exit();
	
}
else {
	echo "X";
	exit();
}

?> 