<?php
$id_user = $_SESSION["ID_USER"];
$new = md5($_POST['NEW_PASSWD'].$id_user);
$conf = $_POST['CONF_PASSWD'];

    $db = getDB();
	$db->query("update tb_user set password_enc = '".$new."' where id = '".$id_user."'");
	echo "OK";
?>