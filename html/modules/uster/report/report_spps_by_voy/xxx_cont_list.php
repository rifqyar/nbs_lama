<?php
	$tl = xliteTemplate("cont_liist.htm");
	$db = getDB("storage");
	$no_ukk = $_POST["NO_UKK"];
	$nm_kapal = $_POST["NM_KAPAL"];
	$voyage = $_POST["VOYAGE"];
	$kegiatan = $_POST["KEGIATAN"];
	$no_booking = $_POST["NO_BOOKING"];
	if($kegiatan == "stripping"){
			$query = "select * from (select mc.no_container, mc.size_, cs.no_request, rs.tgl_request, mu1.nama_lengkap nm_request, vm.nm_pbm, cs.commodity,
            case when rs.status_req = 'PERP'
            then cs.start_perp_pnkn
            else cs.tgl_bongkar
            end as tgl_awal, 
            case when rs.status_req = 'PERP'
            then cs.end_stack_pnkn
            else case when cs.tgl_selesai is null
                    then cs.tgl_bongkar+4 
                    else cs.tgl_selesai 
                    end
            end as tgl_akhir,
            case when rs.status_req = 'PERP'
            then 'PERP'
            else 'NEW'
            end as STATUS,
            ns.lunas, cs.tgl_realisasi, mu2.nama_lengkap nm_realisasi, bg.tgl_in tgl_gate, hc.kegiatan
            from container_stripping cs join master_container mc
            on cs.no_container = mc.no_container join request_stripping rs on cs.no_request = rs.no_request
            join nota_stripping ns on rs.no_request = ns.no_request 
            join v_mst_pbm vm on rs.kd_consignee = vm.kd_pbm and vm.kd_cabang = '05'
            left join border_gate_in bg on bg.no_container = cs.no_container and bg.no_request = rs.no_request_receiving
            join history_container hc on hc.no_container = cs.no_container and hc.no_request = cs.no_request and kegiatan = 'REALISASI STRIPPING' 
            left join master_user mu1 on rs.id_user = mu1.id
            left join master_user mu2 on cs.id_user_realisasi = mu2.id
            left join  petikemas_cabang.ttd_bp_cont b on b.cont_no_bp = mc.no_container and b.bp_id = hc.no_booking
            join petikemas_cabang.ttm_bp_cont c on b.bp_id = c.bp_id where c.no_ukk = '$no_ukk'   
            AND CS.STATUS_REQ IS NULL ORDER BY mc.NO_CONTAINER, rs.tgl_request) q
            --where q.kegiatan = 'REALISASI STRIPPING'
			";
			
	} else {
			$query = "select q.*, bg.tgl_in tgl_gate from (select  cs.no_container, mc.size_, rs.no_request, rs.tgl_request, mu1.nama_lengkap nm_request , vm.nm_pbm, 
                        cs.start_stack tgl_awal, case when cs.status_req = 'PERP' THEN 
                        cs.end_stack_pnkn
                        else
                        cs.start_perp_pnkn end tgl_akhir, ns.lunas, cs.tgl_realisasi, mu2.nama_lengkap nm_realisasi, cs.commodity,
                        rd.no_request no_request_delivery
                        from container_stuffing cs
                        join master_container mc on cs.no_container = mc.no_container 
                        join request_stuffing rs on cs.no_request = rs.no_request
                        join nota_stuffing ns on rs.no_request = ns.no_request
                        left join master_user mu1 on rs.id_user = mu1.id
                        left join master_user mu2 on cs.id_user_realisasi = mu2.id
                        left join history_container hc on cs.no_request = hc.no_request and cs.no_container = hc.no_container and hc.kegiatan = 'REALISASI STUFFING'                                                
                        join v_mst_pbm vm on rs.kd_consignee = vm.kd_pbm and vm.kd_cabang = '05'
                        left join request_delivery rd on rs.no_booking = rd.no_booking                         
                        inner join container_delivery cd on rd.no_request = cd.no_request and cd.no_container = cs.no_container                          
                        and rs.no_booking = '$no_booking'
                        where ns.status not in ('BATAL') and cs.status_req is null or cs.status_req = 'PERP'
                        order by no_container) q left join border_gate_out bg on q.no_container = bg.no_container and q.no_request_delivery = bg.no_request
                        union all
                        select c.no_container, mc.size_, r.no_request, r.tgl_request, mu.nama_lengkap nm_request , ns.emkl, c.start_stack tgl_awal, case when c.status_req = 'PERP' THEN 
                        c.end_stack_pnkn
                        else
                        c.start_perp_pnkn end tgl_akhir, ns.lunas, 
                        c.tgl_realisasi, mu1.nama_lengkap nm_realisasi , c.commodity, '' no_request_delivery, to_date('') tgl_gate
                        from container_stuffing c join request_stuffing r on c.no_request = r.no_request join master_container mc on c.no_container = mc.no_container 
                        join nota_stuffing ns on r.no_request = ns.no_request join master_user mu on r.id_user = mu.id left join master_user mu1 on mu1.id = c.id_user_realisasi where r.no_booking = '$no_booking' 
                        and (c.status_req is null or c.status_req = 'PERP')
                        and no_container not in (select q.no_container from (select  cs.no_container, mc.size_, rs.no_request, rs.tgl_request, mu1.nama_lengkap nm_request , vm.nm_pbm, 
                        cs.start_stack tgl_awal, cs.start_perp_pnkn tgl_akhir, ns.lunas, cs.tgl_realisasi, mu2.nama_lengkap nm_realisasi, cs.commodity,
                        rd.no_request no_request_delivery
                        from container_stuffing cs
                        join master_container mc on cs.no_container = mc.no_container 
                        join request_stuffing rs on cs.no_request = rs.no_request
                        join nota_stuffing ns on rs.no_request = ns.no_request
                        left join master_user mu1 on rs.id_user = mu1.id
                        left join master_user mu2 on cs.id_user_realisasi = mu2.id
                        left join history_container hc on cs.no_request = hc.no_request and cs.no_container = hc.no_container and hc.kegiatan = 'REALISASI STUFFING'                                                
                        join v_mst_pbm vm on rs.kd_consignee = vm.kd_pbm and vm.kd_cabang = '05'
                        left join request_delivery rd on rs.no_booking = rd.no_booking                         
                        inner join container_delivery cd on rd.no_request = cd.no_request and cd.no_container = cs.no_container                          
                        and rs.no_booking = '$no_booking'
                        where ns.status not in ('BATAL') and cs.status_req is null or cs.status_req = 'PERP'
                        order by no_container) q left join border_gate_out bg on q.no_container = bg.no_container and q.no_request_delivery = bg.no_request)";
	}
	$r_query = $db->query($query); 
	$row_q = $r_query->getAll();
	
	
	$tl->assign('row_q', $row_q);
	$tl->assign('kapal', $nm_kapal);
	$tl->assign('voyage', $voyage);
	$tl->assign('kegiatan', $kegiatan);
	
	$tl->renderToScreen();
	
?>