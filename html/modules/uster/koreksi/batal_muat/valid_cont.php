<?php
$db = getDB("storage");
$no_req = $_POST['no_req'];
$no_cont = $_POST['no_cont'];
$jenis_bm = $_POST['jenis_bm'];
$newvessel = $_POST['newvessel'];
//cek inputan
	if($jenis_bm == 1){
	$query 			= "select mc.no_container, cs.no_request, mc.type_, mc.size_, hc.status_cont, mc.no_booking, vb.nm_kapal 
						from master_container mc inner join
						container_stuffing cs on mc.no_container = cs.no_container
						inner join history_container hc on cs.no_request = hc.no_request and
						cs.no_container = hc.no_container
						inner join v_booking_stack_tpk vb on mc.no_booking = vb.no_booking
						where 
						--cs.tgl_realisasi is not null and
						hc.kegiatan = 'REALISASI STUFFING'
						and mc.no_container like '%$no_cont%' and cs.no_request = '$no_req'";
	} else if($jenis_bm == 2) {
	$query			= "select mc.no_container, rd.no_req_ict, cd.no_request, mc.type_, mc.size_, hc.status_cont, 
						mc.no_booking, vb.nm_kapal, hc.kegiatan
						from master_container mc inner join
						container_delivery cd on mc.no_container = cd.no_container
						inner join history_container hc on cd.no_request = hc.no_request and
						cd.no_container = hc.no_container
						inner join request_delivery rd on cd.no_request = rd.no_request
						inner join v_booking_stack_tpk vb on mc.no_booking = vb.no_booking
						where cd.aktif = 'Y' and mc.location = 'IN_YARD' and cd.no_container like '%$no_cont%' and cd.no_request = '$no_req'
						and rd.delivery_ke = 'TPK' and hc.kegiatan in ('REQUEST DELIVERY','PERP DELIVERY')";
	} else if($jenis_bm == 3){
	$query 			= "select mc.no_container, cs.no_request, mc.type_, mc.size_, hc.status_cont, mc.no_booking, vb.nm_kapal, cs.end_stack_pnkn
						from master_container mc inner join
						container_stuffing cs on mc.no_container = cs.no_container
						inner join history_container hc on cs.no_request = hc.no_request and
						cs.no_container = hc.no_container
						inner join v_booking_stack_tpk vb on mc.no_booking = vb.no_booking
						where mc.no_container like '%$no_cont%' and cs.no_request = '$no_req'";
	}

//$result			= $db->query($query);
//$cek			= $result->recordCount();
$cek =  1;
$qvalidves = "select count(1) cek from request_stuffing a, container_stuffing b
            where a.no_request = b.no_request and b.no_container = '$no_cont' and a.no_booking = '$newvessel' and a.no_request = '$no_req'";
$rvalidves = $db->query($qvalidves)->fetchRow();
if($rvalidves['CEK'] > 0){
    echo 'X';
    die;
}
else {
    if($cek == 0){
        echo 'T';
        die;
    }
    else{
        echo 'Y';
        die;
    }
}
?>