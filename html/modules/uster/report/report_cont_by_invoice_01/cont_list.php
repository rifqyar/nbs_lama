<?php
	$tl = xliteTemplate("cont_list.htm");
	$db = getDB("storage");
	$no_nota = $_POST["NO_NOTA"];
	$no_request = $_POST["NO_REQUEST"];
	$kegiatan = $_POST["KEGIATAN"];
	
	if($kegiatan == 'DELIVERY_MTY' || $kegiatan == 'RELOKASI_MTY_KE_TPK' || $kegiatan == 'RELOKASI_TPK_EKS_STUFFING' || $kegiatan == 'PENUMPUKAN_SP2'){
		$query = "select cd.no_container, cd.start_stack tgl_awal, cd.tgl_delivery tgl_akhir, mc.size_, mc.type_, cd.status, cd.hz, cd.komoditi, cd.berat
				from container_delivery cd inner join master_container mc 
				on cd.no_container = mc.no_container
				where no_request = '$no_request'";
	}
	else if($kegiatan == 'STRIPPING' || $kegiatan == 'RELOKASI_MTY_EKS_STRIPPING'){
		$query = "select cd.no_container, cd.tgl_bongkar tgl_awal, case when cd.tgl_selesai is null then cd.tgl_bongkar+4 else cd.tgl_selesai end tgl_akhir,
				mc.size_, mc.type_, 'FCL' status , cd.hz, cd.commodity komoditi, '22000' berat
				from container_stripping cd inner join master_container mc 
				on cd.no_container = mc.no_container
				where no_request = '$no_request'";
	}
	else if($kegiatan == 'STUFFING'){
		$query = "select cd.no_container, cd.start_stack tgl_awal, cd.start_perp_pnkn tgl_akhir,
				mc.size_, mc.type_, 'MTY' status , cd.hz, cd.commodity komoditi, cd.berat
				from container_stuffing cd inner join master_container mc 
				on cd.no_container = mc.no_container
				where no_request = '$no_request'";
	}
	else if($kegiatan == 'RECEIVING'){
		$query = "select cd.no_container, '' tgl_awal, '' tgl_akhir,
				mc.size_, mc.type_, 'MTY' status , case when cd.hz is null then 'N' else cd.hz end hz,
				cd.komoditi , case when mc.size_ = '20' then '2000' else '4000' end as berat
				from container_receiving cd inner join master_container mc 
				on cd.no_container = mc.no_container
				where no_request = '$no_request'";
	}
	else if($kegiatan == 'BATAL_MUAT'){
		$query = "select cd.no_container, cd.start_pnkn tgl_awal , cd.end_pnkn tgl_akhir,
                mc.size_, mc.type_, cd.status status , 'N' hz,
                '' komiditi , case when mc.size_ = '20' then '2000' else '4000' end as berat
                from container_batal_muat cd inner join master_container mc 
                on cd.no_container = mc.no_container
				where no_request = '$no_request'";
	}
	
	
	$r_query = $db->query($query); 
	$row_q = $r_query->getAll();
	
	
	$tl->assign('row_q', $row_q);
	$tl->assign('kapal', $nm_kapal);
	$tl->assign('voyage', $voyage);
	
	$tl->renderToScreen();
	
?>