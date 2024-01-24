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
$nm_user = $_SESSION["NAMA_LENGKAP"];	

$html = '<style>
				.general  {
					width : 10px;
					height : 5px;
					font-size : 5pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#FFFFFF;
				}
				.label  {
					width : 10px;
					height : 5px;
					font-size : 10pt; 
					font-family : verdana;
				}
				.bongkar  {
					width : 10px;
					height : 5px;
					font-size : 7pt; 
					font-family : verdana; 
					border : 1px solid #000000;
					background-color:#99FF66;
				}
				.muat  {
					background-color:#e4e4e4;
					border : 1px solid #b6b5b6;
				}
				.muat2  {
					background-color:#4c4c4c;
					color:#ffffff;
					border : 1px solid #b6b5b6;
				}
		</style>
		<font size="10"><b>COPY YARD</b> ['.date('Y-m-d H:i:s').'] REPRINT::'.$nm_user.'::</font>
		<hr/>
		<br/>
		<div align="right"><i><font size="8">Inhost Single Window System</font></i></div>';			
			
			$db             = getDB();
            $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            //debug($blok);die;
            $width      = $blok['WIDTH'];
            
$html .='<table cellspacing="0" cellpadding="0" align="center">';
$html .='<tr>';
		 
			$query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI,a.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA b,         
                            YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = '23' AND a.SIZE_PLAN_PLC IS NULL 
                            UNION 
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '40d'
                            UNION
                            SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_PLC FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = '23' AND d.SIZE_PLAN_PLC = '20'
                            ORDER BY INDEX_CELL ASC";
			$result3     = $db->query($query_cell2);
			
            $blok2       = $result3->getAll();
  
            foreach ($blok2 as $row)
			{
				$id_block     = $row['ID'];
				$slot_        = $row['SLOT_'];
				$row_         = $row['ROW_'];
				$name         = $row['NAME'];
				$index_cell_  = $row['INDEX_CELL'];
				$st_bm		  = $row['STATUS_BM'];
				$pos		  = $row['POSISI'];
				$sz_plc	      = $row['SIZE_PLAN_PLC'];
				
				if ($sz_plc=='40d')
				{
					if($pos=='vertical')
					{
						$cr="rowspan=2";
					}
					else if($pos=='horizontal')
						$cr="colspan=2";
				}
				else
					$cr='';
				
				
                if ($row['NAME'] <> 'NULL')
				{                
					$id_block     = $row['ID'];
                    $slot_        = $row['SLOT_'];
                    $row_         = $row['ROW_'];
                    $name         = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					
					
					$query_place = "SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' AND FLAG_HP IS NULL";
					
					
                    $result2     = $db->query($query_place);
                    $place       = $result2->fetchRow();
                     
            
                    $placement   = $place['JUM'];
					
					if ($placement <> 0)
					{
					
						if ($placement>=5)
						{
							$color_plc='#FF0033';
						}
						else if ($placement==4)
						{
							$color_plc='#f89311';
						}
						else if ($placement==3)
						{
							$color_plc='#f8ea11';
						}
						else if ($placement==2)
						{
							$color_plc='#df11f8';
						}
						else if ($placement==1)
						{
							$color_plc='#5b0cad';
						}
						$html .='<td class="muat2">'.$placement.'</td>';
					
                    }
					else 
					{
						$html .='<td align="center" class="muat">&nbsp;</td>';
					}
					
				}
				else 
				{
					$html .='<td align="center" >';
					if (($slot_ == 0) AND ($row_ <> 0) AND ($st_bm == NULL)) 
					{
						$html .=$row_;
					} 
					else if (($slot_ <> 0) AND ($row_ == 0) AND ($st_bm == NULL)) 
					{
						$html .=$slot_;
					} 
					else if (($slot_ == 0) AND ($row_ == 0) AND ($st_bm <> NULL)) 
					{
						$html .='&nbsp;';
					}
					if(($row['INDEX_CELL']==398) ||($row['INDEX_CELL']==767))
					{
						$html .='Blok ';
					}					
					if($row['INDEX_CELL']==399)
					{
						$html .='R0102';
					}
					if($row['INDEX_CELL']==768)
					{
						$html .='R03';
					}
					$html .='</td>';
					if (($row['INDEX_CELL']+1) % $width == 0)
					{
						$html .='</tr><tr>';
					}
				}
			}
		
$html .='<td></td></tr></table>';
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_bayplan.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>