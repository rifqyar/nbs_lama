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

$pdf->SetFont('courier', '', 10);
 
//
$db = getDB();
// 

$id_req = $_GET["id"];
$remark = $_GET["remark"]; // shift, non shift
$nm_user = $_SESSION["NAMA_LENGKAP"];

//$adm = $_GET["adm"];
//$ttl = $_GET["total"];
//$ppn = $_GET["ppn"];
//$bayar = $_GET["bayar"];


$row12 = $db->query("SELECT NO_UPER_ALAT, 
                           NAMA_PBM,
                           VESSEL,
                           VOYAGE,
						   KADE,
						   TERMINAL,
						   PPN,
						   TOTAL
						   FROM GLC_UPER_ALAT_H
                           WHERE ID_REQ = '$id_req'"
						   )->fetchRow();

$row13 = $db->query("SELECT TO_CHAR(ETA,'DD-MM-YYYY') AS ETA,
					        TO_CHAR(ETD,'DD-MM-YYYY') AS ETD
						   FROM GLC_REQUEST
                           WHERE ID_REQ = '$id_req'"
						   )->fetchRow();


$no_uper_alat = $row12['NO_UPER_ALAT'];
$pbm = $row12['NAMA_PBM'];
$vessel = $row12['VESSEL'];
$voyage = $row12['VOYAGE'];
$kade = $row12['KADE'];
$terminal = $row12['TERMINAL'];
$eta = $row13['ETA'];
$etd = $row13['ETD'];	
$ppn = $row12['PPN'];
$total = $row12['TOTAL'];
$byr = $ppn+$total;

$tglproses = date("d-m-Y H:i:s");

$bilangan = toTerbilang($byr);

$html = ' 	
<div id="print" style="margin-left:0px;" >
<table>
	<tr><td colspan="32">&nbsp;</td></tr>
    <tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="32" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
	</tr>
	<tr>
		<td colspan="32" align="left"><b>CABANG TANJUNG PRIOK</b></td>
	</tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="19"></td>
		<td colspan="4" align="left">Nomor</td>
		<td colspan="1" align="right">:</td>
		<td colspan="8" align="left"> '.$no_uper_alat.'</td>
	</tr>
	<tr>
		<td colspan="19"></td>
		<td colspan="4" align="left">No. Doc</td>
		<td colspan="1" align="right">:</td>
		<td colspan="8" align="left"> '.$id_req.'</td>
	</tr>
	
	<tr>
		<td COLSPAN="19"></td>
		<td COLSPAN="4" align="left">Tgl.Proses</td>
		<td colspan="1" align="right">:</td>
		<td COLSPAN="8" align="left"> '.$tglproses.'</td>
	</tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td COLSPAN="32" align="center"><font size="12"><b>UPER PENGGUNAAN ALAT</b></font></td>
	</tr> 
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$pbm.'</font></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$vessel.'/'.$voyage.'</font></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$kade.'/'.$terminal.'</font></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$eta.' s/d '.$etd.'</font></td>
		<td></td>
	</tr>
    <tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td></td>
		<td colspan="3" ><b>ALAT</b></td>
		<td colspan="5" align="center"><b>START</b></td>
		<td colspan="5" align="center"><b>END</b></td>
	    <td colspan="4" align="center"><b>SHIFT</b></td>
		<td colspan="6" align="right"><b>TARIF</b></td>
		<td colspan="6" align="right"><b>JUMLAH</b></td>
		<td colspan="2"></td>
	</tr>
	<tr><td colspan="32">--------------------------------------------------------------------------------------------</td></tr>
	';

//======================= Detail Uper ===========================//

