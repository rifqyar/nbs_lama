<?php
require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');
$p1=$_GET['p1'];
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
$query="SELECT A.*, B.*, OG_GETNAMA.GET_PERDAGANGAN(B.JN_DAGANG) PERDAGANGAN FROM OG_NOTA_LOLOH A, OG_BPRPH B WHERE A.ID_NOTA='$p1' AND A.ID_BPRP=B.ID_BPRP";
$data = $db->query($query)->fetchRow();

$query_pbm="SELECT NAMA_PELANGGAN, ALAMAT_1, ALAMAT_2, NO_NPWP, SELEKSI FROM OG_PELANGGAN WHERE KODE_PELANGGAN='".$data[ID_CUST]."'";
$data_pbm = $db->query($query_pbm)->fetchRow();

// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM OG_NOTA_LOLOD A WHERE A.ID_NOTA='$p1'";
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 5;	//jumlah data dibatasi per page 5 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum['JUM_DETAIL']%$jum_data_page)>3 || ($data_jum['JUM_DETAIL']%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 3, tambah 1 page lagi
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
	$pdf->SetFont('helvetica', 'B', 11);
	$pdf->Write(0, 'NOTA LOLO OGDK', '', 0, 'R', true, 0, false, false, 0);

	$pdf->SetFont('helvetica', '', 4);
	$pdf->Write(0, ' ', '', 0, 'R', true, 0, false, false, 0);

	$pdf->SetFont('helvetica', '', 9);
	$tbl = <<<EOD
	<table cellpadding="1">
		<tr>
			<td width="45%"></td>
			<td width="55%">$data[NM_PBM]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[NM_CUST] / $data[ID_CUST]</td>
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
			<td>$data[NM_GUDLAP]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[ID_BPRP]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[VESSEL] / $data[VOYAGE] / $data[ETA]</td>
		</tr>
		<tr>
			<td></td>
			<td>$data[PERDAGANGAN]</td>
		</tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
		<tr><td></td><td></td></tr>
	</table>
	<br>
	TANGGAL KEGIATAN
EOD;
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$limit1 = ($jum_data_page * ($pg-1)) + 1;	//limit bawah
	$limit2 = $jum_data_page * $pg;				//limit atas
	$query_dtl="SELECT A.*, OG_GETNAMA.GET_NAMABRG(A.JNS_BRG) NM_BRG FROM OG_NOTA_LOLOD A WHERE A.ID_NOTA='$p1' ORDER BY NO";
	$res = $db->query($query_dtl);
	$i=0;
	unset($detail);
	$detail .= '<tr>
							<td align="center" width="5%">&nbsp;</td>
							<td width="25%">&nbsp;</td>
							<td align="center" width="15%"><b>BONGKAR</b></td>
							<td align="center" width="5%"><b>MUAT</b></td>
							<td align="center" width="12%">&nbsp;</td>
							<td align="right">&nbsp;</td>
							<td align="right">&nbsp;</td>
							<td align="right">&nbsp;</td>
						</tr>';
	while($data_dtl = $res->fetchRow())
	{
		$sewa1a = desimal(($data_dtl[MASA1_1]*$data_dtl[JUMLAH]*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN1_1]/100),1);
		$sewa1b = desimal(($data_dtl[MASA1_2]*$data_dtl[JUMLAH]*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN1_2]/100),1);
		$sewa2 = desimal(($data_dtl[MASA2]*$data_dtl[JUMLAH]*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN2]/100),1);
		$sewa_sppb = desimal(($data_dtl[MASA_SPPB]*$data_dtl[JUMLAH]*$data_dtl[TRF_PENUMPUKAN]*$data_dtl[PROSEN_SPPB]/100),1);

	
		$detail .= '
					<tr>
							<td align="center" width="5%">'.$data_dtl[NO].'.</td>
							<td width="25%">'.$data_dtl[NM_BRG].'</td>
							<td align="center" width="15%">'.$data_dtl[LIFT_ON].' BOX</td>
							<td align="center" width="5%">'.$data_dtl[LIFT_OFF].' BOX</td>
							<td align="center" width="12%">x Rp.</td>
							<td align="right">'.desimal($data_dtl[TRF_LOLO],1).' = Rp.</td>
							<td align="right" colspan=2 width="25.4%">'.desimal($data_dtl[SUB_TOTAL],1).'</td>
						</tr>';
		
		$i++;
	}

	//if($i>0) {
		$pdf->SetFont('helvetica', '', 8);
		$tbl = <<<EOD
		<table cellpadding="1">
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

while($i<3) {	// apabila jumlah barang kurang dari 3 pada page terakhir, ditambahkan space
	$space .= "<tr><td> <br> <br> <br> <br> </td><tr>";
	$i++;
}

$tgh_awal = desimal($data[TGH_AWAL],1);
$lain = desimal($data[LAINNYA],1);
$b_form = desimal($data[B_FORM],1);
$tagihan = desimal($data[TAGIHAN],1);
$ppn = desimal($data[PPN],1);
$total = desimal($data[TOTAL],1);
//$val="Rp";
$pdf->SetFont('helvetica', '', 8);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td align="right" width="5%">1.</td>
		<td width="70%">JUMLAH SEBENARNYA</td>
		<td align="right" width="25%">$tgh_awal</td>
	</tr>
	<tr>
		<td align="right">2.</td>
		<td>PEMBULATAN ATAS PELY.JASA MINIMAL</td>
		<td align="right">$lain</td>
	</tr>
	<tr>
		<td align="right">3.</td>
		<td>BIAYA FORMULIR</td>
		<td align="right">$b_form</td>
	</tr>
	<tr>
		<td align="right">4.</td>
		<td>JUMLAH PENDAPATAN / DASAR PENGENAAN PAJAK</td>
		<td align="right">$tagihan</td>
	</tr>
	<tr>
		<td align="right">5.</td>
		<td>PPN 10%</td>
		<td align="right">$ppn</td>
	</tr>
	<tr>
		<td align="right">6.</td>
		<td>PIUTANG</td>
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
$pdf->Write(0, $data[TGL_INVOICE], '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Write(0, $data[ID_NOTA], '', 0, 'R', true, 0, false, false, 0);

if (strpos($data[TOTAL],"."))	{	//cek apakah ada koma
	$bulat = substr($data[TOTAL],0,strpos($data[TOTAL],"."));
	$terbilang1 = toTerbilang($bulat);
	$pecahan = substr($data[TOTAL],strpos($data[TOTAL],".")+1,strlen($data[TOTAL])-(strpos($data[TOTAL],".")+1));
	$terbilang2 = toTerbilang($pecahan);
	$terbilang = $terbilang1." koma ".$terbilang2;
}
else
	$terbilang = toTerbilang($data[TOTAL]);
$terbilang .= " rupiah";

$pdf->SetFont('helvetica', '', 9);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="5%"></td>
		<td width="18%">NAMA PERUSAHAAN</td>
		<td width="3%">:</td>
		<td width="40%"><b>$data[NM_CUST]</b></td>
		<td width="34%"></td>
	</tr>
	<tr>
		<td></td>
		<td>NO ACCOUNT</td>
		<td>:</td>
		<td><b>$data[ID_CUST]</b></td>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>UNTUK KAPAL</td>
		<td>:</td>
		<td><b>$data[VESSEL] / $data[VOYAGE] / $data[ETA]</b></td>
		<td align="right">$data[TGL_INVOICE]</td>
	</tr>
	<tr>
		<td></td>
		<td>JUMLAH UANG</td>
		<td>:</td>
		<td><b>$total</b></td>
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