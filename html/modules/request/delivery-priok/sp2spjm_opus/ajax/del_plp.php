<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$idplp = $_POST['ID_PLP'];
$nocont = $_POST['NO_CONT'];

$db=getDB();

//==== update detail ===//
$update_plp_d = "update spjm_approval_d set flag_sp2spjm = '1' where trim(id_plp) = trim('$idplp') and trim(id_barang) = trim('$nocont')";
$db->query($update_plp_d);

echo "OK";
?>