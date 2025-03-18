<?php
$db = getDB('dbint');
$call_sign = $_GET['call_sign'];
$voyage_in = $_GET['voyage_in'];
$voyage_out = $_GET['voyage_out'];

$query = "declare begin proc_add_cont_baplie_export('$call_sign','$voyage_in',$voyage_out); end;";         
$db->query($query); 
?>


