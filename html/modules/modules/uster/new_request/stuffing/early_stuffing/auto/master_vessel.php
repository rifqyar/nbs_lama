<?php
//echo "tes";die;
$nama_kapal		= strtoupper($_GET["term"]);
$hari			= $_GET["hari"];

$db 			= getDB("storage");
	

// where to_date(A.TGL_JAM_BERANGKAT) >=add_months(sysdate,-1)
//Ada variable hari, untuk menentukan batas open stack

$query			= "SELECT 	a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, 
							a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING, 	   
							TO_DATE(a.tgl_jam_berangkat,'dd/mm/RRRR') TGL_BERANGKAT,
							TO_DATE(a.tgl_stacking) TGL_STACKING, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT  
					FROM v_booking_stack_early a
					WHERE a.NM_KAPAL LIKE '%$nama_kapal%' 
						AND SYSDATE BETWEEN TO_TIMESTAMP (a.DOC_CLOSING_DATE_DRY-(3+TO_NUMBER('$hari')))
										AND TO_TIMESTAMP (a.DOC_CLOSING_DATE_DRY)";

								
									
$result		= $db->query($query);
$row		= $result->getAll();	
//echo $query;die;

echo json_encode($row);
// echo json_encode($hari);

?>