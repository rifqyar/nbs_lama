<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('nota_list.htm');
	
	$db 	= getDB("storage");
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	$status_req	= $_POST["status_req"]; 
	$id_menu2 	= $_POST['id_menu2'];
	
	$jum = count($id_menu2);
	$orderby = 'ORDER BY ';
	for($i=0;$i<count($id_menu2);$i++){
		$orderby .= $id_menu2[$i];
		if($i != $jum-1){
			$orderby .= ",";
		}		
	}
	 
	
	
	if($jum == 1){
		$orderby = " ";
	}
	
	$query_list_ = "select * from (select a.no_container, b.size_, b.type_, 
				case when a.tgl_realisasi is null then 'FCL'
                else 'MTY'
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
                where a.status_req is null and ns.status <> 'BATAL' 
                and a.aktif = 'Y'
                order by a.no_container ) q
                where q.perp_ke = q.max_perp and TRUNC(TO_DATE(q.tgl_approve,'dd-mm-yy')) BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy/mm/dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy/mm/dd')) 
				$orderby";
				
	
	
	//echo $tgl_awal;die;
	
	
	
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("jenis",$jenis);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
