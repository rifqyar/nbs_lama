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
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
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

$pdf->ln();
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Write(0, 'PERHITUNGAN UPER BONGKAR MUAT', '', 0, 'C', true, 0, false, false, 0);
$pdf->ln();

$db = getDB();
$p1 = $_GET['p1'];
$query="SELECT * FROM UPER_H WHERE NO_UPER='$p1'";
$data = $db->query($query)->fetchRow();

$query2="SELECT NM_PEMILIK, ALAMAT, COA, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, NO_NPWP, NM_KAPAL, VOYAGE_IN, VOYAGE_OUT
					FROM RBM_H WHERE NO_UKK='".$data[NO_UKK]."'";
$data2 = $db->query($query2)->fetchRow();

// $arr_term2 = array('TO1'=>'I','TO2'=>'II','TO3'=>'III');
// $terminal = $arr_term2[$data[TERMINAL]];
if($data[VALUTA]=='USD')	$perdagangan="Luar Negeri";
else 						$perdagangan="Dalam Negeri";

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="18%"></td>
		<td valign="top" width="25%">No Uper</td>
		<td valign="top" width="2%">:</td>
		<td width="55%">$data[NO_UPER]</td>
	</tr>
	<!--<tr>
		<td></td>
		<td valign="top">Terminal</td>
		<td valign="top">:</td>
		<td>$terminal</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">PBM</td>
		<td valign="top">:</td>
		<td>$data[NM_PBM]</td>
	</tr>-->
	<tr>
		<td></td>
		<td valign="top">Pemakai Jasa</td>
		<td valign="top">:</td>
		<td>$data2[NM_PEMILIK]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Alamat</td>
		<td valign="top">:</td>
		<td>$data2[ALAMAT]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">No Account</td>
		<td valign="top">:</td>
		<td>$data2[COA]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">NPWP</td>
		<td valign="top">:</td>
		<td>$data2[NO_NPWP]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Kapal / Voyage / Tanggal</td>
		<td valign="top">:</td>
		<td>$data2[NM_KAPAL] / $data2[VOYAGE_IN] - $data2[VOYAGE_OUT] / $data2[TGL_JAM_TIBA]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Gudang / Lapangan / Kade</td>
		<td valign="top">:</td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Jenis Perdagangan</td>
		<td valign="top">:</td>
		<td>$perdagangan</td>
	</tr>	
	<tr>
		<td></td><td valign="top">Periode Kegiatan</td>
		<td valign="top">:</td>
		<td>$data2[TGL_JAM_TIBA] -s/d- $data2[TGL_JAM_BERANGKAT]</td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$query_dtl="SELECT NO_URUT, SIZE_, TYPE_, STATUS, HEIGHT_CONT, DECODE(HZ,'T','Tidak','Y','Ya') AS HZ, BONGKAR, MUAT, TARIF, JUMLAH, VALUTA, FLAG_OI, KEGIATAN, SUBKEG FROM UPER_D WHERE NO_UPER='$p1' ORDER BY NO_URUT";
$res = $db->query($query_dtl);
$i=$j=1;
while($data_dtl = $res->fetchRow()) {
	unset($height);
	if($data_dtl[HEIGHT_CONT]=='OOG')	$height = ' / '.$data_dtl[HEIGHT_CONT];
	if($data_dtl[KEGIATAN]=='BM')
		$jumbm = '<td valign="top" align="center">'.$data_dtl[BONGKAR].'</td><td align="center">'.$data_dtl[MUAT].'</td>';
	else if($data_dtl[KEGIATAN]=='SHIFT') {
		if($data_dtl[SUBKEG]=='CRDMG1' || $data_dtl[SUBKEG]=='CRKPL1')
			$jumbm = '<td valign="top" colspan="2">shifting unlanded : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';
		else
			$jumbm = '<td valign="top" colspan="2">shifting landed : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';
	}
	else if($data_dtl[KEGIATAN]=='TRANS')
		$jumbm = '<td colspan="2">Transhipment : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';		
	$detail .= '<tr>
					<td valign="top">'.$i.'</td>
					<td valign="top">Container '.$data_dtl[SIZE_].'-'.$data_dtl[TYPE_].'-'.$data_dtl[STATUS].$height.'</td>
					<td valign="top" align="center">Petikemas</td>
					<td valign="top" align="center">'.$data_dtl[HZ].'</td>
					'.$jumbm.'
					<td valign="top" align="right">'.number_format($data_dtl[TARIF]).'</td>
					<td valign="top" align="right">'.number_format($data_dtl[JUMLAH]).'</td>
				</tr>';
	$i++;
}

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1" border="1" width="100%">
	<tr>
		<th width="4%" rowspan="2" valign="center" align="center"><b>No</b></th>		
		<th width="27%" rowspan="2" valign="center" align="center"><b>Jenis Barang</b></th>
		<th width="12%" rowspan="2" valign="center" align="center"><b>Kemasan</b></th>
		<th width="8%" rowspan="2" valign="center" align="center"><b>Hz</b></th>
		<th width="20%" colspan="2" valign="center" align="center"><b>Jumlah</b></th>
		<th width="12%" rowspan="2" valign="center" align="center"><b>Tarif</b></th>
		<th width="17%" rowspan="2" valign="center" align="center"><b>Biaya</b></th>
	</tr>
	<tr>
		<th width="10%" valign="center" align="center"><b>Bongkar</b></th>
		<th width="10%" valign="center" align="center"><b>Muat</b></th>
	</tr>
	$detail
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

if($data[VALUTA]=='IDR')	$val="Rp";
else 						$val="US$";

$jumlah = number_format($data[JUMLAH],2);
$adm = number_format($data[BIAYA_ADM],2);
$ppn = number_format($data[PPN],2);
$total = number_format($data[TOTAL],2);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="50%"></td>
		<td align="right" width="22%"><b>Jumlah</b></td>
		<td align="right" width="8%"><b>$val</b></td>
		<td align="right" width="20%"><b>$jumlah</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>Administrasi</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$adm</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>PPN (10%)</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$ppn</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>Jumlah Uper</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$total</b></td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>