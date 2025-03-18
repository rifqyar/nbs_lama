<?php
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

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// add a page
$pdf->AddPage();

// set font


$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();

$db = getDB();
$p1 = $_GET['p1'];
$query="SELECT A.*, B.*, OG_GETNAMA.GET_PERDAGANGAN(B.JN_DAGANG) PERDAGANGAN ,TO_CHAR(A.TGH_AWAL,'9,999,999,999,999') TAGIHAN,TO_CHAR(A.PPN,'9,999,999,999,999') TPPN,TO_CHAR(A.TOTAL,'9,999,999,999,999') TOT FROM OG_NOTA_LOLOH A, OG_BPRPH B WHERE A.ID_NOTA='$p1' AND A.ID_BPRP=B.ID_BPRP";
$data = $db->query($query)->fetchRow();

$query_pbm="SELECT NAMA_PELANGGAN, ALAMAT_1, ALAMAT_2, NO_NPWP, SELEKSI FROM OG_PELANGGAN WHERE KODE_PELANGGAN='".$data[ID_CUST]."'";
$data_pbm = $db->query($query_pbm)->fetchRow();
if($data[JN_DAGANG]=="1")
{
	$ket="MUAT";
}
else if ($data[JN_DAGANG]=="2")
{
	$ket="BONGKAR";
}

