<?php
$db = getDB("storage");
$now_pass = $_POST["new_pass"];
$new_pass = md5($now_pass);
$id = $_POST["id"];
$db->query("UPDATE MASTER_USER SET PASSWORD = '$new_pass' WHERE ID = '$id'");
?>