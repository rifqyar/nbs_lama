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
$req = $_GET['no_req'];

// jumlah detail barangnya
//$query_jum="SELECT COUNT(1) JUM_DETAIL FROM TB_NOTA_RECEIVING_D A WHERE A.ID_REQ='$no_req'";
$data_jum = 10;
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum%$jum_data_page)>10 || ($data_jum%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 9);
	
$qh = "select h.NO_REQ_DEV, 
		          h.NO_NOTA, 
				  h.NM_AGEN,
				  d.NM_KAPAL,
				  d.VOY_IN,
				  d.VOY_OUT,
				  to_char(sum(TARIF), '999,999,999.99') as TOTTARIF,
				  to_char(sum(TARIF_5_PERSEN), '999,999,999.99') as PENGEMBALIAN,
				  to_char(sum(PPN_5_PERSEN), '999,999,999.99') as PPN_PENGEMBALIAN,
				  to_char((sum(TARIF_5_PERSEN)+sum(PPN_5_PERSEN)), '999,999,999.99') as TTL_PENGEMBALIAN,
				  to_char(h.TGL_BA,'DAY') as H_BA,
				  to_char(h.TGL_BA,'DD') as DAY_BA,
				  to_char(h.TGL_BA,'MM') as MONTH_BA,
				  to_char(h.TGL_BA,'YYYY') as YEAR_BA
	        from DISKON_NOTA_DEL_H h, DISKON_NOTA_DEL_DTL d 
			      where h.NO_REQ_DEV = d.KD_PERMINTAAN
			      and h.NO_REQ_DEV = '$req'
			      group by h.NO_REQ_DEV,
				           h.NO_NOTA,
						   h.NM_AGEN,
						   d.NM_KAPAL,
						   d.VOY_IN,
						   d.VOY_OUT
						   ";
	$res=$db->query($qh);
	$rd=$res->fetchRow();	
	
