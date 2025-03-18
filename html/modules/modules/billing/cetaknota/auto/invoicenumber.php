<?php

$inv			= TRIM(strtoupper($_GET["term"]));
$db 			= getDB();	


$query 			= "SELECT a.no_nota,
						   a.no_request id_req,
						   a.cust_name,
						   TO_CHAR (a.kredit, '999,999,999,999') kredit,
						   b.JENIS,
						   b.VESSEL,
						   b.VOYIN,
						   b.VOYOUT,
						   CASE WHEN a.DATE_PAID IS NULL THEN 'N' ELSE 'Y' END PAYMENT
					  FROM TTH_NOTA_ALL2 a,
						   (SELECT id_req no_req,
								   'ANNE' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_RECEIVING_H
							UNION
							SELECT id_req no_req,
								   'SP2' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_DELIVERY_H
							UNION
							SELECT id_request no_req,
								   'HICO' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_HICOSCAN
							UNION
							SELECT id_req no_req,
								   'TRANS' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_TRANSHIPMENT_H
							UNION
							SELECT id_request no_req,
								   'BH' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM BH_REQUEST
							UNION
							SELECT id_req no_req,
								   'REEX' JENIS,
								   VESSEL,
								   VOYAGE_IN VOYIN,
								   VOYAGE_OUT VOYOUT
							  FROM REQ_STACKEXT_H
							UNION
							SELECT id_req no_req,
								   'RXP' JENIS,
								   VESSEL,
								   VOYAGE VOYIN,
								   VOYAGE_DT VOYOUT
							  FROM REQ_REEXPORT_H) b
					 WHERE TRIM (a.NO_NOTA) = TRIM ('".$inv."')
						   AND TRIM (a.no_request) = TRIM (b.no_req)";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>