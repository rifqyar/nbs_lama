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
$pdf->Write(0, 'RINCIAN PERHITUNGAN BONGKAR MUAT', '', 0, 'C', true, 0, false, false, 0);
$pdf->ln();

$db = getDB();
$p1 = $_GET['p1'];
$query="SELECT * FROM OG_HNOTA WHERE ID_NOTA='$p1'";
$data = $db->query($query)->fetchRow();

$query_pbm="SELECT NAMA_PELANGGAN, ALAMAT_1, ALAMAT_2, NO_NPWP, SELEKSI FROM OG_PELANGGAN WHERE KODE_PELANGGAN='".$data[PEMILIK]."'";
$data_pbm = $db->query($query_pbm)->fetchRow();

$arr_term2 = array('TO1'=>'I','TO2'=>'II','TO3'=>'III');
$terminal = $arr_term2[$data[TERMINAL]];
if($data[PERDAGANGAN]=='L')	$perdagangan="Luar Negeri";
else 						$perdagangan="Dalam Negeri";

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
		<td valign="top">No Uper</td>
		<td valign="top">:</td>
		<td>$data[ID_ORDER]</td>
	</tr>
	<tr>
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
	</tr>
	<tr>
		<td></td>
		<td valign="top">Pemakai Jasa</td>
		<td valign="top">:</td>
		<td>$data[NM_PEMILIK]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Alamat</td>
		<td valign="top">:</td>
		<td>$data_pbm[ALAMAT_1] $data_pbm[ALAMAT_2]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">No Account</td>
		<td valign="top">:</td>
		<td>$data[PEMILIK]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">NPWP</td>
		<td valign="top">:</td>
		<td>$data_pbm[NO_NPWP]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Kapal / Voyage / Tanggal</td>
		<td valign="top">:</td>
		<td>$data[VESSEL] / $data[VOYAGE] / $data[RTA]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Gudang / Lapangan / Kade</td>
		<td valign="top">:</td>
		<td>$data[NM_KADE]</td>
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
		<td>$data[RTB] - $data[RTD]</td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$query_dtl="SELECT A.*, OG_GETNAMA.GET_NAMABRG(A.JNS_BRG) NM_BRG, OG_GETNAMA.GET_NAMAKMS(A.KEMASAN) NM_KMS FROM OG_DNOTA A WHERE A.ID_NOTA='$p1' ORDER BY A.NO";
