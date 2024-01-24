<?php
require_lib('praya.php');

$db=getDb('storage');
	$container =TRIM($_POST['NO_CONTAINER']);
    $size_cont=$_POST['SIZE_CONT'];
	$type_cont=$_POST['TYPE_CONT'];
	$status_cont = $_POST['STATUS_CONT'];
	$status_payment = $_POST['STATUS_PAYMENT'];
	$id_req = $_POST['ID_REQ'];
	$id_ureq = $_POST['ID_UREQ'];
	$rename_container = TRIM($container._."rename");
	$no_ukk = $_POST['NO_UKK'];
	$vessel = $_POST['VESSEL'];
	$voyage_in = $_POST['VOYAGE_IN'];
	$no_ba = $_POST['NO_BA'];
	$billing_user = $_SESSION["NAMA_PENGGUNA"];
	$id_user = $_SESSION["LOGGED_STORAGE"];

	//cel location container, jika bukan in yard baru hit praya
	$cek_location = "SELECT NO_CONTAINER, LOCATION  
				FROM MASTER_CONTAINER mc 
				WHERE NO_CONTAINER = '$container'";

	$res_location=$db->query($cek_location);
	$row_location=$res_location->fetchRow();
	$location = $row_location['LOCATION'];


	if($location != 'IN_YARD'){
		$batal = batalContainer($container, $id_req ,'batal spps');

		if ($batal['code'] == '0') {
			echo 'gagal';die;
		}	
	}


$selheader = "SELECT
							NO_REQUEST_RECEIVING
				FROM
							REQUEST_STRIPPING
				WHERE
							NO_REQUEST = '$id_req'";

	$ret_header=$db->query($selheader);
	$row_header=$ret_header->fetchRow();
	$idreq_rec = $row_header['NO_REQUEST_RECEIVING'];

	$query = "INSERT
						INTO REQ_BATAL_SPPS
				VALUES
						('$no_ba',
						'$id_req',
						'$container',
						SYSDATE,
						'$billing_user',
						'$rename_container',
						'$no_ukk',
						'$vessel',
						'$voyage_in')";

	$result=$db->query($query);

	//update req deliver d, container receiving, container striping

	$q_update = "	UPDATE
							CONTAINER_STRIPPING
					SET
							NO_CONTAINER='$rename_container'
					WHERE
							no_container='$container'
							and no_request = '$id_req'";
	$db->query($q_update);


	$q_update2 = "	UPDATE
							CONTAINER_RECEIVING
					SET
							NO_CONTAINER='$rename_container'
					WHERE
							no_container='$container'
							and no_request = '$idreq_rec'";
	$db->query($q_update2);

	$q_update3 = "	UPDATE
							REQ_DELIVERY_D@DBBIL_LINK
					SET
							NO_CONTAINER='$rename_container'
					WHERE
							no_container='$container'
							and id_req = '$id_ureq'";

	$db->query($q_update3);

	//insert history container
	$q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$container' ORDER BY COUNTER DESC";
	$r_getcounter1 = $db->query($q_getcounter1);
	$row_counter = $r_getcounter1->fetchRow();
	$cur_booking1  = $row_counter["NO_BOOKING"];
	$cur_counter1  = $row_counter["COUNTER"];

	$query_log = "INSERT INTO 
					HISTORY_CONTAINER(
						NO_CONTAINER, 
						NO_REQUEST, 
						KEGIATAN, 
						TGL_UPDATE, 
						ID_USER, 
						STATUS_CONT,
						NO_BOOKING,
						COUNTER
					) VALUES (
						'$container',
						'$id_req',
						'BATAL STRIPPING',
						SYSDATE,
						'$id_user', 
						'FCL', 
						'$cur_booking1',
						'$cur_counter1'
					)";

	$result_log=$db->query($query_log);

	echo 'sukses';die;


?>
