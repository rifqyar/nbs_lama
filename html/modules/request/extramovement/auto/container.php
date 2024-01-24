<?php
//utk menon-aktifkan template default
outputRaw();
$tipe = strtoupper($_GET["tipe"]);
$no_cont = strtoupper($_GET["term"]);

$vessel=$_GET["vessel"];
$voy=$_GET["voyin"];
$voyo=$_GET["voyout"];

$db = getDB('dbint');
$query = "SELECT A.NO_CONTAINER, A.SIZE_, A.TYPE_, A.STATUS, A.HZ, A.ID_BARANG AS KD_BARANG, B.ID_VES_SCD AS NO_UKK, B.VESSEL AS NM_KAPAL,
			B.VOYAGE_IN AS VOY,B.VOYAGE_OUT
			FROM OPS_LIST_CONTAINER A
				LEFT JOIN OPS_VES_SCD B ON A.VESSEL=B.VESSEL AND A.VOYAGE_IN=B.VOYAGE_IN AND A.VOYAGE_OUT=B.VOYAGE_OUT
			WHERE UPPER(TRIM(A.NO_CONTAINER)) LIKE '%$no_cont%'
				AND A.CONT_LOCATION='YARD'
				AND ACTIVE='Y'
				AND A.VESSEL='$vessel' AND A.VOYAGE_IN='$voy' AND A.VOYAGE_OUT='$voyo'
			ORDER BY A.NO_CONTAINER";
// echo $query;die;
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>