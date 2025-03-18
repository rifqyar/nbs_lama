<?php
$id_vsb=$_POST['NO_UKK'];
$user_id=$_SESSION['PENGGUNA_ID'];

$db=getDb();
$query="begin bil_prc_bilstvrpd_s2('$id_vsb','$user_id');end;";
$db->query($query);

echo 'success';
?>