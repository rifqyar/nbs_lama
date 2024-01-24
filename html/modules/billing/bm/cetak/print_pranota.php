<?php

//print_r("coba");die;
//require "login_check.php";

require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	// Page footer
	public function Footer() {
		// Position at 10 mm from bottom
		$this->SetY(-10);
		// Set font
		$this->SetFont('helvetica', 'I', 6);
		// Page number
		$this->Cell(0, 10, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(5, 16, 8);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

$db = getDB();
$date=date('d F Y H:i');

// jumlah detail barangnya
//$query_jum="SELECT COUNT(1) JUM_DETAIL FROM TB_NOTA_RECEIVING_D A WHERE A.ID_REQ='$no_req'";
$data_jum = 10;
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum/$jum_data_page);	//hasil bagi pembulatan ke atas
$no_ukk=$_GET["id_vessel"];
$prn=$_GET["no_pr"];
//$no_ukk='2';
$query_h 	= "select ALAMAT, NO_NPWP, NM_KAPAL, NM_PEMILIK, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, VOYAGE_IN, VOYAGE_OUT, a.NO_PRANOTA, a.NO_UKK, a. VAL_P,
TO_CHAR(a.TAGIHAN,'999,999,999,999.00') TAGIHAN, 
TO_CHAR(a.ADM,'999,999,999,999.00') ADM, 
TO_CHAR(a.TAGIHAN_,'999,999,999,999.00') TAGIHAN_, 
TO_CHAR(a.TOTAL,'999,999,999,999.00') TOTAL, 
TO_CHAR(a.TOTAL_UPER,'999,999,999,999.00') TOTAL_UPER, 
TO_CHAR(a.PPN,'999,999,999,999.00') PPN, 
TO_CHAR((a.TOTAL_UPER-a.TOTAL),'999,999,999,999.00') SISA,
a.NO_UPER, a.OI
FROM rbm_h_pr a left join rbm_h b on TRIM(a.NO_UKK)=TRIM(b.NO_UKK) where a.NO_UKK='$no_ukk' and a.NO_PRANOTA='$prn'";
//print_r($query_h);die;
$result_h   = $db->query($query_h);
$header     = $result_h->fetchrow();
$ves_id 	= $header['NM_KAPAL'];
$owner 		= $header['NM_PEMILIK'];
$rta 		= $header['TGL_JAM_TIBA'];
$rtd 		= $header['TGL_JAM_BERANGKAT'];
$voy_in 	= $header['VOYAGE_IN'];
$voy_out 	= $header['VOYAGE_OUT'];
$alamat 	= $header['ALAMAT'];
$no_npwp 	= $header['NO_NPWP'];
$no_uper 	= $header['NO_UPER'];
$adm 		= $header['ADM'];
$total 		= $header['TOTAL'];
$tagihan 	= $header['TAGIHAN'];
$tag	 	= $header['TAGIHAN_']; // hanya temporary
$tagtot		= $header['TOTAL'];
$ppn 		= $header['PPN'];
$uper 		= $header['TOTAL_UPER'];
$sisa		= $header['SISA'];
$oi			= $header['OI'];

if($oi=='O')
{
	$pelyr='Ocean Going';
}
else if($oi=='I')
{
	$pelyr='Interinsuler';
}


if(($data_jum%$jum_data_page)>10 || ($data_jum%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) 
{
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 8);

	$tbl = <<<EOD
			<table border="0">
					<tr><td colspan="2">PT. PELABUHAN INDONESIA II (PERSERO)</td><td align="right">No. Pranota</td><td>: $prn</td></tr>
					<tr><td>CABANG JAMBI</td><td></td><td align="right">Tgl Proses</td><td>: $date</td></tr>
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
									<td width="80">SHIPPING </td>
									<td>:</td>
									<td colspan="10">$pelyr</td>
								</tr>
								<tr>
									<td width="130">NPWP</td>
									<td>:</td>
									<td colspan="10">$no_npwp</td>
									<td></td>
									<td width="80">NO UPER</td>
									<td>:</td>
									<td colspan="10">$no_uper</td>
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
									<td colspan="2" align="right" width="120">JUMLAH</td>										
								</tr>
								<tr align="center">
									<td colspan="13"><hr></td>	
								</tr>
EOD;

$qh2="select CASE WHEN EI='I' THEN 'DISCH' ELSE 'LOAD' END KETERANGAN, 
		EI,
		CASE WHEN STATUS='UC' THEN '' ELSE TO_CHAR (SIZE_) END SIZE_,
        CASE WHEN STATUS='UC' THEN '' ELSE TYPE_ END TYPE_,
		STATUS,HZ,BOX,OI,
		to_char(TARIF, '999,999,999.99') TARIF, VAL, 
		to_char(JUMLAH, '999,999,999.99') JUMLAH, 
		CASE WHEN STATUS='UC' THEN NULL
        WHEN HEIGHT='OOG' THEN '/OOG'   ELSE '' END HEIGHT
		FROM RBM_D 
		WHERE NO_UKK='$no_ukk' and NO_PRANOTA='$prn'order by EI DESC";
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
					<td align="center">'.$rows['OI'].'</td>
					<td align="center">&nbsp;</td>
					<td align="center">'.$rows['SIZE_'].'</td>
					<td align="center">'.$rows['TYPE_'].''.$rows['HEIGHT'].'</td>
					<td align="center">'.$rows['STATUS'].'</td>
					<td align="center">'.$rows['HZ'].'</td>
					<td align="center">'.$rows['BOX'].'</td>
					<td align="right">'.$rows['TARIF'].'</td>
					<td align="center">'.$rows['VAL'].'</td>
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
	
$tbl .= <<<EOD
						<tr><td colspan="4">
							<table>
										<tr>
											<td width="400"></td>
											<td>Discount</td>
											<td width="25">:</td>
											<td width="100" align="right"> 0</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Administrasi</td><td  width="25">:</td>
											<td width="100" align="right"> $adm</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Dasar Pengenaan Pajak</td><td  width="25">:</td>
											<td width="100" align="right"> $tag</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah PPN</td><td  width="25">:</td>
											<td width="100" align="right"> $ppn</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah PPN Subsidi</td>
											<td  width="25">:</td>
											<td width="100" align="right"> 0</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah Dibayar</td>
											<td  width="25">:</td>
											<td width="100" align="right"> $tagtot</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah Uper Dibayar</td>
											<td  width="25">:</td>
											<td width="100" align="right"> $uper</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Jumlah Sisa Uper / Piutang</td>
											<td  width="25">:</td>
											<td width="100" align="right">$sisa</td>
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
					<tr><td colspan="2"></td><td colspan="2" align="center">CABANG PELABUHAN JAMBI</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">An.GENERAL MANAGER</td></tr>
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
	
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$limit1 = ($jum_data_page * ($pg-1)) + 1;	//limit bawah
	$limit2 = $jum_data_page * $pg;				//limit atas
	
	if($pg < $jum_page) {	//buat garis silang bagian bawah nota
		$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 0, 0));
		$pdf->Line(10, 200, 205, 280, $style);		
		$pdf->Line(10, 280, 205, 200, $style);		
	}
}

while($i<10) {	// apabila jumlah barang kurang dari 12 pada page terakhir, ditambahkan space
	$space .= "<tr><td></td><tr>";
	$i++;
}

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>