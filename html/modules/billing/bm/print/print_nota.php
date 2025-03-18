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

$id_req = $_GET["id_req"];
$nm_user = $_SESSION["NAMA_LENGKAP"];

$adm = $_GET["adm"];
$ttl = $_GET["total"];
$ppn = $_GET["ppn"];
$bayar = $_GET["byr"];


$row10 = $db->query("SELECT A.ID_REQ, 
                           A.NO_UPER,
                           C.NAMA,
						   C.NPWP,
						   C.ALAMAT,
						   C.KODE_PBM,
						   C.STATUS_PBM,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK,
						   A.TERMINAL,
						   TO_CHAR(A.TGL_REQ,'DD-MM-YYYY HH24:MI:SS') AS TGL_REQ FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   AND A.ID_REQ = '$id_req'"
						   )->fetchRow();

$pbm = $row10['NAMA'];
$npwp = $row10['NPWP'];
$alamat = $row10['ALAMAT'];						   
$kode_pbm = $row10['KODE_PBM'];
$terminal = $row10['TERMINAL'];
$vessel = $row10['NAMA_VESSEL'];
$voyage = $row10['VOYAGE'];
$tgl_entry = $row10['TGL_REQ'];
$remark = $row10['REMARK'];
$status_pbm = $row10['STATUS_PBM'];

$jml_bayar = number_format($bayar,2);
$bilangan = toTerbilang($bayar);

//===================== Update Status Request =====================//
$update_req_stat = "UPDATE GLC_REQUEST SET STATUS='I' WHERE ID_REQ='$id_req'";
$db->query($update_req_stat);
//===================== Update Status Request =====================//

//===================== generate ID NOTA =======================//
$prefiks = "REQ";
$tglnota = date("d-m-Y H:i:s");
$tahun = date("Y");
$bulan = date("m");
$id_nota = $prefiks."/".$bulan."/".$tahun;

$insert_nota = "INSERT INTO GLC_NOTA (ID_NOTA,ID_REQ,TGL_NOTA,KODE_PBM,TERMINAL,VESSEL,VOYAGE,ADM,PPN,TOTAL,USER_ENTRY,TGL_ENTRY,REMARK) VALUES ('$id_nota','$id_req',TO_DATE('$tglnota','dd/mm/yyyy HH24:MI:SS'),'$kode_pbm','$terminal','$vessel','$voyage','$adm','$ppn','$ttl','$nm_user',TO_DATE('$tgl_entry','dd/mm/yyyy HH24:MI:SS'),'$remark')";
$db->query($insert_nota);
//===================== generate ID NOTA =======================//

$html = ' 	
<div id="print" style="margin-left:0px;" >
<table>
	<tr><td colspan="32">&nbsp;</td></tr>
    <tr><td colspan="32">&nbsp;</td></tr>
    <tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="32" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
	</tr>
	<tr>
		<td colspan="32" align="left"><b>CABANG TANJUNG PRIOK</b></td>
	</tr>
	<tr>
		<td colspan="26"></td>
		<td colspan="5" align="right"><b>'.$id_nota.'</b></td>
	</tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="19"></td>
		<td colspan="4" align="left">No. Faktur</td>
		<td colspan="1" align="right">:</td>
		<td colspan="8" align="left">-</td>
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
		<td COLSPAN="8" align="left"> '.$tglnota.'</td>
	</tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td COLSPAN="32" align="right"><font size="12"><b>PERHITUNGAN PELAYANAN JASA SEWA ALAT</b></font></td>
	</tr> 
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$pbm.'</font></td>
		<td></td>
	</tr> 
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$npwp.'</font></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$alamat.'</font></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">'.$vessel.'/'.$voyage.'</font></td>
		<td></td>
	</tr>	   
    <tr><td colspan="32">&nbsp;</td></tr>
    <tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="4" ><b>ALAT</b></td>
		<td colspan="5" align="center"><b>START</b></td>
		<td colspan="5" align="center"><b>END</b></td>
	    <td colspan="4" align="center"><b>SHIFT</b></td>
		<td colspan="5" align="right"><b>TARIF</b></td>
		<td colspan="2" align="left"><b>VAL</b></td>
		<td colspan="5" align="right"><b>JUMLAH</b></td>
		<td colspan="2"></td>
	</tr>
	<tr><td colspan="32">--------------------------------------------------------------------------------------------</td></tr>
	';

