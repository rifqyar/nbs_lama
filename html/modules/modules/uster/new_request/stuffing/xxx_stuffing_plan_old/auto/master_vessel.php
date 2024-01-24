<?php

$nama_kapal		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
//$query 		= "select a.NAMA_VESSEL, b.NO_BOOKING, b.VOYAGE from vessel a, voyage b WHERE a.KODE_VESSEL = b.KODE_VESSEL AND a.NAMA_VESSEL LIKE '%$nama%' ";

// $query			= "select 	a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING, 	   
							// TO_DATE(a.tgl_jam_berangkat,'dd/mm/RRRR') TGL_BERANGKAT,TO_DATE(a.tgl_stacking) TGL_STACKING, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT  
					// from petikemas_cabang.v_booking_stack_tpk a 
					// where a.NM_KAPAL LIKE '%$nama_kapal%' 
						// and to_date(A.TGL_JAM_BERANGKAT) >=add_months(sysdate,-1)";


//query di bawah diaktifin kalo dah di prod, soalnya data di dev kapale lama2 jadi di baypas						
$query			= "select 	a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING, 	   
							TO_DATE(a.tgl_jam_berangkat,'dd/mm/RRRR') TGL_BERANGKAT,TO_DATE(a.tgl_stacking) TGL_STACKING, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT  
					from petikemas_cabang.v_booking_stack_tpk a
					where a.NM_KAPAL LIKE '%$nama_kapal%' 
						and sysdate between to_timestamp (a.TGL_JAM_CLOSE-2)
									and to_timestamp (a.TGL_JAM_CLOSE)";
						
$result		= $db->query($query);
$row		= $result->getAll();	
//echo $query;
echo json_encode($row);


?>