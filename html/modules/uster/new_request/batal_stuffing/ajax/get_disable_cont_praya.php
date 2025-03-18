<?php
require_lib('praya.php');

$no_cont = $_POST['no_cont'];
$get_disable_container = getDisableContainer($no_cont, null , 'batal stuffing');

echo json_encode($get_disable_container);
