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
$idplp = $_GET['id_plp'];
$nm_user = $_SESSION["NAMA_PENGGUNA"];

$query = "SELECT NAME_TPL1 AS TPS_ASAL,
				 NAME_TPL2 AS TPS_TUJUAN,
				 VESSEL,
				 VOYAGE,
				 TIPE_REQ,
				 JUMLAH_BARANG
        FROM PLAN_REQ_PLP_H
		WHERE TRIM(ID_PLP) = TRIM('$idplp')";
$data = $db->query($query)->fetchRow();

$date6 = date('d M Y H:i:s');
$nm_user = $_SESSION['NAMA_PENGGUNA'];

$query_dtl = "SELECT TIPE_BARANG,
					 ID_BARANG,
					 CODE,
					 SZ,
					 TY,
					 ST
			  FROM PLAN_REQ_PLP_D 
			  WHERE TRIM(ID_PLP) = TRIM('$idplp')";
$res = $db->query($query_dtl);
	
	//print_R($res);die;
	$i=0;
	//unset($detail);
	$row2=$res->getAll();
	foreach($row2 as $rows) {
					
		$detail .= '
		<tr><td colspan="3" width="120"><font size="8">'.$rows[TIPE_BARANG].'</font></td>
                        <td width="80" align="center"><font size="8">'.$rows[ID_BARANG].'</font></td>
                        <td width="60" align="center"><font size="8">'.$rows[CODE].'</font></td>
                        <td width="40" align="center"><font size="8">'.$rows[SZ].'</font></td>    
                        <td width="40" align="center"><font size="8">'.$rows[TY].'</font></td>
                        <td width="32" align="center"><font size="8">'.$rows[ST].'</font></td>        
                        </tr>                        
						
		';
		$i++;
	}

// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM PLAN_REQ_PLP_D WHERE TRIM(ID_PLP) = TRIM('$idplp')";
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;
//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 9);
	
	$tbl = <<<EOD
			<table border='0'>
                <tr>
                    <td COLSPAN="14" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" align="right">$nm_user :: $date6</td>
                </tr>
                <tr>
                    <td COLSPAN="14"></td>                    
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="14" align="center"><font size="11"><b>SURAT PERMOHONAN PLP</b></font></td>                    
                </tr>
                <tr>
                <td></td>
                </tr>
				<tr>
                <td></td>
                </tr>
				<tr>
                    <td COLSPAN="3">No Request</td>
					<td> : </td>
                    <td COLSPAN="10" align="left">$idplp</td>
                </tr>
                <tr>
                    <td COLSPAN="3">Vessel-Voyage</td>
					<td> : </td>
                    <td COLSPAN="10" align="left">$data[VESSEL]&nbsp;[$data[VOYAGE]]</td>
                </tr>
                <tr>
                    <td COLSPAN="3">TPS Asal</td>
					<td> : </td>
                    <td COLSPAN="10" align="left">$data[TPS_ASAL]</td>
                </tr>
				<tr>
                    <td COLSPAN="3">TPS Tujuan</td>
					<td> : </td>
                    <td COLSPAN="10" align="left">$data[TPS_TUJUAN]</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                <td></td>
                </tr> 
                <tr>
                    <td colspan="4" width="150" align="left">JUMLAH $data[TIPE_REQ] : $data[JUMLAH_BARANG]</td>
                </tr>    
                <tr>
                <td></td>
                </tr>
                <tr>
                <td></td>
                </tr> 
                <tr>
                    <th colspan="3" width="120"><font size="8"><b>TIPE BARANG</b></font></th>
                    <th width="80" align="center"><font size="8"><b>ID BARANG</b></font></th>
                    <th width="60" align="center"><font size="8"><b>ISOCODE</b></font></th>
                    <th width="40" align="center"><font size="8"><b>SIZE</b></font></th>
                    <th width="40" align="center"><font size="8"><b>TYPE</b></font></th>
                    <th width="32" align="center"><font size="8"><b>STS</b></font></th>
                </tr>
                <tr>
                    <td colspoan="14">
                        <hr style="border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                </tr>
				$detail
				<tr>
                    <td colspoan="14">
                        <hr style="border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                </tr>
				
                </table>
                
EOD;
	
	$pdf->writeHTML($tbl, true, false, false, false, '');

	$limit1 = ($jum_data_page * ($pg-1)) + 1;	//limit bawah
	$limit2 = $jum_data_page * $pg;				//limit atas
	
	
	if($pg < $jum_page) {	//buat garis silang bagian bawah nota
		$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,10', 'color' => array(0, 0, 0));
		$pdf->Line(10, 200, 205, 280, $style);		
		$pdf->Line(10, 280, 205, 200, $style);		
	}
}

while($i<10) {	// apabila jumlah barang kurang dari 12 pada page terakhir, ditambahkan space
	$space .= "<tr><td></td><tr>";
	$i++;
}

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('request_plp.pdf', 'I');
?>