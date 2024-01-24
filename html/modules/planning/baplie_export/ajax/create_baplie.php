<?php
$db = getDB('dbint');
$call_sign = $_GET[call_sign];
$voyage_in = $_GET[voyage_in];
$voyage_out = $_GET[voyage_out];

$query_kapal = "SELECT 
						VESSEL, 
						VOYAGE,
						VESSEL_CODE
				FROM 
						M_VSB_VOYAGE 
				WHERE 
						call_sign='$call_sign' 
						and voyage_in='$voyage_in' 
						and voyage_out='$voyage_out'";
						
//echo $query_kapal;
//die();
						
$row_kapal= $db->query($query_kapal)->fetchRow();
$voyage = $row_kapal[VOYAGE];
$vessel_code = $row_kapal[VESSEL_CODE];

$query = "declare begin PRO_IF_OUTBAPLIE1('$vessel_code', '$voyage'); end;";         
$db->query($query); 
?>

<script> alert('sukses create data')</script>


<?php die(); ?>