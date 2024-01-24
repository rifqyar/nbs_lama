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

$pdf->SetFont('courier', '', 4);
$pdf->setPageOrientation('l');
 
//
$db = getDB();
// 

$id_yd = $_GET["id_yard"];
$id_user = $_SESSION["ID_USER"];	
$vessel = $_GET['vessel'];
$voyage = $_GET['voyage'];
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
			$querybaru = "SELECT ID,NAMA_YARD,WIDTH,LENGTH FROM YD_YARD_AREA WHERE STATUS = 'AKTIF' AND ID = '$id_yd'";
			$resultbaru_  = $db->query($querybaru);
			$ydbaru_      = $resultbaru_->fetchRow();
			
			 $query_blok = "SELECT WIDTH, ID FROM YD_YARD_AREA WHERE ID = '$id_yd'";
            $result_    = $db->query($query_blok);
            $blok       = $result_->fetchRow();
            $width      = $blok['WIDTH'];
            $yard_id    = $blok['ID'];

            $query_cell2 = "SELECT a.INDEX_CELL as INDEX_CELL, b.ID AS ID, b.NAME AS NAME, a.SLOT_ AS SLOT_, a.ROW_ AS ROW_, a.STATUS_BM,b.POSISI,a.SIZE_PLAN_ALLO FROM YD_BLOCKING_AREA b, YD_BLOCKING_CELL a WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = $id_yd AND a.SIZE_PLAN_ALLO IS NULL 
							UNION 
							SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_, d.STATUS_BM,c.POSISI,d.SIZE_PLAN_ALLO FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = $id_yd AND (d.SIZE_PLAN_ALLO = '40d' or d.SIZE_PLAN_ALLO = '40b' )
							UNION
							SELECT d.INDEX_CELL AS INDEX_CELL, c.ID AS ID, c.NAME AS NAME, d.SLOT_ AS SLOT_, d.ROW_ AS ROW_,d.STATUS_BM,c.POSISI,d.SIZE_PLAN_ALLO FROM YD_BLOCKING_AREA c, YD_BLOCKING_CELL d WHERE d.ID_BLOCKING_AREA = c.ID AND c.ID_YARD_AREA = $id_yd AND d.SIZE_PLAN_ALLO = '20'
							ORDER BY INDEX_CELL ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();

