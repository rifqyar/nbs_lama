<?php
$tgl=date('d F Y H:i');
$tanggal=date("dmY");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Pranota Bongkar Muat-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>

<?php

// ---------------------------------------------------------

$db = getDB();
$date=date('d F Y H:i');

// jumlah detail barangnya
//$query_jum="SELECT COUNT(1) JUM_DETAIL FROM TB_NOTA_RECEIVING_D A WHERE A.ID_REQ='$no_req'";
$data_jum = 10;
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum/$jum_data_page);	//hasil bagi pembulatan ke atas
$no_ukk=$_GET["id_vessel"];
//$no_ukk='2';
$query_h 	= "select ALAMAT, NO_NPWP, NO_DO, NO_BL, NO_PEB,NM_KAPAL, NM_PEMILIK, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, VOYAGE_IN, VOYAGE_OUT FROM rbm_h where NO_UKK='$no_ukk'";
$result_h   = $db->query($query_h);
$header     = $result_h->fetchrow();
$ves_id 	= $header['NM_KAPAL'];
$owner 		= $header['NM_PEMILIK'];
$rta 		= $header['TGL_JAM_TIBA'];
$rtd 		= $header['TGL_JAM_BERANGKAT'];
$voy_in 	= $header['VOYAGE_IN'];
$voy_out 	= $header['VOYAGE_OUT'];
$no_do 		= $header['NO_DO'];
$no_bl 		= $header['NO_BL'];
$no_peb 	= $header['NO_PEB'];
$alamat 	= $header['ALAMAT'];
$no_npwp 	= $header['NO_NPWP'];

