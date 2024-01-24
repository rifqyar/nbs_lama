<?php
	
	$db = getDB("storage");
	if(isset($_SESSION["LOGGED_STORAGE"]))
	{
		$no_cont = $_POST["NO_CONT"];
		$no_req = $_POST["NO_REQ"];

		$qdata = "SELECT * FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
		$rdata = $db->query($qdata);
		$rwdata = $rdata->fetchRow();
		$oldno_req = $rwdata["PERP_DARI"];

		$cek = $db->query("SELECT * FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		$rcek = $cek->fetchRow();
		if ($rcek["NO_CONTAINER"] != "NULL") {
			$db->query("DELETE CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");

			$db->query("DELETE HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");

			$db->query("UPDATE CONTAINER_STRIPPING SET AKTIF = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$oldno_req'");	
			echo "OK";
			die();
		}
		
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
				
	?>