<?php

$no_cont = strtoupper($_GET["term"]);

$db = getDB();

$query = "
    SELECT NO_CONTAINER,
                           SIZE_,
                           TYPE_,
                           STATUS,
                           ISO_CODE,
                           HEIGHT,
                           CARRIER,
                           E_I,
                           ACTIVE,
                           BERAT,
                           NO_UKK,
                           HZ,
                           LOKASI_BP,
                           --NO_REQUEST,
                           KODE_STATUS,
                           POD,
                           POL,
                           NM_KAPAL,
                           VOYAGE_IN,
                           VOYAGE_OUT,
                           ID_JOBSLIP,
                           IMO, 
                           POINT,
                           HOLD_STATUS,
                           HOLD_DATE,
                           NAME_YD
                      FROM (  SELECT a.NO_CONTAINER,
                                     SIZE_CONT AS SIZE_,
                                     TYPE_CONT AS TYPE_,
                                     STATUS,
                                     a.ISO_CODE,
                                     HEIGHT,
                                     a.CARRIER,
                                     a.E_I,
                                     ACTIVE,
                                     a.WEIGHT AS BERAT,
                                     c.ID_VSB_VOYAGE AS NO_UKK,
                                     a.HZ,
                                     BAYPLAN_POSITION AS LOKASI_BP,                                    
                                     ACTIVITY AS KODE_STATUS,
                                     a.POD,
                                     a.POL,
                                     a.VESSEL AS NM_KAPAL,
                                     a.VOYAGE_IN,
                                     a.VOYAGE_OUT,
                                     '' AS ID_JOBSLIP,
                                     IMO, 
                                     a.POINT as POINT,
                                     HOLD_STATUS,
                                     SUBSTR(a.HOLD_DATE,0,4)||'/'||SUBSTR(a.HOLD_DATE,5,2)||'/'||SUBSTR(a.HOLD_DATE,7,2) HOLD_DATE,
                                     CASE WHEN ((SELECT e.name_yd || ' - ' || e.no_request name_yd
                                          FROM bil_delob_h e, bil_delob_d f
                                         WHERE e.id_del = f.id_del
                                         AND  e.vessel = a.VESSEL
                                         AND  e.voy_in = a.VOYAGE_IN
                                         AND  f.no_cont = a.NO_CONTAINER
                                         group by name_yd,no_request ) <> '') THEN (SELECT e.name_yd
                                          FROM bil_delob_h e, bil_delob_d f
                                         WHERE e.id_del = f.id_del
                                         AND  e.vessel = a.VESSEL
                                         AND  e.voy_in = a.VOYAGE_IN
                                         AND  f.no_cont = a.NO_CONTAINER
                                         group by name_yd ) ELSE
                                         (SELECT e.name_yd || ' - ' || e.no_request name_yd
                                          FROM bil_delspjm_h e, bil_delspjm_d f
                                         WHERE e.id_del = f.id_del
                                         AND  e.vessel = a.VESSEL
                                         AND  e.voy_in = a.VOYAGE_IN
                                         AND  f.no_cont = a.NO_CONTAINER
                                         group by name_yd, no_request )  END NAME_YD
                                FROM M_CYC_CONTAINER@DBINT_LINK a                                    
                                     LEFT JOIN M_VSB_VOYAGE@DBINT_LINK c
                                        ON     a.VESSEL = c.VESSEL
                                           AND a.VOYAGE_IN = c.voyage_in
                                           AND a.VOYAGE_OUT = c.voyage_out
                               WHERE a.NO_CONTAINER LIKE '$no_cont%'
                           )
                     WHERE ROWNUM < 5
                     --and ACTIVE = 'Y'";
$result = $db->query($query);
$row = $result->getAll();

//print_r($query);die;

echo json_encode($row);
?>