if(($data_jum%$jum_data_page)>10 || ($data_jum%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) 
{
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 8);

	$tbl = <<<EOD
			<table border="0">
					<tr><td colspan="2">PT. PELABUHAN INDONESIA II (PERSERO)</td><td align="right">No Doc</td><td>: $no_ukk</td></tr>
					<tr><td>TANJUNG PRIOK</td><td></td><td align="right">Tgl Proses</td><td>: $date</td></tr>
					<tr><td></td><td></td><td align="right">Halaman </td><td>: 1/1</td></tr>
					<tr><td>Print by : DATIN</td><td></td><td></td><td></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4" align="center"><font size="12pt"><b>PRANOTA PERHITUNGAN PELAYANAN JASA <br>
BONGKAT / MUAT</b></font></td></tr>
					<tr><td colspan="4"><hr></td></tr>
					<tr>
						<td colspan="4">
							<table border="0" >
								<tr>
									<td width="130">Untuk Perusahaan</td>
									<td>:</td>
									<td colspan="10">$owner </td>
									<td></td>
									<td width="80">NO DO</td>
									<td>:</td>
									<td colspan="10">$no_do </td>
								</tr>
								<tr>
									<td width="130">NPWP</td>
									<td>:</td>
									<td colspan="10">$no_npwp</td>
									<td></td>
									<td width="80">NO BL/PEB</td>
									<td>:</td>
									<td colspan="10">$no_bl/$no_peb</td>
								</tr>
								<tr>
									<td width="130">Alamat</td>
									<td>:</td>
									<td colspan="10">$alamat</td>
									<td></td>
									<td width="80">Bongkar Muat</td>
									<td>:</td>
									<td colspan="10">BONGKAR/MUAT </td>
								</tr>
								<tr>
									<td width="130">Nama Kapal/Voyage</td>
									<td>:</td>
									<td colspan="10">$ves_id/$voy_in-$voy_out</td>
									<td></td>
									<td width="80">Tgl Tiba</td>
									<td>:</td>
									<td colspan="10">$rta </td>
								</tr>
								
							</table>
						</td>
					</tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"><hr></td></tr>
					<tr>
						<td colspan="4">
							<table border="0" >
								<tr valign="middle" align="center">
									<td width="100">KETERANGAN</td>
									<td width="40">E/I</td>
									<td width="40">O/I</td>
									<td width="40">CRANE</td>
									<td width="40">SZ</td>
									<td width="70">TY</td>	
									<td width="40">ST	</td>	
									<td width="40">HZ</td>		
									<td width="40">BOX	</td>	
									<td width="90" align="right">TARIF</td>	
									<td width="40">VAL	</td>	
									<td colspan="2" align="right" width="100">JUMLAH</td>										
								</tr>
								<tr align="center">
									<td colspan="13"><hr></td>	
								</tr>
EOD;

$qh2="select CASE WHEN EI='I' THEN 'DISCH' ELSE 'LOAD' END KETERANGAN, 
		EI,SIZE_,TYPE_,STATUS,HZ,BOX,
		to_char(TARIF, '999,999,999.99') TARIF, VAL, 
		to_char(JUMLAH, '999,999,999.99') JUMLAH, 
		CASE WHEN HEIGHT='OOG' THEN '/OOG' ELSE '' END HEIGHT 
		FROM RBM_D 
		WHERE NO_UKK='$no_ukk' order by EI DESC";
	$res2=$db->query($qh2);
	$rd2=$res2->getAll();

$qh_shift="select CASE WHEN EI='I' THEN 'DISCH' ELSE 'LOAD' END KETERANGAN, 
		EI,SIZE_,TYPE_,STATUS,HZ,BOX,
		to_char(TARIF, '999,999,999.99') TARIF, VAL, 
		to_char(JUMLAH, '999,999,999.99') JUMLAH, 
		CASE WHEN HEIGHT='OOG' THEN '/OOG' ELSE '' END HEIGHT 
		FROM RBM_D 
		WHERE NO_UKK='$no_ukk' order by EI DESC";
	$res_shift=$db->query($qh_shift);
	$rd_shift=$res_shift->getAll();	
	
foreach ($rd2 as $rows)
{

	$tamb_tbl	.='<tr>
					<td align="center">'.$rows['KETERANGAN'].'</td>
					<td align="center">'.$rows['EI'].'</td>
					<td align="center"> O </td>
					<td align="center">&nbsp;</td>
					<td align="center">'.$rows['SIZE_'].'</td>
					<td align="center">'.$rows['TYPE_'].''.$rows['HEIGHT'].'</td>
					<td align="center">'.$rows['STATUS'].'</td>
					<td align="center">'.$rows['HZ'].'</td>
					<td align="center">'.$rows['BOX'].'</td>
					<td align="right">'.$rows['TARIF'].'</td>
					<td align="center">USD</td>
					<td colspan="2" align="right">'.$rows['JUMLAH'].'</td>
				</tr>';
}
//===shifting===//
$q_shift="select CASE WHEN JENIS_SHIFT='Landed'  THEN 'SHIF LAND' 
                  WHEN JENIS_SHIFT='Unlanded'  THEN 'SHIF UNLAND' 
                  END KETERANGAN_SHIFT, 
         ALAT_SHIFT, 
         SIZE_C,
         JUMLAH_KEG,
        to_char(TARIF, '999,999,999.99') TARIF,
        VAL, 
        to_char(JUMLAH, '999,999,999.99') JUMLAH 
        FROM RBM_D_SHIFT
        WHERE NO_UKK='$no_ukk'";
	$red_shift=$db->query($q_shift);
	$r_shift=$red_shift->getAll();

foreach ($r_shift as $row)
{
	$tbls .='	<tr>
					<td  align="center"><font size="8">'.$row['KETERANGAN_SHIFT'].'</font></td>
                    <td  align="center"><font size="8">&nbsp;</font></td>
                    <td  align="center"><font size="8"> OI</font></td>
                    <td  align="center"><font size="8">'.$row['ALAT_SHIFT'].'</font></td>
                    <td  align="center"><font size="8">'.$row['SIZE_C'].'</font></td>
                    <td  align="center"><font size="8">&nbsp;</font></td>
                    <td  align="center"><font size="8">&nbsp;</font></td>
                    <td  align="center"><font size="8">&nbsp;</font></td>
                    <td  align="center"><font size="8">'.$row['JUMLAH_KEG'].'</font></td>
                    <td  align="right"><font size="8">'.$row['TARIF'].'</font></td>
                    <td  align="center"><font size="8">'.$row['VAL'].'</font></td>
                    <td  colspan="2" align="right"><font size="8" >'.$row['JUMLAH'].'</font></td>
                </tr>';
}
//---hatch movement--//
	$res_hm=$db->query("select nvl(sum(b.JUMLAH),0) JUMLAH_HM from RBM_HM b where b.NO_UKK='$no_ukk'");
	$rd_hm=$res_hm->fetchRow();	
	if ($rd_hm['JUMLAH_HM']==0)
	{
		$tamb_hm	.='<tr>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center">&nbsp;</td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="right"></td>
					<td align="center"></td>
					<td colspan="2" align="right"></td>
				</tr>';
	}
	else
	{
		$jm=$rd_hm['JUMLAH_HM'];
		$tr_hm=$db->query("select to_char(a.TARIF_HATCH, '999,999,999.99') TARIF_HATCH, to_char((a.TARIF_HATCH*$jm), '999,999,999.99') TTL_HM
							from MASTER_TARIF_HC a where a.SIZE_C=20");
		$trhm=$tr_hm->fetchRow();	
		
		
		$tamb_hm	.='<tr>
					<td align="center">HATCH</td>
					<td align="center">&nbsp;</td>
					<td align="center"> O </td>
					<td align="center">&nbsp;</td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center">'.$rd_hm['JUMLAH_HM'].'</td>
					<td align="right">'.$trhm['TARIF_HATCH'].'</td>
					<td align="center">USD</td>
					<td colspan="2" align="right">'.$trhm['TTL_HM'].'</td>
				</tr>';
		
	}
//---hatch movement--//


$tbl .= <<<EOD
									$tamb_tbl
									$tbls
									$tamb_hm
									<tr>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center">&nbsp;</td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="center"></td>
					<td align="right"></td>
					<td align="center"></td>
					<td colspan="2" align="right"></td>
				</tr>
									</table>
						</td>	
					</tr>
					<tr><td colspan="4"><hr></td></tr>
EOD;

$qh3="select NO_UKK, NM_KAPAL, VOYAGE_IN, VOYAGE_OUT, 
		NM_PEMILIK, ALAMAT, NO_DO, NO_BL, NO_PEB, 
		TGL_JAM_TIBA, TGL_JAM_BERANGKAT, SYSDATE AS DATE_N, 
		to_char(TAGIHAN, '999,999,999.99') TAGIHAN, 
		to_char(TOTAL, '999,999,999.99') TOTAL, 
		to_char(ADM, '999,999,999.99') ADM,
		to_char(TAGIHAN + ADM, '999,999,999.99') TAG_ADM,
		 to_char(TAGIHAN + ADM + PPN, '999,999,999.99') TAG_TOT,
		to_char(PPN, '999,999,999.99') PPN FROM RBM_H WHERE NO_UKK='$no_ukk'";
	$res3=$db->query($qh3);
	$rd3=$res3->fetchRow();
	$adm 		= $rd3['ADM'];
	$total 		= $rd3['TOTAL'];
	$tagihan 	= $rd3['TAGIHAN'];
	$tag	 	= $rd3['TAG_ADM']; // hanya temporary
	$tagtot		= $rd3['TAG_TOT'];
	$ppn 		= $rd3['PPN'];
$tbl .= <<<EOD
						<tr><td colspan="4">
							<table>
										<tr>
											<td width="400"></td>
											<td>Discount</td>
											<td width="25">:</td>
											<td width="80" align="right"> 0</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Administrasio</td><td  width="25">:</td>
											<td width="80" align="right"> $adm</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Dasar Pengenaan Pajak</td><td  width="25">:</td>
											<td width="80" align="right"> $tag</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah PPN</td><td  width="25">:</td>
											<td width="80" align="right"> $ppn</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah PPN Subsidi</td>
											<td  width="25">:</td>
											<td width="80" align="right"> 0</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah Dibayar</td>
											<td  width="25">:</td>
											<td width="80" align="right"> $tagtot</td>
										</tr>
									</table>
								</td>
							</tr>
EOD;

	$tbl .= <<<EOD
							</table>
						</td>
					</tr>
EOD;
	
	
	$tbl .= <<<EOD
					<tr><td colspan="4"></td></tr>
					<tr><td  width="200"></td></tr>
					<tr><td width="200">&nbsp;</td></tr>
					<tr><td  width="200">&nbsp;</td></tr>
					<tr><td></td><td></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">CABANG PELABUHAN TANJUNG PRIOK</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">An.DEPUTI TERMINAL 3</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">MANAGER OPERASI</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">TENGKU MURSALIN RAHIM</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">NIPP : 271085951</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4" align="center">DOKUMEN INI DITERBITKAN DARI SISTEM DAN TIDAK MEMERLUKAN TANDA TANGAN</td></tr>
				</table>
EOD;
	

?>