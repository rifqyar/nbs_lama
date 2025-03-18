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
$no_ukk = $_GET["no_ukk"];
$vessel = $_GET["vessel"];
$voyage = $_GET["voyage"];
$id_user = $_SESSION["ID_USER"];
$nm_user = $_SESSION["NAMA_LENGKAP"];	

$html = '<style>
				.general  {
					width : 30px;
					height : 20px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#FFFFFF;
				}
				.label  {
					width : 30px;
					height : 20px;
					font-size : 10pt; 
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
					width : 30px;
					height : 20px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#99FF66;
				}
				.muat  {
					width : 30px;
					height : 20px;
					font-size : 8pt; 
					font-family : times; 
					border : 1px solid #000000;
					background-color:#C8E3FF;
				}
				.mty  {
					width : 30px;
					height : 20px;
					font-size : 8pt; 
					font-family : times; 
					border : 1px solid #000000;
					background-color:#beff65;
				}
				.40dry  {
					width : 30px;
					height : 20px;
					font-size : 8pt; 
					font-family : times; 
					border : 1px solid #000000;
					background-color:#f8d94d;
				}
				.20dry  {
					width : 30px;
					height : 20px;
					font-size : 8pt; 
					font-family : times; 
					border : 1px solid #000000;
					background-color:#1484e6;
				}				
				.40hq  {
					width : 30px;
					height : 20px;
					font-size : 8pt; 
					color : #FFFFFF;
					font-family : times; 
					border : 1px solid #000000;
					background-color:#1e158e;
				}
				.45hq  {
					width : 30px;
					height : 20px;
					font-size : 8pt; 
					color : #FFFFFF;
					font-family : times; 
					border : 1px solid #000000;
					background-color:#688539;
				}				
				.40ot  {
					width : 30px;
					height : 20px;
					font-size : 8pt; 
					color : #000000;
					font-family : times; 
					border : 1px solid #000000;
					background-color:#c1c5b9;
				}
		</style>
		<font size="10"><b>YARD ALLOCATION</b> ['.date('Y-m-d H:i:s').'] REPRINT::'.$nm_user.'::</font>
		<hr/>
		<br/>
		<div align="right"><i><font size="8">Inhost Single Window System</font></i></div>';			
			
			$query_yd = "SELECT ID,NAMA_YARD FROM YD_YARD_AREA WHERE STATUS = 'AKTIF' AND ID = '$id_yd'";
			$result_  = $db->query($query_yd);
			$yd_      = $result_->fetchRow();
			
			$query_block = "SELECT NAME, TIER, 
							(select max(b.SLOT_) from YD_BLOCKING_CELL b WHERE b.ID_BLOCKING_AREA='$id_block') AS JML_SLOT, 
							(select max(b.ROW_) from YD_BLOCKING_CELL b WHERE b.ID_BLOCKING_AREA='$id_block') AS JML_ROW 
							FROM YD_BLOCKING_AREA a WHERE a.ID_YARD_AREA='$id_yd' AND a.ID='$id_block'";
			$result_block = $db->query($query_block);
			$blck_ = $result_block->fetchRow();
			
			$width_block = $blck_['JML_SLOT'];
			$length_block = $blck_['JML_ROW'];
			$l_t = $width_block*$length_block;

