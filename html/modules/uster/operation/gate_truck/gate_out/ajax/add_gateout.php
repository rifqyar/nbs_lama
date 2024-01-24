<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_kartu	= $_POST["NO_KARTU"]; 
$no_pol		= $_POST["NO_POL"]; 
$id_yard	= $_SESSION["IDYARD_STORAGE"];
$id_user	= $_SESSION["LOGGED_STORAGE"];

			//insert di gate in
			$query_insert	= "INSERT INTO GATE_OUT_TRUCK(NO_CONTAINER, NO_KARTU, NO_POL, ID_USER, TGL_OUT, ID_YARD) VALUES('$no_cont', '$no_kartu', '$no_pol', '$id_user', SYSDATE, '$id_yard')";
			$result_insert	= $db->query($query_insert);
			
			//update kartu stripping
			$query_upd1		= "UPDATE GATE_IN_TRUCK SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_KARTU = '$no_kartu'";
			$db->query($query_upd1);
			
			/*
			//update status location di master container
			$query_upd2		= "UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'";
			$db->query($query_upd2);
			
			//update status AKTIF di container receiving
			$query_get_req_rec	= "SELECT REQUEST_STRIPPING.* FROM REQUEST_STRIPPING INNER JOIN KARTU_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = KARTU_STRIPPING.NO_REQUEST WHERE KARTU_STRIPPING.NO_KARTU LIKE '$no_kartu' AND KARTU_STRIPPING.NO_CONTAINER LIKE '$no_cont'";
			$result_req_rec		= $db->query($query_get_req_rec);
			$row_req_rec		= $result_req_rec->fetchRow();
			
			$no_req_rec			= $row_req_rec["NO_REQUEST_RECEIVING"];
			
			$query_upd	= "UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_rec'";
			$db->query($query_upd);
			*/
			
			
			echo "OK";

?>