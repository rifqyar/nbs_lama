<?php
$ukk=$_POST['ID_VS'];
$user=$_SESSION['NAMA_PENGGUNA'];

$db=getdb();
$query="begin proc_grouping_cont_im('$ukk','$user');end;";
//PRINT_R($query);DIE;
$db->query($query);
echo 'SUKSES';

?>