<?php
outputRaw();
$db = getDB();
//$sql = "SELECT round(to_number(TO_DATE('"++"','YYYY-MM-DD HH24:MI') - TO_DATE('"+$_POST["mulai"]+"','YYYY-MM-DD HH24:MI'))) AS JML FROM DUAL";
//CEIL((((86400*(TGL_PLUG_OUT - TGL_PLUG_IN))/60)/60)/ 8)AS SHIFT  
$sql = "SELECT CEIL((((86400*(TO_DATE('". $_POST["nanti"] ."','YYYY-MM-DD HH24:MI') - TO_DATE('".$_POST["mulai"]."','YYYY-MM-DD HH24:MI')))/60)/60)/ 8) AS JML FROM DUAL";
//echo $sql;exit;
$rs = $db->query($sql);
if($rs){
	$row = $rs->FetchRow();
	echo $row["JML"];
}
?>