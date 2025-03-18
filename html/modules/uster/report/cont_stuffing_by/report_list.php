<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('report_list.htm');
	
	$db 	= getDB("storage");
	
	if($_POST["option_status"] == 1){
		$query_list_ = "select cd.no_container, mc.size_, mc.type_, cd.status, rd.no_request, bg.tgl_in, cd.no_req_stuffing,
						rd.kd_emkl, v_mst_pbm.nm_pbm, rd.no_booking, vb.nm_kapal
						from container_delivery cd inner join request_delivery rd on cd.no_request = rd.no_request
						join border_gate_out bg on cd.no_container = bg.no_container and cd.no_request = bg.no_request
						join master_container mc on cd.no_container = mc.no_container
						join v_mst_pbm on rd.kd_emkl = v_mst_pbm.kd_pbm
						join v_booking_stack_tpk vb on rd.no_booking = vb.no_booking
						where rd.jn_repo = 'EKS_STUFFING' and cd.aktif = 'T' AND KELUAR = 'Y'
						and rd.no_request in (select no_request from border_gate_out)";
						
	} else if($_POST["option_status"] == 2){
		$query_list_ = "select cd.no_container, mc.size_, mc.type_, cd.status, rd.no_request, rd.tgl_request, 
						cd.no_req_stuffing, rd.kd_emkl, v_mst_pbm.nm_pbm, rd.no_booking, vb.nm_kapal
						from container_delivery cd inner join request_delivery rd on cd.no_request = rd.no_request
						join master_container mc on cd.no_container = mc.no_container
						join v_mst_pbm on rd.kd_emkl = v_mst_pbm.kd_pbm
						join v_booking_stack_tpk vb on rd.no_booking = vb.no_booking
						where rd.jn_repo = 'EKS_STUFFING' and cd.aktif = 'Y' AND KELUAR = 'N'";
						
	} else if($_POST["option_status"] == 3) {
		$query_list = "select al.*,mc.size_, mc.type_, ba.name , pl.slot_, pl.row_, pl.tier_ from
					(select cs.no_container, cs.no_request, 'FCL' status
					from container_stuffing cs where cs.aktif='T' AND cs.tgl_realisasi is not null
					minus
					select no_container, no_req_stuffing, status
					from container_delivery where no_req_stuffing is not null) al
					join master_container mc on al.no_container = mc.no_container
					join placement pl on al.no_container = pl.no_container
					and pl.tgl_placement = (select max(tgl_update) from placement where no_container = al.no_container)
					join blocking_area ba on PL.ID_BLOCKING_AREA = ba.id";
	}
	
	
	
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
