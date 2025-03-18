<?php
$nama = strtoupper($_GET["term"]);

$db 			= getDB('dbint');

//debug($nama_kapal);die;
	
$query 			= "SELECT id_vsb_voyage NO_UKK,
					      vessel NM_KAPAL,
					      VOYAGE_IN,
						  VOYAGE_OUT,
						  operator_name NM_PEMILIK
						  
					FROM m_vsb_voyage
                    WHERE vessel LIKE '%$nama%' OR VOYAGE_IN LIKE '%$nama%'
					ORDER BY id_vsb_voyage DESC";
$result			= $db->query($query);
$row			= $result->getAll();	

//debug($row);die;

echo json_encode($row);


?>