<?php
//$tl 	=  xliteTemplate('home.htm');
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=NotaPerPeriodik-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");


	$tgl_awal	= $_GET["tgl_awal"]; 
	$tgl_akhir	= $_GET["tgl_akhir"]; 
	$jenis		= $_GET["jenis"];
	$pembayaran	= $_GET["pembayaran"];
	$status_bayar = $_GET["status_bayar"];
	$status_nota = $_GET["status_nota"];
	$corporatetype = $_GET["corp"];
	//print_r($jenis);die();
	if($status_nota == 'NEW'){
		$status_nota = " AND STATUS IN ('NEW', 'KOREKSI', 'PERP') ";
	}
	else if($status_nota == 'BATAL') {
		$status_nota = " AND STATUS IN ('BATAL') ";
	}
	else{
		$status_nota = " AND STATUS IN ('NEW', 'KOREKSI', 'PERP','BATAL') ";
	}
	
	if($status_bayar == "YES"){
		$status_bayar = "AND LUNAS = 'YES'";
	}
	else if($status_bayar == "NO"){
		$status_bayar = "AND LUNAS = 'NO'";
	}
	else{
		$status_bayar = "";
	}
	
	if($pembayaran == ''){
		$pembayaran = "";
	}
	else if($pembayaran == 'BANK'){
		$pembayaran = "AND BAYAR LIKE '%BANK%'";
	}
	else if($pembayaran == 'CASH'){
		$pembayaran = "AND BAYAR LIKE '%CASH%'";
	}
	else if($pembayaran == 'AUTODB'){
		$pembayaran = "AND BAYAR LIKE '%AUTODB%'";
	}
	else if($pembayaran == 'DEPOSIT'){
		$pembayaran = "AND BAYAR LIKE '%DEPOSIT%'";
	}
	
	// debug($_POST);
	//echo $jenis;die();
	
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	//$db_uster_ict 	= getDB("uster_ict"); 
	
	//ECHO 'CORP: '.$corporatetype;DIE;
	// if($corporatetype=='IPC'){
	// $query_list_ = "SELECT * FROM (
	// 				    SELECT TRANSFER,
	// 				       NO_FAKTUR NO_NOTA,
	// 				       NO_FAKTUR,
	// 				       NOTA_ALL_H.NO_REQUEST,
	// 				       TRUNC (TGL_NOTA) TGL_NOTA,
	// 				       CASE WHEN SUBSTR(NO_REQUEST,0,3) = 'DEL' THEN 'DELIVERY'
	// 				                WHEN SUBSTR(NO_REQUEST,0,3) = 'STP' THEN 'STRIPPING'
	// 				                WHEN SUBSTR(NO_REQUEST,0,3) = 'STR' THEN 'STRIPPING'
	// 				                WHEN SUBSTR(NO_REQUEST,0,3) = 'STF' THEN 'STUFFING'
	// 				                WHEN SUBSTR(NO_REQUEST,0,3) = 'SFP' THEN 'STUFFING'
	// 				                WHEN SUBSTR(NO_REQUEST,0,3) = 'REC' THEN 'RECEIVING'
	// 				                WHEN SUBSTR(NO_REQUEST,0,3) = 'BMU' THEN 'BATALMUAT'
	// 				       END AS KEGIATAN,
	// 				       EMKL,
	// 				       --TO_CHAR (TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN,
	// 					   TOTAL_TAGIHAN,
	// 				       BAYAR,
	// 				       NOTA_ALL_H.STATUS STATUS,
	// 				       LUNAS,
	// 				       RECEIPT_ACCOUNT
	// 				  FROM    NOTA_ALL_H
	// 				 WHERE (TRUNC (TGL_NOTA) BETWEEN TO_DATE ('$tgl_awal', 'yyyy-mm-dd')
	// 				                             AND TO_DATE ('$tgl_akhir', 'yyyy-mm-dd')))                             
	// 				  WHERE KEGIATAN LIKE '%$jenis%' ".$pembayaran." ".$status_nota." ".$status_bayar;
					
	//  // echo $query_list_;
	// }
	// else
	// {
		$query_list_ = "select CASE WHEN A.STATUS='3' THEN 'Y' ELSE 'N' END AS TRANSFER,
						A.NO_NOTA_MTI, 
						A.NO_FAKTUR_MTI,
						A.NO_REQUEST,
						TRUNC (A.TRX_DATE) TGL_NOTA,
						CASE WHEN SUBSTR(A.NO_REQUEST,0,3) = 'DEL' THEN 'DELIVERY'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'STP' THEN 'STRIPPING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'STR' THEN 'STRIPPING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'STF' THEN 'STUFFING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'SFP' THEN 'STUFFING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'REC' THEN 'RECEIVING'
													WHEN SUBSTR(A.NO_REQUEST,0,3) = 'BMU' THEN 'BATALMUAT'
										   END AS KEGIATAN,
						B.NM_PBM EMKL, 
						a.KREDIT as TOTAL_TAGIHAN,        
						A.RECEIPT_METHOD BAYAR,
						CASE WHEN A.STATUS_NOTA =1 THEN 'KOREKSI' ELSE 'NEW' END AS STATUS,  
						CASE WHEN A.TGL_PELUNASAN IS NOT NULL THEN 'YES' ELSE 'NO' END AS LUNAS,        
						A.RECEIPT_ACCOUNT      
						from ITPK_NOTA_HEADER A JOIN MST_PELANGGAN B ON A.CUSTOMER_NUMBER=B.NO_ACCOUNT_PBM
						WHERE (TRUNC (TRX_DATE) BETWEEN TO_DATE ('$tgl_awal', 'yyyy-mm-dd')
																 AND TO_DATE ('$tgl_akhir', 'yyyy-mm-dd'))";
																 
						
					//	}
	//ECHO $query_list_;DIE;
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();


