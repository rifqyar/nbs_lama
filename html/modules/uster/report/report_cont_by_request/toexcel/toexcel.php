<?php
$no_request = $_GET["NO_REQUEST"];
$kegiatan = $_GET["KEGIATAN"];

$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=Container-".$kegiatan."-".$no_request.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	
	$db 	= getDB("storage");
	
	
	if($kegiatan == 'DELIVERY'){
		$query = "select cd.no_container, cd.start_stack tgl_awal, cd.tgl_delivery tgl_akhir, mc.size_, mc.type_, cd.status, cd.hz, cd.komoditi, cd.berat
				from container_delivery cd inner join master_container mc 
				on cd.no_container = mc.no_container
				where no_request = '$no_request'";
		$get_nm_kapal = "select vessel, voyage from request_delivery where no_request = '$no_request'";
		$r_nmkapl = $db->query($get_nm_kapal);
		$rnm = $r_nmkapl->fetchRow();
		$vessel = $rnm["VESSEL"];
		$voy = $rnm["VOYAGE"];
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


?>
 <div id="list">
	<center> No.Request : <?=$no_request?> <br/> <?=$vessel?> (<?=$voy?>)</center>
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO </th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">NO CONTAINER</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TGL AWAL</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">TGL AKHIR</th> 
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">SIZE</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TYPE</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">STATUS</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">HZ</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">COMMODITY</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">GROSS</th>
                              </tr>
							  <?php $i=0;
							  foreach($row_q as $rows) { ?>
							  <?php  $i++;	?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
							   <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>								
                                 <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows[NO_CONTAINER]?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_AWAL]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TGL_AKHIR]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[SIZE_]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TYPE_]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[STATUS]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[HZ]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[KOMODITI]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[BERAT]?></font></td>
							</tr>
							<?php } ?>
        </table>
 </div>