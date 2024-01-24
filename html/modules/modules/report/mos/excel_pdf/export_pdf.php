<?php
//require "login_check.php";

require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

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

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(5, 16, 8);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
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
$date=date('d M Y');

// jumlah detail barangnya
//$query_jum="SELECT COUNT(1) JUM_DETAIL FROM TB_NOTA_RECEIVING_D A WHERE A.ID_REQ='$no_req'";
/*$data_jum = 10;
$jum_data_page = 47;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum/$jum_data_page);	//hasil bagi pembulatan ke atas*/
$no_ukk=$_GET["no_ukk"];
$kg=$_GET["keg"];
//$no_ukk='2';
$query_h 	= "select NM_KAPAL, NM_PEMILIK, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, VOYAGE_IN, VOYAGE_OUT, JML_SHIFT, JML_HATCH FROM rbm_h where NO_UKK='$no_ukk'";
$result_h   = $db->query($query_h);
$header      = $result_h->fetchrow();
$ves_id = $header['NM_KAPAL'];
$owner = $header['NM_PEMILIK'];
$rta = $header['TGL_JAM_TIBA'];
$rtd = $header['TGL_JAM_BERANGKAT'];
$voy_in = $header['VOYAGE_IN'];
$voy_out = $header['VOYAGE_OUT'];

$query_hatch 	= "SELECT SUM(JUMLAH) JUMLAH
					FROM RBM_HM
					WHERE  NO_UKK ='$no_ukk'";
$result_hatch   = $db->query($query_hatch);
$hatch      = $result_hatch->fetchrow();
$jm_ht = $hatch['JUMLAH'];

$query_shift 	= "SELECT SUM(JUMLAH) JUMLAH
					FROM RBM_SHIFT_ACCU
					WHERE  NO_UKK ='$no_ukk'";
$result_shift   = $db->query($query_shift);
$shift      = $result_shift->fetchrow();
$jm_sh = $shift['JUMLAH'];

//$jm_sh = $header['JML_SHIFT'];
//$jm_ht = $header['JML_HATCH'];

if(($data_jum%$jum_data_page)>10 || ($data_jum%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
/*for($pg=1; $pg<=$jum_page; $pg++) {*/
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 6);
	
	$tbl = <<<EOD
			<table border="0">
					<tr><td colspan="2">PT. PELABUHAN INDONESIA II (PERSERO)</td><td></td><td></td></tr>
					<tr><td>TANJUNG PRIOK</td><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td><td>Tanggal : $date</td></tr>
					<tr><td>Print by : DATIN</td><td></td><td></td><td>Halaman : 1/1</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="16" align="center"><font size="12pt"><b><i>LIST CONTAINER BY STATUS</i></b></font></td></tr>
					<tr><td colspan="16" align="center"></td></tr>
					</table>
						
										<table border="1">
		  <tr>
			<td align="center" width="20">No.</td>
			<td align="center" width="80">Container No.</td>
			<td align="center" colspan="4" width="100">Sz - Typ - Sts - Hz</td>
			<td align="center" width="40">Code Status</td>
			<td width="70" align="center">Gate In - Out</td>
			<td width="70" align="center">Yard Allocation</td>
			<td width="70" align="center">Date Placement</td>
			<td colspan="4" width="100" align="center">Yard Placement</td>
			<td width="70" align="center">Bay Plan</td>
			<td width="70" align="center">Date Load - Disch. Confirm</td>
		  </tr>
EOD;
$query_db	= "SELECT NO_CONTAINER, SIZE_, TYPE_, STATUS,HZ, KODE_STATUS, TO_CHAR(TGL_GATE_IN,'DD-MM-YYYY HH24:MI') 
				TGL_GATE_IN,ID_JOBSLIP,LOKASI,TO_CHAR(TGL_PLACEMENT,'DD-MM-YYYY HH24:MI') TGL_PLACEMENT, BLOCK,
				SLOT, ROW_,TIER, BAY, TO_CHAR(DATE_CONFIRM,'DD-MM-YYYY HH24:MI') DATE_CONFIRM FROM  ISWS_LIST_CONTAINER
				WHERE NO_UKK='$no_ukk' and E_I='$kg' and kode_status <> 'NA' and rownum<=300 ORDER BY TGL_PLACEMENT DESC "; 
//$query_db 	= "select EI, SIZE_, TYPE_, STATUS, HZ,CASE WHEN HEIGHT='OOG' THEN '/OOG' ELSE '' END HEIGHT, BOX from rbm_d where NO_UKK='$no_ukk' AND EI='I' order by SIZE_,TYPE_,STATUS";
$result_db   = $db->query($query_db);

$ddb      = $result_db->getAll();
$i=1;
foreach($ddb as $rowb)
{	
	$tblx .='
			<tr>
			<td>'.$i.'</td>
					<td align="center" >'.$rowb['NO_CONTAINER'].'</td>
					<td align="center" >'.$rowb['SIZE_'].'</td>
					<td align="center" >'.$rowb['TYPE_'].'</td>
					<td align="center" > '.$rowb['STATUS'].'</td>
					<td align="center" >'.$rowb['HZ'].'</td>
					<td align="center" >'.$rowb['KODE_STATUS'].'</td>
					<td align="center" >'.$rowb['TGL_GATE_IN'].'&nbsp;'.$row['ID_JOBSLIP'].'</td>
					<td align="center" >'.$rowb['LOKASI'].'</td>
					<td align="center" >'.$rowb['TGL_PLACEMENT'].'</td>
					<td align="center" >'.$rowb['BLOCK'].'</td>
					<td align="center" >'.$rowb['SLOT'].'</td>
					<td align="center" >'.$rowb['ROW_'].'</td>
					<td align="center" >'.$rowb['TIER'].'</td>
					<td align="center" >'.$rowb['BAY'].'</td>
					<td align="center" >'.$rowb['DATE_CONFIRM'].'</td>
		  </tr>
	';
			$i++;
}


	$tbl .= <<<EOD
						$tblx
							</table>
EOD;

	
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$limit1 = ($jum_data_page * ($pg-1)) + 1;	//limit bawah
	$limit2 = $jum_data_page * $pg;				//limit atas
	
	/*
	if($pg < $jum_page) {	//buat garis silang bagian bawah nota
		$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 0, 0));
		$pdf->Line(10, 200, 205, 280, $style);		
		$pdf->Line(10, 280, 205, 200, $style);		
	}
/*}

while($i<10) {	// apabila jumlah barang kurang dari 12 pada page terakhir, ditambahkan space
	$space .= "<tr><td></td><tr>";
	$i++;
}
*/
$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>