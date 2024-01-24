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
$idplp = $_GET['id_plp'];
$row13 = $db->query("SELECT NAME_TPL1 AS TPS_ASAL,
							 NAME_TPL2 AS TPS_TUJUAN,
							 KD_TPL2,
							 VESSEL,
							 VOYAGE,
							 TIPE_REQ,
							 JUMLAH_BARANG,
							 TEUS_TPL2,
							 YOR_TPL2
					FROM PLAN_REQ_PLP_H
					WHERE TRIM(ID_PLP) = TRIM('$idplp')")->fetchRow();	

$tpl1 = $row13['TPS_ASAL'];
$tpl2 = $row13['TPS_TUJUAN'];
$kd_tpl2 = $row13['KD_TPL2'];
$teus_tpl2 = $row13['TEUS_TPL2'];
$yor_tpl2 = $row13['YOR_TPL2'];
$vs = $row13['VESSEL'];
$vy = $row13['VOYAGE'];

$row40 = $db->query("SELECT COUNT(*) JML FROM PLAN_REQ_PLP_D WHERE TRIM(ID_PLP) = TRIM('$idplp') AND SZ IN ('40','45')")->fetchRow();	
$teus40 = $row40['JML']*2;

$row20 = $db->query("SELECT COUNT(*) JML FROM PLAN_REQ_PLP_D WHERE TRIM(ID_PLP) = TRIM('$idplp') AND SZ = '20'")->fetchRow();	
$teus20 = $row20['JML'];

$jmlteus = $teus40+$teus20;

$tpscap = $db->query("SELECT KAPASITAS,
							 LUAS_EFEKTIF,
							 LUAS_LAP
					FROM MST_TPL
					WHERE TRIM(KD_TPS) = TRIM('$kd_tpl2')")->fetchRow();	

$tpsteus = $tpscap['KAPASITAS'];
$tgl = date('d M Y');

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
                    <td colspan="32" align="center"><u><b><font size="+1">SURAT PERNYATAAN YOR '.$kd_tpl2.'</font></b></u></td>
                </tr>
                <tr>
                    <td colspan="32" align="center"><b><font size="10">No ............................................</font></b></td>
                </tr>
		</table>
	';

//================== content =====================//

$html .= '<p align="justify" style="margin-left:50px; margin-right:50px;"><font size="10">
			Sehubungan dengan adanya rencana Pindah Lokasi Penimbunan Barang Import dari TPS Lapangan '.$tpl1.' ke TPS '.$tpl2.' dengan data sebagai berikut :
			<ol style="margin-left:50px; margin-right:50px;">
				<li>
					<table>
						<tr>
							<td colspan="6" align="left">Jumlah Container</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">'.$jmlteus.' TEUS</td>
						</tr>
						<tr>
							<td colspan="6" align="left">No. Container</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">Terlampir</td>
						</tr>
						<tr>
							<td colspan="6" align="left">Vessel/Voyage</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">'.$vs.' '.$vy.'</td>
						</tr>
						<tr>
							<td colspan="6" align="left">Jumlah POS BC 1.1</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">1 POS</td>
						</tr>
						<tr>
							<td colspan="6" align="left">Nomor B/L</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">---</td>
						</tr>
						<tr>
							<td colspan="6" align="left">Jumlah Kemasan</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">---</td>
						</tr>
						<tr>
							<td colspan="6" align="left">Kapasitas Lapangan</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">'.$tpsteus.' TEUS</td>
						</tr>
						<tr>
							<td colspan="6" align="left">Kapasitas Terisi</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">'.$teus_tpl2.' TEUS</td>
						</tr>
						<tr>
							<td colspan="6" align="left">YOR</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">'.$yor_tpl2.' %</td>
						</tr>
						<tr>
							<td colspan="6" align="left">Perusahaan Bongkar/Muat</td>
							<td colspan="2" align="left">&nbsp;:&nbsp;</td>
							<td colspan="24" align="left">PT. PELABUHAN INDONESIA INDONESIA II (Persero)</td>
						</tr>
					</table>
				</li>
			</ol>
		Dengan ini menyatakan adanya ketersediaan ruang / tempat atas barang-barang tersebut di atas untuk dipindah lokasikan ke TPS '.$tpl2.' PT. Pelabuhan Indonesia II (Persero) Cabang Tanjung Priok.<br/><br/>
		Demikian Surat Pernyataan ini dibuat dengan sebenarnya.		
		</p>';

//================== content =====================//	
	
$html .='<p><br/></p>
		 <p><br/></p>
		 <p><br/></p>
		 <p><br/></p>
			<table>
					<tr>
						<td colspan="32" align="center">&nbsp;</td>
					</tr>	
					<tr>
						<td colspan="32" align="center"><b><font size="9">DISETUJUI OLEH :</font></b></td>
					</tr>
					<tr>
						<td colspan="32" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="32" align="center">TANJUNG PRIOK, '.$tgl.'</td>
					</tr>
					<tr>
						<td colspan="32" align="center"><b><font size="9">SPV GUDANG DAN LAPANGAN</font></b></td>
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
						<td colspan="32" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="32" align="center"><u>HERJUNO DARPITO</u><br/><b><font size="9">NIPP. 271096310</font></b></td>
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