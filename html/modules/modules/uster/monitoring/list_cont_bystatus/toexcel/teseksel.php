<?php
$jenis		= $_GET["jenis"];
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-".$jenis."-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$db 	= getDB("storage");
	$i = 0;
	$query_list_ 	= "SELECT CASE WHEN NO_NOTA LIKE '01%' AND REQUEST_DELIVERY.DELIVERY_KE = 'LUAR' THEN 'DELIVERY EMPTY'
						WHEN NO_NOTA LIKE '01%' AND REQUEST_DELIVERY.DELIVERY_KE = 'TPK' AND REQUEST_DELIVERY.JN_REPO = 'EKS_STUFFING' THEN 'REPO EKS STUFFING'
						WHEN NO_NOTA LIKE '01%' AND REQUEST_DELIVERY.DELIVERY_KE = 'TPK' AND REQUEST_DELIVERY.JN_REPO = 'EMPTY' THEN 'REPO EMPTY'
						WHEN NO_NOTA LIKE '02%' THEN 'RECEIVING'
						WHEN NO_NOTA LIKE '03%' THEN 'STRIPPING'
						WHEN NO_NOTA LIKE '04%' THEN 'STUFFING'
						WHEN NO_NOTA LIKE '05%' THEN 'PERPANJANGAN DELIVERY'
						WHEN NO_NOTA LIKE '06%' THEN 'PERPANJANGAN STRIPPING'
						WHEN NO_NOTA LIKE '07%' THEN 'PERPANJANGAN STUFFING'
						WHEN NO_NOTA LIKE '09%' THEN 'BATAL MUAT'
						WHEN NO_NOTA LIKE '10%' THEN 'RELOKASI MTY'
						WHEN NO_NOTA LIKE '11%' THEN 'PENUMPUKAN DELIVERY'
						WHEN NO_NOTA LIKE '12%' THEN 'PENUMPUKAN STUFFING'
						END KEGIATAN,  
						NO_NOTA, NOTA_ALL_H.NO_REQUEST, TO_CHAR (TAGIHAN, '999,999,999,999') TAGIHAN, TO_CHAR (TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, TO_CHAR (PPN, '999,999,999,999') PPN
						FROM NOTA_ALL_H LEFT JOIN REQUEST_DELIVERY ON NOTA_ALL_H.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST 
						WHERE EMKL LIKE 'SAMUDERA INTAN PERMATA PT' AND NOTA_ALL_H.STATUS <> 'BATAL'
						ORDER BY KEGIATAN";
	$result_list_	= $db->query($query_list_); 
	$row_list		= $result_list_->getAll();

	foreach($row_list as $row_){
		$no_nota = $row_[NO_NOTA];
		$kegiatan = $row_[KEGIATAN];
		$no_req = $row_[NO_REQUEST];
		$tagihan = $row_[TOTAL_TAGIHAN];
		$ppn = $row_[PPN];
		$tgh = $row_[TAGIHAN];
		$i++;
		if($kegiatan == 'PENUMPUKAN DELIVERY'){ 
			$QUERY	= "SELECT TO_CHAR(a.START_STACK,'dd/mm/yyyy') START_STACK,TO_CHAR(a.END_STACK,'dd/mm/yyyy') END_STACK, 
							  a.KETERANGAN, a.JML_CONT, a.JML_HARI, b.SIZE_, b.TYPE_, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA 
						FROM NOTA_PNKN_DEL_D a, iso_code b, NOTA_PNKN_DEL c 
						WHERE a.ID_NOTA = c.NO_NOTA 
						AND a.ID_ISO = b.ID_ISO(+)  
						AND c.TGL_NOTA = (SELECT MAX(d.TGL_NOTA) 
											FROM NOTA_PNKN_DEL d 
											WHERE d.NO_REQUEST = '$no_req') ";
		}
		else if($kegiatan == 'RELOKASI MTY'){ 
				$QUERY = "SELECT TO_CHAR (a.START_STACK, 'dd/mm/yyyy') START_STACK,
					 TO_CHAR (a.END_STACK, 'dd/mm/yyyy') END_STACK,
					 a.tekstual keterangan,a.JML_CONT,
					 a.JML_HARI,
					 b.SIZE_,
					 b.TYPE_,
					 case a.tekstual when 'GERAKAN ANTAR BLOK'
					 THEN '-'
					 ELSE 
					  b.STATUS
					  END AS STATUS,
					 a.HZ,
					 TO_CHAR(SUM(a.TARIF),'999,999,999,999') TARIF,
					 TO_CHAR(SUM(a.BIAYA),'999,999,999,999') BIAYA,
					 case a.tekstual when 'GERAKAN ANTAR BLOK'
					 THEN 10
					 ELSE 
					  a.urut
					  END AS urut
				FROM nota_relokasi_mty_d a, iso_code b
			   WHERE     a.KETERANGAN <> 'ADMIN NOTA'
					 AND a.ID_ISO = b.ID_ISO(+)
					 AND a.NO_NOTA = (SELECT MAX (d.NO_NOTA)
										FROM nota_relokasi_mty d
									   WHERE d.NO_REQUEST = '$no_req')
				GROUP BY a.tekstual, a.START_STACK, a.END_STACK,  a.JML_CONT,  b.SIZE_,  b.TYPE_, a.HZ, a.JML_HARI, case a.tekstual when 'GERAKAN ANTAR BLOK'
					 THEN '-'
					 ELSE 
					  b.STATUS
					  END,
					  case a.tekstual when 'GERAKAN ANTAR BLOK'
					 THEN 10
					 ELSE 
					  a.urut
					  END
				ORDER BY urut";
		}
		else if($kegiatan == 'PERPANJANGAN STUFFING'){ 
			$QUERY	= "SELECT TO_CHAR(a.START_STACK,'dd/mm/yyyy') START_STACK,
							  TO_CHAR(a.END_STACK,'dd/mm/yyyy') END_STACK, a.KETERANGAN, a.JUMLAH_CONT, a.JML_HARI, b.SIZE_, b.TYPE_, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA 
						FROM nota_stuffing_d a, iso_code b 
						WHERE a.KETERANGAN <> 'ADMIN NOTA' 
							AND a.ID_ISO = b.ID_ISO(+) 
							AND a.NO_NOTA = (SELECT MAX(d.NO_NOTA) FROM NOTA_STUFFING d WHERE d.NO_REQUEST = '$no_req')
						ORDER BY URUT";
		}
		else if($kegiatan == 'PERPANJANGAN STRIPPING'){ 
			 $QUERY	= "SELECT TO_CHAR(a.START_STACK,'dd/mm/yyyy') START_STACK,TO_CHAR(a.END_STACK,'dd/mm/yyyy') END_STACK, a.KETERANGAN, a.JML_CONT, a.JML_HARI, b.SIZE_, b.TYPE_, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA FROM nota_stripping_d a, iso_code b, nota_stripping c WHERE a.ID_ISO = b.ID_ISO(+) AND a.NO_NOTA = c.NO_NOTA AND a.NO_NOTA = (SELECT MAX(d.NO_NOTA) FROM NOTA_STRIPPING d WHERE d.NO_REQUEST = '$no_req')";
		
		}
		else if($kegiatan == 'PERPANJANGAN DELIVERY'){ 
			$QUERY	= "SELECT TO_CHAR(a.START_STACK,'dd/mm/yyyy') START_STACK,TO_CHAR(a.END_STACK,'dd/mm/yyyy') END_STACK, a.KETERANGAN, a.JML_CONT, a.JML_HARI, b.SIZE_, b.TYPE_, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA FROM nota_delivery_d a, iso_code b, nota_delivery c WHERE a.ID_NOTA = c.NO_NOTA AND a.ID_ISO = b.ID_ISO(+)  AND c.TGL_NOTA = (SELECT MAX(d.TGL_NOTA) FROM NOTA_DELIVERY d WHERE d.NO_REQUEST = '$no_req') ";
		}
		else if($kegiatan == 'RECEIVING'){
			$QUERY	= "/* Formatted on 12/28/2012 1:46:36 PM (QP5 v5.163.1008.3004) */
						SELECT a.KETERANGAN,
							   a.JML_CONT,
							   a.JML_HARI,
							   b.SIZE_,
							   b.TYPE_,
							   b.STATUS,
							   a.HZ,
							   TO_CHAR (a.TARIF, '999,999,999,999') TARIF,
							   TO_CHAR (a.BIAYA, '999,999,999,999') BIAYA
						  FROM nota_receiving_d a, iso_code b, nota_receiving c
						 WHERE     a.ID_ISO = b.ID_ISO(+)
							   AND a.NO_NOTA = c.NO_NOTA
							   AND a.KETERANGAN <> 'ADMIN NOTA'
							   AND c.TGL_NOTA = (SELECT MAX (d.TGL_NOTA)
												   FROM NOTA_RECEIVING d
												  WHERE d.NO_REQUEST = '$no_req') ";
		}
		else if($kegiatan == 'STRIPPING'){
			$QUERY = "SELECT TO_CHAR (a.START_STACK, 'dd/mm/yyyy') START_STACK,
						 TO_CHAR (a.END_STACK, 'dd/mm/yyyy') END_STACK,
						 a.tekstual keterangan, a.JML_CONT,
						 --case a.tekstual when 'PAKET STRIPPING'
						 --THEN (select count(no_container) from container_stripping where no_request = '$no_req')
						 --ELSE 
						  --a.JML_CONT
						  --END AS JML_CONT,
						 a.JML_HARI,
						 b.SIZE_,
						 b.TYPE_,
						 case a.tekstual when 'PAKET STRIPPING'
						 THEN '-'
						 ELSE 
						  b.STATUS
						  END AS STATUS,
						 a.HZ,
						 TO_CHAR(SUM(a.TARIF),'999,999,999,999') TARIF,
						 TO_CHAR(SUM(a.BIAYA),'999,999,999,999') BIAYA,
						 case a.tekstual when 'PAKET STRIPPING'
						 THEN 10
						 ELSE 
						  a.urut
						  END AS urut
					FROM nota_stripping_d a, iso_code b
				   WHERE     a.KETERANGAN <> 'ADMIN NOTA'
						 AND a.ID_ISO = b.ID_ISO(+)
						 AND a.NO_NOTA = (SELECT MAX (d.NO_NOTA)
											FROM NOTA_STRIPPING d
										   WHERE d.NO_REQUEST = '$no_req')
					GROUP BY a.tekstual, a.START_STACK, a.END_STACK,  a.JML_CONT,  b.SIZE_,  b.TYPE_, a.HZ, a.JML_HARI, case a.tekstual when 'PAKET STRIPPING'
						 THEN '-'
						 ELSE 
						  b.STATUS
						  END,
						  case a.tekstual when 'PAKET STRIPPING'
						 THEN 10
						 ELSE 
						  a.urut
						  END
					ORDER BY urut";
		}
		else if ($kegiatan  == "STUFFING"){
			$QUERY	= "SELECT a.JML_HARI,
                             TO_CHAR(SUM(a.TARIF),'999,999,999,999') TARIF,
                             TO_CHAR(SUM(a.BIAYA),'999,999,999,999') BIAYA,
                             a.tekstual KETERANGAN,
                             a.HZ,
							 --a.JUMLAH_CONT,
							 case a.tekstual 
                              when 'PAKET STUFF LAPANGAN' THEN (a.JUMLAH_CONT)
                              when 'PAKET STUFF GUDANG EKS TONGKANG' THEN (a.JUMLAH_CONT)
                              when 'PAKET STUFF GUDANG EKS TRUCK' THEN (a.JUMLAH_CONT)
                             ELSE 
                             SUM( a.JUMLAH_CONT) 
                              END AS JML_CONT,
                              --case a.tekstual 
                              --when 'PAKET STUFF LAPANGAN' THEN (select count(no_container) from container_stuffing where no_request = '$no_req')
                             -- when 'PAKET STUFF GUDANG EKS TONGKANG' THEN (select count(no_container) from container_stuffing where no_request = '$no_req')
                             -- when 'PAKET STUFF GUDANG EKS TRUCK' THEN (select count(no_container) from container_stuffing where no_request = '$no_req')
                           --  ELSE 
                            --  a.JUMLAH_CONT
                             -- END AS JUMLAH_CONT,
                             TO_DATE (a.START_STACK, 'dd/mm/rrrr') START_STACK,
                             TO_DATE (a.END_STACK, 'dd/mm/rrrr') END_STACK,
                             b.SIZE_,
                             b.TYPE_,
                              case a.tekstual 
                                  when 'PAKET STUFF LAPANGAN' THEN '-'
                                  when 'PAKET STUFF GUDANG EKS TONGKANG' THEN '-'
                                  when 'PAKET STUFF GUDANG EKS TRUCK' THEN '-'
                             ELSE 
                              b.STATUS
                              END AS STATUS,
                              case a.tekstual 
                                 when 'PAKET STUFF LAPANGAN' THEN 10
                                  when 'PAKET STUFF GUDANG EKS TONGKANG' THEN 10
                                  when 'PAKET STUFF GUDANG EKS TRUCK' THEN 10
                             ELSE 
                              a.urut
                              END AS urut
                        FROM nota_stuffing_d a, iso_code b
                       WHERE     a.KETERANGAN <> 'ADMIN NOTA'
                             AND a.ID_ISO = b.ID_ISO(+) 
							 AND a.NO_NOTA = (SELECT MAX(d.NO_NOTA) FROM NOTA_STUFFING d WHERE d.NO_REQUEST = '$no_req')
                    GROUP BY a.tekstual, a.START_STACK, a.END_STACK,  a.JUMLAH_CONT,  b.SIZE_,  b.TYPE_, a.HZ, a.JML_HARI,
                    case a.tekstual 
                                  when 'PAKET STUFF LAPANGAN' THEN '-'
                                  when 'PAKET STUFF GUDANG EKS TONGKANG' THEN '-'
                                  when 'PAKET STUFF GUDANG EKS TRUCK' THEN '-'
                             ELSE 
                              b.STATUS
                              END,
                    case a.tekstual 
                                 when 'PAKET STUFF LAPANGAN' THEN 10
                                  when 'PAKET STUFF GUDANG EKS TONGKANG' THEN 10
                                  when 'PAKET STUFF GUDANG EKS TRUCK' THEN 10
                             ELSE 
                              a.urut
                              END
                    ORDER BY urut ASC";
		
		}
		else if($kegiatan == "PENUMPUKAN STUFFING"){
			$QUERY = "SELECT a.JML_HARI,
                             TO_CHAR(a.TARIF,'999,999,999,999') TARIF,
                             TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA,
                             a.KETERANGAN,
                             a.HZ,a.JUMLAH_CONT JML_CONT,
                             TO_DATE (a.START_STACK, 'dd/mm/rrrr') START_STACK,
                             TO_DATE (a.END_STACK, 'dd/mm/rrrr') END_STACK,
                             b.SIZE_,
                             b.TYPE_,b.STATUS,urut
                        FROM nota_pnkn_stuf_d a, iso_code b
                       WHERE a.id_iso = b.id_iso
                             AND a.no_nota = '$no_nota'
							 AND  a.KETERANGAN <> 'ADMIN NOTA'
                    ORDER BY urut ASC";		
		}
		else if($kegiatan == "BATAL MUAT"){
			$QUERY	= "SELECT TO_CHAR (a.START_STACK, 'dd/mm/yyyy') START_STACK,
					   TO_CHAR (a.END_STACK, 'dd/mm/yyyy') END_STACK,
					   a.KETERANGAN,
					   a.JML_CONT,
					   a.JML_HARI,
					   b.SIZE_,
					   b.TYPE_,
					   b.STATUS,
					   a.HZ,
					   TO_CHAR (a.TARIF, '999,999,999,999') TARIF,
					   TO_CHAR (a.BIAYA, '999,999,999,999') BIAYA
				  FROM nota_batal_muat_d a, iso_code b, nota_batal_muat c
				 WHERE     a.ID_NOTA = c.NO_NOTA
					   AND a.ID_ISO = b.ID_ISO(+)
					   AND c.TGL_NOTA = (SELECT MAX (d.TGL_NOTA)
										   FROM nota_batal_muat d
										  WHERE d.NO_REQUEST = '$no_req')";
		}
		else if($kegiatan == "DELIVERY EMPTY"){
			$QUERY	= "SELECT TO_CHAR(a.START_STACK,'dd/mm/yyyy') START_STACK,TO_CHAR(a.END_STACK,'dd/mm/yyyy') END_STACK, 
							  a.KETERANGAN, a.JML_CONT, a.JML_HARI, b.SIZE_, b.TYPE_, b.STATUS, a.HZ, TO_CHAR(a.TARIF,'999,999,999,999') TARIF , TO_CHAR(a.BIAYA,'999,999,999,999') BIAYA 
						FROM nota_delivery_d a, iso_code b, nota_delivery c 
						WHERE a.ID_NOTA = c.NO_NOTA 
						AND a.ID_ISO = b.ID_ISO(+)  
						AND c.TGL_NOTA = (SELECT MAX(d.TGL_NOTA) 
											FROM NOTA_DELIVERY d 
											WHERE d.NO_REQUEST = '$no_req') ";
		}
		else if($kegiatan == "REPO EKS STUFFING" || $kegiatan == "REPO EMPTY"){
			$QUERY="SELECT a.JML_HARI,
               TO_CHAR (SUM(a.TARIF), '999,999,999,999') AS TARIF,
               TO_CHAR (SUM(a.BIAYA), '999,999,999,999') AS BIAYA, 
               --TO_CHAR (a.TARIF, '999,999,999,999') AS TARIF,
               --TO_CHAR (a.BIAYA, '999,999,999,999') AS BIAYA,      
               case when a.tekstual is null
               then a.KETERANGAN
               else a.tekstual
               end keterangan,
               a.HZ,
               a.JML_CONT,
               TO_DATE (a.START_STACK, 'dd/mm/yyyy') START_STACK,
               TO_DATE (a.END_STACK, 'dd/mm/yyyy') END_STACK,
               b.SIZE_,
               b.TYPE_,
               b.STATUS
              FROM nota_delivery_d a, iso_code b
              WHERE a.id_iso = b.id_iso 
              AND a.id_nota = '$no_nota'  
              AND a.KETERANGAN <> 'ADMIN NOTA'
              AND a.JML_HARI IS NULL
--              AND a.KETERANGAN in ('GERAKAN ANTAR BLOK')
              GROUP BY case when a.tekstual is null
               then a.KETERANGAN
               else a.tekstual
               end, a.JML_HARI, a.HZ, a.JML_CONT, b.SIZE_, b.TYPE_, b.STATUS, a.START_STACK, a.END_STACK 
               UNION 
               SELECT a.JML_HARI,
               TO_CHAR (a.TARIF, '999,999,999,999') AS TARIF,
               TO_CHAR (SUM(a.BIAYA), '999,999,999,999') AS BIAYA, 
               --TO_CHAR (a.TARIF, '999,999,999,999') AS TARIF,
               --TO_CHAR (a.BIAYA, '999,999,999,999') AS BIAYA,      
               case when a.tekstual is null
               then a.KETERANGAN
               else a.tekstual
               end keterangan,
               a.HZ,
               SUM(a.JML_CONT),
               TO_DATE (a.START_STACK, 'dd/mm/yyyy') START_STACK,
               TO_DATE (a.END_STACK, 'dd/mm/yyyy') END_STACK,
               b.SIZE_,
               b.TYPE_,
               b.STATUS
              FROM nota_delivery_d a, iso_code b
              WHERE a.id_iso = b.id_iso 
              AND a.id_nota = '$no_nota' 
              AND a.KETERANGAN <> 'ADMIN NOTA'
              AND a.JML_HARI IS NOT NULL
--              AND a.KETERANGAN not in ('GERAKAN ANTAR BLOK')
              GROUP BY case when a.tekstual is null
               then a.KETERANGAN
               else a.tekstual
               end,a.TARIF, a.JML_HARI, a.HZ, a.JML_CONT, b.SIZE_, b.TYPE_, b.STATUS, a.START_STACK, a.END_STACK  ";
		}
			$show_all	= $db->query($QUERY);
			$resultall = $show_all->getAll();
?>

  <div id="list">
	<center><h2><?=$i?>. DETAIL NOTA <?=$kegiatan?> NO REQ : <?=$no_req?> </h2></center>	
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">KETERANGAN</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">START STACK</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">END STACK</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">JML CONT</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">SIZE</th> 
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">TYPE</th> 
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">STATUS</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">HZ</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">JML HARI</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TARIF</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">VAL</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">BIAYA</th>								  
                              </tr>
							<?php foreach($resultall as $rows) { ?>
								<tr>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows["KETERANGAN"]?></b></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?=$rows["START_STACK"]?></b></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["END_STACK"]?></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows["JML_CONT"]?> </td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["SIZE_"]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TYPE_"]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["STATUS"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["HZ"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["JML_HARI"]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["TARIF"]?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">IDR</font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows["BIAYA"]?></font></td>
							</tr>
						<?php } ?>
						<!--<tr><td colspan="12">TAGIHAN : <?=$tgh?></td></tr>
						<tr><td colspan="12">PPN : <?=$ppn?></td></tr>
						<tr><td colspan="12">TOTAL : <?=$tagihan?></td></tr>-->
        </table>
 </div>
 <?php } ?>