<?php
$no_cont		= strtoupper($_GET["term"]);
$db 			= getDB('storage');

$query 			= " SELECT
                            A.NO_CONTAINER,
                            A.NO_REQUEST,
                            B.SIZE_,
                            B.TYPE_,
                            D.O_VESSEL AS VESSEL,
                            D.O_VOYIN AS VOYAGE_IN,
                            D.O_VOYOUT AS VOYAGE_OUT,
                            B.LOCATION,
                            CASE WHEN C.LUNAS='YES' THEN 'Sudah Bayar'
                                 WHEN C.LUNAS = 'NO' THEN 'Belum Bayar'
                            END AS STATUS,
                            D.O_REQNBS AS ID_UREQ
                    FROM
                            container_stuffing A
                    LEFT JOIN
                            MASTER_CONTAINER B
                            ON (TRIM(A.NO_CONTAINER) = TRIM(B.NO_CONTAINER))
                    LEFT JOIN
                            NOTA_STUFFING C ON
                            (TRIM(A.NO_REQUEST) = TRIM(C.NO_REQUEST))
                    LEFT JOIN
                            REQUEST_STUFFING D ON
                            TRIM(A.NO_REQUEST) = TRIM(D.NO_REQUEST)
                    WHERE
                            A.NO_CONTAINER = '$no_cont'";
//echo $query; die();

$result			= $db->query($query);
$row			= $result->getAll();


//print_r($row);

echo json_encode($row);

die();

?>
