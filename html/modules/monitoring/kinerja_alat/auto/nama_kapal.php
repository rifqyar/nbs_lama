<?php
$nama = strtoupper($_GET["term"]);

$db 			= getDB();

//debug($nama_kapal);die;
	
$query 			= "SELECT NO_UKK,
					      NM_KAPAL,
					      VOYAGE_IN,
						  VOYAGE_OUT,
						  NM_PEMILIK,
						  TO_CHAR(TGL_JAM_TIBA,'YYYYMMDD') AS SEQ
					FROM RBM_H
                    WHERE NM_KAPAL LIKE '%$nama%' OR VOYAGE_IN LIKE '%$nama%'
					ORDER BY SEQ DESC";
$result			= $db->query($query);
$row			= $result->getAll();	

//debug($row);die;

echo json_encode($row);


?>