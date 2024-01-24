<?php
$ship 			= $_GET["ship"];
$call_sign 		= $_GET["call_sign"];
$vin 			= $_GET["vin"];
$no_cont		= strtoupper($_GET["term"]);
$req			= ($_GET["req"]);
$tipe_req_cont 	= $_GET["tipe_req_cont"];
$db 			= getDB();


$query 			= " SELECT 
                            A.NO_CONTAINER, 
                            A.ID_REQ, 
                            A.SIZE_CONT, 
                            A.TYPE_CONT, 
                            A.STATUS_CONT, 
                            A.HZ,
                            A.VESSEL, 
                            A.VOYAGE_IN,
                            A.NO_UKK,
                            case when C.STATUS IS NULL then 'Belum Bayar'
                                 when C.STATUS IS NOT NULL THEN C.STATUS
                            end as STATUS, 
                            B.CONT_LOCATION 
                    FROM 
                            REQ_DELIVERY_D A
                    LEFT JOIN
                            M_CYC_CONTAINER@DBINT_LINK B
                            ON (A.NO_CONTAINER=B.NO_CONTAINER
                                   AND A.VESSEL = B.VESSEL
                                   AND A.VOYAGE_IN = B.VOYAGE_IN)
                 	LEFT JOIN  
                 			NOTA_DELIVERY_H C
                            ON (TRIM(A.ID_REQ) = TRIM(C.ID_REQ))         
                    WHERE 
                            A.NO_CONTAINER = '$no_cont'";
//echo $query; die();

$result			= $db->query($query);
$row			= $result->getAll();	


//print_r($row);

echo json_encode($row);

die();

?>