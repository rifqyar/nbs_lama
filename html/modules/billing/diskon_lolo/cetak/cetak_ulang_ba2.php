<?php
//============================================================+
// File name   : example_002.php
// Begin       : 2008-03-04
// Last Update : 2010-08-08
//
// Description : Example 002 for TCPDF class
//               Removing Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               Manor Coach House, Church Hill
//               Aldershot, Hants, GU12 4RQ
//               UK
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Removing Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 048');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);


// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins($left = 4,
				 $top,
				 $right = 4,
				 $keepmargins = false );
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
//$pdf->SetFont('courier', 'B', 20);

// add a page
$pdf->AddPage();

//$pdf->Write(0, 'Example of HTML tables', '', 0, 'L', true, 0, false, false, 0);

$pdf->SetFont('times', '', 10);


$db = getDB();
$req = $_GET['no_req'];


$row13 = $db->query("select h.NO_NOTA, 
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
									   d.VOY_OUT,
									   h.TGL_BA"
									   )->fetchRow();	

$no_nota = $row13['NO_NOTA'];
$nm_agen = $row13['NM_AGEN'];
$nm_kapal = $row13['NM_KAPAL'];
$voy_in = $row13['VOY_IN'];
$voy_out = $row13['VOY_OUT'];
$tot_tarif = $row13['TOTTARIF'];
$pengembalian = $row13['PENGEMBALIAN'];
$ppn_pengembalian = $row13['PPN_PENGEMBALIAN'];
$ttl_pengembalian = $row13['TTL_PENGEMBALIAN'];
$hari = hr_ina($row13['H_BA']);
$day_ba = $row13['DAY_BA'];
$month_ba = bulan_indonesia($row13['MONTH_BA']);
$year_ba = $row13['YEAR_BA'];


$html = ' 	
	<div id="print" style="margin-left:0px;" >
		<table>
				<tr>
                    <td colspan="32" align="left">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="left">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="left">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="left">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="left">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><u><b><font size="+1">BERITA ACARA</font></b></u></td>
                </tr>
                <tr>
                    <td colspan="32" align="center"><b><font size="10">No ............................................</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><b><font size="8">TENTANG</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><font size="8">PEMBERIAN INSENTIF LIFT ON UNTUK BIAYA PENUMPUKAN / GERAKAN (DELIVERY)</font></td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><font size="8">A/N '.$nm_agen.'</font></td>
                </tr>
		</table>
	';

//================== content =====================//

$html .= '<p align="justify" style="margin-left:50px; margin-right:50px;"><font size="10">
			Pada hari ini '.$hari.', tanggal '.$day_ba.'&nbsp;'.$month_ba.'&nbsp;'.$year_ba.' yang bertanda tangan di bawah ini menyatakan bahwa :
			<ol style="margin-left:50px; margin-right:50px;">
				<li>
				<p align="justify"><font size="10">Menunjuk Surat Keputusan Direksi nomor KU.30/3/6/PI.II-12 tanggal 28 Oktober 2012 perihal Pemberian Insentif Terhadap Percepatan Pengeluaran Petikemas Impor Isi.
				</p>
				</li>
				<li>
				<p align="justify"><font size="10">Mengalir butir 1 (satu) di atas, bersama ini kami sampaikan hal sebagai berikut :
					<ul style="margin-left:18px;">
						<li><font size="10">Bahwa '.$nm_agen.' telah membayar Nota Pelayanan Jasa Penumpukan / Gerakan (Delivery) Nomor <b>'.$no_nota.'</b> pada '.$nm_kapal.' Voy. '.$voy_in.'/'.$voy_out.' dengan data container, sebagai berikut :
							<br>
							<table border="1" width="670px" align="center" style="border-collapse:collapse; border-color:#000000;">
								<tr>
									<td align="center" width="30">NO</td>
									<td align="center">SZ/TY/ST</td>
									<td align="center">JUMLAH</td>
									<td align="center">KETERANGAN</td>
									<td align="center">TARIF DASAR</td>
									<td align="center">INTENSIF 5%</td>
									<td align="center">PPN</td>
									<td align="center" width="90">PENGEMBALIAN</td>
								</tr>';
								
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
	$html .='	<tr>					
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
								
