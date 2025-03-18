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

// ---------------------------------------------------------

$db = getDB();
$p1 = $_GET['p1'];
$query="SELECT A.*, to_char(A.TGL_NOTA,'DD-MM-YYYY') TGL_NOTA2 FROM OG_HNOTA2 A WHERE A.ID_NOTA='$p1'";
$data = $db->query($query)->fetchRow();
if($data[PERDAGANGAN]=='L')	$perdagangan="Luar Negeri";
else 						$perdagangan="Dalam Negeri";

$query_pbm="SELECT NAMA_PELANGGAN, ALAMAT_1, ALAMAT_2, NO_NPWP, SELEKSI FROM OG_PELANGGAN WHERE KODE_PELANGGAN='".$data[PEMILIK]."'";
$data_pbm = $db->query($query_pbm)->fetchRow();

// jumlah detail barangnya
$query_jum="SELECT (COUNT(1)*2) JUM_DETAIL FROM OG_DNOTA2 A WHERE A.ID_NOTA='$p1'";	// count dikali 2 karena dipecah per-line antara dermaga & kebersihan
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>14 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 14, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('helvetica', '', 9);
	$pdf->Write(0, "Kode & No. Seri Faktur Pajak : ".$data[TAX_NUMBER], '', 0, 'R', true, 0, false, false, 0);

	$pdf->ln();
	$pdf->SetFont('helvetica', 'B', 9);
	$pdf->Write(0, $data[ID_NOTA], '', 0, 'R', true, 0, false, false, 0);

	$pdf->ln();
	$pdf->SetFont('helvetica', 'B', 9);
	$pdf->Write(0, 'B/M PETIKEMAS DERMAGA KONVENSIONAL', '', 0, 'R', true, 0, false, false, 0);
	$pdf->Write(0, '(Dermaga & Kebersihan)', '', 0, 'R', true, 0, false, false, 0);

	$pdf->SetFont('helvetica', '', 9);
	$tbl = <<<EOD
	<table cellpadding="1">
		<tr>
			<td width="45%"></td>
			<td width="55%">$data[NM_PBM]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[NM_PEMILIK] / $data[PEMILIK]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data_pbm[ALAMAT_1] $data_pbm[ALAMAT_2]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data_pbm[NO_NPWP]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[NM_KADE]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[ID_ORDER]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[VESSEL] / $data[VOYAGE] / $data[ETA]</td>
		</tr>
		<tr>
			<td></td>
			<td>$perdagangan</td>
		</tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
	</table>
EOD;
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$limit1 = (($jum_data_page/2) * ($pg-1)) + 1;	//limit bawah	dibagi 2 soalnya 1 detail 2 line
	$limit2 = ($jum_data_page/2) * $pg;				//limit atas
	//$query_dtl="SELECT A.*, OG_GETNAMA.GET_NAMABRG(A.JNS_BRG) NM_BRG, OG_GETNAMA.GET_NAMAKMS(A.KEMASAN) NM_KMS FROM OG_DNOTA2 A WHERE A.ID_NOTA='$p1' AND A.NO BETWEEN $limit1 AND $limit2 ORDER BY A.NO";
	$query_dtl="(SELECT NO, OG_GETNAMA.GET_NAMABRG(JNS_BRG) NM_BRG, KEMASAN, BONGKAR, MUAT, SATUAN, TRF_DMG TARIF, VALUTA, ((NVL(BONGKAR,0)+NVL(MUAT,0))*TRF_DMG) SUBTOTAL, 'DERMAGA' KET FROM OG_DNOTA2 WHERE ID_NOTA='$p1' AND NO BETWEEN $limit1 AND $limit2
				 UNION
				 SELECT NO, OG_GETNAMA.GET_NAMABRG(JNS_BRG) NM_BRG, KEMASAN, BONGKAR, MUAT, SATUAN, TRF_KBS TARIF, VALUTA, ((NVL(BONGKAR,0)+NVL(MUAT,0))*TRF_KBS) SUBTOTAL, 'KEBERSIHAN' KET FROM OG_DNOTA2 WHERE ID_NOTA='$p1' AND NO BETWEEN $limit1 AND $limit2)
				ORDER BY NO, KET";
	$res = $db->query($query_dtl);
	$i=0;
	unset($detail);
	while($data_dtl = $res->fetchRow()) {		
		if($data_dtl[BONGKAR]<>'')	$bongkar = "$data_dtl[BONGKAR] $data_dtl[SATUAN]";
		else					$bongkar = "-";
		if($data_dtl[MUAT]<>'')	$muat = "$data_dtl[MUAT] $data_dtl[SATUAN]";
		else					$muat = "-";
					
		/*$detail .= '<tr>
						<td valign="top" align="right">'.$data_dtl[NO].'.</td>
						<td valign="top">'.$data_dtl[NM_BRG].' '.$info.'</td>
						<td valign="top" align="right">'.$bongkar.'</td>
						<td valign="top" align="right">'.$muat.'</td>
						<td valign="top" align="right">x &nbsp;&nbsp;</td>
						<td valign="top" align="right">'.desimal($data_dtl[TOT_TARIF],$data[VALUTA]).'</td>
						<td valign="top" align="right">'.desimal($data_dtl[SUBTOTAL],$data[VALUTA]).'</td>
					</tr>';*/
		$detail .= '<tr>
						<td valign="top" align="right">'.++$i.'.</td>
						<td valign="top">'.$data_dtl[NM_BRG].' ('.$data_dtl[KET].')</td>
						<td valign="top" align="right">'.$bongkar.'</td>
						<td valign="top" align="right">'.$muat.'</td>
						<td valign="top" align="right">x</td>
						<td valign="top" align="right">'.desimal($data_dtl[TARIF],$data[VALUTA]).'</td>
						<td valign="top" align="right">'.desimal($data_dtl[SUBTOTAL],$data[VALUTA]).'</td>
					</tr>';
		//$i++;
	}

	//if($i>0) {
		$pdf->SetFont('helvetica', '', 8);
		$tbl = <<<EOD
		<table cellpadding="1">
			<tr>
				<th width="3%"></th>
				<th width="40%"></th>
				<th width="10%" align="right">Bongkar</th>
				<th width="10%" align="right">Muat</th>
				<th width="7%"></th>
				<th width="10%" align="right">Tarif</th>
				<th width="20%"></th>
			</tr>
			$detail
		</table>