$query3 = "SELECT ID_ALAT FROM GLC_UPER_ALAT_D WHERE NO_UPER_ALAT = '$no_uper_alat'";
$eksekusi3 = $db->query($query3);
$col_preview3 = $eksekusi3->getAll();

	$n=0;
	foreach($col_preview3 as $b)
	{
		$id_alat[$n] = $b['ID_ALAT'];
		$hasil3[$n] = $db->query("SELECT TO_CHAR(START_DATE,'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
								   TO_CHAR(END_DATE,'DD-MM-YYYY HH24:MI:SS') AS END_WORK,
								   JUMLAH_SHIFT,
								   TARIF,
								   TOTAL
    							   FROM GLC_UPER_ALAT_D 
								   WHERE NO_UPER_ALAT = '$no_uper_alat' 
								   AND ID_ALAT = '$id_alat[$n]'")->fetchRow();
		
		$start_work[$n] = $hasil3[$n]['START_WORK'];
		$end_work[$n] = $hasil3[$n]['END_WORK'];
		$jumlah_shift[$n] = $hasil3[$n]['JUMLAH_SHIFT'];
		$td[$n] = $hasil3[$n]['TARIF'];
		$ttl[$n] = $hasil3[$n]['TOTAL'];
		
		$html .='<tr>
			<td></td>
			<td colspan="3">'.$id_alat[$n].'</td>
			<td colspan="5" align="center">'.$start_work[$n].'</td>
			<td colspan="5" align="center">'.$end_work[$n].'</td>
			<td colspan="4" align="center">'.$jumlah_shift[$n].'</td>
     		<td colspan="6" align="right">'.number_format($td[$n],2).'</td>
			<td colspan="6" align="right">'.number_format($ttl[$n],2).'</td>
			<td colspan="2"></td>
		</tr>';
		
		$n++;
	}

//======================= Detail Uper ===========================//	

$html .='	<tr><td colspan="32">--------------------------------------------------------------------------------------------</td></tr>
			<tr><td colspan="32">&nbsp;</td></tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Tagihan :</td>
                    <td colspan="6" align="right">'.number_format($total,2).'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">PPN 10% :</td>
                    <td colspan="6" align="right">'.number_format($ppn,2).'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Total Tagihan :</td>
                    <td colspan="6" align="right">'.number_format($byr,2).'</td>
				</tr>
				<tr><td colspan="32">&nbsp;</td></tr>
	            <tr><td colspan="32">&nbsp;</td></tr>
	            <tr><td colspan="32">&nbsp;</td></tr>
				<tr>
					<td colspan="6"><font size="9">USER : '.$nm_user.'</font></td>
				</tr>
				<tr><td colspan="32">&nbsp;</td></tr>
				<tr>
				<td colspan="17"><font size="8">Nota sebagai Faktur Pajak berdasarkan Peraturan Dirjen Pajak
				Nomor 10/PJ/2010 Tanggal 9 Maret 2010</font></td>
				</tr>
				<tr><td colspan="32">&nbsp;</td></tr>
				<tr>
					<td colspan="22"></td>
					<td colspan="7" align="center"><font size="8">'.date("d-m-Y H:i:s").'</font></td>
				</tr>
				<tr>
					<td colspan="22"></td>
					<td colspan="7" align="center"><font size="8">MANAGER KEUANGAN</font></td>
				</tr>
				<tr><td></td></tr><tr><td></td></tr>
				<tr>
					<td colspan="22"></td>
					<td colspan="7" align="center"><font size="8"><u>TRY DJUNAIDY</u></font></td>
				</tr>
				<tr>
					<td colspan="22"></td>
					<td colspan="7" align="center"><font size="8">NIPP : 270066463</font></td>
				</tr>
				<tr><td colspan="32">&nbsp;</td></tr>
				<tr>
					<td colspan="22"><font size="8"># <b><i>'.$bilangan.' rupiah</i></b></font></td>
					<td colspan="7"></td>
				</tr>
				<tr>
					<td colspan="22"><font size="8">Pelayanan Jasa Sewa Alat</font></td>
					<td colspan="7"></td>
				</tr>
				<tr>
					<td></td>
				</tr>
			</table>
			</div>';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('nota_glc.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>