?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">LAPORAN NOTA PER PERIODIK TANGGAL <?=$tgl_awal?> sampai <?=$tgl_akhir?></tr>
							  <tr style=" font-size:10pt"></tr>
							  <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No. Nota </th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">No. Faktur Pajak </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">No. Request </th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Kegiatan</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Kegiatan</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Pemilik Barang</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Pembayaran</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">Total Tagihan</th> 
								  <th valign="top" class="grid-header"  style="font-size:8pt">Status Lunas</th> 
								  <th valign="top" class="grid-header"  style="font-size:8pt">Status Batal/Tidak</th> 
								  <th valign="top" class="grid-header"  style="font-size:8pt">Status Nota</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Status Transfer Ke Simkeu</th>
								  <th valign="top" class="grid-header"  style="font-size:8pt">Bank</th>
                              </tr>
                              <?php $i=0; 
									$total=0;
							  foreach($row_list as $rows){ $i++;?>
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?php echo $rows["NO_NOTA_MTI"]; ?></b></td>
								  <td width="22%" align="center" valign="middle" class="grid-cell"   style="font-family:Arial; font-size:11pt; color:#555555"><b><?php echo $rows["NO_FAKTUR_MTI"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_REQUEST"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["KEGIATAN"]; ?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["TGL_NOTA"]; ?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["EMKL"]; ?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["BAYAR"]; ?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["TOTAL_TAGIHAN"]; ?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["LUNAS"]; ?></font></td>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["STATUS"]; ?></font></td>
								  <?php //echo 'dadahdhh';die;
								  if($rows["ASAL"] == "TPK"){
									$no_request = $rows["NO_REQUEST"];
											$db 	= getDB("storage");
											$query_cek_ = "SELECT * FROM (
														SELECT CONTAINER_DELIVERY.NO_REQUEST NO_REQUEST, CONTAINER_DELIVERY.NO_CONTAINER NO_CONTAINER, BORDER_GATE_OUT.TGL_IN TGL
														FROM CONTAINER_DELIVERY LEFT JOIN BORDER_GATE_OUT ON CONTAINER_DELIVERY.NO_REQUEST = BORDER_GATE_OUT.NO_REQUEST
														UNION
														SELECT CONTAINER_RECEIVING.NO_REQUEST NO_REQUEST, CONTAINER_RECEIVING.NO_CONTAINER NO_CONTAINER, BORDER_GATE_IN.TGL_IN TGL
														FROM CONTAINER_RECEIVING LEFT JOIN BORDER_GATE_IN ON CONTAINER_RECEIVING.NO_REQUEST = BORDER_GATE_IN.NO_REQUEST
														UNION
														SELECT CONTAINER_STRIPPING.NO_REQUEST NO_REQUEST, CONTAINER_STRIPPING.NO_CONTAINER NO_CONTAINER, BORDER_GATE_IN.TGL_IN TGL
														FROM CONTAINER_STRIPPING LEFT JOIN BORDER_GATE_IN ON CONTAINER_STRIPPING.NO_REQUEST = BORDER_GATE_IN.NO_REQUEST
														UNION
														SELECT CONTAINER_STUFFING.NO_REQUEST NO_REQUEST, CONTAINER_STUFFING.NO_CONTAINER NO_CONTAINER, BORDER_GATE_IN.TGL_IN TGL
														FROM CONTAINER_STUFFING LEFT JOIN BORDER_GATE_IN ON CONTAINER_STUFFING.NO_REQUEST = BORDER_GATE_IN.NO_REQUEST)
														WHERE NO_REQUEST LIKE '$no_request'";
											//$res_ungate = $db->query($query_cek_);
											//$row_ungate = $res_ungate->getAll();
									
											//$jumlah = count($row_ungate);
											$jumlah = 1;
											if($jumlah > 0){?> 
											  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
											  <a style="color:red">Not Ready to Transfer</a></font></td>
											  <?if ($rows['TRANSFER'] == 'Y'){?>
											  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
											  <a style="color:green">Sudah Tertansfer</a></font></td>
											  <? } else {?>
												  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
												<a style="color:red">Belum Tertansfer</a></font></td>
											  <?}?>
								  <?php 
											}
											else{?>
											  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
											  <a style="color:green">Ready to Transfer</a></font></td>
											    <?if ($rows['TRANSFER'] == 'Y'){?>
											  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
											  <a style="color:green">Sudah Tertansfer</a></font></td>
											  <? } else {?>
												  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
												<a style="color:red">Belum Tertansfer</a></font></td>
											  <?}?>
											<?php }?>
								  <?php
								  } 
								  else { ?> 
											  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
											  <a style="color:green">Ready to Transfer</a></font></td>
											   <?if ($rows['TRANSFER'] == 'Y'){?>
											  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
											  <a style="color:green">Sudah Tertansfer</a></font></td>
											  <? } else {?>
												  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt">
												<a style="color:red">Belum Tertansfer</a></font></td>
											  <?}?>
								  <?php }?>
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $rows["RECEIPT_ACCOUNT"]; ?></font></td>
                              </tr>
                              <?php 
									$total = $total + $rows["TOTAL_TAGIHAN"];
								
								} ?>
							  <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td> 
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td> 
								  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?php echo $total; ?></font></td> 
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td> 
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td> 
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
								  <td valign="top" class="grid-header"  style="font-size:8pt"></td>
							  </tr>
							  
							  
        </table>
 </div>