<?
$id_vs = $_POST['ID_VS'];
$kd_vs = $_POST['KD_KAPAL'];
$id_user = $_POST['ID_USER'];

$db = getDB();

$overwrite = "begin overwrite_vesprofil('$id_vs'); end;";
$db->query($overwrite);

$vs_assign = "begin vessel_assign('$id_vs','$kd_vs','$id_user'); end;";
$db->query($vs_assign);

$update_flag = "update RBM_H set FLAG_PROFILE = 'Y' WHERE NO_UKK = '$id_vs'";
$db->query($update_flag);

echo "OK";
?>