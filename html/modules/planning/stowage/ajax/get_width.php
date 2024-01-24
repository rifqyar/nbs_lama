<?php

$db = getDB();
$id_vs = $_POST["ID_VS"];
//echo $id_vs;exit;

$query_width = "SELECT JML_ROW FROM STW_BAY_AREA WHERE ID_VS = '$id_vs'";
$result10 = $db->query($query_width);
$get_width = $result10->fetchRow();
$width = $get_width['JML_ROW'];

echo $width+1;

?>