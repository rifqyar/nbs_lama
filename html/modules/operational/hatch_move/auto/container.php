<?php
//utk menon-aktifkan template default
outputRaw();
$ukk = strtoupper($_GET["ukk"]);
$tipe = strtoupper($_GET["tipe"]);
$no_cont = strtoupper($_GET["term"]);
$db = getDB();

if($tipe=="IMPORT")	$status='03';
else	$status='51';

$query = "SELECT NO_CONTAINER, (SIZE_||'-'||TYPE_||'-'||STATUS) AS JENIS, HEIGHT, HZ, KD_BARANG
			FROM ISWS_LIST_CONTAINER
			WHERE UPPER(TRIM(NO_CONTAINER)) LIKE '%$no_cont%'
				AND NO_UKK='$ukk'
				AND OI='O'
				AND KODE_STATUS='$status'
			ORDER BY NO_CONTAINER";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>