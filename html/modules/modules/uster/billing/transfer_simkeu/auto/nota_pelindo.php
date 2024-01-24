<?php
$no_nota		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
$query 			= "SELECT NO_NOTA, NO_REQUEST, EMKL, ALAMAT, NPWP, TOTAL_TAGIHAN, PPN, TAGIHAN TOTAL, NO_FAKTUR, TRANSFER, LUNAS, TO_CHAR(TANGGAL_LUNAS,'dd/mm/yyyy') TGL_LUNAS, NVL(TRANSFER, '-') FROM NOTA_ALL_H WHERE NO_FAKTUR LIKE '$no_nota%'";
$result			= $db->query($query);
$row			= $result->getAll();	

// print_r($result);
// print_r($row);

echo json_encode($row);


?>