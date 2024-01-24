<?php
//require "login_check.php";
require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', '', 8);
$pdf->Write(0, "Tanggal Cetak ".date('d-M-Y'), '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Write(0, 'PRANOTA PAS OVER BAGASI', '', 0, 'C', true, 0, false, false, 0);
$pdf->ln();$pdf->ln();$pdf->ln();

$db = getDB();
$p1 = $_GET['p1'];
$query="SELECT A.NO_NOTA,
        A.TGL_NOTA,
        A.NO_STOK,
        B.TGL_STOK,
        B.PERIODE
        FROM POB_PENDAPATAN A,
        POB_REQUEST B
        WHERE A.NO_STOK = B.NO_STOK
        AND NO_NOTA = '$p1'";
$data = $db->query($query)->fetchRow();

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td valign="top" width="15%">No Pranota</td>
		<td valign="top" width="2%">:</td>
		<td valign="top" width="55%">$data[NO_NOTA]</td>
	</tr>
	<tr>
		<td valign="top">Tanggal</td>
		<td valign="top">:</td>
		<td valign="top">$data[TGL_NOTA]</td>
	</tr>
	<tr>
		<td valign="top">No Stok/Tanggal</td>
		<td valign="top">:</td>
		<td valign="top">$data[NO_STOK]/$data[TGL_STOK]</td>
	</tr>
	<tr>
		<td valign="top">Periode</td>
		<td valign="top">:</td>
		<td valign="top">$data[PERIODE]</td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->ln();$pdf->ln();

$query2="SELECT SERI_X,
        SERI_Y,
        JML_LEMBAR,
        TARIF,
        TOTAL,
        PPN,
        PENDAPATAN
        FROM POB_PENDAPATAN
        WHERE NO_NOTA = '$p1'";
$data2 = $db->query($query2)->fetchRow();

$tarif = desimal($data2[TARIF],1);
$total = desimal($data2[TOTAL],1);
$ppn   = desimal($data2[PPN],1);
$inc   = desimal($data2[PENDAPATAN],1);
$blank = '';
$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1" border="1">
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
		<td align="center" valign="center" width="25%">$blank<br><br>$data2[SERI_X] s/d $data2[SERI_Y]</td>
		<td align="center" valign="center" width="14%">$blank<br><br>$data2[JML_LEMBAR]</td>
		<td align="center" valign="center" width="14%">$blank<br><br>$tarif</td>
		<td align="right" valign="center" width="17%">$blank<br><br>$total</td>
	</tr>
	<tr>
	  <td colspan="5" align="right">
          $blank<br><br>
          JUMLAH SEBENARNYA<br>
          PPN(10%)<br>
          <b>JUMLAH PENDAPATAN</b><br>
    </td>
	  <td align="right">
	        $blank<br><br>
          Rp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$total</b><br>
          Rp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$ppn</b><br>
          Rp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$inc</b>
    </td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();


//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>