//======================= Insert Detail Nota ===========================//

if($status_pbm=="SELEKSI")
	{
		$tarif_dasar=6500000;
		$td = number_format($tarif_dasar,2); 
	}
	else
	{
		$tarif_dasar=7000000;
		$td = number_format($tarif_dasar,2);
	}

$query3 = "SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req'";
$eksekusi3 = $db->query($query3);
$col_preview3 = $eksekusi3->getAll();

	$n=0;
	foreach($col_preview3 as $b)
	{
		$id_alat[$n] = $b['ID_ALAT'];
		$hasil3[$n] = $db->query("SELECT TO_CHAR(MIN(ST_WRK),'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
								   TO_CHAR(MAX(END_WRK),'DD-MM-YYYY HH24:MI:SS') AS END_WORK,
								   ROUND((SUM(WRK_R)*(24))/8) AS JML_SHIFT
    							   FROM GLC_DETAIL_REQ_SHIFT WHERE ID_REQ = '$id_req' AND ID_ALAT = '$id_alat[$n]'")->fetchRow();
		
		$start_work[$n] = $hasil3[$n]['START_WORK'];
		$end_work[$n] = $hasil3[$n]['END_WORK'];
		$jumlah_shift[$n] = $hasil3[$n]['JML_SHIFT'];		
		$total[$n] = number_format($jumlah_shift[$n]*$tarif_dasar,2);
		
		$html .='<tr>
			<td colspan="4">'.$id_alat[$n].'</td>
			<td colspan="5" align="center">'.$start_work[$n].'</td>
			<td colspan="5" align="center">'.$end_work[$n].'</td>
			<td colspan="4" align="center">'.$jumlah_shift[$n].'</td>
     		<td colspan="5" align="right">'.$td.'</td>
			<td colspan="2" align="left">IDR</td>
			<td colspan="5" align="right">'.$total[$n].'</td>
			<td colspan="2"></td>
		</tr>';
		
		$insert_detail_nota[$n] = "INSERT INTO GLC_DETAIL_NOTA_SHIFT (NO_NOTA,ID_ALAT,START_DATE,END_DATE,JUMLAH_SHIFT,TARIF,TOTAL) VALUES ('$id_nota','$id_alat[$n]',TO_DATE('$start_work[$n]','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end_work[$n]','dd/mm/yyyy HH24:MI:SS'),'$jumlah_shift[$n]','$td','$total[$n]')";
		$db->query($insert_detail_nota[$n]);
		
		$n++;
	}

//======================= Insert Detail Nota ===========================//	

$html .='	<tr><td colspan="32">--------------------------------------------------------------------------------------------</td></tr>
			<tr><td colspan="32">&nbsp;</td></tr>
	        <tr><td colspan="32">&nbsp;</td></tr>
			<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Discount :</td>
                    <td colspan="6" align="right">-</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Administrasi :</td>
                    <td colspan="6" align="right">'.$adm.'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Dasar Pengenaan Pajak :</td>
                    <td colspan="6" align="right">'.$ttl.'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Jumlah PPN :</td>
                    <td colspan="6" align="right">'.$ppn.'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Bea Materai :</td>
                    <td colspan="6" align="right">-</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Jumlah Dibayar :</td>
                    <td colspan="6" align="right">'.$jml_bayar.'</td>
				</tr>
				<tr><td colspan="32">&nbsp;</td></tr>
	            <tr><td colspan="32">&nbsp;</td></tr>
				<tr>
					<td colspan="6"><font size="9">USER : '.$nm_user.'</font></td>
				</tr>
				<tr><td colspan="32">&nbsp;</td></tr>
	            <tr><td colspan="32">&nbsp;</td></tr>
				<tr><td colspan="32">&nbsp;</td></tr>
	            <tr><td colspan="32">&nbsp;</td></tr>
				<tr><td></td></tr>
				<tr>
				<td colspan="17"><font size="8">Nota sebagai Faktur Pajak berdasarkan Peraturan Dirjen Pajak
				Nomor 10/PJ/2010 Tanggal 9 Maret 2010</font></td>
				</tr>
				<tr><td colspan="32">&nbsp;</td></tr>
	            <tr><td colspan="32">&nbsp;</td></tr>
				<tr><td colspan="32">&nbsp;</td></tr>
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