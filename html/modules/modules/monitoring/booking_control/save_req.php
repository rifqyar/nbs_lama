<?php
$db = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=192.168.29.88)(Port=1521)))(CONNECT_DATA=(SERVER=dedicated)(SERVICE_NAME=TOSDBTEST)))";
$conn = ocilogon("OPUS_REPO", "opus_repo", "$db")or die("can't connect to server");


$ves = $_POST['VES'];
$voy = $_POST['VOY'];
$voyo = $_POST['VOYO'];
$jml_cont = $_POST['JML_CONT'];

echo $jml_cont;

                
$masukan = "UPDATE M_VSB_VOYAGE 
            set CONTAINER_LIMIT = $jml_cont                
            where VOYAGE_IN = '$voy'
                  and VOYAGE_OUT = '$voyo' ";
$masukan = OCIparse($conn, $masukan);
ociexecute($masukan);
?>