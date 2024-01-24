<?php
//require "login_check.php";
require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	//Page header
	/*public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'logo_example.jpg';
		$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 6);
		// Title
		$this->Cell(0, 10, ' ', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}*/

	// Page footer
	public function Footer() {
		// Position at 10 mm from bottom
		$this->SetY(-10);
		// Set font
		$this->SetFont('helvetica', 'I', 6);
		// Page number
		$this->Cell(0, 10, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(5, 16, 8);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

$db = getDB();
$p1 = $_GET['p1'];
$query = "SELECT NO_NOTA,
         TAX_NO,
         JML_LEMBAR,
         SERI_X,
         SERI_Y,
         TARIF,
         TOTAL,
         PPN,
         PENDAPATAN
         FROM POB_PENDAPATAN
         WHERE NO_NOTA = '$p1'";
$data = $db->query($query)->fetchRow();

$tarif = desimal($data[TARIF],1);
$total = desimal($data[TOTAL],1);
$ppn   = desimal($data[PPN],1);
$inc   = desimal($data[PENDAPATAN],1);
$blank = '';

	// add a page
$pdf->AddPage();
	
  // set font
$pdf->SetFont('helvetica', '', 9);
$pdf->Write(0, ' ', '', 0, 'R', true, 0, false, false, 0);
$pdf->ln();

$pdf->SetFont('helvetica', 'B', 9);
$pdf->Write(0, $data[NO_NOTA] , '', 0, 'R', true, 0, false, false, 0);
$pdf->ln();$pdf->ln();

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Write(0, 'PAS OVER BAGASI', '', 0, 'R', true, 0, false, false, 0);
$pdf->Write(0, 'TERMINAL PENUMPANG', '', 0, 'R', true, 0, false, false, 0);
$pdf->ln();$pdf->ln();$pdf->ln();

$pdf->SetFont('helvetica', '', 9);
$pdf->Write(0, 'NO SERI FAKTUR PAJAK : '.$data[TAX_NO] , '', 0, 'R', true, 0, false, false, 0);
$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();


$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1" border="0">
  <tr>
		<td align="center" width="5%">NO</td>
		<td align="center" width="25%">JENIS PAS</td>
		<td align="center" width="25%">NO SERI</td>
		<td align="center" width="14%">LEMBAR</td>
		<td align="center" width="14%">TARIF</td>
		<td align="center" width="17%">TOTAL</td>
	</tr>
	<tr>
		<td align="center" valign="center" width="5%">$blank<br><br>1<br><br></td>
		<td align="center" valign="center" width="25%">$blank<br><br>PAS OVER BAGASI</td>
		<td align="center" valign="center" width="25%">$blank<br><br>$data[SERI_X] s/d $data[SERI_Y]</td>
		<td align="center" valign="center" width="14%">$blank<br><br>$data[JML_LEMBAR]</td>
		<td align="center" valign="center" width="14%">$blank<br><br>$tarif</td>
		<td align="right" valign="center" width="17%">$blank<br><br>$total</td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1" border="0">
  <tr>
	  <td colspan="5" align="right">JUMLAH SEBENARNYA &nbsp; Rp</td>
	  <td align="right"><b>$total</b> </td>
	</tr>
	<tr>
	  <td colspan="5" align="right">PPN (10%) &nbsp; Rp</td>
	  <td align="right"><b>$ppn</b> </td>
	</tr>
	<tr>
	  <td colspan="5" align="right">JUMLAH PENDAPATAN &nbsp; Rp</td>
	  <td align="right"><b>$inc</b> </td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

if (strpos($data[PENDAPATAN],"."))	{	//cek apakah ada koma
	$bulat = substr($data[PENDAPATAN],0,strpos($data[PENDAPATAN],"."));
	$terbilang1 = toTerbilang($bulat);
	$pecahan = substr($data[PENDAPATAN],strpos($data[PENDAPATAN],".")+1,strlen($data[PENDAPATAN])-(strpos($data[PENDAPATAN],".")+1));
	$terbilang2 = toTerbilang($pecahan);
	$terbilang = $terbilang1." koma ".$terbilang2;
}
else
	$terbilang = toTerbilang($data[PENDAPATAN]);
$terbilang .= " rupiah";

$pdf->SetFont('helvetica', '', 9);
$pdf->Write(0, 'Terbilang : '.$terbilang, '', 0, 'L', true, 0, false, false, 0);


$pdf->SetFont('helvetica', '', 9);
$pdf->Write(0, ' ', '', 0, 'R', true, 0, false, false, 0);
$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();

$pdf->SetFont('helvetica', '', 6);
$pdf->Write(0, '*Nota sebagai faktur pajak berdasarkan Peratutan Dirjen Pajak Nomor 10/PJ/2010 tanggal 9 Maret 2010', '', 0, 'L', true, 0, false, false, 0);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>
