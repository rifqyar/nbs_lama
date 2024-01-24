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
							  to_char(h.TGL_REKAP,'DAY') as H_REKAP,
							  to_char(h.TGL_REKAP,'DD') as DAY_REKAP,
							  to_char(h.TGL_REKAP,'MM') as MONTH_REKAP,
							  to_char(h.TGL_REKAP,'YYYY') as YEAR_REKAP
						from DISKON_NOTA_DEL_H h, DISKON_NOTA_DEL_DTL d 
							  where h.NO_REQ_DEV = d.KD_PERMINTAAN
							  and h.NO_REQ_DEV = '$req'
							  group by h.NO_REQ_DEV,
									   h.NO_NOTA,
									   h.NM_AGEN,
									   d.NM_KAPAL,
									   d.VOY_IN,
									   d.VOY_OUT,
									   h.TGL_REKAP"
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
$hari = hr_ina($row13['H_REKAP']);
$day_rekap = $row13['DAY_REKAP'];
$month_rekap = bulan_indonesia($row13['MONTH_REKAP']);
$year_rekap = $row13['YEAR_REKAP'];


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
                    <td colspan="32" align="center"><b><font size="+1">REKAPITULASI INSENTIF LIFT ON NOTA PENUMPUKAN / GERAKAN (DELIVERY)</font></b></td>
                </tr>
                <tr>
                    <td colspan="32" align="center"><b><font size="10">A/N '.$nm_agen.'</font></b></td>
                </tr>
				<tr>
                    <td colspan="32" align="left">&nbsp;</td>
                </tr>
		</table>
	';

//================== content =====================//

$html .= '<table border="1" width="760" height="30" align="center" style="border-collapse:collapse; border-color:#000000; font-size:30px" valign="center">
								<tr>
									<td align="center" width="30">NO</td>
									<td align="center">NO REQUEST</td>
									<td align="center">NO NOTA</td>
									<td align="center" width="60">STATUS</td>
									<td align="center">TAGIHAN</td>
									<td align="center" width="100">PENGEMBALIAN</td>
									<td align="center">PPN</td>
									<td align="center">TOTAL</td>
									<td align="center" width="100">KETERANGAN</td>
								</tr>
								<tr>
									<td align="center">1</td>
									<td align="center">'.$req.'</td>
									<td align="center">'.$no_nota.'</td>
									<td align="center">LUNAS</td>
									<td align="center">'.$tot_tarif.'</td>
									<td align="center">'.$pengembalian.'</td>
									<td align="center">'.$ppn_pengembalian.'</td>
									<td align="center">'.$ttl_pengembalian.'</td>
									<td align="center">INSENTIF LIFT ON</td>
								</tr>
							</table> ';

//================== content =====================//


$html .='		
		<table>
				<tr>
                    <td colspan="32" align="center">&nbsp;</td>
                </tr>	
				<tr>
                    <td colspan="32" align="center"><b><font size="9">'.$hari.', Tanggal '.$day_rekap.'&nbsp;'.$month_rekap.'&nbsp;'.$year_rekap.'<br/>DIVERIFIKASI OLEH :</font></b></td>
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