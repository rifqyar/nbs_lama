<?php
//utk menon-aktifkan template default
outputRaw();
$tipe = strtoupper($_GET["tipe"]);
$no_cont = strtoupper($_GET["term"]);
$db = getDB();

if($tipe=="A")
{$status='51';} 
else {$status='49';}

if ($tipe == 'A'){
	$query = "SELECT A.NO_CONTAINER, 
					(A.SIZE_CONT||'-'||A.TYPE_CONT||'-'||A.STATUS) AS JENIS, 
					 A.HEIGHT, 
					 A.HZ,
					 C.KODE_BARANG AS KD_BARANG, 
					 B.ID_VSB_VOYAGE AS NO_UKK, 
					 A.SIZE_CONT, 
					 A.TYPE_CONT, 
					 A.STATUS, 
                                         to_date(to_char(to_date(B.ETD, 'yyyymmddhh24miss'), 'yyyymmdd'), 'yyyymmdd') as ETD_LAMA
			  FROM M_CYC_CONTAINER@dbint_link A
			  INNER JOIN M_VSB_VOYAGE@dbint_link B ON (A.VESSEL_CODE = B.VESSEL_CODE AND A.VOYAGE_OUT = B.VOYAGE_OUT AND A.VOYAGE_IN=B.VOYAGE_IN)
				  left join master_barang C on C.UKURAN = A.SIZE_CONT AND C.TYPE = A.TYPE_CONT AND C.STATUS = CASE WHEN A.STATUS='FULL' THEN 'FCL' ELSE 'MTY' END 
				  AND CASE WHEN A.HEIGHT<>'OOG' THEN 'BIASA' ELSE 'OOG' END = C.HEIGHT_CONT
				  WHERE UPPER(TRIM(NO_CONTAINER)) LIKE '%$no_cont%'
				  AND A.E_I='E' AND ACTIVE='Y'
				  and UPPER(A.CONT_LOCATION) = 'YARD'
				  ORDER BY NO_CONTAINER";
} else {
					  
	$query = "SELECT A.NO_CONTAINER, 
                    (A.SIZE_CONT||'-'||A.TYPE_CONT||'-'||A.STATUS) AS JENIS, 
                     A.HEIGHT, 
                     A.HZ,
                     C.KODE_BARANG AS KD_BARANG, 
                     B.ID_VSB_VOYAGE AS NO_UKK, 
                     B.VESSEL,
                     B.VOYAGE_IN,
                     A.SIZE_CONT, 
                     A.TYPE_CONT, 
                     A.STATUS
              FROM M_CYC_CONTAINER@dbint_link A
              INNER JOIN M_VSB_VOYAGE@dbint_link B ON (A.VESSEL_CODE = B.VESSEL_CODE AND A.VOYAGE_OUT = B.VOYAGE_OUT)
                  left join master_barang C on C.UKURAN = A.SIZE_CONT AND C.TYPE = A.TYPE_CONT AND C.STATUS = CASE WHEN A.STATUS='FULL' THEN 'FCL' ELSE 'MTY' END 
                  AND CASE WHEN A.HEIGHT<>'OOG' THEN 'BIASA' ELSE 'OOG' END = C.HEIGHT_CONT
                  WHERE UPPER(TRIM(NO_CONTAINER)) LIKE '%$no_cont%'
                  AND A.E_I='E'
                  and (A.CONT_LOCATION='Out' or A.CONT_LOCATION='Chassis' or A.CONT_LOCATION='Yard')
                  AND b.ID_VSB_VOYAGE = (SELECT MAX(c.ID_VSB_VOYAGE) FROM (SELECT 
                     B.ID_VSB_VOYAGE
              FROM M_CYC_CONTAINER@dbint_link A
              INNER JOIN M_VSB_VOYAGE@dbint_link B ON (A.VESSEL_CODE = B.VESSEL_CODE AND A.VOYAGE_OUT = B.VOYAGE_OUT)
                  left join master_barang C on C.UKURAN = A.SIZE_CONT AND C.TYPE = A.TYPE_CONT AND C.STATUS = CASE WHEN A.STATUS='FULL' THEN 'FCL' ELSE 'MTY' END 
                  AND CASE WHEN A.HEIGHT<>'OOG' THEN 'BIASA' ELSE 'OOG' END = C.HEIGHT_CONT
                  WHERE UPPER(TRIM(NO_CONTAINER)) LIKE '%$no_cont%'
                  AND A.E_I='E'
                  and (A.CONT_LOCATION='Out' or A.CONT_LOCATION='Chassis')) c)
                  ORDER BY NO_CONTAINER";
	
}
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>