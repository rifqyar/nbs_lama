<?php
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

$tbl .= <<<EOD
									$tamb_tbl
									
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
		to_char(PPN, '999,999,999.99') PPN FROM RBM_H WHERE NO_UKK='$no_ukk'";
	$res3=$db->query($qh3);
	$rd3=$res3->fetchRow();
	$adm 		= $rd3['ADM'];
	$total 		= $rd3['TOTAL'];
	$tagihan 	= $rd3['TAGIHAN'];
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
											<td>Administrasi</td><td  width="25">:</td>
											<td width="80" align="right"> $adm</td>
										</tr>
										<tr>
											<td width="400"></td>
											<td>Dasar Pengenaan Pajak</td><td  width="25">:</td>
											<td width="80" align="right"> $tagihan</td>
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
											<td width="80" align="right"> $total</td>
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