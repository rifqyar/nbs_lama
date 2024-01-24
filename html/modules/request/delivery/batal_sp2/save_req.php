<?php
$db=getDb();
	$container =TRIM($_POST['NO_CONTAINER']);
    $size_cont=$_POST['SIZE_CONT'];
	$type_cont=$_POST['TYPE_CONT'];
	$status_cont = $_POST['STATUS_CONT'];
	$status_payment = $_POST['STATUS_PAYMENT'];
	$id_req = $_POST['ID_REQ'];
	$rename_container = TRIM($container._."rename");
	$no_ukk = $_POST['NO_UKK'];
	$vessel = $_POST['VESSEL'];
	$voyage_in = $_POST['VOYAGE_IN'];
	$no_ba = $_POST['NO_BA'];
	$billing_user = $_SESSION["NAMA_PENGGUNA"];	

$query = "INSERT 
					INTO REQ_BATAL_SP2
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


$q_update = "	UPDATE 
						REQ_DELIVERY_D
				SET 
						NO_CONTAINER='$rename_container'
				WHERE 
						no_container='$container'
						and id_req = '$id_req'";
$result=$db->query($q_update);

die();

?>