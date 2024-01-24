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
$pdf->setPageOrientation('p');
 
//
$db = getDB();
// 

$id_vs = $_GET["no_ukk"];
$alat = $_GET["alat"];
$id_user = $_SESSION["ID_USER"];	
$nm_user = $_SESSION["NAMA_LENGKAP"];

//======================= Header ===========================//
	$ves_voy = "SELECT NM_KAPAL,
					   VOYAGE_IN|| '-' ||VOYAGE_OUT AS VOYAGE
					FROM RBM_H
                    WHERE NO_UKK = '$id_vs'";
	$vvoy = $db->query($ves_voy);
	$hasil_vv = $vvoy->fetchRow();
	$vessel = $hasil_vv['NM_KAPAL'];
	$voyage = $hasil_vv['VOYAGE'];
//======================= Header ===========================//

$html = '<b>'.$alat.' ALLOCATION DISCH</b> ['.date('Y-m-d H:i:s').']::'.$nm_user.'
		<hr/>
		<br/>
		<div align="right">'.$vessel.' ['.$voyage.']</div>
		<br/>
		<br/>';
		
		$cek_rt_vessel = "SELECT FLAG_RT_VESPROFIL FROM RBM_H WHERE NO_UKK = '$id_vs'";
		$result_rt   = $db->query($cek_rt_vessel);
	    $vessel_rt   = $result_rt->fetchRow();
		$flag_rt     = $vessel_rt['FLAG_RT_VESPROFIL'];
		
		if($flag_rt=='T')
		{
			$table1 = "STW_BAY_AREA";
			$table2 = "STW_BAY_CELL";
			$table3 = "STW_BAY_CAPACITY";
		}
		else
		{
			$table1 = "STW_RT_BAY_AREA";
			$table2 = "STW_RT_BAY_CELL";
			$table3 = "STW_RT_BAY_CAPACITY";
		}
		
			$query_sql = "SELECT TRIM(A.SZ_PLAN) SZ_PLAN,
                                            B.BAY, 
                                            A.POSISI_STACK,
                                            A.SEQ_ALAT,
											A.ID_BAY_AREA,
											A.ACTIVITY,
											A.PRIORITY,
											A.ID_ALAT,
											A.JML_MOV
                            FROM STW_BAY_CSL A, STW_BAY_AREA B
                            WHERE A.ID_VS = '$id_vs' 
                              AND A.ID_ALAT = '$alat'
							  AND B.ID = A.ID_BAY_AREA
							  AND TRIM(A.SZ_PLAN) NOT IN ('40b','45b')
                              ORDER BY SEQ_ALAT ASC";
			$csl_hsl   = $db->query($query_sql);
			$csl       = $csl_hsl->getAll();
			
$html .= '<br/>
          <center>
		  <table border="1" align="center" width="800px">
			<tr align="center">
				<th style="background-color:#607095" width="20">NO</th>
				<th style="background-color:#607095" width="80">CATEGORY</th>
				<th style="background-color:#607095" width="50">BAY</th>
				<th style="background-color:#607095" width="50">POSISI</th>
				<th style="background-color:#607095" width="30">E_I</th>
				<th style="background-color:#607095" width="80">MOVEMENT</th>
			</tr>';	
			
			$no=1;
			foreach ($csl as $row_csl)
			{
				if($row_csl['SZ_PLAN']=='20')
				{
					$bay_csl = $row_csl['BAY'];
					$sz_csl = $row_csl['SZ_PLAN'];
				}
				else
				{
					$bay_csl = $row_csl['BAY']+1;
					$sz_csl = str_replace('d','',$row_csl['SZ_PLAN']);
				}
				
				$html .= '<tr align="center">
							<td align="center" bgcolor="#FAFAFA">'.$no.'</td> 
							<td align="center" bgcolor="#FAFAFA">'.$sz_csl.'</td>
							<td align="center" bgcolor="#FAFAFA">'.$bay_csl.'</td>
							<td align="center" bgcolor="#FAFAFA">'.$row_csl['POSISI_STACK'].'</td>
							<td align="center" bgcolor="#FAFAFA">'.$row_csl['ACTIVITY'].'</td>
							<td align="center" bgcolor="#FAFAFA">'.$row_csl['JML_MOV'].'</td>						
						  </tr>';
				$no++;
			}
				
$html .= 	'</table>
		  </center>';
		

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_bayplan.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>