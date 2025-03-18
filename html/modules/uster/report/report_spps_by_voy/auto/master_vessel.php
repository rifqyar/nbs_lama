<?php

$nama_kapal		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
//$query 		= "select a.NAMA_VESSEL, b.NO_BOOKING, b.VOYAGE from vessel a, voyage b WHERE a.KODE_VESSEL = b.KODE_VESSEL AND a.NAMA_VESSEL LIKE '%$nama%' ";

/*$query			= "select pkk.voyage_in, pkk.nm_kapal, pkk.no_ukk, ttm.no_booking from petikemas_cabang.v_pkk_cont pkk
					join petikemas_cabang.tth_cont_booking ttm on pkk.no_ukk = ttm.no_ukk 
					where pkk.kd_cabang = '05'
					and to_char(pkk.tgl_jam_tiba,'yyyy') = '2013' and pkk.nm_kapal like '%$nama_kapal%' or pkk.voyage_in like '%$nama_kapal%' 
					order by pkk.voyage_in desc";
					*/
				
$query			= "select voyage_in, nm_kapal, no_ukk, no_booking from v_pkk_cont
                    where kd_cabang = '05'
                    and nm_kapal like '%$nama_kapal%' or voyage_in like '%$nama_kapal%' 
                    order by tgl_jam_tiba DESC";
				
$result		= $db->query($query);
$row		= $result->getAll();	
//echo $query;
echo json_encode($row);


?>