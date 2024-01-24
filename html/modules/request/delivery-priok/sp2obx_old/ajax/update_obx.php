<?php
$user = $_SESSION["NAMA_PENGGUNA"];
$tgl_perp = $_POST['TGL_DEL'];
$iddel = $_POST['ID_DEL'];

$db=getDB();
$db8=getDB('ora');

//==== update header ===//
$update_obxh = "update bil_delob_h set exp_date = to_date('$tgl_perp','DD-MM-YYYY'), modified_by = '$user', modified_date = sysdate where trim(id_del) = trim('$iddel')";
//print_r($update_obxh);die;
$db->query($update_obxh);
$db8->query($update_obxh);
echo "OK";
?>