<?php
$jenis		= $_GET["keg"];
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-".$jenis."-perkapal-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$no_ukk = $_GET["ukk"];		
	$kegiatan = $_GET["keg"];
	$no_booking = $_GET["no_booking"];
	$db 	= getDB("storage");
	
	$query1			= "select pkk.voyage_in, pkk.nm_kapal, pkk.no_ukk, pkk.no_booking from v_pkk_cont pkk
					where pkk.no_ukk like '$no_ukk%'";
	$result		= $db->query($query1);
	$row		= $result->fetchRow();
	$nm_kapal 	= $row["NM_KAPAL"];
	$voyage 	= $row["VOYAGE_IN"];

	if($kegiatan == "stripping"){
     $bp_id = "select no_booking from v_pkk_cont where no_ukk = '$no_ukk' and no_booking like 'BP%'";
      $rbp_id = $db->query($bp_id);
      $rwbp   = $rbp_id->fetchRow();
      $bp_id_ = $rwbp["NO_BOOKING"];
			/*$query = "select * from (select mc.no_container, mc.size_, cs.no_request, rs.tgl_request, mu1.nama_lengkap nm_request, vm.nm_pbm, cs.commodity,
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
			";*/
       $query = "SELECT * FROM (  SELECT b.tgl_approve,
                     a.no_container,
                     a.size_,
                     a.type_,
                     CASE WHEN tgL_realisasi IS NULL THEN 'FCL' ELSE 'MTY' END
                        status_,
                     TO_DATE (c.tgl_request, 'dd/mm/rrrr') tgl_request,
                     c.no_request,
                     b.commodity,
                     d.emkl nm_pbm,
                     b.hz,
                     c.id_yard,
                     f.nama_lengkap nm_request,
                     c.no_request_receiving,
                     b.tgl_realisasi,
                     c.perp_ke,
                     hc.no_booking,
                     tgl_bongkar tgl_awal, 
                    case when c.status_req = 'PERP'
                    then b.end_stack_pnkn
                    else case when b.tgl_selesai is null
                            then b.tgl_bongkar+4 
                            else b.tgl_selesai 
                            end
                    end as tgl_akhir,
                    d.lunas,
                    g.nama_lengkap nm_realisasi,
                    d.status VIA,
					case when b.pemakaian_alat = 1 then 'Y' else 'N'
					end pemakaian_alat,
                    bg.tgl_in tgl_gate
                FROM master_container a
                     JOIN container_stripping b
                        ON a.no_container = b.no_container
                     JOIN history_container hc
                        ON b.no_container = hc.no_container
                           AND hc.no_request = b.no_request
                           AND hc.kegiatan IN
                                  ('REQUEST STRIPPING',
                                   'PERPANJANGAN STRIPPING')
                     JOIN request_stripping c
                        ON b.no_request = c.no_request
                     JOIN nota_stripping d
                        ON c.no_request = d.no_request
                     LEFT JOIN master_user f
                        ON c.id_user = f.id
                     LEFT JOIN master_user g
                        ON b.id_user_realisasi = g.id
                     LEFT JOIN border_gate_in bg
                        ON bg.no_request = c.no_request_receiving AND bg.no_container = b.no_container
               WHERE     d.status <> 'BATAL'
                     AND (b.status_req IS NULL OR b.status_req = 'PERP')
                     AND d.LUNAS = 'YES'
            ORDER BY no_container) a, (SELECT MAX (perp_ke) max_perp, history_container.no_booking booking, container_stripping.no_container container_
                        FROM request_stripping, container_stripping, history_container
                       WHERE container_stripping.no_request =
                                request_stripping.no_request                             
                             AND history_container.no_container = container_stripping.no_container   
                             and history_container.no_request = container_stripping.no_Request
                             GROUP BY history_container.no_booking, container_stripping.no_container) z
           WHERE  a.no_container = z.container_(+) and a.no_booking=z.booking(+) and a.no_booking = '$bp_id_'
           and a.perp_ke = z.max_perp";
			
	} else {
     $bp_id = "select no_booking from v_pkk_cont where no_ukk = '$no_ukk' and no_booking not like 'BP%'";
      $rbp_id = $db->query($bp_id);
      $rwbp   = $rbp_id->fetchRow();
      $bp_id_ = $rwbp["NO_BOOKING"];
			/*$query = "select q.*, bg.tgl_in tgl_gate from (select  cs.no_container, mc.size_, rs.no_request, rs.tgl_request, mu1.nama_lengkap nm_request , vm.nm_pbm, 
                        cs.start_stack tgl_awal, case when cs.status_req = 'PERP' THEN 
                        cs.end_stack_pnkn
                        else
                        cs.start_perp_pnkn end tgl_akhir, ns.lunas, cs.tgl_realisasi, mu2.nama_lengkap nm_realisasi, cs.commodity,
                        rd.no_request no_request_delivery,case when cs.type_stuffing = 'GUD_TONGKANG' then 'Gudang Lapangan'
                                                                                                                             when cs.type_stuffing = 'STUFFING_LAP' then 'Lapangan'
                                                                                                                             when cs.type_stuffing = 'STUFFING_GUD_TONGKANG' then 'Gudang Tongkang'
                                                                                                                             when cs.type_stuffing = 'STUFFING_GUD_TRUCK' then 'Gudang Truk'
                                                                                                                        else cs.type_stuffing
                                                                                                                        end as VIA
                        from container_stuffing cs
                        join master_container mc on cs.no_container = mc.no_container 
                        join request_stuffing rs on cs.no_request = rs.no_request
                        left join nota_stuffing ns on rs.no_request = ns.no_request
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
                        c.tgl_realisasi, mu1.nama_lengkap nm_realisasi , c.commodity, '' no_request_delivery, case when c.type_stuffing = 'GUD_TONGKANG' then 'Gudang Lapangan'
                                                                                                                             when c.type_stuffing = 'STUFFING_LAP' then 'Lapangan'
                                                                                                                             when c.type_stuffing = 'STUFFING_GUD_TONGKANG' then 'Gudang Tongkang'
                                                                                                                             when c.type_stuffing = 'STUFFING_GUD_TRUCK' then 'Gudang Truk'
                                                                                                                        else c.type_stuffing
                                                                                                                        end as VIA,to_date('') tgl_gate
                        from container_stuffing c join request_stuffing r on c.no_request = r.no_request join master_container mc on c.no_container = mc.no_container 
                        left join nota_stuffing ns on r.no_request = ns.no_request join master_user mu on r.id_user = mu.id left join master_user mu1 on mu1.id = c.id_user_realisasi where r.no_booking = '$no_booking' 
                        and no_container not in (select q.no_container from (select  cs.no_container, mc.size_, rs.no_request, rs.tgl_request, mu1.nama_lengkap nm_request , vm.nm_pbm, 
                        cs.start_stack tgl_awal, cs.start_perp_pnkn tgl_akhir, ns.lunas, cs.tgl_realisasi, mu2.nama_lengkap nm_realisasi, cs.commodity,
                        rd.no_request no_request_delivery
                        from container_stuffing cs
                        join master_container mc on cs.no_container = mc.no_container 
                        join request_stuffing rs on cs.no_request = rs.no_request
                        left join nota_stuffing ns on rs.no_request = ns.no_request
                        left join master_user mu1 on rs.id_user = mu1.id
                        left join master_user mu2 on cs.id_user_realisasi = mu2.id
                        left join history_container hc on cs.no_request = hc.no_request and cs.no_container = hc.no_container and hc.kegiatan = 'REALISASI STUFFING'                                                
                        join v_mst_pbm vm on rs.kd_consignee = vm.kd_pbm and vm.kd_cabang = '05'
                        left join request_delivery rd on rs.no_booking = rd.no_booking                         
                        inner join container_delivery cd on rd.no_request = cd.no_request and cd.no_container = cs.no_container                          
                        and rs.no_booking = '$no_booking'
                        where ns.status not in ('BATAL') and cs.status_req is null or cs.status_req = 'PERP'
                        order by no_container) q left join border_gate_out bg on q.no_container = bg.no_container and q.no_request_delivery = bg.no_request)";*/
                         $query = "SELECT a.no_container,
           a.HIDE,
           a.size_,
           a.no_request,
           a.tgl_request,
           a.nama_lengkap nm_request,
           a.emkl nm_pbm,
           a.tgl_awal,
           a.tgl_akhir,
           a.lunas,
           a.hz,
           a.nama_yard,           
           a.commodity,
           a.asal_cont,
           a.berat,
           a.tgl_realisasi,
           a.nm_realisasi,
           rd.tgl_request tgl_req_delivery,
           RD.NO_REQUEST no_request_delivery,
           rd.vessel,
           rd.voyage, 
           bd.tgl_in tgl_gate,
              a.no_booking,
              case when a.type_stuffing = 'STUFFING_LAP' then 'Lapangan'
               when a.type_stuffing = 'STUFFING_GUD_TONGKANG' then 'Gudang Tongkang'
               when a.type_stuffing = 'STUFFING_GUD_TRUCK' then 'Gudang Truk'
          else a.type_stuffing end as STATUS,
		  pemakaian_alat
      FROM (SELECT a.no_container,
                    CASE 
                        WHEN b.aktif = 'T' and b.tgl_realisasi IS NULL THEN 'NO'
                        ELSE 'YES'
                   END HIDE,
                   a.size_,
                   a.type_,
                   CASE
                      WHEN b.tgl_realisasi IS NULL THEN 'MTY'
                      ELSE 'FCL'
                   END
                      status,
                   TO_CHAR (c.tgl_request, 'dd/mm/rrrr hh24:mi:ss') tgl_request,
                   CASE 
                        WHEN c.status_req = 'PERP' THEN b.start_perp_pnkn
                        ELSE b.start_stack
                   END tgl_awal,
                   CASE 
                        WHEN c.status_req = 'PERP' THEN b.end_stack_pnkn
                        ELSE b.start_perp_pnkn
                   END tgl_akhir,
                   c.no_request,
                   v.nm_pbm emkl,
                   case when c.stuffing_dari = 'AUTO' then 'YES'
                   else d.lunas
                   end LUNAS,
                   b.hz,
                   e.nama_yard,
                   f.nama_lengkap,
                   j.nama_lengkap nm_realisasi,
                   c.no_request_receiving,
                   b.tgl_realisasi,
                   b.commodity,
                   b.berat,
                   b.type_stuffing,
                   b.asal_cont,
                   c.no_booking,
                   b.f_batal_muat,
				   case when B.pemakaian_alat = '1' then 'Y' else 'N'
					end pemakaian_alat
              FROM master_container a
                   JOIN container_stuffing b
                      ON a.no_container = b.no_container
                   JOIN request_stuffing c
                      ON b.no_request = c.no_request
                   LEFT JOIN v_mst_pbm v
                      ON c.kd_consignee = v.kd_pbm AND v.kd_cabang = '05'
                   LEFT JOIN nota_stuffing d
                      ON c.no_request = d.no_request AND d.status <> 'BATAL' AND D.LUNAS = 'YES'
                   LEFT JOIN yard_area e
                      ON c.id_yard = e.id
                   LEFT JOIN master_user f
                      ON c.id_user = f.id
                   LEFT JOIN master_user j
                      ON b.id_user_realisasi = j.id
             WHERE  (B.STATUS_REQ = 'PERP' OR B.STATUS_REQ IS NULL) AND b.f_batal_muat IS NULL) a
           LEFT JOIN container_delivery cd
              ON cd.noreq_peralihan = a.no_request
                 AND cd.no_container = a.no_container
           LEFT JOIN request_delivery rd
              ON rd.no_request = cd.no_request
           LEFT JOIN border_gate_out bd
              ON cd.no_container = bd.no_container
                 AND bd.no_request = cd.no_request
           WHERE a.HIDE = 'YES' and a.no_booking = '$bp_id_'"; 

            $new_query = "SELECT a.*, b.no_req_baru NO_REQUEST_DELIVERY FROM 
                      (SELECT master_container.no_container,
                             master_container.size_,
                             container_stuffing.no_request,
                             container_delivery.no_request req_del,
                             request_stuffing.tgl_request,
                             container_stuffing.commodity,
                             master_user.nama_lengkap NM_REQUEST,
                             nota_stuffing.emkl NM_PBM,
                             CASE 
                                  WHEN request_stuffing.status_req = 'PERP' THEN container_stuffing.start_perp_pnkn
                                  ELSE container_stuffing.start_stack
                             END tgl_awal,
                             CASE 
                                  WHEN request_stuffing.status_req = 'PERP' THEN container_stuffing.end_stack_pnkn
                                  ELSE container_stuffing.start_perp_pnkn
                             END tgl_akhir,
                             nota_stuffing.lunas,
                             'X' tgl_gate,
                             container_stuffing.tgl_realisasi,
                             m1.nama_lengkap as nm_realisasi,
                             case when container_stuffing.type_stuffing = 'STUFFING_LAP' then 'Lapangan'
                                     when container_stuffing.type_stuffing = 'STUFFING_GUD_TONGKANG' then 'Gudang Tongkang'
                                     when container_stuffing.type_stuffing = 'STUFFING_GUD_TRUCK' then 'Gudang Truk'
                                else container_stuffing.type_stuffing end as STATUS
                        FROM request_stuffing,
                             container_stuffing,
                             nota_stuffing,
                             master_container,
                             container_delivery,
                             master_user,
                             master_user m1
                       WHERE     request_stuffing.no_request = container_stuffing.no_request
                             AND request_stuffing.no_request = nota_stuffing.no_request
                             AND container_stuffing.no_container = master_container.no_container
                             AND container_delivery.noreq_peralihan = container_stuffing.no_request
                             AND container_delivery.no_container = container_stuffing.no_container
                             AND master_user.id = request_stuffing.id_user
                             AND m1.id = container_stuffing.id_user_realisasi) a,
                      (SELECT container_batal_muat.no_req_batal, container_batal_muat.no_container, request_batal_muat.no_req_baru
                        FROM  request_batal_muat
                             JOIN container_batal_muat
                                ON request_batal_muat.no_request = container_batal_muat.no_request
                       WHERE kapal_tuju = '$bp_id_' and status_gate = 2) b
                       WHERE a.no_container = b.no_container AND a.req_del = b.no_req_batal";
	}
	$r_query = $db->query($query); 
	$row_q = $r_query->getAll();
	
    if ($kegiatan == "stuffing") {
      $n_query = $db->query($new_query);
      $row_n   = $n_query->getAll();
      if(count($row_n) > 0) {
      foreach ($row_n as $key) {
        if ($key[TGL_GATE] == 'X') {

          $req_dl     = $key[NO_REQUEST_DELIVERY];
          $g_tgl_gate = $db->query("SELECT TGL_IN FROM BORDER_GATE_OUT WHERE NO_REQUEST = '$req_dl'");
          $r_tgl_gate = $g_tgl_gate->fetchRow();
          $key[TGL_GATE] = $r_tgl_gate[TGL_IN]; 
           //echo $key[TGL_GATE]; die(); 
        }
      }
      $row_n[0] = &$key;      
      
        $all_res = array_merge($row_q, $row_n);
        $row_q = &$all_res;
      }
  }

