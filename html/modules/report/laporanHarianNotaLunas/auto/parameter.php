<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();

$query			= "/* Formatted on 12/16/2012 7:24:01 PM (QP5 v5.163.1008.3004) */
					SELECT a.NO_REQ_ANNE,
                           a.NO_CONTAINER,
                           a.SIZE_CONT,
                           (SELECT ISO_CODE
                              FROM MASTER_ISO_CODE b
                             WHERE     a.SIZE_CONT = b.SIZE_
                                   AND REGEXP_REPLACE (a.TYPE_CONT, '[[:space:]]', '') = B.TYPE_
                                   AND ROWNUM = 1)
                              ISO_CODE,
                           (SELECT h_iso
                              FROM MASTER_ISO_CODE b
                             WHERE     a.SIZE_CONT = b.SIZE_
                                   AND REGEXP_REPLACE (a.TYPE_CONT, '[[:space:]]', '') = B.TYPE_
                                   AND ROWNUM = 1)
                              H_ISO,
                           a.TYPE_CONT,
                           a.STATUS_CONT,
                           a.HZ,
                           a.PEL_TUJ,
                           a.VESSEL,
                           CONCAT(CONCAT(CONCAT(CONCAT(' [ ', REGEXP_REPLACE (a.VOYAGE_IN, '[[:space:]]', '')), ' / '),REGEXP_REPLACE (b.VOYAGE_OUT, '[[:space:]]', '')), ' ] ') VOYAGE_IN,
                           b.VOYAGE_OUT,
                           a.PEL_ASAL,
                           b.ID_PEL_ASAL,
                           b.ID_PEL_TUJ,
                           b.NO_UKK,
                           b.NO_BOOKING,
                           b.RTA, 
                           b.RTD,
                           NVL(c.PLACEMENT_DATE,'') PLACEMENT_DATE
                      FROM TR_NOTA_ANNE_ICT_D a, TR_NOTA_ANNE_ICT_H b, YD_PLACEMENT_YARD c
                     WHERE REGEXP_REPLACE (a.NO_REQ_ANNE, '[[:space:]]', '') =
                     REGEXP_REPLACE (b.NO_REQ_ANNE, '[[:space:]]', '')
                     --AND a.FLAG_USE = 0 
                     AND a.NO_CONTAINER = c.NO_CONTAINER(+)
                     AND a.NO_CONTAINER LIKE upper('$no_cont%')
                     AND rownum <=5";

$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>