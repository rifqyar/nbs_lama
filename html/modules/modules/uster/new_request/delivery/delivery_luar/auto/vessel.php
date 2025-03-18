<?php

$nama			= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
//$query 			= "select a.NAMA_VESSEL, b.NO_BOOKING, b.VOYAGE from vessel a, voyage b WHERE a.KODE_VESSEL = b.KODE_VESSEL AND a.NAMA_VESSEL LIKE '%$nama%' ";
$query			= "select 	a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING, 	   
							TO_DATE(a.tgl_jam_berangkat,'dd/mm/RRRR') TGL_BERANGKAT,TO_DATE(a.tgl_stacking) TGL_STACKING, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT,
						a.NM_PELABUHAN_ASAL, a.NM_PELABUHAN_TUJUAN
					from v_booking_stack_tpk a
					where a.NM_KAPAL LIKE '%$nama%' 
						and to_timestamp(sysdate) between to_timestamp (a.TGL_STACKING)
									and to_timestamp (a.DOC_CLOSING_DATE_DRY)";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
echo json_encode($row);


?>