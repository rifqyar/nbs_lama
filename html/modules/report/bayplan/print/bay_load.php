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
$id_bay = $_GET["id_bay"];
$no_bay = $_GET["no_bay"];
$pss_bay = $_GET["posisi_bay"];
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

$html = '<b>BAYPLAN DISCHARGE</b> ['.date('Y-m-d H:i:s').']
		<hr/>
		<br/>
		<div align="right">'.$vessel.' ['.$voyage.']</div>
		<br/>
		<div align="center"><b>BAY '.$no_bay.' '.$pss_bay.'</b></div>';
		
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
		
			$query_bay = "SELECT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM $table1 WHERE ID_VS = '$id_vs' AND ID = '$id_bay'";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			if($pss_bay=='ABOVE')
			{
				$height = $jml_tier_on+2;
			}
			else
			{
				$height = $jml_tier_under+2;
			}					

$html .= '<style>
				.allocation  {
					width : 60px;
					height : 60px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #FFFFFF;
					background-color:#D6D6C2;					
				}
				.label  {
					width : 60px;
					height : 30px;
					font-size : 8pt; 
					font-family : verdana;
				}
				.general  {
					width : 60px;
					height : 60px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #FFFFFF;
					background-color:#FFFFFF;
				}
				.palka  {
					width : 60px;
					height : 30px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#919292;
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
								
			$html .= '<td align="center">
					  <table align="center">
						<tbody>
						<tr>
							<td colspan="'.($jumlah_row+1).'" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						</tr>';
					
					for($t=1;$t<=$height;$t++)
					{				
						$html .= '<tr>';
						for($r=1;$r<=($jumlah_row+1);$r++)
						{							
							if($pss_bay=='ABOVE')
							{
								$index_cell = (($t-1)*($jumlah_row+1))+$r;
							}
							else
							{
								$index_cell = (($t-1)*($jumlah_row+1))+$r+($jml_tier_on*$width);
							}
							$cell_number = $index_cell-1;
							$cek_status = "SELECT ID,ROW_,TIER_,STATUS_STACK, POSISI_STACK FROM $table2 WHERE ID_BAY_AREA = '$id_bay' AND CELL_NUMBER = '$cell_number' AND POSISI_STACK IN ('$pss_bay','HATCH')";
							$result_cek = $db->query($cek_status);
							$cell_status = $result_cek->fetchRow();
							
							$id_cellx = $cell_status['ID'];
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
							else if($cell_number>(($jumlah_row+1)*($jml_tier_on+$jml_tier_under+2)))
							{
								if($cell_number==($jml_tier_on+$jml_tier_under+3))
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
								$html .= '<td class="label">&nbsp;<br/><br/>'.$br.'</td>';
							}
							else
							{
								if(($cell_status['STATUS_STACK']=='A')||($cell_status['STATUS_STACK']=='P')||($cell_status['STATUS_STACK']=='R'))
								{
									$cek_cont = "SELECT A.BAY,
														A.NO_CONTAINER,
														A.SIZE_,
														CASE WHEN TRIM(A.TYPE_)='DRY' THEN 'DC'
															 WHEN TRIM(A.TYPE_)='HQ' THEN 'HC'
															 WHEN TRIM(A.TYPE_)='TNK' THEN 'TK'
															 WHEN TRIM(A.TYPE_)='RFR' THEN 'RF'
														END TYPE_CONT,
														substr(A.ID_PEL_ASAL,3,3) AS POL,
														substr(A.ID_PEL_TUJ,3,3) AS POD,
														A.CARRIER,
														RPAD(round((A.GROSS/1000),2),4,0) AS GROSS,
														round((B.H_ISO*30.48)) AS HEIGHT 
												FROM STW_PLACEMENT_BAY A, MASTER_ISO_CODE B 
												WHERE A.ID_CELL = '$id_cellx'
													AND A.ACTIVITY = 'MUAT'
													AND B.ISO_CODE = A.ISO_CODE";
									$result_cont = $db->query($cek_cont);
									$cont_ = $result_cont->fetchRow();
									
									$nocont = $cont_['NO_CONTAINER'];
									$pol = $cont_['POL'];
									$pod = $cont_['POD'];
									$carrier = $cont_['CARRIER'];
									$gross = $cont_['GROSS'];
									$sz = $cont_['SIZE_'];
									$ty = $cont_['TYPE_CONT'];
									$ht = $cont_['HEIGHT'];
									$by = $cont_['BAY'];
									
									if(($sz=='40')&&($by!=1)&&(($by-1)%4!=0))
									{
										$html .= '<td class="allocation">&nbsp;<br/>&nbsp;<br/>&nbsp;<br/>+++</td>';
									}
									else
									{
										$html .= '<td class="allocation">&nbsp;<br/>'.$pol.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$pod.'<br/>'.$nocont.'<br/>'.$carrier.'&nbsp;&nbsp;&nbsp;&nbsp;'.$gross.'<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$sz.$ty.'<br/>'.$ht.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
									}								
								}
								else
								{
									$html .= '<td class="general"></td>';
								}
							}						
						}
						$html .= '</tr>';
					}
					$html .= '</tbody>
					          </table>
							  </td>';
				   
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