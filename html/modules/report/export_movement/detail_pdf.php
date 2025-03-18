<?php
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

$tgl = date('d F Y H:i');
$voyage_in = $_GET['voyage_in'];
$voyage_out = $_GET['voyage_out'];
$vessel = $_GET['vessel'];
$kg = $_GET['keg'];

if(($data_jum%$jum_data_page)>10 || ($data_jum%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
/*for($pg=1; $pg<=$jum_page; $pg++) {*/
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 6);
	
	$tbl = <<<EOD
			<table border="0">
					<tr><td colspan="2">PT. PELABUHAN INDONESIA II (PERSERO)</td><td></td><td></td></tr>
					<tr><td>PONTIANAK</td><td></td><td></td><td></td></tr>
					<tr><td></td><td></td><td></td><td>Tanggal : $date</td></tr>
					<tr><td>Print by : DATIN</td><td></td><td></td><td>Halaman : 1/1</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="16" align="center"><font size="12pt"><b><i>List Kartu Muat PerKapal ($vessel $voyage_in - $voyage_out)</i></b></font></td></tr>
					<tr><td colspan="16" align="center"></td></tr>
					</table>
						
		  <table border="1">										
		  <tr>
			<th align="center" width="20" height="20">No.</th>
			<th align="center" width="60">No Container</th>
			<th align="center" width="30">Type</th>
			<th align="center" width="20">Size</th>
			<th width="40" align="center">Stat</th>
			<th width="40" align="center">Gross</th>
			<th width="40" align="center">POD</th>
			<th width="20" align="center">IS</th>
			<th width="150" align="center">EMKL</th>
			<th width="100" align="center">Commodity</th>
			<th width="70" align="center">GATE IN</th>
			<th width="20" align="center">EX</th>
			<th width="70" align="center">NO.NPE</th>			
		  </tr>
		  
EOD;
$query_db	= "SELECT 
                a.no_container,
                b.id_req,               
                a.type_cont, 
                a. size_cont, 
                a.status, 
                a.weight, 
                a.pod,
                '56' as sebagai,  
                c.KODE_PBM,
                d.nm_commodity,          
                a.activity, 
                a.cont_location,                 
                a.gate_in_date,
               c.NPE,                
                a.E_I,
                b.kd_comodity,
				'RCV' FITRI   				
           FROM 
                OPUS_REPO.m_cyc_container a left join req_receiving_d b on (a.no_container=b.no_container and a.vessel=b.vessel and trim(a.voyage_in) = trim(b.voyage_in) and trim(a.voyage_out) = trim(b.voyage_out))
                left join req_receiving_h c on (b.id_req = c.id_req)
                left join master_commodity d on (b.kd_comodity = d.kd_commodity)
           WHERE 
                a.vessel = '$vessel' 
                and a.voyage_in = '$voyage_in'
                and a.voyage_out = '$voyage_out'
                and a.E_I = 'E'
                and a.cont_location = 'Vessel'"; 

$result_db   = $db->query($query_db);

//echo $query_db;
//die();

$ddb      = $result_db->getAll();
$i=1;
foreach($ddb as $rowb)
{	
	$tblx .='
			<tr>
			<td>'.$i.'</td>
					<td align="center" >'.$rowb['NO_CONTAINER'].'</td>
					<td align="center" >'.$rowb['TYPE_CONT'].'</td>					
					<td align="center" >'.$rowb['SIZE_CONT'].'</td>
					<td align="center" >'.$rowb['STATUS'].'</td>
					<td align="center" >'.$rowb['WEIGHT'].'</td>
					<td align="center" >'.$rowb['POD'].'</td>
					<td align="center" >'.$rowb['SEBAGAI'].'</td>
					<td align="center" >'.$rowb['KODE_PBM'].'</td>
					<td align="center" >'.$rowb['NM_COMMODITY'].'</td>
					<td align="center" >'.$rowb['GATE_IN_DATE'].'</td>
					<td align="center" >'.$rowb['FITRI'].'</td>
					<td align="center" >'.$rowb['NPE'].'</td>										
		  </tr>
	';
			$i++;
}


	$tbl .= <<<EOD
						$tblx
							</table>
							
<p></p><p></p>
EOD;

$tbl.='<table border="1">
		<tr>
		<th width="25">POD</th>
		<th width="25">TYPE CONT</th>
		<th width="25">SIZE CONT</th>
		<th width="30">STATUS</th>
		<th width="30">JUMLAH</th>		
		</tr>
	  ';
	  
$query_detail = "SELECT 
						count(no_container) as jumlah,
						type_cont,
						size_cont,
						status,
						pod 
				 FROM(
						select 
								no_container, 
								type_cont, 
								size_cont, 
								status,pod                      
						FROM 
								opus_repo.m_cyc_container a
						WHERE 
						a.vessel = 'LUMOSO BAHAGIA' 
						and a.voyage_in = '215'
						and a.voyage_out = '215'
						and a.E_I = 'E'
						and a.cont_location = 'Vessel')
				group by 
						type_cont, size_cont, status,pod";
						
$result_detail   = $db->query($query_detail);
$ddb_detail      = $result_detail->getAll();
$i=1;
foreach($ddb_detail as $rowd){	

$tbl .= '
		<tr>
		<td>'.$rowd['POD'].'</td>
		<td>'.$rowd['TYPE_CONT'].'</td>
		<td>'.$rowd['SIZE_CONT'].'</td>
		<td>'.$rowd['STATUS'].'</td>
		<td>'.$rowd['JUMLAH'].'</td>
		</tr>';		
		$i++;
}

$tbl.='</table>';

$tbl.='<p></p><p></p><table border="1">
<tr>		
		<th width="50">SIZE CONT</th>
		<th width="40">STATUS</th>
		<th width="40">JUMLAH</th>		
		</tr>
';

$query_detail2 = "SELECT 
						count(no_container) as jumlah,						
						size_cont,
						status						
				 FROM(
						select 
								no_container,								 
								size_cont, 
								status                      
						FROM 
								opus_repo.m_cyc_container a
						WHERE 
						a.vessel = 'LUMOSO BAHAGIA' 
						and a.voyage_in = '215'
						and a.voyage_out = '215'
						and a.E_I = 'E'
						and a.cont_location = 'Vessel')
				group by 
						size_cont, status";
						
$result_detail2   = $db->query($query_detail2);
$ddb_detail2      = $result_detail2->getAll();
$i=1;
foreach($ddb_detail as $rowd2){	

$tbl .= '
		<tr>		
		<td>'.$rowd2['SIZE_CONT'].'</td>
		<td>'.$rowd2['STATUS'].'</td>
		<td>'.$rowd2['JUMLAH'].'</td>
		</tr>';		
		$i++;
}

$tbl.='</table>';

	
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