<?php
$tipe 			= $_GET["tipe"];
$nota			= strtoupper($_GET["term"]);

$db 			= getDB();

//anne
if ($tipe == 'ANNE'){
	
$query 			= " SELECT ID_NOTA, ID_REQ,VESSEL, VOYAGE_IN, VOYAGE_OUT, NO_UKK,
				    EMKL, COA, ALAMAT, NPWP,TOTAL
					FROM NOTA_RECEIVING_H
					WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA = '$nota'";
} else if ($tipe == 'SP2') {
$query 			= " SELECT ID_NOTA, ID_REQ,VESSEL, VOYAGE_IN, VOYAGE_OUT, NO_UKK,
				    EMKL, COA, ALAMAT, NPWP,TOTAL
					FROM NOTA_DELIVERY_H
					WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA LIKE '%$nota%'";
}  else if ($tipe == 'BM') {
$query 			= " SELECT NO_NOTA ID_NOTA, ID_BATALMUAT ID_REQ,VESSEL, VOYAGE VOYAGE_IN, VOYAGE VOYAGE_OUT, NO_UKK,
				    EMKL, KODE_PBM COA, ALAMAT, NPWP, BAYAR TOTAL
					FROM NOTA_BATALMUAT_H
					WHERE (STATUS = 'P' OR STATUS = 'T') AND NO_NOTA LIKE '%$nota%'";
} else if ($tipe == 'REEX') {
$query 			= " SELECT ID_NOTA, ID_REQUEST ID_REQ,NM_KAPAL VESSEL, VOYAGE_IN, VOYAGE_OUT, NO_UKK,
				    NM_PEMILIK EMKL, COA, ALAMAT, NPWP, TOTAL
					FROM NOTA_REEKSPOR_H
					WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA LIKE '%$nota%'";
} else if ($tipe == 'EXMO') {
$query 			= " SELECT ID_NOTA, ID_REQUEST ID_REQ,NM_KAPAL VESSEL, VOYAGE_IN, VOYAGE_OUT, NO_UKK,
				    EMKL, COA, ALAMAT, NPWP, TOTAL
					FROM EXMO_NOTA LEFT JOIN RBM_H ON (RBM_H.NO_UKK = EXMO_NOTA.NO_UKK)
					WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA LIKE '%$nota%'";
}else if ($tipe == 'BH') {
$query 			= " SELECT ID_NOTA, ID_REQUEST ID_REQ,VESSEL, VOYAGE VOYAGE_IN, VOYAGE VOYAGE_OUT, NO_UKK,
				    EMKL, COA, ALAMAT_EMKL AS ALAMAT, NPWP, TOTAL
					FROM BH_NOTA
					WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA LIKE '%$nota%'";
}

$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);
?>