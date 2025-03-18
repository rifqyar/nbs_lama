<?php
$tipe       = $_GET["tipe"];
$reqtype      = $_GET["reqtype"];
$no_cont    = strtoupper($_GET["term"]);

$db       = getDB();
  
if($reqtype== 'NEW'){
$query          = "SELECT  C.NO_CONTAINER, D.ID_REQ,
                       C.SIZE_CONT,
                       C.TYPE_CONT,
                       C.STATUS,
                       B.ID_VSB_VOYAGE AS NO_UKK,
                       C.VESSEL,
                       C.VOYAGE_IN,
                       C.VOYAGE_OUT,
                       C.CARRIER,
                       C.POD,
                       C.FPOD,
                       C.POL,
                       C.DO_NUMBER AS DO,
                       C.CUSTOMS_REF_NUMBER AS CUSTNO,
                       C.BOOKING_SL,
                       TO_CHAR(TO_DATE(C.PLUG_IN,'yyyymmddhh24miss'),'dd/mm/rrrr hh24:mi:ss') PLUG_IN,
                       TO_CHAR(TO_DATE(C.PLUG_OUT,'yyyymmddhh24miss'),'dd/mm/rrrr hh24:mi:ss') PLUG_OUT,
                       '' PLUG_OUT_EXT,
                       C.REEFER_TEMP,
                       '' OLD_REQ
                  FROM  req_monreefer d
                  RIGHT JOIN m_cyc_container@dbint_link C 
                  ON TRIM(C.NO_CONTAINER) = TRIM(D.NO_CONTAINER) 
                  AND TRIM(C.VOYAGE_IN)   = TRIM(D.VOYAGE_IN)
                  AND TRIM(C.VESSEL)      = TRIM(D.VESSEL)
                  LEFT JOIN m_vsb_voyage@dbint_link B 
                  ON TRIM(C.VESSEL_CODE) = TRIM(B.VESSEL_CODE) 
                  AND TRIM(C.VOYAGE_IN) = TRIM(B.VOYAGE_IN)
                  WHERE C.E_I ='$tipe'
                  AND C.TYPE_CONT = 'RFR'
                  AND C.NO_CONTAINER = '$no_cont'
                  AND D.ID_REQ IS NULL";
}
else {
$query          = "SELECT   C.NO_CONTAINER,
                       C.SIZE_CONT,
                       C.TYPE_CONT,
                       C.STATUS,
                       B.ID_VSB_VOYAGE AS NO_UKK,
                       C.VESSEL,
                       C.VOYAGE_IN,
                       C.VOYAGE_OUT,
                       C.CARRIER,
                       C.POD,
                       C.FPOD,
                       C.POL,
                       C.DO_NUMBER AS DO,
                       C.CUSTOMS_REF_NUMBER AS CUSTNO,
                       C.BOOKING_SL,
                       TO_CHAR(TO_DATE(C.PLUG_IN,'yyyymmddhh24miss'),'dd/mm/rrrr hh24:mi:ss') PLUG_IN,
                       TO_CHAR(D.PLUG_OUT,'dd/mm/rrrr hh24:mi:ss') PLUG_OUT,
                       TO_CHAR(TO_DATE(c.PLUG_OUT,'yyyymmddhh24miss'),'dd/mm/rrrr hh24:mi:ss') PLUG_OUT_EXT,
                       C.REEFER_TEMP,
                       d.ID_REQ OLD_REQ
                  FROM  m_vsb_voyage@dbint_link B, m_cyc_container@dbint_link C, req_monreefer d
                 WHERE  C.VESSEL_CODE = B.VESSEL_CODE AND C.VOYAGE_IN = B.VOYAGE_IN
                 AND c.no_container = d.no_container and b.id_vsb_voyage = d.id_vsb_voyage
                AND  C.E_I ='$tipe' AND C.NO_CONTAINER = '$no_cont'
                AND c.TYPE_CONT = 'RFR'";
    
}
  //echo $query; die;     
$result     = $db->query($query);
$row      = $result->getAll();  

//print_r($row);

echo json_encode($row);


?>