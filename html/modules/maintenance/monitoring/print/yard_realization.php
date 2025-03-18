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
$id_block = $_GET["id_block"];
$nm_block = $_GET["nm_block"];
$slot = $_GET["slot"];
$id_user = $_SESSION["ID_USER"];
$nm_user = $_SESSION["NAMA_LENGKAP"];	

$html = '<style>
				.general  {
					width : 60px;
					height : 60px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#FFFFFF;
				}
				.label  {
					width : 60px;
					height : 60px;
					font-size : 10pt; 
					font-family : verdana;
				}
				.bongkar  {
					width : 60px;
					height : 60px;
					font-size : 7pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#99FF66;
				}
				.muat  {
					width : 60px;
					height : 60px;
					font-size : 7pt; 
					font-family : times; 
					border : 1px solid #000000;
					background-color:#C8E3FF;
				}
		</style>
		<font size="10"><b>YARD REALIZATION</b> ['.date('Y-m-d H:i:s').'] REPRINT::'.$nm_user.'::</font>
		<hr/>
		<br/>
		<div align="right"><i><font size="8">Inhost Single Window System</font></i></div>';			
			
			$query_yd = "SELECT ID,NAMA_YARD FROM YD_YARD_AREA WHERE STATUS = 'AKTIF' AND ID = '$id_yd'";
			$result_  = $db->query($query_yd);
			$yd_      = $result_->fetchRow();
			
			$query_lap = "SELECT NAME, TIER, (select count(1) from YD_BLOCKING_CELL b WHERE b.ID_BLOCKING_AREA='$id_block' and b.SLOT_='$slot') AS JML_ROW FROM YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA='23' AND a.ID='$id_block'";
							
			$result_lap = $db->query($query_lap);	
			$hasil3 = $result_lap->fetchRow();
			$blok3 = $hasil3['NAME'];
			$tier3 = $hasil3['TIER'];
			$row3 = $hasil3['JML_ROW'];

				$width=$row3;
				$heigth=$tier3;
				$t=$heigth;
				$u=$width;
			
$html .= '<center>
		  <br/>
		  <div align="left"><b><font size="14">Layout '.$yd_['NAMA_YARD'].'</font></b></div>
		  <div align="left"><b><font size="12">Block '.$nm_block.' Slot '.((2*$slot)-1).'</b></font></div>
		  <br/>
		  <br/>
		  <table align="center">';
		for($x=1;$x<=($heigth+1);$x++)
		{
			$tr = $t-($x-1);
			if($tr==0)
			{
				$label_tr = "T/R";
			}
			else
			{
				$label_tr = $tr;
			}			
			
			$html .= '<tr>
					  <td class="label">&nbsp;<br/><b>'.$label_tr.'</b></td>';					  
			for($i=1;$i<=$width;$i++)
			{
				$cek_status = "SELECT STATUS_BM FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slot' AND ROW_ = '$i'";
				$result_cek = $db->query($cek_status);
				$cell_status = $result_cek->fetchRow();
				$bm = $cell_status['STATUS_BM'];
												
				$query2 = "SELECT A.ID_PLACEMENT, 
				                  A.NO_CONTAINER, 
				     			  A.SIZE_, 
								  A.TYPE_CONT, 
								  A.STATUS_CONT, 
								  A.ID_VS, 
								  A.ACTIVITY, 
								  A.HZ, 
								  A.TON, 
					    		  A.ID_PEL_ASAL, 
								  A.ID_PEL_TUJ, 
								  A.KODE_PBM, 
						    	  A.NO_BOOKING_SL, 
								  B.H_ISO,
								  A.ISO_CODE,
								  A.IMO_CLASS,
					        	  A.CELCIUS
							FROM YD_PLACEMENT_YARD A, MASTER_ISO_CODE B 
							WHERE ID_BLOCKING_AREA='$id_block' 
						     AND SLOT_YARD='$slot' 
							 AND ROW_YARD='$i' 
							 AND TIER_YARD='$tr' 
						
							 AND A.ISO_CODE = B.ISO_CODE";
				/*if($tr==4)
				{
					print_r($query2);
					echo '<br>';
				}*/
				$result3= $db->query($query2);	
				$hasil2 = $result3->fetchRow();
										
				$vs_id=$hasil2['ID_VS'];
				$kegiatan=$hasil2['ACTIVITY'];
				$no_cont=$hasil2['NO_CONTAINER'];
				$size_cont=$hasil2['SIZE_'];
				$type_cont=$hasil2['TYPE_CONT'];
				$status_cont=$hasil2['STATUS_CONT'];
				$id_plc=$hasil2['ID_PLACEMENT'];
				$hz_=$hasil2['HZ'];
				$gross_=$hasil2['TON'];
				$pl_asal=$hasil2['ID_PEL_ASAL'];
				$pl_tuj=$hasil2['ID_PEL_TUJ'];
				$pel_asal = str_replace(' ','',$pl_asal);
				$pel_tuj = str_replace(' ','',$pl_tuj);
				$kode_pbm=$hasil2['KODE_PBM'];
				$no_booking=$hasil2['NO_BOOKING_SL'];
				$height=$hasil2['H_ISO'];
				$tinggi=ceil($height*30.48);
				$isocode=$hasil2['ISO_CODE'];
				$imoclass=$hasil2['IMO_CLASS'];
				$celcius=$hasil2['CELCIUS'];
				
								
				if($tr==0)
				{
					$html .= '<td class="label">&nbsp;<br/><b>'.$i.'</b></td>';
				}
				else
				{
					if($no_cont != NULL)
					{	
						if($bm=='Muat')
						{
							$html .= '<td class="muat">&nbsp;<br/>'.$no_cont.'<br/>'.$size_cont.'/'.$type_cont.'/'.$status_cont.'<br/>'.$pel_tuj.'<br/>'.$gross_.' kgs</td>';
						}
						else if($bm=='Bongkar')
						{
							$html .= '<td class="bongkar">&nbsp;<br/>'.$no_cont.'<br/>'.$size_cont.'/'.$type_cont.'/'.$status_cont.'<br/>'.$pel_tuj.'<br/>'.$gross_.' kgs</td>';
						}
						else
						{
							$html .= '<td class="general">&nbsp;<br/>'.$no_cont.'<br/>'.$size_cont.'/'.$type_cont.'/'.$status_cont.'<br/>'.$pel_tuj.'<br/>'.$gross_.' kgs</td>';						
						}
					}
					else
					{
						$html .= '<td class="general">&nbsp;</td>';
					}
				}								
			}
			$html .= '</tr>';						
		}	//die;
	$html .= '</table>
		  </center>
		  <br/>';
		

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_bayplan.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>