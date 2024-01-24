<?php 
$db = getDb("dbint");
$db2 = getDb();

$id_vsb = $_POST["ID_VSB"];
$status = $_POST["STATUS"];

$query = "update m_vsb_voyage set rbm_dld = '$status' where ID_VSB_VOYAGE='$id_vsb'";
if ($db->query($query)) {
    $qupdst = "update bil_rpstv_h set sts_rpstv = 'C' where id_vsb_voyage = '$id_vsb'";
    $db2->query($qupdst);
    $qupdel = "delete bil_pnstv_h where id_vsb_voyage = '$id_vsb'";
    $db2->query($qupdel);
	echo "Y";
	die();
}
?>