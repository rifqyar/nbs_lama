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
$db = getDB("dblocal");
// 

$id_req = $_GET["id"];
$nm_user = $_SESSION["NAMA_LENGKAP"];
$id_user = $_SESSION["ID_USER"];
$remarks = $_GET["remark"]; //shift, non shift	

//======================= Delete Uper ===========================//
$querynya = "SELECT NO_UPER_ALAT FROM GLC_UPER_ALAT_H WHERE ID_REQ = '$id_req'";
$result1 = $db->query($querynya);
$row4 = $result1->fetchRow();			
$no_uper_alat = $row4['NO_UPER_ALAT'];
 
$delete_uper_h = "DELETE FROM GLC_UPER_ALAT_H WHERE ID_REQ='$id_req'";
$delete_uper_d = "DELETE FROM GLC_UPER_ALAT_D WHERE NO_UPER_ALAT='$no_uper_alat'";
$db->query($delete_uper_h);
$db->query($delete_uper_d);

//======================= Delete Uper ===========================//

	//======================= Uper Perusahaan, NPWP, Alamat, Vessel/Voyage ===========================//
	$query2 = "SELECT C.NAMA,					  
					  C.STATUS_PBM,
                      B.NAMA_VESSEL,
                      A.VOYAGE,
                      A.STATUS,
					  A.KADE,
					  A.TERMINAL,
					  TO_CHAR(A.ETA,'DD-MM-YYYY') AS ETA,
					  TO_CHAR(A.ETD,'DD-MM-YYYY') AS ETD,
					  TO_CHAR(A.ETA,'MM') AS MONTH_ETA,
					  TO_CHAR(A.ETA,'YYYY') AS YEAR_ETA,
					  A.ID_HEADER
					  FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                      WHERE A.KODE_KAPAL = B.KODE_KAPAL
                      AND A.KODE_PBM = C.KODE_PBM
					  AND A.ID_REQ='$id_req'";
    $eksekusi2 = $db->query($query2);
	$row_preview2 = $eksekusi2->fetchRow();

	$pbm = $row_preview2['NAMA'];	
	$vessel = $row_preview2['NAMA_VESSEL'];
	$voyage = $row_preview2['VOYAGE'];
	$status_pbm = $row_preview2['STATUS_PBM'];
	$kade = $row_preview2['KADE'];
	$terminal = $row_preview2['TERMINAL'];
	$eta = $row_preview2['ETA'];
	$etd = $row_preview2['ETD'];
	$eta_month = $row_preview2['MONTH_ETA'];
	$eta_year = $row_preview2['YEAR_ETA'];
	$seq_uper = $row_preview2['ID_HEADER'];
	//======================= Uper Perusahaan, NPWP, Alamat, Vessel/Voyage ===========================//
	
	//=============================== Uper Nomor, No.Doc, Tgl Proses =================================//	
	$rmw = Romawi($eta_month);
	$tglproses = date("d-m-Y H:i:s");
	$term = "T".$terminal;
	$nomor = $seq_uper."/".$rmw."/".$term."-".$eta_year;
	//=============================== Uper Nomor, No.Doc, Tgl Proses =================================//
	
	
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
		<td colspan="8" align="left"> '.$nomor.'</td>
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

$query3 = "SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_EST_SHIFT WHERE ID_REQ = '$id_req' ORDER BY ID_ALAT";
$eksekusi3 = $db->query($query3);
$col_preview3 = $eksekusi3->getAll();

	$n=0;
	foreach($col_preview3 as $b)
	{
		$id_alat[$n] = $b['ID_ALAT'];
		$hasil3[$n] = $db->query("SELECT TO_CHAR(MIN(MULAI),'DD-MM-YYYY HH24:MI:SS') AS START_WORK,
								   TO_CHAR(MAX(SELESAI),'DD-MM-YYYY HH24:MI:SS') AS END_WORK,
								   SUM(SHIFT) AS JML_SHIFT
    							   FROM GLC_DETAIL_EST_SHIFT 
								   WHERE ID_REQ = '$id_req' 
								   AND ID_ALAT = '$id_alat[$n]'
								   AND STAT_TIME = 'WORKING'")->fetchRow();
		
		$start_work[$n] = $hasil3[$n]['START_WORK'];
		$end_work[$n] = $hasil3[$n]['END_WORK'];
		$jumlah_shift[$n] = $hasil3[$n]['JML_SHIFT'];
        $ttl[$n] = $jumlah_shift[$n]*$tarif_dasar;		
		$total[$n] = number_format($ttl[$n],2);
		
		$html .='<tr>
			<td></td>
			<td colspan="3">'.$id_alat[$n].'</td>
			<td colspan="5" align="center">'.$start_work[$n].'</td>
			<td colspan="5" align="center">'.$end_work[$n].'</td>
			<td colspan="4" align="center">'.$jumlah_shift[$n].'</td>
     		<td colspan="6" align="right">'.$td.'</td>
			<td colspan="6" align="right">'.$total[$n].'</td>
			<td colspan="2"></td>
		</tr>';
		
		$insert_detail_uper[$n] = "INSERT INTO GLC_UPER_ALAT_D (NO_UPER_ALAT,ID_ALAT,START_DATE,END_DATE,JUMLAH_SHIFT,TARIF,TOTAL) VALUES 
               ('$nomor','$id_alat[$n]',TO_DATE('$start_work[$n]','dd/mm/yyyy HH24:MI:SS'),TO_DATE('$end_work[$n]','dd/mm/yyyy HH24:MI:SS'),'$jumlah_shift[$n]','$tarif_dasar','$ttl[$n]')";
		$db->query($insert_detail_uper[$n]);
		
		$n++;
	}
	
	$jml_alat = count($id_alat);
	$jml_total = array_sum($ttl);
	$jml_ppn = $jml_total*0.1;
	$jml_bayar = $jml_total+$jml_ppn;
	$bilangan = toTerbilang($jml_bayar);
	
//======================= Detail Uper ===========================//	

	//=============================== Insert Uper =================================//
	$insert_uper = "INSERT INTO GLC_UPER_ALAT_H (NO_UPER_ALAT,ID_REQ,NAMA_PBM,KADE,TERMINAL,VESSEL,VOYAGE,PPN,TOTAL,REMARK,TGL_INVOICE,LUNAS) VALUES 
               ('$nomor','$id_req','$pbm','$kade','$terminal','$vessel','$voyage','$jml_ppn','$jml_total','$remarks',SYSDATE,'T')";
	$db->query($insert_uper);
	//=============================== Insert Uper =================================//
		
	

$html .='	<tr><td colspan="32">--------------------------------------------------------------------------------------------</td></tr>
			<tr><td colspan="32">&nbsp;</td></tr>			
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Tagihan :</td>
                    <td colspan="6" align="right">'.number_format($jml_total,2).'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">PPN 10% :</td>
                    <td colspan="6" align="right">'.number_format($jml_ppn,2).'</td>
				</tr>
				<tr>
					<td colspan="17"></td>
					<td colspan="8"  align="right">Total Tagihan :</td>
                    <td colspan="6" align="right">'.number_format($jml_bayar,2).'</td>
				</tr>
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