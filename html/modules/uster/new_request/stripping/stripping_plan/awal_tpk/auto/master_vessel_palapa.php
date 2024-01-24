<?php

$nama_kapal		= strtoupper($_GET["term"]);

$db 			= getDB("dbint");
	

$query 			= "SELECT
                        TML_CD,
                        VESSEL_CODE,
                        OPERATOR_NAME,
                        OPERATOR_ID,
                        VESSEL,
                        VOYAGE_IN,
                        VOYAGE_OUT,
                        ID_VSB_VOYAGE,
                        CALL_SIGN,
                        TO_CHAR (TO_DATE (ATD, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi:SS') ATD,
                        TO_CHAR (TO_DATE (OPEN_STACK, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi:SS') OPEN_STACK,
                        TO_CHAR (TO_DATE (CLOSSING_DOC, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi:SS') CLOSING_TIME_DOC,
                        TO_CHAR (TO_DATE (ETA, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi:SS') ETA,
                        TO_CHAR (TO_DATE (ETD, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi:SS') ETD,
                        TO_CHAR (TO_DATE (ATA, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi:SS') ATA,
                        ID_POL,
                        POL,
                        ID_POD,
                        POD,
                        CONTAINER_LIMIT,
                        TO_CHAR (TO_DATE (CLOSSING_TIME, 'YYYYMMDDHH24MISS'), 'DD-MM-YYYY HH24:Mi:SS') CLOSING_TIME,
                        VOYAGE
                    FROM
                        M_VSB_VOYAGE_PALAPA
                    WHERE
                        TML_CD = 'PNK'
                        --AND SYSDATE BETWEEN TO_DATE(OPEN_STACK, 'YYYYMMDDHH24MISS') AND TO_DATE(CLOSSING_TIME, 'YYYYMMDDHH24MISS')
                        AND (VESSEL LIKE '%$nama_kapal%'
                        OR VOYAGE_IN LIKE '%$nama_kapal%'
                        OR VOYAGE_OUT LIKE '%$nama_kapal%'
                        OR VOYAGE LIKE '%$nama_kapal%')
                    ORDER BY VESSEL, VOYAGE_IN DESC";
$result		= $db->query($query);
$row		= $result->getAll();	
// echo $query;
echo json_encode($row);


?>