$html .= '<center>
		  <br/>
		  <div align="center"><b><font size="14">Layout '.'</font></b></div>
		  <br/>
		  <br/>
		  <table align="center" border="1"> <tr>';
		foreach ($blok2 as $row){
         
				$id_block   = $row['ID'];
                    $slot_       = $row['SLOT_'];
                    $row_        = $row['ROW_'];
                    $name_        = $row['NAME'];
                    $index_cell_  = $row['INDEX_CELL'];
					$st_bm		  = $row['STATUS_BM'];
					$pos	=$row['POSISI'];
					
					if($pos=='vertical')
					{
						$cr='rowspan="2"';
					}
					else
						$cr='colspan="2"';
					
			
              if ($row['NAME'] <> 'NULL' and $row['SIZE_PLAN_ALLO']<>'40b')
				{
                    $voyage2=explode("-",$voyage);
                    $queryukk="SELECT NO_UKK FROM RBM_H WHERE NM_KAPAL='$vessel' AND VOYAGE_IN='$voyage2[0]' ";//print_r($queryukk);die;					
					$result10=$db->query($queryukk);
					
					$result10_=$result10->fetchRow();
					//print_r($queryukk);die;
					$no_ukk=$result10_['NO_UKK'];
                 //  echo $index_cell_."-".$row['INDEX_CELL']."-".$row['NAME']."<br>";
                // echo " SELECT COUNT(ID_PLACEMENT) JUM FROM YD_PLACEMENT_YARD WHERE ID_BLOCKING_AREA = '$id_block' AND SLOT_YARD = '$slot_' AND ROW_YARD = '$row_' AND ID_CELL = '$index_cell_' ";
                      $query_place = "SELECT distinct a.INDEX_CELL ,a.SIZE_, a.TYPE_,a.STATUS_CONT, a.KATEGORI,a.HZ,a.ID_PEL_TUJ,a.STATUS_BM FROM YD_YARD_ALLOCATION_PLANNING a, YD_BLOCKING_AREA b, YD_YARD_AREA c WHERE a.ID_BLOCKING_AREA = b.ID AND b.ID_YARD_AREA = c.ID AND c.ID=$id_yd AND a.INDEX_CELL = '$index_cell_' AND TRIM(a.ID_VS)='$no_ukk'";
					//  print_r($query_place);die;
                     $result2     = $db->query($query_place);
                     $place       = $result2->fetchRow();
                      $stbm_       = $place['STATUS_BM'];
                     
					if($stbm_=='Bongkar')
					 {
						$size_       = $place['SIZE_'];
						$status_	  = $place['STATUS_CONT'];
						$type_       = $place['TYPE_'];
						$kategori2   = 'DCH';
					 }
					 else
					 {
						$size_       = $place['SIZE_'];
						$status_	  = $place['STATUS_CONT'];
						$type_       = $place['TYPE_'];
						$kategori2   = $place['KATEGORI'];
					 }
					 $hz		= $place['HZ'];
                     
             
                     $placement   = $place['JUM'];
               if (($size_ == '40') && (trim($type_ )== 'HQ')){ 
                         $html.='  <td bgcolor="#660066" '.$cr.' >'.$kategori2.'</td>';
                     }
					else if (($size_ == '40') && ($hz =='Y')){ 
                            $html .=' <td bgcolor="#FF3399"  '. $cr.' >'.$kategori2.'</td>';
					     } else if (($size_ == '20') && ($hz =='Y')){ 
                           $html .=' <td bgcolor="#FF3399"><font color="#ffffff">'.$status_.'</font></td>';
					  } else if (($size_ == '20') && ($type_ =='OVD')){ 
                            $html .=' <td bgcolor="#333333"><font color="#ffffff">'.$status_.'</font></td>';
						  	}else if (($size_ == '40') && ($type_ == 'OVD')){ 
                             $html .=' <td bgcolor="#333333" '.$cr.'>'.$kategori2.'</td>';
							 	}else if (($size_ == '40') && ($type_ == 'DRY') &&($status_ =='FCL')){ 
                            $html .=' <td bgcolor="#FFFF00" '.$cr .' >'.$kategori2.'</td>';
					   } else if (($size_ == '40') && ($status_ =='MTY')){ 
                           $html .=' <td bgcolor="#33FF66" '. $cr.'><font color="#ffffff">'.$status_.'</font></td>';
						   
					   } else if (($size_ == '20') && ($status_ =='MTY')){ 
                           $html .=' <td bgcolor="#33FF66" ><font color="#ffffff">'.$status_.'</font></td>';
						   }
						       else if (($size_ == '40') && (trim($type_) == 'DG')){ 
                             $html.=' <td bgcolor="#FF3399" '.$cr.' >'.$kategori2.'</td>';
                     } else if (($size_ == '45') && ($type_ == 'DRY')){
                           $html .=' <td bgcolor="#009900" '.$cr.'><font color="#ffffff">'.$kategori2.'</font></td>';
                     } else if (($size_ == '45') && (trim($type_ )== 'HQ')){ 
                            $html .=' <td bgcolor="#009900" '.$cr.'> '.$kategori2.'</td>';
                      } else if (($size_ == '45') && (trim($type_) == 'DG')){
                            $html .=' <td bgcolor="#FF3399" '.$cr.'>'.$kategori2.'</td>';
					  }
					     else if (($size_ == '20') && ($type_ == 'DRY')){ 
                           $html .=' <td bgcolor="#3366FF"><font color="#ffffff">'.$kategori2.'</font></td>';
                   } else if (($size_ == '20') && (trim($type_) == 'HQ')){ 
                           $html .=' <td bgcolor="#3366FF">'.$kategori2.'</td>';
                    } else if (($size_ == '20') && (trim($type_) == 'DG')){
                           $html .=' <td bgcolor="#FF3399">'.$kategori2.'</td>';
					 } else if (($size_ == '40') && ($type_ == 'RFR')){ 
                          $html .=' <td bgcolor="#009966" '.$cr.'> '.$kategori2.'</td>';
					} else if (($size_ == '20') && ($type_ == 'RFR')){
                          $html .=' <td bgcolor="#009966" >'.$kategori2.'</td>';}
					    else if (($size_ == '20') && ($type_ == 'TNK')){ 
                          $html .=' <td bgcolor="#663399">'.$kategori2.'</td>';
				  } else if (($size_ == '40') && ($type_ == 'TNK')){ 
                          $html .=' <td bgcolor="#663399" '.$cr.'>'.$kategori2.'</td>';
					 } else if (($size_ == '20') && (trim($type_) == 'OT')){
                           $html .=' <td bgcolor="#CCCCCC">'.$kategori2.'</td>';
					  } else if (($size_ == '40') && (trim($type_) == 'OT')){ 
                          $html .=' <td bgcolor="#CCCCCC" '.$cr.'>'.$kategori2.'</td>';
					  }
				   else{
				 if($row['SIZE_PLAN_ALLO']=='40d'){
						  $html .= '<td  bgcolor="#C8E3FF" '.$cr.' >'.$name_.'</td>';
					  }
					   else
                       $html .= '<td   bgcolor="#C8E3FF" >'.$name_.'</td>';
				 }
					 
                    
            
			  }
			   else if( $row['SIZE_PLAN_ALLO']<>'40b')
			   
			   {
                     $html .= ' <td class="general">';
					  if (($slot_ == NULL) AND ($row_ <> NULL) AND ($st_bm == NULL)) {
							$html .=$row_;
					   } else if (($slot_ <> NULL) AND ($row_ == NULL) AND ($st_bm == NULL)) {
							$html .=$slot_;
					   } else if (($slot_ == NULL) AND ($row_ == NULL) AND ($st_bm <> NULL)) {
							$html .='<font ><b>'.$st_bm.'</b></font>';
							//$html.=$st_bm;
							//$html.='</b></font>';
					
						}
						$html .= '</td>';
           }
                    if (($row['INDEX_CELL']+1) % $width == 0){ 
   $html .='</tr>';
  if (($row['INDEX_CELL']+1)<count($blok2) )$html .=' <tr>';
                     }
            } 
			
	$html .= '</table>
		  </center>
		  <br/>
		  Klasifikasi Tonage';
	// print_r($html);die;
// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_bayplan.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>