<?php

$db = getDB();
$no_ukk = $_POST["NO_UKK"];
$no_cont = $_POST["NO_CONTAINER"];
$alat = $_POST["ALAT"];
$soa = $_POST["SOA"];
$id_user = $_SESSION["ID_USER"];
$nm_user = $_SESSION["NAMA_LENGKAP"];

$confirm = "begin confirm_load_d('$no_ukk','$no_cont','$id_user','$nm_user','$alat'); end;";
$db->query($confirm);

echo "OK";

?>