$no_req = $rd['NO_REQ_DEV'];
$no_nota = $rd['NO_NOTA'];
$nm_agen  $rd['NM_AGEN'];
$nm_kapal = $rd['NM_KAPAL'];
$voy_in = $rd['VOY_IN'];
$voy_out = $rd['VOY_OUT'];
$tot_tarif = $rd['TOTTARIF'];
$pengembalian = $rd['PENGEMBALIAN'];
$ppn_pengembalian = $rd['PPN_PENGEMBALIAN'];
$ttl_pengembalian = $rd['TTL_PENGEMBALIAN'];
$hari = hr_ina($rd['H_BAY'];
$day_ba = $rd['DAY_BA'];
$month_ba = bulan_indonesia($rd['MONTH_BA']);
$year_ba = $rd['YEAR_BA'];


//================== detail =====================//
$qd = "select SZ,
			  TY,
			  ST,
			  count(NO_CONTAINER) as JUM_CONT,
			  KETERANGAN,
			  to_char(sum(TARIF), '999,999,999.99') TARIF,
			  to_char(sum(TARIF_5_PERSEN), '999,999,999.99') TARIF_5_PERSEN,
			  to_char(sum(PPN_5_PERSEN), '999,999,999.99') PPN_5_PERSEN,
			  to_char(sum(TARIF_5_PERSEN+PPN_5_PERSEN), '999,999,999.99') TTL_INSENTIF
		from DISKON_NOTA_DEL_DTL 
		where KD_PERMINTAAN = '$req'
		group by SZ,TY,ST,KETERANGAN,TARIF,TARIF_5_PERSEN,PPN_5_PERSEN";
	$red=$db->query($qd);
	$rdt=$red->getAll();

$no=1;	
foreach ($rdt as $row8)
{
	$tblx .='	<tr>					
					<td align="center">'.$no.'</td>
					<td align="center">'.$row8['SZ'].'/'.$row8['TY'].'/'.$row8['ST'].'</td>
					<td align="center">'.$row8['JUM_CONT'].'</td>
					<td align="center">'.$row8['KETERANGAN'].'</td>
					<td align="center">'.$row8['TARIF'].'</td>
					<td align="center">'.$row8['TARIF_5_PERSEN'].'</td>
					<td align="center">'.$row8['PPN_5_PERSEN'].'</td>
					<td align="center">'.$row8['TTL_INSENTIF'].'</td>
                </tr>';
	$no++;
}
//================== detail =====================//

	
	$tbl = <<<EOD
			<table border="0">
				<tr height="20">
                    <td colspan="32" align="left"></td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><u><b><font size="+2">BERITA ACARA</font></b></u></td>
                </tr>
                <tr>
                    <td colspan="32" align="center"><b><font size="+1">No ............................................</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><b><font size="2">TENTANG</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><font size="2">PEMBERIAN INSENTIF LIFT ON UNTUK BIAYA PENUMPUKAN / GERAKAN (DELIVERY)</font></td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><font size="2">A/N $nm_agen</font></td>
                </tr>
                <tr height="20">
                    <td COLSPAN="32" align="center"></td>
                </tr>
EOD;

$content = '
			<p align="justify" style="margin-left:50px; margin-right:50px;"><font size="2">
			Pada hari ini $hari, tanggal $day_ba&nbsp;$month_ba&nbsp;$year_ba yang bertanda tangan di bawah ini menyatakan bahwa :
			<ol style="margin-left:50px; margin-right:50px;">
				<li>
				<p align="justify"><font size="2">Menunjuk Surat Keputusan Direksi nomor KU.30/3/6/PI.II-12 tanggal 28 Oktober 2012 perihal Pemberian Insentif Terhadap Percepatan Pengeluaran Petikemas Impor Isi.
				</p>
				</li>
				<li>
				<p align="justify"><font size="2">
				Mengalir butir 1 (satu) di atas, bersama ini kami sampaikan hal sebagai berikut :
					<ol type="a" style="margin-left:18px;">
						<li>
						<p align="justify"><font size="2">
							Bahwa $nm_agen telah membayar Nota Pelayanan Jasa Penumpukan / Gerakan (Delivery) Nomor <b>$no_nota</b> pada $nm_kapal Voy. $voy_in/$voy_out dengan data container, sebagai berikut :
							<br>
							<table border="1" width="700px" align="center" style="border-collapse:collapse; border-color:#000000;">
								<tr>
									<td align="center" width="30">NO</td>
									<td align="center">SZ/TY/ST</td>
									<td align="center">JUMLAH</td>
									<td align="center">KETERANGAN</td>
									<td align="center">TARIF DASAR</td>
									<td align="center">INTENSIF 5%</td>
									<td align="center">PPN</td>
									<td align="center">PENGEMBALIAN</td>
								</tr>
								$tblx
								<tr>
									<td align="center" colspan="4"><b>TOTAL</b></td>
									<td align="center"><b>$tot_tarif</b></td>
									<td align="center"><b>$pengembalian</b></td>
									<td align="center"><b>$ppn_pengembalian</b></td>
									<td align="center"><b>$ttl_pengembalian</b></td>
								</tr>
							</table>
						</p>
						</li>
						<li>
						<p align="justify"><font size="2">
							Terkait dengan butir a. di atas bahwa data container yang mendapatkan insentif Lift On sebesar 5% dikarenakan masih dalam masa bebas biaya penumpukan dan sudah keluar dari lapangan.
						</p>
						</li>
					</ol>
				</p>
				</li>
				<li>
				<p align="justify"><font size="2">
					Mengingat kejadian di atas, maka $nm_agen dapat diberikan insentif Lift On atas Nota Pelayanan Jasa Penumpukan / Gerakan (Delivery) Nomor $no_nota yaitu sebesar Rp $pengembalian (sudah termasuk PPN 10%).
				</p>
				</li>
				<li>
				<p align="justify"><font size="2">
					Demikian Berita Acara ini dibuat dengan sebenarnya untuk diketahui dan dapat dipergunakan seperlunya dan apabila di kemudian hari terdapat kekeliruan dapat diperbaiki sebagaimana semestinya.
				</p>
				</li>
			</ol>
		</font></p> ';

	$tbl .= <<<EOD
				<tr>
                    <td colspan="32" align="center">
					$content
					</td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>	
				<tr>
                    <td colspan="32" align="center"><b><font size="2">DIVERIFIKASI OLEH :</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>	
                <tr>
                    <td colspan="8" align="center"><b><font size="2">SPV PEMASARAN<br/>OPERASI TERMINAL III</font></b></td>
					<td colspan="8" align="center"><b><font size="2">SPV NOTA BARANG DAN<br/>RUPA-RUPA</font></b></td>
					<td colspan="8" align="center"><b><font size="2">SPV PENGOPERASIAN<br/>SISTEM</font></b></td>
					<td colspan="8" align="center"><b><font size="2">SPV HUBUNGAN<br/>PELANGGAN</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="8" align="center"><b><font size="2"><u>ARIEF NUGROHO RIADI</u><br/>NIPP. 270115635</font></b></td>
					<td colspan="8" align="center"><b><font size="2"><u>CECEP TATENG</u><br/>NIPP. 266085865</font></b></td>
					<td colspan="8" align="center"><b><font size="2"><u>DONALD H. SITOMPUL</u><br/>NIPP. 277066981</font></b></td>
					<td colspan="8" align="center"><b><font size="2"><u>HENDRI ADOLF</u><br/>NIPP. 270035957</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><b><font size="2">MENGETAHUI :</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="8" align="center"><b><font size="2">MANAGER<br/>PEMASARAN & ADMINISTRASI<br/>OPERASI TERMINAL III</font></b></td>
					<td colspan="8" align="center"><b><font size="2">ASMAN<br/>PENDAPATAN & PIUTANG</font></b></td>
					<td colspan="8" align="center"><b><font size="2">ASMAN<br/>SISTEM INFORMASI</font></b></td>
					<td colspan="8" align="center"><b><font size="2">ASMAN<br/>PELAYANAN PELANGGAN</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="8" align="center"><b><font size="2"><u>SUNU BEKTI PUDJOTOMO</u><br/>NIPP. 262054566</font></b></td>
					<td colspan="8" align="center"><b><font size="2"><u>ABDUL LATIEF</u><br/>NIPP. 272086096</font></b></td>
					<td colspan="8" align="center"><b><font size="2"><u>DICKY SANTOSA</u><br/>NIPP. 274046923</font></b></td>
					<td colspan="8" align="center"><b><font size="2"><u>SOFYAN GUMELAR S.</u><br/>NIPP. 270045410</font></b></td>
                </tr>
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