?>

  <div id="list">
	<center><h2>KAPAL <?=$nm_kapal?> (<?=$voyage?>)</h2></center>
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO </th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">NO CONTAINER</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">SIZE</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">NO REQUEST</th> 
								  <?php if($kegiatan == "stuffing") { ?>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">NO REQUEST DELIVERY</th>
								  <?php } ?>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">TGL REQUEST</th> 
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">KOMODITI</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">USER</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">CONSIGNEE</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TGL AWAL</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TGL AKHIR</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">LUNAS</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TGL_GATE</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TGL REALISASI</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">USER</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">VIA</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">ALAT</th>
                              </tr>
							  <?php $i=0;?>
                              <?php foreach ($row_q as $rows) { ?>
							  <?php  $i++;	?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
							   <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>								
                                 <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows[NO_CONTAINER]?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[SIZE_]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NO_REQUEST]?></font></td>
								  <?php if($kegiatan == "stuffing") { ?>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NO_REQUEST_DELIVERY]?></font></td>
								  <?php } ?>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_REQUEST]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[COMMODITY]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NM_REQUEST]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NM_PBM]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_AWAL]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_AKHIR]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[LUNAS]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_GATE]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_REALISASI]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[NM_REALISASI]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[STATUS]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[PEMAKAIAN_ALAT]?></font></td>
								  
							</tr>							
							<?php } //if(count($rows) > 0) {?>							
        </table>
		<center><h2>Total Jumlah Container = <?=$i?> Box</h2></center>
 </div>