$res = $db->query($query_dtl);
$i=$j=1;
while($data_dtl = $res->fetchRow()) {
	//berbahaya
	if($data_dtl[BERBAHAYA]==1)			$info = "/BDL";
	else if($data_dtl[BERBAHAYA]==2)	$info = "/BTL";
	else								$info = "";
	//Mengganggu
	if($data_dtl[MENGGANGGU]==1)	$info2 = "/G";
	else							$info2 = "";
	
	if($data_dtl[KEGIATAN]=='SHIFT') {	//Kegiatan Shifting
		//cek subkeg, apakah ada angka 2 (utk membedakan landed dan unlanded)
		if(stristr($data_dtl[SUBKEG],'2') || $data_dtl[SUBKEG]=='LRO')	$land = "landed";
		else	$land = "unlanded";
		$jumbm = '<td colspan="2">shifting '.$land.' : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';
	}
	else if($data_dtl[KEGIATAN]=='TRANS')	//Kegiatan Transhipment
		$jumbm = '<td colspan="2">transhipment : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';
	else if($data_dtl[KEGIATAN]=='TRANS2')	//Kegiatan Transhipment TPK
		$jumbm = '<td colspan="2">transhipment tpk : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';
	else
		$jumbm = '<td align="center">'.$data_dtl[BONGKAR].'</td><td align="center">'.$data_dtl[MUAT].'</td>';
	$detail .= '<tr>
					<td>'.$i.'</td>
					<td align="center">'.$data_dtl[VIA].'</td>
					<td>'.$data_dtl[NM_BRG].$info.$info2.'</td>
					<td align="center">'.$data_dtl[NM_KMS].'</td>
					'.$jumbm.'
					<td align="right">'.$data_dtl[TARIF].'</td>
					<td align="right">'.desimal($data_dtl[SUBTOTAL],$data[VALUTA]).'</td>
				</tr>';
	
	if($data_dtl[BIAYA] <> "") {	//cek biaya reduksi
		$jumbm2 = $data_dtl[BONGKAR] + $data_dtl[MUAT];		
		if($data_pbm[SELEKSI]=='Y')	//PBM terseleksi / CMS
			$q_sharing="SELECT PBM_SELEKSI SELEKSI FROM OG_TR_SHARING WHERE (TO_DATE('$data[RTB]','DD-MON-YY') BETWEEN START_DATE AND END_DATE)";
		else	//PBM non-seleksi / non-CMS
			$q_sharing="SELECT PBM_NONSELEKSI SELEKSI FROM OG_TR_SHARING WHERE (TO_DATE('$data[RTB]','DD-MON-YY') BETWEEN START_DATE AND END_DATE)";
		$data_sharing = $db->query($q_sharing)->fetchRow();
			$biaya = round(($data_dtl[TARIF] * $data_sharing[SELEKSI]),2);	//biaya untuk reduksi ke PBM		
		
		$detail_biaya .= '<tr>
							<td>'.$j.'</td>
							<td align="center">'.$data_dtl[VIA].'</td>
							<td>'.$data_dtl[NM_BRG].$info.$info2.'</td>
							<td align="center">'.$data_dtl[NM_KMS].'</td>
							<td align="center">'.$jumbm2.'</td>
							<td align="right">'.$data_dtl[TARIF].'</td>
							<td align="right">'.$biaya.'</td>
							<td align="right">'.desimal($data_dtl[BIAYA],$data[VALUTA]).'</td>
						  </tr>';
		$j++;
	}
	$i++;
}

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1" border="1" width="100%">
	<tr>
		<th width="5%" rowspan="2" valign="center" align="center"><b>No</b></th>
		<th width="5%" rowspan="2" valign="center" align="center"><b>Via</b></th>
		<th width="33%" rowspan="2" valign="center" align="center"><b>Jenis Barang</b></th>
		<th width="12%" rowspan="2" valign="center" align="center"><b>Kemasan</b></th>
		<th width="20%" colspan="2" valign="center" align="center"><b>Jumlah</b></th>
		<th width="10%" rowspan="2" valign="center" align="center"><b>Tarif</b></th>
		<th width="15%" rowspan="2" valign="center" align="center"><b>Biaya</b></th>
	</tr>
	<tr>
		<th width="10%" valign="center" align="center"><b>Bongkar</b></th>
		<th width="10%" valign="center" align="center"><b>Muat</b></th>
	</tr>
	$detail
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

if($data[VALUTA]==1)	$val="Rp";
else 					$val="US$";

$jumlah = desimal($data[JUMLAH],$data[VALUTA]);
$ppn = desimal($data[PPN],$data[VALUTA]);
$total = desimal($data[TOTAL],$data[VALUTA]);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="56%"></td>
		<td align="right" width="20%"><b>Jumlah</b></td>
		<td align="right" width="10%"><b>$val</b></td>
		<td align="right" width="14%"><b>$jumlah</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>PPN (10%)</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$ppn</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>Jumlah Tagihan</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$total</b></td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->Write(0, 'Perhitungan Biaya', '', 0, 'L', true, 0, false, false, 0);
$tbl = <<<EOD
<table cellpadding="1" border="1" width="100%">
	<tr>
		<th width="5%" valign="center" align="center"><b>No</b></th>
		<th width="5%" valign="center" align="center"><b>Via</b></th>
		<th width="33%" valign="center" align="center"><b>Jenis Barang</b></th>
		<th width="12%" valign="center" align="center"><b>Kemasan</b></th>
		<th width="10%" valign="center" align="center"><b>B / M</b></th>
		<th width="10%" valign="center" align="center"><b>Tarif</b></th>
		<th width="10%" valign="center" align="center"><b>Biaya</b></th>
		<th width="15%" valign="center" align="center"><b>Total Biaya</b></th>
	</tr>
	$detail_biaya
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$jumlah = desimal($data[JUM_BIAYA],$data[VALUTA]);
$ppn = desimal($data[PPN_BIAYA],$data[VALUTA]);
$tot_biaya = desimal($data[TOT_BIAYA],$data[VALUTA]);
$pph = desimal($data[PPH_BIAYA],$data[VALUTA]);
$biaya_nett = desimal($data[BIAYA_DIBAYARKAN],$data[VALUTA]);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="56%"></td>
		<td align="right" width="20%"><b>Jumlah</b></td>
		<td align="right" width="10%"><b>$val</b></td>
		<td align="right" width="14%"><b>$jumlah</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>PPN (10%)</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$ppn</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>Jumlah Biaya</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$tot_biaya</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>PPh (2%)</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$pph</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>Biaya Dibayarkan</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$biaya_nett</b></td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>