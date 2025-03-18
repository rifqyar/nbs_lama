<?php
$kd_pbm = $_GET["kd_pbm"];

$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=Container-".$kd_pbm."-Nonrealisasi.xls");
header("Pragma: no-cache");
header("Expires: 0");
$db 	= getDB("storage");
	
/*	
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


*/
$query_list_="SELECT * FROM  (SELECT CONTAINER_STRIPPING.NO_CONTAINER,CONTAINER_STRIPPING.NO_REQUEST,SIZE_,TYPE_,NO_REQUEST_RECEIVING,
                   BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_ , PLACEMENT.TIER_,
                   REQUEST_STRIPPING.KETERANGAN, CONTAINER_STRIPPING.TGL_APPROVE, REQUEST_STRIPPING.PERP_KE, HISTORY_CONTAINER.NO_BOOKING, VP.NM_KAPAL, VP.VOYAGE_IN,
                   (SELECT MAX(PERP_KE) FROM REQUEST_STRIPPING R, CONTAINER_STRIPPING C WHERE R.NO_REQUEST = C.NO_REQUEST AND C.NO_CONTAINER = CONTAINER_STRIPPING.NO_CONTAINER) MAX_PERP
                   FROM REQUEST_STRIPPING INNER JOIN CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                   INNER JOIN MASTER_CONTAINER ON MASTER_CONTAINER.NO_CONTAINER=CONTAINER_STRIPPING.NO_CONTAINER
                   INNER JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST AND LUNAS='YES'
                   LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER = PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING
                   LEFT JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
                   INNER JOIN HISTORY_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER 
                   AND CONTAINER_STRIPPING.NO_REQUEST = HISTORY_CONTAINER.NO_REQUEST AND (HISTORY_CONTAINER.KEGIATAN = 'REQUEST STRIPPING' OR HISTORY_CONTAINER.KEGIATAN = 'PERPANJANGAN STRIPPING')
                   INNER JOIN PETIKEMAS_CABANG.TTM_BP_CONT TM ON TM.BP_ID =  HISTORY_CONTAINER.NO_BOOKING
                   INNER JOIN PETIKEMAS_CABANG.V_PKK_CONT VP ON TM.NO_UKK = VP.NO_UKK 
                   WHERE KD_CONSIGNEE ='$kd_pbm' AND CONTAINER_STRIPPING.AKTIF = 'Y'
                   -- AND CONTAINER_STRIPPING.NO_CONTAINER NOT IN
                  --(SELECT A.NO_CONTAINER FROM HISTORY_CONTAINER A,REQUEST_STRIPPING B WHERE A.NO_REQUEST=B .NO_REQUEST
                   --AND B.KD_CONSIGNEE='$kd_pbm' AND KEGIATAN='REALISASI STRIPPING' )
                   AND (CONTAINER_STRIPPING.STATUS_REQ='PERP' OR CONTAINER_STRIPPING.STATUS_REQ IS NULL)  ORDER BY CONTAINER_STRIPPING.TGL_REALISASI DESC) KE
                   WHERE KE.PERP_KE = MAX_PERP";
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	$jum = count($row_list);
	
	$q_detail1= "SELECT EMKL,KD_EMKL,
					(SELECT COUNT(CONTAINER_STRIPPING.NO_CONTAINER) FROM REQUEST_STRIPPING INNER JOIN   CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
					INNER JOIN MASTER_CONTAINER ON MASTER_CONTAINER.NO_CONTAINER=CONTAINER_STRIPPING.NO_CONTAINER
					INNER JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST AND LUNAS='YES' 
					LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING          
					LEFT JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID                     
					WHERE REQUEST_STRIPPING.KD_CONSIGNEE ='$kd_pbm' AND CONTAINER_STRIPPING.NO_CONTAINER NOT IN
					(SELECT  A.NO_CONTAINER FROM HISTORY_CONTAINER A,REQUEST_STRIPPING B WHERE A.NO_REQUEST=B .NO_REQUEST
					AND B.KD_CONSIGNEE ='$kd_pbm'
					AND KEGIATAN='REALISASI STRIPPING' )  AND (CONTAINER_STRIPPING.STATUS_REQ='PERP' OR CONTAINER_STRIPPING.STATUS_REQ IS NULL))JUMLAH FROM NOTA_STRIPPING WHERE KD_EMKL='$kd_pbm'";
		$r_detail1 = $db->query($q_detail1);
		$r_de11 = $r_detail1->FetchRow();
?>
 <div id="list">
  <br>

	<center> Nama EMKL = <?=$r_de11['EMKL']?><br>
	Total container yang belum realiasai stripping : <?=$jum?> BOX <br>
	<!--No.Request : <?=$no_request?> <br/> <?=$vessel?> (<?=$voy?>)-->
	</center>
	<table border="1" align="center" cellspacing="1" cellpadding="1">
		
		<tr bgcolor="#006699">
			<th class="grid-header" align="center" width="15" style="font-size:6pt">NO</th>
			<th class="grid-header" align="center"  style="font-size:6pt">NO CONTAINER</th>
			<th class="grid-header" align="center"  style="font-size:6pt">SIZE-TYPE</th>
			<th class="grid-header" align="center"  width="70" style="font-size:6pt">NO REQUEST</th>
			<th class="grid-header" align="center"  style="font-size:6pt">B-S-R-T</th>
			<th class="grid-header" align="center"  style="font-size:6pt">TERTANDA</th>
			<th class="grid-header" align="center"  style="font-size:6pt">TANGGAL APPROVE</th>
			<th class="grid-header"  style="font-size:8pt">NAMA KAPAL</td>
		</tr>
		 <?php $i=0;
							  foreach ($row_list as $row){ ?>
							  <?php  $i++;	?>
                             <tr align='center'>
				<td align='center' >	<?=$i?></td>
				<td align='center' >	<?=$row["NO_CONTAINER"]?></td>
				<td align='center'>	<?=$row["SIZE_"]?> - <?=$row["TYPE_"]?></td>
				<td align='center'>	<?=$row["NO_REQUEST"]?></td>
				<td align='center'>	<?=$row["BLOK_"]?> - <?=$row["SLOT_"]?> - <?=$row["ROW_"]?> - <?=$row["TIER_"]?></td>
				<td align='center'>	<?=$row["KETERANGAN"]?></td>
				<td align='center'>	<?=$row["TGL_APPROVE"]?></td>
				<td align='center'>	<?=$row["NM_KAPAL"]?>. [<?=$row["VOYAGE_IN"]?>]</td>
				</tr>
							<?php } ?>
    </table>
 </div>