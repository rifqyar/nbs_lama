<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('gate_list.htm');
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	$shift		= $_POST["shift"]; 
	$size		= $_POST["size"]; 
	$status		= $_POST["status"];
	
	if ($shift == NULL){
		$query_shift = '';
	} else {
		if ($shift == 1){
			$query_shift = "and to_date(substr(to_char(a.tgl_in,'dd/mm/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss') between to_date('08:00:00','hh24:mi:ss')
and to_date('16:00:00','hh24:mi:ss')";
		} else if ($shift == 2){
			$query_shift = "and to_date(substr(to_char(a.tgl_in,'dd/mm/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss') between to_date('16:00:00','hh24:mi:ss')
and to_date('00:00:00','hh24:mi:ss')";
		} else if ($shift == 3){
			$query_shift = "and to_date(substr(to_char(a.tgl_in,'dd/mm/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss') between to_date('00:00:00','hh24:mi:ss')
and to_date('08:00:00','hh24:mi:ss')";
		} else {
			$query_shift = "";
		}
	}
	
	if ($size == NULL){
		$query_size = '';
	} else {
		if ($size == 20){
			$query_size = "and b.size_ = '20'";
		} else if ($size == 40){
			$query_size = "and b.size_ = '40'";
		} else if ($size == 45){
			$query_size = "and b.size_ = '45'";
		} else {
			$query_size = '';
		}
	}
	
	if ($status == NULL){
		$query_status = '';
	} else {
		if ($status == 'FCL'){
			$query_status = "and a.status = 'FCL'";
		} else if ($status == 'MTY'){
			$query_status = "and a.status = 'MTY'";
		} else if ($status == 'LCL'){
			$query_status = "and a.status = 'LCL'";
		} else {
			$query_status = "";
		}
	}
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	
	if ($jenis == 'GATI'){
	$query_list_ 	= "select a.no_container, a.no_request, TO_CHAR(a.tgl_in,'dd/mm/rrrr hh:ii:ss') tgl_in, a.nopol, a.NO_SEAL, f.username,
						b.size_, b.type_, a.status, c.nm_pbm, d.nama_yard, 'GATE IN' kegiatan
						from gate_in a, master_container b, v_mst_pbm c, yard_area d, request_receiving e, master_user f
						where a.no_container = b.no_container
						and a.no_request = e.no_request
						and e.kd_consignee = c.kd_pbm
						and a.id_yard = d.id
						and a.id_user = f.id
						and to_date(a.tgl_in,'dd/mm/rrrr') between to_date('$tgl_awal', 'dd/mm/rrrr') and to_date('$tgl_akhir', 'dd/mm/rrrr') " . $query_shift.' '.$query_size.' '.$query_status.' '.
						"order by a.tgl_in desc";
	} else if ($jenis == 'GATO'){
	$query_list_ 	= "select a.no_container, a.no_request,TO_CHAR(a.tgl_in,'dd/mm/rrrr hh:ii:ss') tgl_in, a.nopol, a.NO_SEAL, f.username,
						b.size_, b.type_, a.status, c.nm_pbm, d.nama_yard, 'GATE OUT' kegiatan
						from gate_out a, master_container b, v_mst_pbm c, yard_area d, request_delivery e, master_user f
						where a.no_container = b.no_container
						and a.no_request = e.no_request
						and e.kd_emkl = c.kd_pbm
						and a.id_yard = d.id
						and a.id_user = f.id
						and to_date(a.tgl_in,'dd/mm/rrrr') between to_date('$tgl_awal', 'dd/mm/rrrr') and to_date('$tgl_akhir', 'dd/mm/rrrr') " . $query_shift.' '.$query_size.' '.$query_status.' '.
						"order by a.tgl_in desc";
	
	} else {
	$query_list_ 	= "SELECT a.no_container,a.no_request, a.tgl_in, a.nopol, a.NO_SEAL, a.username,
                       a.size_, a.type_, a.status,a.nm_pbm, a.nama_yard, a.kegiatan FROM (select a.no_container,a.no_request, TO_CHAR(a.tgl_in,'dd/mm/rrrr hh:ii:ss') tgl_in, a.nopol, a.NO_SEAL, f.username,
                        b.size_, b.type_, a.status, c.nm_pbm, d.nama_yard, 'GATE IN' kegiatan
                        from gate_in a, master_container b, v_mst_pbm c, yard_area d, request_receiving e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_consignee = c.kd_pbm
                        and a.id_yard = d.id
                        and a.id_user = f.id
                        and to_date(a.tgl_in,'dd/mm/rrrr') between to_date('$tgl_awal', 'dd/mm/rrrr') and to_date('$tgl_akhir', 'dd/mm/rrrr') ". $query_shift.' '.$query_size.' '.$query_status.' '.  " UNION
                        select a.no_container,a.no_request, TO_CHAR(a.tgl_in,'dd/mm/rrrr hh:ii:ss') tgl_in, a.nopol, a.NO_SEAL, f.username,
                        b.size_, b.type_, a.status, c.nm_pbm, d.nama_yard, 'GATE OUT' kegiatan
                        from gate_out a, master_container b, v_mst_pbm c, yard_area d, request_delivery e, master_user f
                        where a.no_container = b.no_container
                        and a.no_request = e.no_request
                        and e.kd_emkl = c.kd_pbm
                        and a.id_yard = d.id
                        and a.id_user = f.id 
                        and to_date(a.tgl_in,'dd/mm/rrrr') between to_date('$tgl_awal', 'dd/mm/rrrr') and to_date('$tgl_akhir', 'dd/mm/rrrr') ". $query_shift.' '.$query_size.' '.$query_status.' '. " ) a
                         order by a.tgl_in desc";
	}
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