$html .= '<center>
		  <br/>
		  <div align="left"><b><font size="14">Layout '.$yd_['NAMA_YARD'].'</font></b></div>
		  <div align="left"><b><font size="12">Block '.$nm_block.'</b> ['.$vessel.' '.$voyage.']</font></div>
		  <br/>
		  <br/>
		  <table align="center">';
		for($index=1;$index<=$l_t;$index++)
		{
			$index_cell = $index-1;
			$mod = $index%$width_block;
			$div = ceil($index/$width_block);
			
			if($mod==0)
			{
				$row_yd = $div;
				$slot_yd = $width_block;
			}
			else
			{
				$row_yd = $div;
				$slot_yd = $mod;
			}
			
			$cek_status = "SELECT STATUS_BM, TRIM(SIZE_PLAN_ALLO) AS SIZE_PLAN_ALLO FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slot_yd' AND ROW_ = '$row_yd'";
			$result_cek = $db->query($cek_status);
			$cell_status = $result_cek->fetchRow();
			$bm = $cell_status['STATUS_BM'];
			$sz_plan = $cell_status['SIZE_PLAN_ALLO'];
			
			$html .= '<tr>';
			
			if($index%$width_block==0)
			{
				if($sz_plan=='40b')
				{
					$html .= '</tr>';
				}
				else if($sz_plan=='20')
				{
					$html .= '<td class="general">'.$index.'</td>
							  </tr>';
				}
				else
				{
					$html .= '<td class="general">'.$index.'</td>
							  </tr>';
				}				
			}
			else
			{				
				if($sz_plan=='40d')
				{
					$html .= '<td colspan="2" class="general">'.$index.'</td>';
				}
				else if($sz_plan=='40b')
				{
					$html .= '';
				}
				else if($sz_plan=='20')
				{
					$html .= '<td class="general">'.$index.'</td>';
				}
				else
				{
					$html .= '<td class="general">'.$index.'</td>';
				}
			}
		
			/*
			$row_yd = $rw-1;
			if($row_yd==0)
			{
				$label = "R\S";
			}
			else
			{
				$label = $row_yd;
			}
			$html .= '<tr>
					  <td class="label"><b>'.$label.'</b></td>';
			for($slt=1;$slt<=$width_block;$slt++)
			{	
				$cek_status = "SELECT STATUS_BM, SIZE_PLAN_ALLO FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slt' AND ROW_ = '$row_yd'";
				$result_cek = $db->query($cek_status);
				$cell_status = $result_cek->fetchRow();
				$bm = $cell_status['STATUS_BM'];
				$sz_plan = $cell_status['SIZE_PLAN_ALLO'];
				
				$cek_alokasi = "SELECT DISTINCT STATUS_CONT,KATEGORI,SIZE_,TYPE_ FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_ = '$slt' AND ROW_ = '$row_yd' AND ID_VS = '$no_ukk'";
				$result_alokasi = $db->query($cek_alokasi);
				$cell_alokasi = $result_alokasi->fetchRow();
				$bm_alokasi = $cell_alokasi['KATEGORI'];
				$statcont_alokasi = $cell_alokasi['STATUS_CONT'];
				$size_alokasi = $cell_alokasi['SIZE_'];
				$type_alokasi = $cell_alokasi['TYPE_'];
				
				if($row_yd==0)
				{
					$html .= '<td class="label"><b>'.$slt.'</b></td>';
				}
				else
				{
					if($bm=='Muat')
					{												
						if($bm_alokasi!=NULL)
						{
							if($statcont_alokasi=='MTY')
							{
								$html .= '<td class="mty">MTY</td>';
							}
							else
							{
								if(($size_alokasi=='40')&&($type_alokasi=='DRY'))
								{
									$html .= '<td class="40dry"><b>'.$bm_alokasi.'</b></td>';
								}
								else if(($size_alokasi=='40')&&($type_alokasi=='HQ'))
								{
									$html .= '<td class="40hq"><b>'.$bm_alokasi.'</b></td>';
								}
								else if(($size_alokasi=='45')&&(($type_alokasi=='HQ')||($type_alokasi=='DRY')))
								{
									$html .= '<td class="45hq"><b>'.$bm_alokasi.'</b></td>';
								}								
								else if(($size_alokasi=='40')&&($type_alokasi=='OT'))
								{
									$html .= '<td class="40ot"><b>'.$bm_alokasi.'</b></td>';
								}
								else if(($size_alokasi=='20')&&($type_alokasi=='DRY'))
								{
									$html .= '<td class="20dry"><b>'.$bm_alokasi.'</b></td>';
								}
								else
								{
									$html .= '<td class="muat"><b>'.$bm_alokasi.'</b></td>';
								}							
							}						
						}
						else
						{
							$html .= '<td class="muat">&nbsp;</td>';
						}									
					}
					else if($bm=='Bongkar')
					{
						if($sz_plan=='40d')
						{
							if($bm_alokasi!=NULL)
							{
								if($statcont_alokasi=='MTY')
								{
									$html .= '<td class="mty">MTY</td>';
								}
								else
								{
									if(($size_alokasi=='40')&&($type_alokasi=='DRY'))
									{
										$html .= '<td colspan="2" class="40dry"><b>'.$bm_alokasi.'</b></td>';
									}
									else if(($size_alokasi=='40')&&($type_alokasi=='HQ'))
									{
										$html .= '<td colspan="2" class="40hq"><b>'.$bm_alokasi.'</b></td>';
									}
									else if(($size_alokasi=='45')&&(($type_alokasi=='HQ')||($type_alokasi=='DRY')))
									{
										$html .= '<td class="45hq"><b>'.$bm_alokasi.'</b></td>';
									}								
									else if(($size_alokasi=='40')&&($type_alokasi=='OT'))
									{
										$html .= '<td class="40ot"><b>'.$bm_alokasi.'</b></td>';
									}
									else
									{
										$html .= '<td class="bongkar"><b>'.$bm_alokasi.'</b></td>';
									}							
								}						
							}
							else
							{
								$html .= '<td class="bongkar">&nbsp;</td>';
							}
						}
						else if($sz_plan=='40b')
						{
							if($bm_alokasi!=NULL)
							{
								if($statcont_alokasi=='MTY')
								{
									$html .= '<td class="mty">&nbsp;</td>';
								}
								else
								{
									if(($size_alokasi=='40')&&($type_alokasi=='DRY'))
									{
										$html .= '';
									}
									else if(($size_alokasi=='40')&&($type_alokasi=='HQ'))
									{
										$html .= '';
									}
									else if(($size_alokasi=='45')&&(($type_alokasi=='HQ')||($type_alokasi=='DRY')))
									{
										$html .= '<td class="45hq">&nbsp;</td>';
									}								
									else if(($size_alokasi=='40')&&($type_alokasi=='OT'))
									{
										$html .= '<td class="40ot">&nbsp;</td>';
									}
									else
									{
										$html .= '<td class="bongkar">&nbsp;</td>';
									}							
								}						
							}
							else
							{
								$html .= '<td class="bongkar">&nbsp;</td>';
							}
						}
						else if($sz_plan=='20')
						{
							if($bm_alokasi!=NULL)
							{
								if($statcont_alokasi=='MTY')
								{
									$html .= '<td class="mty">MTY</td>';
								}
								else
								{
									if(($size_alokasi=='20')&&($type_alokasi=='DRY'))
									{
										$html .= '<td class="20dry"><b>'.$bm_alokasi.'</b></td>';
									}
									else
									{
										$html .= '<td class="bongkar"><b>'.$bm_alokasi.'</b></td>';
									}							
								}						
							}
							else
							{
								$html .= '<td class="bongkar">&nbsp;</td>';
							}
						}
						else
						{
							$html .= '<td class="bongkar">&nbsp;</td>';
						}						
					}
					else
					{	
						$html .= '<td class="general">&nbsp;</td>';
					}
				}						
			}
			$html .= '</tr>';
			*/
		}
	$html .= '</table>
		  </center>
		  <br/>
		  <br/>
		  <table align="center">
		  <tbody>
			<tr align="center">
			<td align="center" width="350">
				Tonage Classification (Full Container)	
			</td>
			<td align="center" width="80">
				&nbsp;	
			</td>
			<td align="center" width="300">
				Color Classification	
			</td>
			</tr>
			<tr align="center">
			<td align="center">
				&nbsp;
			</td>
			<td align="center">
				&nbsp;	
			</td>
			</tr>
			<tr align="center">
			<td align="center" width="380">
				<table align="center" border="1">
					<tbody>
					<tr>
					   <td colspan="2" height="20" valign="middle">Container 20"</td>
					   <td colspan="2" height="20" valign="middle">Container 40"</td>
					</tr>
					<tr>
					   <td>L2</td>
					   <td>2500 s/d 99000</td>
					   <td>L2</td>
					   <td>3500 s/d 99000</td>
					</tr>
					<tr>
					   <td>L1</td>
					   <td>10000 s/d 14900</td>
					   <td>L1</td>
					   <td>10000 s/d 14900</td>
					</tr>
					<tr>
					   <td>M</td>
					   <td>15000 s/d 19900</td>
					   <td>M</td>
					   <td>15000 s/d 19900</td>
					</tr>
					<tr>
					   <td>H</td>
					   <td>20000 s/d 24900</td>
					   <td>H</td>
					   <td>20000 s/d 24900</td>
					</tr>
					<tr>
					   <td>XH</td>
					   <td>25000 s/d 35000</td>
					   <td>XH</td>
					   <td>25000 s/d 35000</td>
					</tr>
					</tbody>
				</table>	
			</td>
			<td align="center" width="160">
				&nbsp;	
			</td>
			<td align="center">
				<table align="center">
					<tbody>
					<tr>
					   <td style="background-color:#f8d94d;" width="20"></td>
					   <td width="70" align="left"> 40 DRY FCL</td>
					</tr>
					<tr>
					   <td style="background-color:#1484e6;" width="20"></td>
					   <td width="70" align="left"> 20 DRY FCL</td>
					</tr>
					<tr>
					   <td style="background-color:#beff65;" width="20"></td>
					   <td width="70" align="left"> 40 HQ MTY</td>
					</tr>
					<tr>
					   <td style="background-color:#1e158e;" width="20"></td>
					   <td width="70" align="left"> 40 HQ FCL</td>
					</tr>					
					<tr>
					   <td style="background-color:#688539;" width="20"></td>
					   <td width="70" align="left"> 45 FCL</td>
					</tr>
					<tr>
					   <td style="background-color:#c1c5b9;" width="20"></td>
					   <td width="70" align="left"> 40 OT FCL</td>
					</tr>
					</tbody>
				</table>	
			</td>
			</tr>
		  </tbody>
		  </table>';
		

// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_bayplan.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>