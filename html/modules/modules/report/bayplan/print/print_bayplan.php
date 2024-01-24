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

$id_vs = $_GET["id"];
$id_user = $_SESSION["ID_USER"];	

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

$html = '<b>BAY ALLOCATION</b> ['.date('Y-m-d H:i:s').']
		<hr/>
		<br/>
		<div align="right">'.$vessel.' ['.$voyage.']</div>';
		
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
		
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM $table1 WHERE ID_VS = '$id_vs'";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			$height = $jml_tier_under+$jml_tier_on+2;
			
			$jml_bay = "SELECT COUNT(*) AS JUMLAH_BAY FROM $table1 WHERE ID_VS = '$id_vs'";
			$result_jml = $db->query($jml_bay);
			$bay_jml = $result_jml->fetchRow();
			
			$jumlah_bay = $bay_jml['JUMLAH_BAY'];
			$lebar = 95*$jumlah_bay;
			
			$query_cell3 = "SELECT ID,BAY FROM $table1 WHERE ID_VS = '$id_vs' ORDER BY BAY ASC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();

$html .= '<style>
				.allocation  {
					width : 10px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #FFFFFF;
					background-color:#D6D6C2;					
				}
				.label  {
					width : 10px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana;
				}
				.general  {
					width : 10px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #FFFFFF;
					background-color:#FFFFFF;
				}
				.palka  {
					width : 10px;
					height : 10px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#663300;
				}
		</style>
		  <br/>
          <center>
		  <table align="center">
		  <tbody>
			<tr align="center">';	
			
			$n='';
			$br='';
			$tr='';
			foreach ($blok8 as $row18)
			{
				$id_area = $row18['ID'];
				$bay_name = $row18['BAY'];
			
				if((($bay_name==1)||(($bay_name-1)%4==0))&&($bay_name<24)) //------baris pertama------//
				{			
					$html .= '<td align="center">
					  <table align="center">
						<tbody>
						<tr>
							<td colspan="'.($jumlah_row+1).'" align="center"><b>'.$bay_name.'('.($bay_name+1).')</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						</tr>';
					
					for($t=1;$t<=$height;$t++)
					{				
						$html .= '<tr>';
						for($r=1;$r<=($jumlah_row+1);$r++)
						{
							$index_cell = (($t-1)*($jumlah_row+1))+$r;
							$cell_number = $index_cell-1;
							$cek_status = "SELECT ROW_,TIER_,STATUS_STACK, POSISI_STACK FROM $table2 WHERE ID_BAY_AREA = '$id_area' AND CELL_NUMBER = '$cell_number'";
							$result_cek = $db->query($cek_status);
							$cell_status = $result_cek->fetchRow();
							
							$br = $n;
							$tr = $cell_status['TIER_'];
							$n = $tr;
							
							if($cell_status['POSISI_STACK']=='HATCH')
							{
								if($index_cell%($jumlah_row+1)==0)
								{
									$html .= '<td class="label">&nbsp;</td>';						
								}
								else
								{
									$html .= '<td class="palka">&nbsp;</td>';
								}
							}
							else if($cell_number<($jumlah_row+1))
							{
								if($cell_number==$jumlah_row)
								{
									$html .= '<td class="label">&nbsp;</td>';
								}
								else
								{
									$html .= '<td class="label">'.$cell_status['ROW_'].'</td>';
								}
							}
							else if($index_cell%($jumlah_row+1)==0)
							{
								$html .= '<td class="label">'.$br.'</td>';
							}
							else
							{
								if(($cell_status['STATUS_STACK']=='A')||($cell_status['STATUS_STACK']=='P'))
								{
									$html .= '<td class="allocation">&nbsp;</td>';
								}
								else
								{
									$html .= '<td class="general">&nbsp;</td>';
								}
							}						
						}
						$html .= '</tr>';
					}
					$html .= '</tbody>
					          </table>
							  </td>';
				}			
			}
					
			$html .= '</tr>
						<tr align="center"><td align="center">&nbsp;</td></tr>
					 <tr align="center">';
				
			$n='';
			$br='';
			$tr='';
			foreach ($blok8 as $row18)
			{
				$id_area = $row18['ID'];
				$bay_name = $row18['BAY'];
			
				if(($bay_name!=1)&&(($bay_name-1)%4!=0)&&($bay_name<24)) //------baris kedua------//
				{			
					$html .= '<td align="center">
					  <table align="center">
						<tbody>
						<tr>
							<td colspan="'.($jumlah_row+1).'" align="center"><b>'.$bay_name.'</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						</tr>';
					
					for($t=1;$t<=$height;$t++)
					{				
						$html .= '<tr>';
						for($r=1;$r<=($jumlah_row+1);$r++)
						{
							$index_cell = (($t-1)*($jumlah_row+1))+$r;
							$cell_number = $index_cell-1;
							$cek_status = "SELECT ROW_,TIER_,STATUS_STACK, POSISI_STACK FROM $table2 WHERE ID_BAY_AREA = '$id_area' AND CELL_NUMBER = '$cell_number'";
							$result_cek = $db->query($cek_status);
							$cell_status = $result_cek->fetchRow();
							
							$br = $n;
							$tr = $cell_status['TIER_'];
							$n = $tr;
							
							if($cell_status['POSISI_STACK']=='HATCH')
							{
								if($index_cell%($jumlah_row+1)==0)
								{
									$html .= '<td class="label">&nbsp;</td>';						
								}
								else
								{
									$html .= '<td class="palka">&nbsp;</td>';
								}
							}
							else if($cell_number<($jumlah_row+1))
							{
								if($cell_number==$jumlah_row)
								{
									$html .= '<td class="label">&nbsp;</td>';
								}
								else
								{
									$html .= '<td class="label">'.$cell_status['ROW_'].'</td>';
								}
							}
							else if($index_cell%($jumlah_row+1)==0)
							{
								$html .= '<td class="label">'.$br.'</td>';
							}
							else
							{
								if(($cell_status['STATUS_STACK']=='A')||($cell_status['STATUS_STACK']=='P'))
								{
									$html .= '<td class="allocation">&nbsp;</td>';
								}
								else
								{
									$html .= '<td class="general">&nbsp;</td>';
								}
							}						
						}
						$html .= '</tr>';
					}
					$html .= '</tbody>
					          </table>
							  </td>';
				}			
			}		 
					 
		 $html .= '</tr>
				  <tr align="center"><td align="center">&nbsp;</td></tr>
				   <tr align="center">';
				   
					$n='';
					$br='';
					$tr='';
					foreach ($blok8 as $row18)
					{
						$id_area = $row18['ID'];
						$bay_name = $row18['BAY'];
					
						if((($bay_name==1)||(($bay_name-1)%4==0))&&($bay_name>24)) //------baris ketiga------//
						{			
							$html .= '<td align="center">
							  <table align="center">
								<tbody>
								<tr>
									<td colspan="'.($jumlah_row+1).'" align="center"><b>'.$bay_name.'('.($bay_name+1).')</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
								</tr>';
							
							for($t=1;$t<=$height;$t++)
							{				
								$html .= '<tr>';
								for($r=1;$r<=($jumlah_row+1);$r++)
								{
									$index_cell = (($t-1)*($jumlah_row+1))+$r;
									$cell_number = $index_cell-1;
									$cek_status = "SELECT ROW_,TIER_,STATUS_STACK, POSISI_STACK FROM $table2 WHERE ID_BAY_AREA = '$id_area' AND CELL_NUMBER = '$cell_number'";
									$result_cek = $db->query($cek_status);
									$cell_status = $result_cek->fetchRow();
									
									$br = $n;
									$tr = $cell_status['TIER_'];
									$n = $tr;
									
									if($cell_status['POSISI_STACK']=='HATCH')
									{
										if($index_cell%($jumlah_row+1)==0)
										{
											$html .= '<td class="label">&nbsp;</td>';						
										}
										else
										{
											$html .= '<td class="palka">&nbsp;</td>';
										}
									}
									else if($cell_number<($jumlah_row+1))
									{
										if($cell_number==$jumlah_row)
										{
											$html .= '<td class="label">&nbsp;</td>';
										}
										else
										{
											$html .= '<td class="label">'.$cell_status['ROW_'].'</td>';
										}
									}
									else if($index_cell%($jumlah_row+1)==0)
									{
										$html .= '<td class="label">'.$br.'</td>';
									}
									else
									{
										if(($cell_status['STATUS_STACK']=='A')||($cell_status['STATUS_STACK']=='P'))
										{
											$html .= '<td class="allocation">&nbsp;</td>';
										}
										else
										{
											$html .= '<td class="general">&nbsp;</td>';
										}
									}						
								}
								$html .= '</tr>';
							}
							$html .= '</tbody>
									  </table>
									  </td>';
						}			
					}
		
		 $html .= '</tr>
				  <tr align="center"><td align="center">&nbsp;</td></tr>
				   <tr align="center">';
				   
				   
				   
		 $html .= '</tr>		   
				  </tbody>
				  </table>
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