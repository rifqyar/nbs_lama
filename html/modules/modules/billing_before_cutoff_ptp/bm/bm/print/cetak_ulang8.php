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
$id_req = $_GET["id"];
$remark = $_GET["remark"]; // shift, non shift

$row12 = $db->query("SELECT A.ID_NOTA, 
                           A.ID_REQ,
                           B.NAMA,
						   B.NPWP,
						   B.ALAMAT,
                           A.VESSEL,
                           A.VOYAGE,
						   A.REMARK,
						   A.TERMINAL,
						   TO_CHAR(A.TGL_ENTRY,'DD-MM-YYYY HH24:MI:SS') AS TGL_ENTRY,
						   TO_NUMBER(A.ADM, '99999999999999999999999999999999999999999999999999999.99') AS ADM,
						   TO_NUMBER(A.PPN, '99999999999999999999999999999999999999999999999999999.99') AS PPN,
						   TO_NUMBER(A.TOTAL, '99999999999999999999999999999999999999999999999999999.99') AS TOTAL,
						   A.USER_ENTRY
						   FROM GLC_NOTA A, MASTER_PBM B
                           WHERE A.KODE_PBM = B.KODE_PBM
						   AND A.ID_REQ = '$id_req'"
						   )->fetchRow();

$pbm = $row12['NAMA'];
$npwp = $row12['NPWP'];
$alamat = $row12['ALAMAT'];						   
$kode_pbm = $row12['KODE_PBM'];
$terminal = $row12['TERMINAL'];
$vessel = $row12['VESSEL'];
$voyage = $row12['VOYAGE'];
$tgl_entry = $row12['TGL_ENTRY'];
$remark = $row12['REMARK'];
$status_pbm = $row12['STATUS_PBM'];
$adm = $row12['ADM'];
$ppn = $row12['PPN'];
$total = $row12['TOTAL'];
$byr = $adm+$ppn+$total;
$administrasi = number_format($adm,2);
$ppns = number_format($ppn,2);
$totals = number_format($total,2);
$bayar = number_format($byr,2);
$nota = $row12['ID_NOTA'];
$tglproses = date("d-m-Y H:i:s");
$nm_user = $row12['USER_ENTRY'];

$bilangan = toTerbilang($byr);


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM GLC_DETAIL_NOTA_SHIFT A WHERE A.NO_NOTA='$id_req'";
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 10;	//jumlah data dibatasi per page 10 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	
	$pdf->SetFont('courier', '', 9);
	$tbl = <<<EOD
	<table>
	<tr>
		<td colspan="32" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
	</tr>
	<tr>
		<td colspan="32" align="left"><b>CABANG TANJUNG PRIOK</b></td>
	</tr>
	<tr>
		<td colspan="26"></td>
		<td colspan="5" align="right"><b>$nota</b></td>
	</tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="19"></td>
		<td colspan="4" align="left">No. Faktur</td>
		<td colspan="1" align="right">:</td>
		<td colspan="8" align="left"> -</td>
	</tr>
	<tr>
		<td colspan="19"></td>
		<td colspan="4" align="left">No. Doc</td>
		<td colspan="1" align="right">:</td>
		<td colspan="8" align="left"> $id_req</td>
	</tr>
	
	<tr>
		<td COLSPAN="19"></td>
		<td COLSPAN="4" align="left">Tgl.Proses</td>
		<td colspan="1" align="right">:</td>
		<td COLSPAN="8" align="left"> $tglproses</td>
	</tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td COLSPAN="32" align="right"><font size="12"><b>PERHITUNGAN PELAYANAN JASA SEWA ALAT</b></font></td>
	</tr> 
	<tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">$pbm</font></td>
		<td></td>
	</tr> 
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">$npwp</font></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">$alamat</font></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="6"></td>
		<td colspan="11" align="left"><font size="9">$vessel/$voyage</font></td>
		<td></td>
	</tr>	   
    <tr><td colspan="32">&nbsp;</td></tr>
    <tr><td colspan="32">&nbsp;</td></tr>
	<tr>
		<td></td>
		<td colspan="4" ><b>ALAT</b></td>
		<td colspan="5" align="center"><b>START</b></td>
		<td colspan="5" align="center"><b>END</b></td>
	    <td colspan="4" align="center"><b>SHIFT</b></td>
		<td colspan="5" align="right"><b>TARIF</b></td>
		<td colspan="2" align="left"><b>VAL</b></td>
		<td colspan="5" align="right"><b>JUMLAH</b></td>
		<td colspan="2"></td>
	</tr>
	<tr><td colspan="32">-------------------------------------------------------------------------------------------------------</td></tr>
	</table>
EOD;
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$limit1 = ($jum_data_page * ($pg-1)) + 1;	//limit bawah
	$limit2 = $jum_data_page * $pg;				//limit atas
}


//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>