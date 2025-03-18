<?php

$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-MONITORING-MASA-STRIPPING-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$db 	= getDB("storage");
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	$status_req	= $_POST["status_req"]; 
	$id_menu2 	= $_POST['menu2'];
	
	$jum = count($id_menu2);
	$orderby = 'ORDER BY ';
	for($i=0;$i<count($id_menu2);$i++){
		$orderby .= $id_menu2[$i];
		if($i != $jum-1){
			$orderby .= ",";
		}		
	}
	
	// $query_list_ = "select * from (select a.no_container, b.size_, b.type_, 
				// case when a.tgl_realisasi is null then 'MTY'
                // else 'FCL'
                // end status_, a.no_request, TO_CHAR(a.tgl_approve,'dd/mm/yyyy') tgl_approve, TO_CHAR(a.tgl_realisasi,'dd/mm/yyyy') tgl_realisasi, ns.emkl, s.consignee_personal,
                // case when s.status_req = 'PERP' then TO_CHAR(a.end_stack_pnkn,'dd/mm/yyyy')
                // else case when a.tgl_selesai is null then TO_CHAR(a.tgl_bongkar+4,'dd/mm/yyyy') else TO_CHAR(a.tgl_selesai,'dd/mm/yyyy') end 
                // end active_to,
                // case when s.status_req = 'PERP' then TO_CHAR(a.start_perp_pnkn,'dd/mm/yyyy')
                // else TO_CHAR(a.tgl_bongkar,'dd/mm/yyyy') end start_to,
                // (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1) LAMA, 
                // case when a.tgl_realisasi is null then 'Belum Realisasi'
                // else 'Sudah Realisasi'
                // end status,
                // case when s.status_req = 'PERP' then (TO_DATE(a.end_stack_pnkn,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1)
                // else case when a.tgl_selesai is null then  (TO_DATE(a.tgl_bongkar+4,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1) 
                // else  (TO_DATE(a.tgl_selesai,'dd/mm/yyyy') - TO_DATE(a.tgl_approve,'dd/mm/yyyy')+1) end end masa_aktif,
               // CASE WHEN  a.tgl_realisasi is null THEN 
                    // case when s.status_req = 'PERP' then (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.end_stack_pnkn,'dd/mm/yyyy'))
                    // else case when a.tgl_selesai is null then  (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.tgl_bongkar+4,'dd/mm/yyyy')) 
                    // else  (TO_DATE(SYSDATE,'dd/mm/yyyy') - TO_DATE(a.tgl_selesai,'dd/mm/yyyy')) end end 
               // ELSE 0 END perpanjangan, perp_ke, (SELECT max(perp_ke) FROM request_stripping rs join container_stripping cs on rs.no_request = cs.no_request where cs.no_container = a.no_container) max_perp
                // from container_stripping a join master_container b
                // on a.no_container = b.no_container
                // join request_stripping s on a.no_request = s.no_request
                // join nota_stripping ns on s.no_request = ns.no_request          
                // where a.status_req is null and ns.status <> 'BATAL' 
                // order by a.no_container ) q
                // where q.perp_ke = q.max_perp and TRUNC(TO_DATE(q.tgl_approve,'dd-mm-yy')) BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy/mm/dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy/mm/dd')) 
				// $orderby";
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
	// echo $query_list_;die;			
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();

	

?>
			<center>	<h2> Laporan Masa Kegiatan Stripping Periode <?=$tgl_awal?> s/d <?=$tgl_akhir?></h2> </center>
           <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
		   
        	<tr style=" font-size:10pt">
            	<th class="grid-header">No.</th>
				<th class="grid-header">NO CONTAINER</th>
				<th class="grid-header">SIZE</th>
            	<th class="grid-header">TYPE</th>
                <th class="grid-header">STATUS</th>
                <th class="grid-header">NO REQUEST</th>
                <th class="grid-header">EMKL</th>
                <!--<th class="grid-header">CP</th>-->
                <th class="grid-header">START PNKN</th>
                <th class="grid-header">TGL APPROVE</th>
                <th class="grid-header">ACTIVE TO</th>
                <th class="grid-header">TGL REALISASI</th>
                <th class="grid-header">MASA AKTIF</th>
                <th class="grid-header">PERP</th>				
            </tr>
        	<?php $i=0; foreach($row_list as $rows){ $i++; ?>
        	<tr>
            	<td class="grid-cell" align="center"><font size="2px"><?=$i?></font></td>
				<td class="grid-cell" align="center"><font size="2px"><?=$rows[NO_CONTAINER]?></font></td>
            	<td class="grid-cell" align="center"><font size="2px"><?=$rows[SIZE_]?></font></td>
            	<td class="grid-cell" align="center"><font size="2px"><?=$rows[TYPE_]?></font></td>
            	<td class="grid-cell" align="center"><font size="2px"><?=$rows[STATUS_]?></font></td>
				<td class="grid-cell" align="center"><font size="2px"><?=$rows[NO_REQUEST]?></font></td>
				<td class="grid-cell" align="center"><font size="2px"><?=$rows[EMKL]?></font></td>
				<!--<td class="grid-cell" align="center"><font size="2px"><?=$rows[CONSIGNEE_PERSONAL]?></font></td>-->
				<td class="grid-cell" align="center"><font size="2px"><?=$rows[START_TO]?></font></td>
            	<td class="grid-cell" align="center"><font size="2px"><?=$rows[TGL_APPROVE]?></font></td>
				<td class="grid-cell" align="center"><font size="2px"><?=$rows[ACTIVE_TO]?></font></td>
            	<td class="grid-cell" align="center"><font size="2px"><?=$rows[TGL_REALISASI]?></font></td>
            	<td class="grid-cell" align="center"><font size="2px"><?=$rows[MASA_AKTIF]?></font></td>
				<td class="grid-cell" align="center"><font size="2px"><?=$rows[PERPANJANGAN]?></font></td>
            </tr>
           <?php } ?>
    </table>