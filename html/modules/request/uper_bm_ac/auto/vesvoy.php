<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db 			= getDB();

/*$query = "SELECT NO_UKK, TRIM(NM_KAPAL) NM_KAPAL, VOYAGE_IN||'-'||VOYAGE_OUT AS VOYAGE, NM_PEMILIK
			FROM RBM_H
			WHERE NM_KAPAL LIKE '%$shipper%'
			ORDER BY TGL_JAM_TIBA DESC";*/

/*$query  = "SELECT A.ID_VES_VOYAGE AS NO_UKK,
				  A.NAME AS NM_KAPAL, 
				  A.VOY AS VOYAGE, 
				  B.ID_VSB_VOYAGE AS UKKS,
				  B.OPERATOR_NAME AS NM_PEMILIK,
				  D.COMPANY_NAME,
				  D.ACCOUNT_NUMBER,
				  E.ID_KADE,
				  E.KADE_NAME,
				  TO_CHAR(C.ETA,'YYYY-MM-DD') ETA,
				  TO_CHAR(C.ETB,'YYYY-MM-DD') ETB,
				  TO_CHAR(C.ETD,'YYYY-MM-DD') ETD
				  FROM 
				  ITOS_OP.V_TR_VESSEL_NBS A
				  LEFT JOIN ITOS_REPO.M_VSB_VOYAGE 	B ON A.ID_VES_VOYAGE  	= B.UKKS
				  LEFT JOIN ITOS_OP.VES_VOYAGE 	C ON A.ID_VES_VOYAGE 	= C.ID_VES_VOYAGE
				  LEFT JOIN ITOS_OP.M_STEVEDORING_COMPANIES D ON C.STV_COMPANY = D.ID_COMPANY
				  LEFT JOIN ITOS_OP.M_KADE 	E ON E.ID_KADE = C.ID_KADE
				  WHERE A.NAME LIKE '%$shipper%'
				  AND B.ATA IS NULL";*/

			/*SELECT A.ID_VES_VOYAGE, A.NAME, A.VOY, B.ID_VSB_VOYAGE AS UKKS
					FROM ITOS_OP.V_TR_VESSEL_NBS A
					LEFT JOIN ITOS_REPO.M_VSB_VOYAGE B ON A.ID_VES_VOYAGE  = B.UKKS*/
// ATA IS NULL DI LEPAS DULU UNTUK KEBUTUHAN DEVELOPMENT					
$query  = "select id_vsb_voyage NO_UKK, vessel NM_KAPAL, voyage_in || '/'|| voyage_out voyage,operator_name NM_PEMILIK
,to_char(to_date(substr(a.ETA,0,8),'yyyymmdd'),'YYYY-MM-DD') eta
,to_char(to_date(substr(a.ETd,0,8),'yyyymmdd'),'YYYY-MM-DD') etd
from 
opus_repo.m_vsb_voyage a
where substr(eta,0,4) >='2019'
and a.ata is null 
and upper(vessel) like upper('%$shipper%')";

//print_r($query);

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>