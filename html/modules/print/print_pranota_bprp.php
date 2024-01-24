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
$pdf->Write(0, 'PRANOTA DAN PERHITUNGAN JASA PENUMPUKAN', '', 0, 'C', true, 0, false, false, 0);
$pdf->ln();

$db = getDB();
$p1 = $_GET['p1'];
$query="SELECT A.*, B.*, OG_GETNAMA.GET_PERDAGANGAN(B.JN_DAGANG) PERDAGANGAN FROM OG_NOTA_BPRPH A, OG_BPRPH B WHERE A.ID_NOTA='$p1' AND A.ID_BPRP=B.ID_BPRP";
$data = $db->query($query)->fetchRow();

$query_pbm="SELECT NAMA_PELANGGAN, ALAMAT_1, ALAMAT_2, NO_NPWP, SELEKSI FROM OG_PELANGGAN WHERE KODE_PELANGGAN='".$data[ID_CUST]."'";
$data_pbm = $db->query($query_pbm)->fetchRow();

if($data[JN_DAGANG]==2) {	//import
$info_sppb ='<tr>
				<td width="18%"></td>
				<td valign="top" width="25%">No SPPB / Tanggal</td>
				<td valign="top" width="2%">:</td>
				<td width="55%">'.$data[NO_SPPB].' / '.$data[TGL_SPPB].'</td>
			 </tr>';
}
if($data[EXT_BPRP]<>"") {	//Perpanjangan
$ext_bprp ='<tr>
				<td width="18%"></td>
				<td valign="top" width="25%">Perpanjangan BPRP</td>
				<td valign="top" width="2%">:</td>
				<td width="55%">'.$data[EXT_BPRP].'</td>
			 </tr>';
}

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="18%"></td>
		<td valign="top" width="25%">No Pranota</td>
		<td valign="top" width="2%">:</td>
		<td width="55%">$data[ID_NOTA]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Koreksi Dari Nota</td>
		<td valign="top">:</td>
		<td>$data[EX_NOTA]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">PBM</td>
		<td valign="top">:</td>
		<td>$data[NM_PBM]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Pemilik / Pemakai Jasa</td>
		<td valign="top">:</td>
		<td>$data[NM_CUST] / $data[ID_CUST]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Alamat</td>
		<td valign="top">:</td>
		<td>$data_pbm[ALAMAT_1] $data_pbm[ALAMAT_2]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">NPWP</td>
		<td valign="top">:</td>
		<td>$data_pbm[NO_NPWP]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Gudang / Lapangan / Kade</td>
		<td valign="top">:</td>
		<td>$data[NM_GUDLAP]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Nomor BPRP</td>
		<td valign="top">:</td>
		<td>$data[ID_BPRP]</td>
	</tr>
	$ext_bprp
	<tr>
		<td></td>
		<td valign="top">Kapal / Voyage / Tanggal</td>
		<td valign="top">:</td>
		<td>$data[VESSEL] / $data[VOYAGE] / $data[ETA]</td>
	</tr>
	<tr>
		<td></td><td valign="top">Jenis Perdagangan</td>
		<td valign="top">:</td>
		<td>$data[PERDAGANGAN]</td>
	</tr>
	$info_sppb
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$query_dtl="SELECT A.*, OG_GETNAMA.GET_NAMABRG(A.JNS_BRG) NM_BRG FROM OG_NOTA_BPRPD A WHERE A.ID_NOTA='$p1' ORDER BY NO";
$res = $db->query($query_dtl);
while($data_dtl = $res->fetchRow()) {
	if($data_dtl[TON]==0 && $data_dtl[M3]==0 && $data_dtl[BOX]==0)
		$jumlah = $data_dtl[JUMLAH];
	else {
		if($data_dtl[TON] > $data_dtl[M3] && $data_dtl[TON] > $data_dtl[BOX])
			$jumlah = $data_dtl[TON];
		else if($data_dtl[M3] > $data_dtl[TON] && $data_dtl[M3] > $data_dtl[BOX])
			$jumlah = $data_dtl[M3];
		else
			$jumlah = $data_dtl[BOX];
	}
	$sewa1a = desimal(($data_dtl[MASA1_1]*$jumlah*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN1_1]/100),1);
	$sewa1b = desimal(($data_dtl[MASA1_2]*$jumlah*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN1_2]/100),1);
	$sewa2 = desimal(($data_dtl[MASA2]*$jumlah*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN2]/100),1);
	$sewa_sppb = desimal(($data_dtl[MASA_SPPB]*$jumlah*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN_SPPB]/100),1);
	
	if($data[JN_DAGANG]==2) {	//import
		$detail .= '<tr>
						<td>'.$data_dtl[NM_BRG].'<br>'.$data_dtl[JUMLAH].' '.$data_dtl[SATUAN].'<br>'.$data_dtl[TON].'/'.$data_dtl[M3].'/'.$data_dtl[BOX].'</td>
						<td align="center">'.$data_dtl[TGL_IN].'<br>'.$data_dtl[TGL_OUT].'</td>
						<td align="center">'.$data_dtl[MASA1_1].'<br>'.$data_dtl[MASA1_2].'<br>'.$data_dtl[MASA2].'<br>'.$data_dtl[MASA_SPPB].'</td>
						<td align="center">'.$data_dtl[PROSEN1_1].'<br>'.$data_dtl[PROSEN1_2].'<br>'.$data_dtl[PROSEN2].'<br>'.$data_dtl[PROSEN_SPPB].'</td>
						<td align="right">'.desimal($data_dtl[TRF_PENUMPUKAN],1).'<br>'.desimal($data_dtl[TRF_KEBERSIHAN],1).'</td>
						<td align="right">'.$sewa1a.'<br>'.$sewa1b.'<br>'.$sewa2.'<br>'.$sewa_sppb.'</td>
						<td align="right">'.desimal($data_dtl[TGH_PENUMPUKAN],1).'<br>'.desimal($data_dtl[TGH_KEBERSIHAN],1).'</td>
					</tr>';
	}
	else {
		$detail .= '<tr>
						<td>'.$data_dtl[NM_BRG].'<br>'.$data_dtl[JUMLAH].' '.$data_dtl[SATUAN].'<br>'.$data_dtl[TON].'/'.$data_dtl[M3].'/'.$data_dtl[BOX].'</td>
						<td align="center">'.$data_dtl[TGL_IN].'<br>'.$data_dtl[TGL_OUT].'</td>
						<td align="center">'.$data_dtl[MASA1_1].'<br>'.$data_dtl[MASA1_2].'<br>'.$data_dtl[MASA2].'</td>
						<td align="center">'.$data_dtl[PROSEN1_1].'<br>'.$data_dtl[PROSEN1_2].'<br>'.$data_dtl[PROSEN2].'</td>
						<td align="right">'.desimal($data_dtl[TRF_PENUMPUKAN],1).'<br>'.desimal($data_dtl[TRF_KEBERSIHAN],1).'</td>
						<td align="right">'.$sewa1a.'<br>'.$sewa1b.'<br>'.$sewa2.'</td>
						<td align="right">'.desimal($data_dtl[TGH_PENUMPUKAN],1).'<br>'.desimal($data_dtl[TGH_KEBERSIHAN],1).'</td>
					</tr>';
	}
	$i++;
}

