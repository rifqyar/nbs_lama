<?php
$id_vsb=$_POST['NO_UKK'];
$user_id=$_SESSION['PENGGUNA_ID'];

$db=getDb();
$cekq = "select count(*) jum from bil_pnstv_h where id_vsb_voyage = '$id_vsb'";
$rcekq = $db->query($cekq)->fetchRow();
if($rcekq['JUM'] > 0){
    $query="begin bil_prc_bilstvrpd_s2('$id_vsb','$user_id');end;";
    $db->query($query);

    echo 'success';

}
else{
    echo 'failed';
}
?>