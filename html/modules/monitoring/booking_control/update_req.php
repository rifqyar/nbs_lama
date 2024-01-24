<?php
//$db = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=192.168.29.85)(Port=1521)))(CONNECT_DATA=(SERVER=dedicated)(SERVICE_NAME=PNKDB)))";
//$conn = ocilogon("OPUS_REPO", "opus_repo", "$db")or die("can't connect to server");


$db = getDB('dbint');


$ves = $_POST['VES'];
$voy = $_POST['VOY'];
$voyo = $_POST['VOYO'];
$jml_cont = $_POST['JML_CONT'];

$masukan = "UPDATE 
					M_VSB_VOYAGE 
            set 
					CONTAINER_LIMIT = $jml_cont                
            where 
					VESSEL_CODE = '$ves'
					and VOYAGE_IN = '$voy'
					and VOYAGE_OUT = '$voyo'";


$db->query($masukan);
die();
?>