$pdf->SetFont('helvetica', '', 9);
if($data[JN_DAGANG]==2) {	//import
$tbl = <<<EOD
<table cellpadding="1" border="1">
	<tr>
		<th align="center" width="30%"><b>Jenis Barang<br>Jumlah Barang</b></th>
		<th align="center" width="10%"><b>Tanggal:<br>Masuk<br>Keluar</b></th>
		<th align="center" width="10%"><b>Jml Hari:<br>Masa 1.1<br>Masa 1.2<br>Masa 2<br>Masa SPPB</b></th>
		<th align="center" width="7%"><b>%</b></th>
		<th align="center"><b>Tarif Dasar:<br>Penumpukan<br>Kebersihan</b></th>
		<th align="center"><b>Sewa:<br>Masa 1.1<br>Masa 1.2<br>Masa 2<br>Masa SPPB</b></th>
		<th align="center"><b>Jumlah:<br>Penumpukan<br>Kebersihan</b></th>
	</tr>
	$detail
</table>
EOD;
}
else {
$tbl = <<<EOD
<table cellpadding="1" border="1">
	<tr>
		<th align="center" width="30%"><b>Jenis Barang<br>Jumlah Barang</b></th>
		<th align="center" width="10%"><b>Tanggal:<br>Masuk<br>Keluar</b></th>
		<th align="center" width="10%"><b>Jml Hari:<br>Masa 1.1<br>Masa 1.2<br>Masa 2</b></th>
		<th align="center" width="7%"><b>%</b></th>
		<th align="center"><b>Tarif Dasar:<br>Penumpukan<br>Kebersihan</b></th>
		<th align="center"><b>Sewa:<br>Masa 1.1<br>Masa 1.2<br>Masa 2</b></th>
		<th align="center"><b>Jumlah:<br>Penumpukan<br>Kebersihan</b></th>
	</tr>
	$detail
</table>
EOD;
}
$pdf->writeHTML($tbl, true, false, false, false, '');

$tgh_awal = desimal($data[TGH_AWAL],1);
$lain = desimal($data[LAINNYA],1);
$b_form = desimal($data[B_FORM],1);
//$tagihan = desimal($data[TAGIHAN],1);
$ppn = desimal($data[PPN],1);
$total = desimal($data[TOTAL],1);
$val="Rp";
//$pdf->SetFont('helvetica', '', 9);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="56%"></td>
		<td align="right" width="20%">JUMLAH SEBENARNYA</td>
		<td align="right" width="10%">$val</td>
		<td align="right" width="14%">$tgh_awal</td>
	</tr>
	<tr>
		<td></td>
		<td align="right">LAIN-LAIN</td>
		<td align="right">$val</td>
		<td align="right">$lain</td>
	</tr>
	<tr>
		<td></td>
		<td align="right">BIAYA FORMULIR</td>
		<td align="right">$val</td>
		<td align="right">$b_form</td>
	</tr>
	<!--<tr>
		<td></td>
		<td align="right">JUMLAH</td>
		<td align="right">$val</td>
		<td align="right">$tagihan</td>
	</tr>-->
	<tr>
		<td></td>
		<td align="right">PPN (10%)</td>
		<td align="right">$val</td>
		<td align="right">$ppn</td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>JUMLAH TAGIHAN</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$total</b></td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>