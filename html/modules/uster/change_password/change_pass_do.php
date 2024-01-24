<?php
$db = getDB("storage");
$id_user = $_POST["ID_USER"];
$password = $_POST["PASSWORD"];
$con_password = $_POST["CON_PASSWORD"];
if($password == $con_password){
	$passw = md5($password);
	$q_change = "UPDATE MASTER_USER SET PASSWORD = '$passw' WHERE ID = '$id_user'";
	if($db->query($q_change)){
		echo "OK";exit();
	}
	else{
		echo "FAILED";exit();
	}
}
else{
	echo "Password dan Confirm Password Tidak Sama";exit();
}

?>