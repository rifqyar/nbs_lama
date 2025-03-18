<?php
//require "login_check.php";
//ivan ganteng

require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	// Page footer
	public function Footer() {
		// Position at 10 mm from bottom
		$this->SetY(-20);
		// Set font
		$this->SetFont('courier', 'I', 22);
		// Page number
		//$this->Cell(0, 25, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// $pdf = new MYPDF('P', 'mm', 'A7', true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(1, 5, 1);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);


// ---------------------------------------------------------

$db			= getDB();
$no_req		= base64_decode($_GET["no_req"]);


	$query_nota	= " /* Formatted on 25-Apr-14 11:24:42 AM (QP5 v5.163.1008.3004) */
				SELECT TO_CHAR (a.CLOSSING_TIME, 'dd/mm/yyyy HH24:MI') CLOSSING_TIME,
					   a.VESSEL,
					   a.VOYAGE_IN,
                                           b.VOYAGE_OUT,
					   b.STATUS_CONT,
					   b.SIZE_CONT,
					   b.TYPE_CONT,
					   b.NO_CONTAINER,
					   b.BERAT,
					   a.PELABUHAN_TUJUAN,
					   a.FPOD,
					   a.IPOD,
					   a.FIPOD,
					   a.PEB,
					   a.NPE,
					   a.KODE_PBM,
					   b.IMO_CLASS,
					   b.TEMP, a.TGL_REQUEST, UPPER(NVL(a.MOVEMENT_BY,'DARAT')) VIA
				  FROM req_receiving_h a, req_receiving_d b
				 WHERE a.ID_REQ = b.id_req AND a.ID_REQ = '$no_req'";

	$result_nota	= $db->query($query_nota);
	$row			= $result_nota->getAll();

$i = 1;
$page=0;
$increment=1;
$tbl;
$temp_tbl;

//print_r($row); die();
foreach ($row as $row_) 
{
//	$resolution= array(100, 200);
//	$pdf->AddPage('P', $resolution);
    // add a page
$temp_tbl .= <<<EOD
<table cellpadding="1" cellspacing="1" style="margin:0px; margin-top:19px; margin-bottom:10px;">   
  <tr>
    <th class="txc" width="13%"  scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="5%" scope="col"></th>
    <th class="txc" width="8%" scope="col"></th>
    <th class="txc" width="15%" scope="col"></th> 
    <th class="txc" width="11%" scope="col"></th>
    <th class="txc" colspan="2" align="left" scope="col">1111</th>
    <th class="txc" width="1%" scope="col"></th>
  </tr>
  <tr>
    <th  class="txc"   scope="row"></th>  
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc"></td>
    <td  class="txc" colspan="2">--paid--</td>
    <td  class="txc"></td>
  </tr>
  <tr>
	<td>&nbsp;</td>
  </tr>

  <tr>
    <th   class="txc" scope="row"></th>
    <td  class="txc"></td>  
    <td  class="txc"></td>
    <td  class="txc" colspan="5"><div  style="padding-right:150px;"><em></em><em>Clossing Time : $row_[CLOSSING_TIME]</em></div></td> 
    <td  class="txc" colspan="2">$row_[TGL_REQUEST]&nbsp;&nbsp;</td>
    <td  class="txc"></td>
  </tr> 

  <tr>
    <th height="15" colspan="8" align="left"   scope="row" style="padding-left:15px; line-height:10px">$row_[VOYAGE_OUT]<br />
      $row_[NO_CONTAINER]</th>
    <td width="10%"></td>
    <td width="8%"></td> 
    <td></td>
  </tr> 
  <tr>
    <th  height="15" scope="row"></th>
    <td colspan="5" align="center"><b><font size="12"> &nbsp;&nbsp;&nbsp;$row_[NO_CONTAINER]</font> - $row_[VOYAGE_OUT]</b></td> 
    <td></td> 
    <td colspan="2" align="center">$row_[VESSEL]</td>
    <td align="left">$row_[VOYAGE_OUT]</td>
    <td></td>
  </tr>

  <tr>
    <th  height="15" scope="row"></th>
    <td align="center">&nbsp;</td>
    <td colspan="3" align="center">$row_[SIZE_CONT] - $row_[TYPE_CONT] - $row_[STATUS_CONT]</td>
    <td align="left">&nbsp;</td>
    <td></td>
    <td align="right">$row_[IPOD]/</td>
    <td colspan="2" align="left" >$row_[FIPOD]</td>
    <td></td>
  </tr>

  <tr>
    <th  height="15" scope="row"></th>
    <td></td>
    <td colspan="4" align="center">IMO Class : $row_[IMO_CLASS] / Gross :$row_[BERAT]</td>
    <td></td>
    <td colspan="3"><span>($row_[PELABUHAN_TUJUAN])</span></td>
    <td></td>
  </tr>
 
  <tr>
    <th  height="15" scope="row"></th>
    <td colspan="5" align="center">Temperature : $row_[TEMP]</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td> 
    <td></td>
  </tr>
  <tr>
    <th  height="15" scope="row"></th>
    <td align="center">&nbsp;&nbsp;</td>  
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td  align="center">&nbsp;</td>
    <td></td>
    <td colspan="3"><span>$row_[KODE_PBM]</span></td>
    <td></td>
  </tr>
  <tr>
    <th  height="15" scope="row"></th>  
    <td></td> 
    <td colspan="4" valign="top"><em>$row_[CLOSSING_TIME] <br />
        $row_[PEB]- NPE :$row_[NPE]</em></td> 
    <td></td> 
    <td colspan="3" valign="top" align="right"><span><br />&nbsp;<br />$_SESSION[NAMA_PENGGUNA];&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>  
    <td></td>
  </tr> 
  <tr> 
    <th  height="15" scope="row"></th>
    <td colspan="5">      
      <strong><span>&nbsp;&nbsp;&nbsp;OPUS - Receiving Card</span> | NO URUT: $i; | $row_[VIA]</strong></td> 
    <td></td>
    <td colspan="2" style="font-size:11px"><strong> CETAKAN KARTU KE : 1</strong></td>   
    <td>&nbsp;</td>  
    <td>&nbsp;</td>
  </tr>    
</table>
EOD;

	  
        if($i!=1 && ($i) % 3 == 0){
	       $pdf->AddPage();
		// set font
		$pdf->SetFont('courier', '', 9); 
		

	$tbl .= <<<EOD
$temp_tbl
EOD;

/*$tbl=<<<EOD
IVAN GANTENG
EOD;*/
/*echo $tbl;
die();*/

$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 12,    
    'stretchtext' => 4
);

$style2 = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 12,    
    'stretchtext' => 4
);                 

			
                //Menampilkan Barcode dari nomor nota
                //$pdf->write1DBarcode("$notanya", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
                //Logo IPC
                //$pdf->Image('images/ipcblack.png', 19, 16, 9, 6, '', '', '', true, 72);
                // $pdf->write1DBarcode("$nocont", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
                $pdf->writeHTML($tbl, true, false, false, false, '');   
                $pdf->SetDrawColor(0);
                $pdf->SetTextColor(0);
                // Start Transformation
                $pdf->StartTransform();
                // Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
                $pdf->Rotate(270); 
                //$pdf->write1DBarcode("$nocont", 'C128', -60, 55, '', 14, 0.4, $style, 'N'); 
                //$pdf->write1DBarcode("$nocont", 'C128', -60, -60, '', 14, 0.4, $style, 'N');  
                $pdf->StopTransform();  
                //$pdf->write1DBarcode("$nocont", 'C128', 137, 62, '', 8, 0.2, $style2, 'N');
				$tbl="";
				$temp_tbl="";
	}				
    $nourut++;
	$i++;
}
 //echo $temp_tbl; die();
 if($i>=2 & $i <=4){
	       $pdf->AddPage();
		  $pdf->SetFont('courier', '', 9); 
 }
	$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 12,    
    'stretchtext' => 4
);

$style2 = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 12,    
    'stretchtext' => 4
);                 

			
                //Menampilkan Barcode dari nomor nota
                //$pdf->write1DBarcode("$notanya", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
                //Logo IPC
                //$pdf->Image('images/ipcblack.png', 19, 16, 9, 6, '', '', '', true, 72);
                // $pdf->write1DBarcode("$nocont", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
                $pdf->writeHTML($temp_tbl, true, false, false, false, '');   
                $pdf->SetDrawColor(0);
                $pdf->SetTextColor(0);
                // Start Transformation
                $pdf->StartTransform();
                // Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
                $pdf->Rotate(270); 
                //$pdf->write1DBarcode("$nocont", 'C128', -60, 55, '', 14, 0.4, $style, 'N'); 
                //$pdf->write1DBarcode("$nocont", 'C128', -60, -60, '', 14, 0.4, $style, 'N');  
                $pdf->StopTransform();  
                //$pdf->write1DBarcode("$nocont", 'C128', 137, 62, '', 8, 0.2, $style2, 'N');

// for($pg=1; $pg<=$jum_page; $pg++) {

// }*/

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>