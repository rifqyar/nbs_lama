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
} else if ($tipe == 'SP2PEN') {
$query 			= " SELECT NO_FAKTUR as ID_NOTA, ID_REQ,VESSEL, VOYAGE_IN, VOYAGE_OUT, NO_UKK,
				    EMKL, COA, ALAMAT, NPWP,TOTAL
					FROM NOTA_DELIVERY_H_PEN
					WHERE (STATUS = 'P' OR STATUS = 'T') AND NO_FAKTUR LIKE '%$nota%'";
}  else if ($tipe == 'BM') {
$query 			= " SELECT ID_NOTA, ID_REQ,VESSEL, VOYAGE_IN,VOYAGE_OUT, NO_UKK,
                    EMKL, KODE_PBM COA, ALAMAT, NPWP, BAYAR TOTAL
					FROM NOTA_BATALMUAT_H
					WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA LIKE '%$nota%'";
}  else if ($tipe == 'BMPEN') {
$query 			= " SELECT ID_NOTA, ID_REQ,VESSEL, VOYAGE_IN,VOYAGE_OUT, NO_UKK,
                    EMKL, KODE_PBM COA, ALAMAT, NPWP, BAYAR TOTAL
					FROM NOTA_BATALMUAT_H_PENUMPUKAN
					WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA LIKE '%$nota%'";
} else if ($tipe == 'REEX') {
$query 			= " SELECT 
							A.ID_NOTA, 
							A.ID_REQ,
							B.VESSEL_NEW AS VESSEL, 
							B.VOYAGE_IN_NEW AS VOYAGE_IN, 
							B.VOYAGE_OUT_NEW AS VOYAGE_OUT, 
							B.NO_UKK_NEW AS NO_UKK,
                    		B.SHIPPING_LINE AS EMKL, 
                    		A.COA, 
                    		B.ALAMAT, 
                    		B.NPWP, 
                    		A.TOTAL
                    FROM 
                    		NOTA_REEXPORT_H A LEFT JOIN REQ_REEXPORT_H B
                    		ON (A.ID_REQ = B.ID_REQ)
                    WHERE
                    		A.ID_NOTA LIKE '%$nota%'
                    		AND (A.STATUS = 'P' OR A.STATUS = 'T')";                     
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
}else if($tipe == 'RBM'){
$query          = "SELECT TRX_NMB ID_NOTA, ID_VSB_VOYAGE ID_REQ, VESSEL, VOY_IN VOYAGE_IN,
                    VOY_OUT VOYAGE_OUT, ID_VSB_VOYAGE NO_UKK, CUST_NAME EMKL, CUST_NO COA, CUST_ADDR ALAMAT, CUST_TAX_NO NPWP, TTL_ TOTAL
                     FROM BIL_NTSTV_H WHERE TRX_NMB = '$nota' AND STS_RPSTV in ('I','T')";
}else if($tipe == 'MON'){
$query          = "SELECT ID_NOTA, ID_REQ,VESSEL, VOYAGE_IN, VOYAGE_OUT, NO_UKK,
                    PELANGGAN EMKL, KD_PELANGGAN COA, ALAMAT, NPWP,TOTAL
                    FROM NOTA_MONREEFER_H
                    WHERE (STATUS = 'P' OR STATUS = 'T') AND ID_NOTA LIKE '$nota%'";
}else if ($tipe == 'SEE') {
$query 			= " SELECT TRX_NMB ID_NOTA, ID_NTSTV ID_REQ,VESSEL, VOY_IN VOYAGE_IN, VOY_OUT VOYAGE_OUT, ID_VSB_VOYAGE NO_UKK,
                    CUST_NAME EMKL, CUST_NO COA, CUST_ADDR ALAMAT, CUST_TAX_NO NPWP, TTL_ TOTAL
                    FROM BIL_NTSTE_H
                    WHERE (STS_RPSTV = 'I' OR STS_RPSTV = 'T') AND TRX_NMB LIKE '%$nota%'";
}

$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);
?>