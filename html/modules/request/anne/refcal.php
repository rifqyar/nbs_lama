<?php
outputRaw();
$db = getDB();

$sql = "SELECT CEIL((((86400*(TO_DATE('". $_POST["nanti"] ."','DD-MM-YYYY HH24:MI') - TO_DATE('".$_POST["mulai"]."','DD-MM-YYYY HH24:MI')))/60)/60)/ 8) AS JML FROM DUAL";

$rs = $db->query($sql);
if($rs){
	$row = $rs->FetchRow();
	echo $row["JML"];
}
?>