$query_dtl="SELECT A.*, OG_GETNAMA.GET_NAMABRG(A.JNS_BRG) NM_BRG,TO_CHAR(A.TRF_LOLO,'9,9999,999,999,999') TARIF,TO_CHAR(A.SUB_TOTAL,'9,9999,999,999,999') TOTAL_TARIF FROM OG_NOTA_LOLOD A WHERE A.ID_NOTA='$p1' ORDER BY NO";
$res = $db->query($query_dtl);
while($data_dtl = $res->fetchRow()) {
	$detail .= '<tr>
					<td style="border-left-width:2%;"></td>
					<td colspan="3">'.$data_dtl[NM_BRG].'</td>
					<td align="center">'.$data_dtl[LIFT_ON].'</td>
					<td align="center">'.$data_dtl[LIFT_OFF].'</td>
					<td align="center" width="20">&nbsp;</td>
					<td align="right" width="65">'.$data_dtl[TARIF].'</td>
					<td align="right">'.$data_dtl[TOTAL_TARIF].'</td>
					<td align="right" style="border-right-width:2%;">&nbsp;</td>
				</tr>';
	$i++;
}
$pdf->SetFont('helvetica', '', 8);
$tbl = <<<EOD
<table cellpadding="1"	>
	<tr>
		<td colspan="7" style="border-left-width:2%; border-top-width:2%;">&nbsp;</td>
		<td align="right" width="80" style="border-top-width:2%;">NO.PRANOTA</td>
		<td colspan="2" align="left" width="80" style="border-top-width:2%;border-right-width:2%;">: $data[ID_NOTA]</td>
	</tr>
	<tr>
		<td colspan="7" style="border-left-width:2%;">&nbsp;</td>
		<td align="right">NO.BPRP</td>
		<td colspan="2" align="left" style="border-right-width:2%;">: $data[ID_BPRP]</td>
	</tr>
	<tr >
		<td colspan="10" align="center" height="34" valign="middle" style="border-top-width:2% ;border-left-width:2%;border-right-width:2%;border-bottom-width:2%;">&nbsp;<br><font size="12px"><B>Realisasi Lift On / Lift Off</B></font></td>
	</tr>
	<tr>
		<td colspan="10" align="center" style="border-left-width:2%; border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" width="50" style="border-left-width:2%;">&nbsp;</td>
		<td width="150">PERIODE KEGIATAN</td>
		<td width="15.5">&nbsp;</td>
		<td colspan="6" style="border-right-width:2%;">: $data[ETA] s/d $data[ETD]</td>
	</tr>
	<tr>
		<td colspan="2" style="border-left-width:2%;">&nbsp;</td>
		<td>PEMILIK/PEMAKAI JASA</td>
		<td>&nbsp;</td>
		<td colspan="6" style="border-right-width:2%;">: $data[NM_CUST]</td>
	</tr>
	
	<tr>
		<td colspan="2" style="border-left-width:2%;">&nbsp;</td>
		<td>NAMA KAPAL</td>
		<td>&nbsp;</td>
		<td colspan="6" style="border-right-width:2%;">: $data[VESSEL] / VOYAGE</td>
	</tr>
	
	<tr>
		<td colspan="2" style="border-left-width:2%;">&nbsp;</td>
		<td>JENIS KEGIATAN</td>
		<td>&nbsp;</td>
		<td colspan="6" style="border-right-width:2%;">: $ket</td>
	</tr>
	<tr>
		<td colspan="2" style="border-left-width:2%;">&nbsp;</td>
		<td>TERMINAL</td>
		<td>&nbsp;</td>
		<td colspan="6" style="border-right-width:2%;">: $data[TERMINAL]</td>
	</tr>
	<tr>
		<td colspan="2" style="border-left-width:2%;">&nbsp;</td>
		<td>GUDANG LAPANGAN / KADE</td>
		<td>&nbsp;</td>
		<td colspan="6" style="border-right-width:2%;">: $data[GUDLAP]</td>
	</tr>
	<tr>
		<td colspan="2" style="border-left-width:2%;">&nbsp;</td>
		<td>JENIS PERDAGANGAN</td>
		<td>&nbsp;</td>
		<td colspan="6" style="border-right-width:2%;">: $data[PERDAGANGAN]</td>
	</tr>
	<tr>
		<td colspan="10" align="center" style="border-left-width:2%; border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="10" align="center" height="25" style="border-left-width:2%; border-right-width:2%;border-bottom-width:2%;border-top-width:2% ;">&nbsp;</td>
	</tr>
	<tr>
		<td width="30" style="border-left-width:2%;">&nbsp;</td>
		<td colspan="3" align="center" width="185" >JENIS BARANG</td>
		<td colspan="2" align="center" width="120" style="border-bottom-width:2%;">JUMLAH</td>
		<td colspan="2" align="center" width="85">TARIF</td>
		<td align="center" width="85">TOTAL TARIF</td>
		<td width="32" style="border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left-width:2%;">&nbsp;</td>
		<td colspan="3" align="center" style="border-bottom-width:2%;">&nbsp;</td>
		<td align="center" style="border-bottom-width:2%;">LIFT ON</td>
		<td align="center" style="border-bottom-width:2%;">LIFT OFF</td>
		<td colspan="2" style="border-bottom-width:2%;">&nbsp;</td>
		<td style="border-bottom-width:2%;">&nbsp;</td>
		<td style="border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left-width:2%;"></td>
		<td colspan="3">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center" width="20">&nbsp;</td>
		<td align="right" width="65">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right" style="border-right-width:2%;">&nbsp;</td>
	</tr>
	$detail
	
	<tr>
		<td style="border-left-width:2%;"></td>
		<td colspan="3">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center">&nbsp;</td>
		<td align="center" width="20">&nbsp;</td>
		<td align="right" width="65">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right" style="border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left-width:2%;"></td>
		<td colspan="3" style="border-top-width:2%;">&nbsp;</td>
		<td colspan="2" align="left" style="border-top-width:2%;"> JUMLAH TAGIHAN</td>
		
		<td align="center" width="20" style="border-top-width:2%;">Rp.</td>
		<td align="right" width="65" style="border-top-width:2%;">&nbsp;</td>
		<td align="right" style="border-top-width:2%;">$data[TAGIHAN]</td>
		<td align="right" style="border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left-width:2%;"></td>
		<td colspan="3">&nbsp;</td>
		<td  colspan="2" align="left">PPN 10%</td>
		
		<td align="center" width="20">Rp.</td>
		<td align="right" width="65">&nbsp;</td>
		<td align="right">$data[TPPN]</td>
		<td align="right" style="border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left-width:2%;"></td>
		<td colspan="3">&nbsp;</td>
		<td align="left"  colspan="2">TOTAL TAGIHAN</td>
	
		<td align="center" width="20">Rp.</td>
		<td align="right" width="65">&nbsp;</td>
		<td align="right">$data[TOT]</td>
		<td align="right" style="border-right-width:2%;">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left-width:2%;border-bottom-width:2%;"></td>
		<td colspan="3" style="border-bottom-width:2%;">&nbsp;</td>
		<td align="left"  colspan="2" style="border-bottom-width:2%;"></td>
	
		<td align="center" width="20" style="border-bottom-width:2%;"></td>
		<td align="right" width="65" style="border-bottom-width:2%;">&nbsp;</td>
		<td align="right" style="border-bottom-width:2%;"></td>
		<td align="right" style="border-right-width:2%;border-bottom-width:2%;">&nbsp;</td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
$noww=date('d-M-Y');
$tbl = <<<EOD
<TABLE>
<tr>
		<td colspan="2" width="50">&nbsp;</td>
		<td width="150">Tanggal Cetak</td>
		<td colspan="7">: $noww</td>
		
	</tr>
	<tr>
		<td colspan="2" width="50">&nbsp;</td>
		<td width="150">User Id</td>
		<td colspan="7">: </td>
		
	</tr>
</TABLE>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>