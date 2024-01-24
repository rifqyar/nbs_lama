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

$pdf->SetFont('times', '', 10);



//koneksi db oracle
$db ="(DESCRIPTION=
    (ADDRESS=
      (PROTOCOL=TCP)
      (HOST=anton-PC)
      (PORT=1521)
    )
    (CONNECT_DATA=
      (SERVER=dedicated)
      (SERVICE_NAME=XE)
    )
  )";	
$conn = ocilogon("STORAGE", "STORAGE","$db")or die ("can't connect to server");

$no_nota	= $_GET["no_nota"];
$no_req		= $_GET["no_req"];

$query_nota	= "SELECT a.NO_NOTA AS NO_NOTA,
                      a.TGL_NOTA AS TGL_NOTA,
                      c.NAMA AS EMKL,
                      a.NO_REQUEST AS NO_REQUEST,
                      d.NO_CONTAINER AS NO_CONTAINER,
                      e.SIZE_ AS SIZE_,
                      e.TYPE_ AS TYPE_,
                      d.STATUS
               FROM storage.NOTA_RECEIVING a,
                    storage.REQUEST_RECEIVING b,
                    storage.MASTER_PBM c,
                    storage.CONTAINER_RECEIVING d,
                    storage.MASTER_CONTAINER e
                   WHERE a.NO_REQUEST = '$no_req' 
                    AND a.NO_REQUEST = b.NO_REQUEST
                    AND     b.ID_EMKL = c.ID
                    AND d.NO_CONTAINER = e.NO_CONTAINER
                    AND d.NO_REQUEST = b.NO_REQUEST
				   ";
	   
$result_nota = OCIparse($conn, $query_nota);
ociexecute($result_nota);
//OCIFetchInto ($result_nota, $row_nota, OCI_ASSOC);
	

while (OCIFetchInto ($result_nota, $row_nota, OCI_ASSOC)) {

$html .='<table>
		<tr><td colspan="30"></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
		<td colspan="3"><font size="6">'.$row_nota['NO_CONTAINER'].'</font></td>
		<td colspan="8"><font size="11"></font></td>
		<td colspan="6"><font size="6">'.$row_nota['TGL_NOTA'].'</font></td>
		</tr>
		<tr>
		<td colspan="3"></td>
		<td colspan="8"><font size="11">'.$row_nota['NO_CONTAINER'].'</font></td>
		<td colspan="6"><font size="11">'.$row_nota['NO_REQUEST'].'</font></td>
		</tr>
		<tr>
		<td colspan="3"></td>
		<td colspan="8"><font size="11">'.$row_nota['SIZE_'].'-'.$row_nota['TYPE_'].'-'.$row_nota['STATUS'].'</font></td>
		<td colspan="6"><font size="7">'.$row_nota['EMKL'].'</font></td>
		</tr>
		<tr>
		<td colspan="3"></td>
		<td colspan="9" ><font size="7">KARTU PENUMPUKKAN</font></td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		
	
		</table>';

 }	
	


// Print text using writeHTMLCell()
$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_pdf.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+

?>