EOD;
		$pdf->writeHTML($tbl, true, false, false, false, '');
	//}
	
	if($pg < $jum_page) {	//buat garis silang bagian bawah nota
		$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 0, 0));
		$pdf->Line(10, 200, 205, 280, $style);		
		$pdf->Line(10, 280, 205, 200, $style);		
	}
}

while($i<14) {	// apabila jumlah barang kurang dari 14 pada page terakhir, ditambahkan space
	$space .= "<tr><td></td><tr>";
	$i++;
}

$ket_selisih="PIUTANG";
$selisih = $data[SELISIH];
if($data[TOT_UPER] <> "") {	// ada upernya
	if($data[SELISIH] < 0) {
		$ket_selisih="LEBIH BAYAR";
		$selisih = $data[TOTAL] * (-1);
	}
}
else	// jika tidak ada upernya, besaran piutang sama dengan total tagihan
	$selisih = $data[TOTAL];

if (strpos($selisih,"."))	{	//cek apakah ada koma
	$bulat = substr($selisih,0,strpos($selisih,"."));
	$terbilang1 = toTerbilang($bulat);
	$pecahan = substr($selisih,strpos($selisih,".")+1,strlen($selisih)-(strpos($selisih,".")+1));
	$terbilang2 = toTerbilang($pecahan);
	$terbilang = $terbilang1." koma ".$terbilang2;
}
else
	$terbilang = toTerbilang($selisih);

if($data[VALUTA]==1) {	//cek valuta
	$val="Rp";
	if($terbilang <> "")	$terbilang .= " rupiah";
}
else {
	$val="$";
	if (strpos($selisih,"."))
		$terbilang = $terbilang1." dollar ".$terbilang2." sen";
	else
		if($terbilang <> "")	$terbilang .= " dollar";
}

$dpp = desimal($data[JUMLAH],$data[VALUTA]);
$ppn = desimal($data[PPN],$data[VALUTA]);
$total = desimal($data[TOTAL],$data[VALUTA]);

$pdf->SetFont('helvetica', '', 8);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td align="right" width="55%">1.</td>
		<td width="30%">DASAR PENGENAAN PAJAK</td>
		<td align="right" width="5%">$val</td>
		<td align="right" width="10%">$dpp</td>
	</tr>
	<tr>
		<td align="right">2.</td>
		<td>PPN 10%</td>
		<td align="right">$val</td>
		<td align="right">$ppn</td>
	</tr>
	<tr>
		<td align="right">3.</td>
		<td>JUMLAH TAGIHAN</td>
		<td align="right">$val</td>
		<td align="right">$total</td>
	</tr>
	$space
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$pdf->ln();
$pdf->SetFont('helvetica', '', 6);
$pdf->Write(0, '*Nota sebagai faktur pajak berdasarkan Peratutan Dirjen Pajak Nomor 10/PJ/2010 tanggal 9 Maret 2010', '', 0, 'L', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', '', 9);
$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Write(0, $data[ID_NOTA], '', 0, 'R', true, 0, false, false, 0);

$pdf->SetFont('helvetica', '', 9);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="5%"></td>
		<td width="18%">NAMA PEMAKAI JASA</td>
		<td width="3%">:</td>
		<td width="40%"><b>$data[NM_PEMILIK]</b></td>
		<td width="34%"></td>
	</tr>
	<tr>
		<td></td>
		<td>NAMA KAPAL</td>
		<td>:</td>
		<td><b>$data[VESSEL]</b></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>TGL KEGIATAN</td>
		<td>:</td>
		<td><b>$data[RTA] / $data[RTD]</b></td>
		<td align="right">$data[TGL_NOTA]</td>
	</tr>
	<tr>
		<td></td>
		<td>JUMLAH</td>
		<td>:</td>
		<td><b>$val $total</b></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>TERBILANG</td>
		<td>:</td>
		<td><b>$terbilang</b></td>
		<td></td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>