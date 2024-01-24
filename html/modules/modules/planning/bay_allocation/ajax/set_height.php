<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_GET["id_vs"];
$id_bay = $_GET["bay_area"];
$no_bay = $_GET["no_bay"];
$jml_row = $_GET["jml_row"];
$posisi = strtoupper($_GET["posisi"]);

if ($no_bay==1) 
{ 
	$bays = $no_bay."(".($no_bay+1).")";  
} 
else if (($no_bay-1)%4==0) 
{ 
	$bays = $no_bay."(".($no_bay+1).")"; 
} 
else 
{ 
	$bays = $no_bay; 
}

$query_row2 = "SELECT ROW_ FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_bay' AND POSISI_STACK = '$posisi' AND ROWNUM <= '$jml_row' ORDER BY CELL_NUMBER ASC";
$result3    = $db->query($query_row2);
$row2       = $result3->getAll();

foreach ($row2 as $row8)
{
	$row_height = $row8['ROW_'];
	$height_capacity = $_POST['row'.$row_height.''];
	
	if($height_capacity=="")
	{
		$height = "0";
	}
	else
	{
		$height = $height_capacity*100;
	}
	
	$update_capacity = "UPDATE STW_BAY_CAPACITY SET HEIGHT_CAPACITY = '$height', PLAN_HEIGHT = '$height', REAL_HEIGHT = '$height' WHERE ID_BAY_AREA = '$id_bay' AND POSISI_STACK = '$posisi' AND ROW_ = '$row_height'";
	$db->query($update_capacity);
}

$keterangan = "SET HEIGHT CAPACITY ".$bays." ".$_GET["posisi"];
$insert_history = "INSERT INTO STW_HISTORY (ID_VS,STATUS,TGL_UPDATE,USER_UPDATE) VALUES ('$id_vs','$keterangan',SYSDATE,'$id_user')";
$db->query($insert_history);

header('Location: '.HOME.'planning.bay_allocation/bay_alokasi?vs='.$id_vs);
?>