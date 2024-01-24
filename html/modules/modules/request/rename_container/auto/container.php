<?php
$tipe 			= $_GET["tipe"];
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB('dbint');
	

$query          = "SELECT  C.NO_CONTAINER OLD_CONT,
                       C.NO_CONTAINER NEW_CONTNO,
                       C.SIZE_CONT,
                       C.TYPE_CONT,
                       C.STATUS,
                       C.VESSEL_CODE AS NO_UKK,
                       C.VESSEL AS NM_KAPAL,
                       C.VOYAGE_IN,
                       C.VOYAGE_OUT,
                       C.CARRIER,
                       C.POD,
                       C.FPOD,
                       C.POL,
                       C.DO_NUMBER AS DO,
                       C.CUSTOMS_REF_NUMBER AS CUSTNO,
                       C.BOOKING_SL,
                       D.KODE_BARANG KD_BARANG
                  FROM  m_vsb_voyage B, m_cyc_container C, master_barang D
                 WHERE  C.VESSEL_CODE = B.VESSEL_CODE AND C.VOYAGE_IN = B.VOYAGE_IN
                 AND C.SIZE_CONT = D.UKURAN
                 AND C.TYPE_CONT = D.TYPE
                 AND D.STATUS =
                 CASE WHEN C.STATUS =  'EMPTY' THEN
                    'MTY'
                    ELSE 
                    'FCL'
                   END
                 AND D.HEIGHT_CONT = CASE WHEN C.HEIGHT <> 'OOG'
                        THEN 'BIASA'
                        ELSE 'OOG'
                 END 
                AND  C.E_I ='$tipe' AND C.NO_CONTAINER = '$no_cont'";
				
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>