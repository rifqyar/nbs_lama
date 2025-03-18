<?php

$pelanggan = $_POST['kd_pelanggan'];
$ves_voyage = $_POST['ves_voyage'];
$no_uper = $_POST['no_uper'];

if ($no_uper !='')
{
	$db 	 = getDB();
	$sql_cekhold = $db->query("SELECT NVL(SUM(1),0) JUM FROM UPER_H WHERE NO_UKK='".$ves_voyage."' AND NO_UPER !='".$no_uper."' AND (USER_LUNAS <> 'X' OR USER_LUNAS IS NULL)");
	$row = $sql_cekhold->getAll();
	$jum =$row[0]['JUM'];
	
	echo json_encode($jum);
}
else
{
	$db 	 = getDB();
	$sql_cekhold = $db->query("SELECT NVL(SUM(1),0) JUM FROM UPER_H WHERE NO_UKK='".$ves_voyage."' AND (USER_LUNAS <> 'X' OR USER_LUNAS IS NULL)");
	$row = $sql_cekhold->getAll();
	$jum =$row[0]['JUM'];
	
	echo json_encode($jum);
}


?>