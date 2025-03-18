<?php
require_lib('praya.php');

$no_cont = $_POST['no_cont'];
$jenis_bm = $_POST['jenis_bm'];
$get_disable_container = getDisableContainer($no_cont, $jenis_bm);

echo json_encode($get_disable_container);
