<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT A.NO_CONTAINER, A.ISO_CODE, B.ISO_SIZE, B.ISO_TYPE, B.ISO_HEIGHT FROM MST_CONT_PONTI A LEFT JOIN MST_ISO B ON A.ISO_CODE=B.ISO_CODE WHERE NO_CONTAINER LIKE '%$no_cont%'";
$result			= $db->query($query);
$row			= $result->getAll();
$count1			= count($row);

if ($count1===0)
{
$arr = array(array("VALIDATE" => 1,"NO_CONTAINER"=>$no_cont,"ISO_CODE"=>"Belum Terdaftar","ISO_HEIGHT"=>"Belum Terdaftar"));
echo json_encode($arr);
}
else {
echo json_encode($row);
}
?>