<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('home.htm');
	
	$db 	= getDB("storage"); 

	$query = "select * from (select a.no_container, b.size_, b.type_, 
				case when a.tgl_realisasi is null then 'MTY'
                else 'FCL'
                end status_, a.no_request, a.tgl_approve, a.tgl_realisasi, ns.emkl, s.consignee_personal,
                case when s.status_req = 'PERP' then a.end_stack_pnkn
                else case when a.tgl_selesai is null then a.tgl_bongkar+4 else a.tgl_selesai end 
                end active_to,
                case when s.status_req = 'PERP' then a.start_perp_pnkn
                else a.tgl_bongkar end start_to,
                (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1) LAMA, 
                case when a.tgl_realisasi is null then 'Belum Realisasi'
                else 'Sudah Realisasi'
                end status,
                case when s.status_req = 'PERP' then (TO_DATE(a.end_stack_pnkn,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1)
                else case when a.tgl_selesai is null then  (TO_DATE(a.tgl_bongkar+4,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1) 
                else  (TO_DATE(a.tgl_selesai,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1) end end masa_aktif,
               CASE WHEN  a.tgl_realisasi is null THEN 
                    case when s.status_req = 'PERP' then (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.end_stack_pnkn,'dd/mm/yyyy'))
                    else case when a.tgl_selesai is null then  (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.tgl_bongkar+4,'dd/mm/yyyy')) 
                    else  (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.tgl_selesai,'dd/mm/yyyy')) end end 
               ELSE 0 END perpanjangan, perp_ke, (SELECT max(perp_ke) FROM request_stripping rs join container_stripping cs on rs.no_request = cs.no_request where cs.no_container = a.no_container) max_perp
                from container_stripping a join master_container b
                on a.no_container = b.no_container
                join request_stripping s on a.no_request = s.no_request
                join nota_stripping ns on s.no_request = ns.no_request          
                where a.status_req is null and ns.status <> 'BATAL' --and a.tgl_realisasi is not null
                order by a.no_container ) q
                where q.perp_ke = q.max_perp";
				
	$start	=  $db->query($query); 
	$data	= $start->getAll();
	$tl->assign("row_list",$data);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
