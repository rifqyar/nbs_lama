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

$pdf->SetFont('courier', '', 8);
$pdf->setPageOrientation('l');
 
//
$db = getDB();
// 

$id_yd = $_GET["id_yard"];
$id_user = $_SESSION["ID_USER"];	

$html = '<style>
				.general  {
					width : 12px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#FFFFFF;
				}
				.label  {
					width : 10px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana;
				}
				.empty  {
					width : 10px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #FFFFFF;
					background-color:#FFFFFF;
				}
				.bongkar  {
					width : 12px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#99FF66;
				}
				.muat  {
					width : 12px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#C8E3FF;
				}
		</style>
		<font size="10"><b>YARD ALLOCATION</b> ['.date('Y-m-d H:i:s').']</font>
		<hr/>
		<br/>
		<div align="right"><i><font size="8">Inhost Single Window System</font></i></div>';
			
			
			$query_yd = "SELECT ID,NAMA_YARD,WIDTH,LENGTH FROM YD_YARD_AREA WHERE STATUS = 'AKTIF' AND ID = '$id_yd'";
			$result_  = $db->query($query_yd);
			$yd_      = $result_->fetchRow();
			
			$width_yd = $yd_['WIDTH'];
			$length_yd = $yd_['LENGTH']-8;
			$luas = $width_yd*$length_yd;

$html .= '<center>
		  <br/>
		  <div align="center"><b><font size="14">Layout '.$yd_['NAMA_YARD'].'</font></b></div>
		  <br/>
		  <br/>
		  <table align="center">';
		for($index=1;$index<=$luas;$index++)
		{
			$html .= '<tr>';
			$index_cell = $index-1;
			$cek_status = "SELECT ybc.STATUS_BM FROM YD_BLOCKING_CELL ybc, YD_BLOCKING_AREA yba WHERE yba.ID = ybc.ID_BLOCKING_AREA AND yba.ID_YARD_AREA = '$id_yd' AND ybc.INDEX_CELL = '$index_cell'";
			$result_cek = $db->query($cek_status);
			$cell_status = $result_cek->fetchRow();
			$bm = $cell_status['STATUS_BM'];
				
			if($bm=='Muat')
			{
				$html .= '<td class="muat">&nbsp;</td>';				
			}
			else if($bm=='Bongkar')
			{
				$html .= '<td class="bongkar">&nbsp;</td>';
			}
			else
			{	
				$html .= '<td class="general">&nbsp;</td>';
			}
			$html .= '</tr>';
		}
	$html .= '</table>
		  </center>
		  <br/>
		  Klasifikasi Tonage';
		

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_bayplan.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>