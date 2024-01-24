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
		$this->SetFont('helvetica', 'I', 5);
		// Page number
		//$this->Cell(0, 15, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A7', true, 'UTF-8', false);
$width = 175;  
$height = 266; 
//$pdf->addFormat("custom", $width, $height); 

// set header and footer fonts
//$font = $this->addTTFfont("BLUEHIGD.TTF");

$pdf->setFooterFont(Array('helvetica', '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(1, 3, 0);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

$db = getDB();
$tid = $_GET['tid'];
$query="SELECT TID, TRUCK_NUMBER, COMPANY_NAME, COMPANY_ADDRESS, COMPANY_PHONE, TO_CHAR(DATE_ENTRY,'dd-mm-yy') DATE_ENTRY FROM TID_REPO where TID=".$tid;
$hasil=$db->query($query);
$hasil_=$hasil->fetchRow();

$jum_page = 1;


for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	//$pdf->AddPage();
	$resolution= array(74, 75);
$pdf->AddPage('P', $resolution);
	// set font
	$pdf->SetFont('helvetica', '', 6);
	
	$tbl = <<<EOD
	<p><b>TID Card Center</b><br/>
	Jalan Pak Kasih No. 11, Kantor IPC<br/>
	Pontianak Kalimantan Barat<br/>
	Telepon. 0561-732181</p>
			<table border='0'>
			<tr>                    
                 <td></td>
				 <td></td>
			</tr>    
			<tr>                    
                 <td COLSPAN="2" align="left"><font size="8"><b>TID : $tid</b></font></td>
                 <td width="100"></td>
			</tr>
			<tr>                    
                 <td COLSPAN="2" align="left"><font size="8"><b>No. Polisi : $hasil_[TRUCK_NUMBER] </b></font></td>
                 <td width="100"></td>
			</tr>
			<tr>                    
                 <td></td>
				 <td></td>
			</tr>
			<tr>                    
                 <td COLSPAN="2" align="left"><font size="8"><b>$hasil_[COMPANY_NAME]</b></font></td>
                 <td width="100"></td>
			</tr>
			
			<tr>                    
                 <td COLSPAN="2" align="left"><font size="8"><b>$hasil_[COMPANY_ADDRESS]</b></font></td>
                 <td width="100"></td>
			</tr>
			<tr>                    
                 <td COLSPAN="2" align="left"><font size="8"><b>Telp./HP : $hasil_[COMPANY_PHONE]</b></font></td>
                 <td width="100"></td>
			</tr>
			<tr>                    
                 <td></td>
				 <td></td>
			</tr>
			<tr>                    
                 <td COLSPAN="2" align="left"><font size="8"><b>Registration Date : $hasil_[DATE_ENTRY]</b></font></td>
                 <td width="100"></td>
			</tr>
		<p>Tanda terima ini harus dibawa saat pengambilan TID dan stiker</p>
        </table>
                
EOD;
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 4,
    'stretchtext' => 4
);

$pdf->write1DBarcode($tid, 'C128', 0, 0, '', 18, 0.4, $style, 'N');


	$pdf->Image('images/ipc2.jpg', 50, 7, 20, 10, '', '', '', true, 72);
	
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


$pdf->SetFont('courier', '', 6);
$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 6);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>