$html .=   '
								<tr>
									<td align="center" colspan="4"><b>TOTAL</b></td>
									<td align="center"><b>'.$tot_tarif.'</b></td>
									<td align="center"><b>'.$pengembalian.'</b></td>
									<td align="center"><b>'.$ppn_pengembalian.'</b></td>
									<td align="center"><b>'.$ttl_pengembalian.'</b></td>
								</tr>
							</table></li>
						<li><font size="10">Terkait dengan butir pertama di atas bahwa data container yang mendapatkan insentif Lift On sebesar 5% dikarenakan masih dalam masa bebas biaya penumpukan dan sudah keluar dari lapangan.</li>
					</ul>
				</p>
				</li>
				<li>
				<p align="justify"><font size="10">Mengingat kejadian di atas, maka '.$nm_agen.' dapat diberikan insentif Lift On atas Nota Pelayanan Jasa Penumpukan / Gerakan (Delivery) Nomor '.$no_nota.' yaitu sebesar Rp '.$pengembalian.' (sudah termasuk PPN 10%).</p>
				</li>
				<li>
				<p align="justify"><font size="10">Demikian Berita Acara ini dibuat dengan sebenarnya untuk diketahui dan dapat dipergunakan seperlunya dan apabila di kemudian hari terdapat kekeliruan dapat diperbaiki sebagaimana semestinya.</p>
				</li>
			</ol>
		</font></p> ';

//================== content =====================//


$html .='		
		<table>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>	
				<tr>
                    <td colspan="32" align="center"><b><font size="9">DIVERIFIKASI OLEH :</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>	
                <tr>
                    <td colspan="8" align="center"><b><font size="9">SPV PEMASARAN<br/>OPERASI TERMINAL III</font></b></td>
					<td colspan="8" align="center"><b><font size="9">SPV NOTA BARANG DAN<br/>RUPA-RUPA</font></b></td>
					<td colspan="8" align="center"><b><font size="9">SPV PENGOPERASIAN<br/>SISTEM</font></b></td>
					<td colspan="8" align="center"><b><font size="9">SPV HUBUNGAN<br/>PELANGGAN</font></b></td>
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
                    <td colspan="8" align="center"><b><font size="9"><u>ARIEF NUGROHO RIADI</u><br/>NIPP. 270115635</font></b></td>
					<td colspan="8" align="center"><b><font size="9"><u>CECEP TATENG</u><br/>NIPP. 266085865</font></b></td>
					<td colspan="8" align="center"><b><font size="9"><u>DONALD H. SITOMPUL</u><br/>NIPP. 277066981</font></b></td>
					<td colspan="8" align="center"><b><font size="9"><u>HENDRI ADOLF</u><br/>NIPP. 270035957</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="32" align="center"><b><font size="8">MENGETAHUI :</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>
				<tr>
                    <td colspan="8" align="center"><b><font size="9">MANAGER<br/>PEMASARAN & ADMINISTRASI<br/>OPERASI TERMINAL III</font></b></td>
					<td colspan="8" align="center"><b><font size="9">ASMAN<br/>PENDAPATAN & PIUTANG</font></b></td>
					<td colspan="8" align="center"><b><font size="9">ASMAN<br/>SISTEM INFORMASI</font></b></td>
					<td colspan="8" align="center"><b><font size="9">ASMAN<br/>PELAYANAN PELANGGAN</font></b></td>
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
                    <td colspan="8" align="center"><b><font size="9"><u>SUNU BEKTI PUDJOTOMO</u><br/>NIPP. 262054566</font></b></td>
					<td colspan="8" align="center"><b><font size="9"><u>ABDUL LATIEF</u><br/>NIPP. 272086096</font></b></td>
					<td colspan="8" align="center"><b><font size="9"><u>DICKY SANTOSA</u><br/>NIPP. 274046923</font></b></td>
					<td colspan="8" align="center"><b><font size="9"><u>SOFYAN GUMELAR S.</u><br/>NIPP. 270045410</font></b></td>
                </tr>
		</table>
			</div>';

			
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('berita_acara.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>