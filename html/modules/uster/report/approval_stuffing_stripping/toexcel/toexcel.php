<?php
$jenis		= $_GET["jenis"];
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-".$jenis."-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

$tgl_awal	= $_GET["tgl_awal"]; 
	$tgl_akhir	= $_GET["tgl_akhir"]; 
	$jenis	= $_GET["jenis"]; 
	$status_req	= $_GET["status_req"]; 
	if($status_req == 'PERP'){
		$status_req1 = " AND REQUEST_STUFFING.STATUS_REQ = 'PERP'";
		$status_req = " AND REQUEST_STRIPPING.STATUS_REQ = 'PERP'";
		$request_ap = "REQUEST PERPANJANGAN";
	}else if($status_req == 'NEW'){
		$status_req1 = " AND REQUEST_STUFFING.STATUS_REQ IS NULL";
		$status_req = " AND REQUEST_STRIPPING.STATUS_REQ IS NULL";
		$request_ap = "REQUEST BARU";
	}
	else{
		$status_req1 = "";
		$status_req = "";
	}
	
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	
	$query_list_ 	= "SELECT * FROM (
                       SELECT CONTAINER_STUFFING.NO_CONTAINER , REQUEST_STUFFING.NO_REQUEST , REQUEST_STUFFING.TGL_REQUEST, V_MST_PBM.NM_PBM, 'STUFFING' KEGIATAN, CONTAINER_STUFFING.TGL_APPROVE, LOKASI_TPK,					   
                       CONTAINER_STUFFING.TGL_REALISASI, PLACEMENT.TGL_PLACEMENT, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, REQUEST_STUFFING.NM_KAPAL, REQUEST_STUFFING.VOYAGE, REQUEST_STUFFING.NO_REQUEST_RECEIVING, CONTAINER_STUFFING.COMMODITY, container_stuffing.TYPE_STUFFING, CASE WHEN REMARK_SP2 = 'Y' THEN CONTAINER_STUFFING.END_STACK_PNKN 
                       ELSE CONTAINER_STUFFING.START_PERP_PNKN END ACTIVE_TO, RD.PIN_NUMBER
                       FROM REQUEST_STUFFING INNER JOIN 
                       CONTAINER_STUFFING ON REQUEST_STUFFING.NO_REQUEST = CONTAINER_STUFFING.NO_REQUEST
                       LEFT JOIN PLAN_CONTAINER_STUFFING ON CONTAINER_STUFFING.NO_CONTAINER = PLAN_CONTAINER_STUFFING.NO_CONTAINER AND
                       CONTAINER_STUFFING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STUFFING.NO_REQUEST,'P','S')
                       INNER JOIN MASTER_CONTAINER ON CONTAINER_STUFFING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                       LEFT JOIN V_MST_PBM ON REQUEST_STUFFING.KD_CONSIGNEE = V_MST_PBM.KD_PBM and V_MST_PBM.KD_CABANG = '05'
                       LEFT JOIN PLACEMENT ON CONTAINER_STUFFING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STUFFING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING
                       LEFT JOIN BILLING_NBS.REQ_DELIVERY_D RD ON REQUEST_STUFFING.O_REQNBS = trim(RD.ID_REQ) AND CONTAINER_STUFFING.NO_CONTAINER = RD.NO_CONTAINER
                       WHERE TRUNC(TO_DATE(REQUEST_STUFFING.TGL_REQUEST,'dd-mm-yy')) BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy-mm-dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy-mm-dd'))
					   AND CONTAINER_STUFFING.STATUS_REQ IS NULL AND REQUEST_STUFFING.NOTA = 'Y' $status_req1
                       UNION
                       SELECT DISTINCT CONTAINER_STRIPPING.NO_CONTAINER , REQUEST_STRIPPING.NO_REQUEST , REQUEST_STRIPPING.TGL_REQUEST,  V_MST_PBM.NM_PBM, 'STRIPPING' KEGIATAN, CONTAINER_STRIPPING.TGL_APPROVE, LOKASI_TPK,
                       CONTAINER_STRIPPING.TGL_REALISASI, PLACEMENT.TGL_PLACEMENT, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, VP.NM_KAPAL NM_KAPAL, VP.VOYAGE_IN VOYAGE, REQUEST_STRIPPING.NO_REQUEST_RECEIVING, CONTAINER_STRIPPING.COMMODITY, '' TYPE_STUFFING, CONTAINER_STRIPPING.TGL_SELESAI ACTIVE_TO, RD.PIN_NUMBER
                       FROM REQUEST_STRIPPING INNER JOIN 
                       CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                       LEFT JOIN PLAN_CONTAINER_STRIPPING ON CONTAINER_STRIPPING.NO_CONTAINER = PLAN_CONTAINER_STRIPPING.NO_CONTAINER AND
                       CONTAINER_STRIPPING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STRIPPING.NO_REQUEST,'P','S')
                       INNER JOIN HISTORY_CONTAINER ON CONTAINER_STRIPPING.NO_REQUEST = HISTORY_CONTAINER.NO_REQUEST AND CONTAINER_STRIPPING.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
                       AND HISTORY_CONTAINER.KEGIATAN IN ('REQUEST STRIPPING','PERPANJANGAN STRIPPING')
                       INNER JOIN MASTER_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                       LEFT JOIN V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                       LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING
                       LEFT JOIN V_PKK_CONT VP ON VP.NO_BOOKING = HISTORY_CONTAINER.NO_BOOKING
                       LEFT JOIN BILLING_NBS.REQ_DELIVERY_D RD ON REQUEST_STRIPPING.O_REQNBS = trim(RD.ID_REQ) AND CONTAINER_STRIPPING.NO_CONTAINER = RD.NO_CONTAINER 
                       --WHERE TRUNC(CONTAINER_STRIPPING.TGL_APPROVE) BETWEEN TO_DATE('2013-04-01','yyyy-mm-dd') AND TO_DATE('2013-04-04','yyyy-mm-dd')
                       WHERE TRUNC(TO_DATE(REQUEST_STRIPPING.TGL_REQUEST,'dd-mm-yy')) BETWEEN TRUNC(TO_DATE('$tgl_awal','yyyy/mm/dd')) AND TRUNC(TO_DATE('$tgl_akhir','yyyy/mm/dd'))                       
                       AND container_stripping.STATUS_REQ IS NULL AND REQUEST_STRIPPING.NOTA = 'Y' $status_req
                       ) A  
                       WHERE A.KEGIATAN LIKE '%$jenis%' ORDER BY NO_REQUEST DESC ";
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>

  <div id="list">
	<center><h2>REPORT <?=$jenis?> (<?=$request_ap?>) PERIODE REQUEST <?=$tgl_awal?> S/D <?=$tgl_akhir?></h2></center>	
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No.  Request</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Request</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">No.  Container</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">Pin Number</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">Size/Type/Status</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">Kegiatan</th> 
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">Lokasi TPK</th> 
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">Lokasi Uster</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Approval</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Active To</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Realisasi</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Pemilik Barang</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Komoditi</th>
								  <?php if($jenis == 'STUFFING') {?>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Via</th>
								  <?php } ?>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Kapal / Voy</th>
								  
                              </tr>
                              <?php $i=0; $before= ""; foreach($row_list as $rows) { 
								$i++;
							  ?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">      <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>                            
								  <?php $no_cont = $rows["NO_CONTAINER"];
								  $no_receive = $rows["NO_REQUEST_RECEIVING"];
								  $no_req = $rows["NO_REQUEST"];
								  $cek_ust = "  SELECT DISTINCT BLOCKING_AREA.NAME, PLACEMENT.SLOT_,
                    PLACEMENT.ROW_, PLACEMENT.TIER_ FROM PLACEMENT, BLOCKING_AREA WHERE PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID AND NO_CONTAINER = '$no_cont' AND PLACEMENT.NO_REQUEST_RECEIVING = '$no_receive'
                    AND PLACEMENT.TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont') 
                    GROUP BY BLOCKING_AREA.NAME,  PLACEMENT.ROW_, PLACEMENT.TIER_, PLACEMENT.SLOT_"; 
									$ro = $db->query($cek_ust);
									$res = $ro->fetchRow();
									$blok = $res["NAME"];
									$slot = $res["SLOT_"];
									$row = $res["ROW_"];
									$tier = $res["TIER_"];
									
									if($blok == NULL){
										$cek_ust = "SELECT HISTORY_PLACEMENT.SLOT_, HISTORY_PLACEMENT.ROW_, HISTORY_PLACEMENT.TIER_, BLOCKING_AREA.NAME FROM 
													HISTORY_PLACEMENT INNER JOIN BLOCKING_AREA
													ON HISTORY_PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
													LEFT JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
													LEFT JOIN MASTER_USER ON HISTORY_PLACEMENT.NIPP_USER = MASTER_USER.NIPP WHERE NO_CONTAINER = '$no_cont' AND HISTORY_PLACEMENT.NO_REQUEST = '$no_receive'
													ORDER BY HISTORY_PLACEMENT.TGL_UPDATE ASC";
										$ro = $db->query($cek_ust);
										$res = $ro->fetchRow();
										$blok = $res["NAME"];
										$slot = $res["SLOT_"];
										$row = $res["ROW_"];
										$tier = $res["TIER_"];
										
									}
									
									// if($rows["LOKASI_TPK"] == NULL){
										// $tpk = $db->query("SELECT LOKASI_TPK FROM PLAN_CONTAINER_STRIPPING, PLAN_REQUEST_STRIPPING WHERE
											   // PLAN_CONTAINER_STRIPPING.NO_REQUEST =  PLAN_REQUEST_STRIPPING.NO_REQUEST AND
											   // NO_CONTAINER = '$no_cont' ORDER BY PLAN_REQUEST_STRIPPING.TGL_REQUEST DESC");
										// $rtp = $tpk->fetchRow();
										// $lokasi_tpk = $rtp["LOKASI_TPK"];
									// }
									// else {
										// $lokasi_tpk = $rows["LOKASI_TPK"];
									// }
									
									$jenis = $rows["KEGIATAN"];
									
									if($jenis == 'STRIPPING')
									{
										if($rows["LOKASI_TPK"] == NULL){
											$tpk = $db->query("SELECT LOKASI_TPK FROM PLAN_CONTAINER_STRIPPING, PLAN_REQUEST_STRIPPING WHERE
												   PLAN_CONTAINER_STRIPPING.NO_REQUEST =  PLAN_REQUEST_STRIPPING.NO_REQUEST AND
												   NO_CONTAINER = '$no_cont' ORDER BY PLAN_REQUEST_STRIPPING.TGL_REQUEST DESC");
											$rtp = $tpk->fetchRow();
											$lokasi_tpk = $rtp["LOKASI_TPK"];
										}
										else {
											$lokasi_tpk = $rows["LOKASI_TPK"];
										}
										
										$t_realisasi = $rows["TGL_REALISASI"];
										
										if($rows["TGL_REALISASI"] == NULL){
											$get_h = $db->query("SELECT NO_REQUEST FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND KEGIATAN LIKE '%$jenis%' ORDER BY TGL_UPDATE DESC");
											$rh = $get_h->fetchRow();
											$cur_noreq = $rh["NO_REQUEST"];
											$tgl_real = $db->query("SELECT TGL_REALISASI FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$cur_noreq'");
											$t_real = $tgl_real->fetchRow();
											$t_realisasi = $t_real["TGL_REALISASI"];
										} else {
											$t_realisasi = $rows["TGL_REALISASI"];
										}
										
										if($blok == NULL){
										$loc_uster = '';									
										}
										else {
											$loc_uster = $blok."/".$slot."-".$row."-".$tier;
										}
									}
									else //$jenis =='STUFFING'
									{
										//$cek tes;
										/*
										$lokasi_tpk = $rows["LOKASI_TPK"];
										
										if($blok == NULL){
										$loc_uster = '';									
										}
										else {
											$loc_uster = $blok."/".$slot."-".$row."-".$tier;
										}
										
										$cek_asal_cont ="SELECT PLAN_CONTAINER_STUFFING.ASAL_CONT ASAL_CONT 
												FROM CONTAINER_STUFFING
												INNER JOIN PLAN_CONTAINER_STUFFING ON CONTAINER_STUFFING.NO_CONTAINER = PLAN_CONTAINER_STUFFING.NO_CONTAINER AND CONTAINER_STUFFING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STUFFING.NO_REQUEST,'P','S')
												WHERE CONTAINER_STUFFING.NO_CONTAINER='$no_cont'
												AND    CONTAINER_STUFFING.NO_REQUEST = '$no_req'";
										*/
										
										$row_asal_cont = $db->query("SELECT PLAN_CONTAINER_STUFFING.ASAL_CONT ASAL_CONT 
												FROM CONTAINER_STUFFING
												INNER JOIN PLAN_CONTAINER_STUFFING ON CONTAINER_STUFFING.NO_CONTAINER = PLAN_CONTAINER_STUFFING.NO_CONTAINER AND CONTAINER_STUFFING.NO_REQUEST = REPLACE(PLAN_CONTAINER_STUFFING.NO_REQUEST,'P','S')
												WHERE CONTAINER_STUFFING.NO_CONTAINER='$no_cont'
												AND    CONTAINER_STUFFING.NO_REQUEST = '$no_req'");
										$asal_cont = $row_asal_cont->fetchRow();
										$asal = $asal_cont["ASAL_CONT"];		
												
										if($asal == 'DEPO')
										{
											$loc_uster = $rows["LOKASI_TPK"];
											$lokasi_tpk ='--';
										}
										else //$asal_cont=='TPK'
										{
											$lokasi_tpk = $rows["LOKASI_TPK"];
											if($blok == NULL)
											{
											$loc_uster = '--';									
											}
											else 
											{
												$loc_uster = $blok."/".$slot."-".$row."-".$tier;
											}
										}
									}
									
									if($rows["TGL_REALISASI"] == NULL){
										$get_h = $db->query("SELECT NO_REQUEST FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND KEGIATAN LIKE '%$jenis%' ORDER BY TGL_UPDATE DESC");
										$rh = $get_h->fetchRow();
										$cur_noreq = $rh["NO_REQUEST"];
										$tgl_real = $db->query("SELECT TGL_REALISASI FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$cur_noreq'");
										$t_real = $tgl_real->fetchRow();
										$t_realisasi = $t_real["TGL_REALISASI"];
									} else {
										$t_realisasi = $rows["TGL_REALISASI"];
									}
									
									
									// if($blok == NULL){
										// $loc_uster = '';									
									// }
									// else {
										// $loc_uster = $blok."/".$slot."-".$row."-".$tier;
									// }
									?> 
								  
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows["NO_REQUEST"]?></b></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows["TGL_REQUEST"]?></b></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["NO_CONTAINER"]?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["PIN_NUMBER"]?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["SIZE_"]?> / <?=$rows["TYPE_"]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["KEGIATAN"]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$lokasi_tpk?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$loc_uster;?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TGL_APPROVE"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["ACTIVE_TO"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$t_realisasi?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["NM_PBM"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["COMMODITY"]?></font></td>
								  <?php if($jenis == 'STUFFING') {
									
								  ?>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TYPE_STUFFING"]?></font></td>
								  <?php } ?>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["NM_KAPAL"]."/".$rows["VOYAGE"]?></font></td>
								  
								  <?php //} else{ ?>
								  <!--<td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"></td>-->
								  <!-- <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["NO_CONTAINER"]?><br><?=$rows["SIZE_"]?> / <?=$rows["TYPE_"]?> / FCL</td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["KEGIATAN"]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["LOKASI_TPK"]?></font></td> 
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TGL_APPROVE"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TGL_REALISASI"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["NM_PBM"]?></font></td>		-->
										<?php //}  $before = $rows["NO_REQUEST"];
								  ?> 
                                  
							</tr>
							<?php } ?>
        </table>
 </div>