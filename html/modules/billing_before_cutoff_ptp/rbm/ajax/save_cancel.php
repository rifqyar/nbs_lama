<?php 
$db = getDb("dbint");

$id_vsb = $_POST["ID_VSB"];
$status = $_POST["STATUS"];

$query = "update m_vsb_voyage set rbm_dld = '$status' where ID_VSB_VOYAGE='$id_vsb'";
if ($db->query($query)) {
	echo "Y";
	die();
}
?>