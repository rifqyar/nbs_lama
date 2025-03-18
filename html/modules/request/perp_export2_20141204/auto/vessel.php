<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("dbint");	
$query 			= "/* Formatted on 01-Dec-13 7:34:00 AM (QP5 v5.163.1008.3004) */
					SELECT vessel,
						   voyage_in,
						   voyage_out,
							  SUBSTR (eta, 7, 2)
						   || '/'
						   || SUBSTR (eta, 5, 2)
						   || '/'
						   || SUBSTR (eta, 1, 4)
						   || ' '
						   || SUBSTR (eta, 9, 2)
						   || ':'
						   || SUBSTR (eta, 11, 2)
						   || ':'
						   || SUBSTR (eta, 13, 2)
							  eta,
							  SUBSTR (etd, 7, 2)
						   || '/'
						   || SUBSTR (etd, 5, 2)
						   || '/'
						   || SUBSTR (etd, 1, 4)
						   || ' '
						   || SUBSTR (etd, 9, 2)
						   || ':'
						   || SUBSTR (etd, 11, 2)
						   || ':'
						   || SUBSTR (etd, 13, 2)
							  etd,
							SUBSTR (first_etd, 7, 2)
						   || '/'
						   || SUBSTR (first_etd, 5, 2)
						   || '/'
						   || SUBSTR (first_etd, 1, 4)
						   || ' '
						   || SUBSTR (first_etd, 9, 2)
						   || ':'
						   || SUBSTR (first_etd, 11, 2)
						   || ':'
						   || SUBSTR (first_etd, 13, 2)
							  first_etd,
							  first_etd as string_frist_etd,
						   id_pol,
						   pol,
						   id_pod,
						   pod,
						   CASE when ata is not null then
							  SUBSTR (ata, 7, 2)
						   || '/'
						   || SUBSTR (ata, 5, 2)
						   || '/'
						   || SUBSTR (ata, 1, 4)
						   || ' '
						   || SUBSTR (ata, 9, 2)
						   || ':'
						   || SUBSTR (ata, 11, 2)
						   || ':'
						   || SUBSTR (ata, 13, 2)
						   else ''
						   end 
							  ata, 
							  CASE when atd is not null then
							  SUBSTR (atd, 7, 2)
						   || '/'
						   || SUBSTR (atd, 5, 2)
						   || '/'
						   || SUBSTR (atd, 1, 4)
						   || ' '
						   || SUBSTR (atd, 9, 2)
						   || ':'
						   || SUBSTR (atd, 11, 2)
						   || ':'
						   || SUBSTR (atd, 13, 2)
						   else ''
						   end 
							  atd,
							  operator_name 
					  FROM m_vsb_voyage
					  where vessel like '%$nama%' OR voyage_in like '%$nama%'";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>