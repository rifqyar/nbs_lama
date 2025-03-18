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
$data_jum = 10;
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum/$jum_data_page);	//hasil bagi pembulatan ke atas
$no_ukk=$_GET["id_vessel"];
//$no_ukk='2';
$query_h 	= "select NM_KAPAL, NM_PEMILIK, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, VOYAGE_IN, VOYAGE_OUT FROM rbm_h where NO_UKK='$no_ukk'";
$result_h   = $db->query($query_h);
$header      = $result_h->fetchrow();
$ves_id = $header['NM_KAPAL'];
$owner = $header['NM_PEMILIK'];
$rta = $header['TGL_JAM_TIBA'];
$rtd = $header['TGL_JAM_BERANGKAT'];
$voy_in = $header['VOYAGE_IN'];
$voy_out = $header['VOYAGE_OUT'];

if(($data_jum%$jum_data_page)>10 || ($data_jum%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 8);
	
	$tbl = <<<EOD
			<style>
				.basic  {
					width: 570px;
					font-family: verdana;
					border-top: none;
					border-right: 1px solid #000000;
					border-left: 1px solid #000000;
					border-bottom: none;
				}
			</style>
			<table border="0">
					<tr><td colspan="2">PT. PELABUHAN INDONESIA II (PERSERO)</td><td></td><td>No.Form : FM.02/03/01/24</td></tr>
					<tr><td>TANJUNG PRIOK</td><td></td><td></td><td>Refisi : 03</td></tr>
					<tr><td></td><td></td><td></td><td>Tanggal : $date</td></tr>
					<tr><td>Print by : DATIN</td><td></td><td></td><td>Halaman : 1/1</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4" align="center"><font size="12pt"><b><i>REALISASI BONGKAR MUAT VESSEL</i></b></font></td></tr>
					<tr><td colspan="4"><hr></td></tr>
					<tr>
						<td colspan="4">
							<table border="0">
								<tr>
									<td width="80">VESSEL ID</td>
									<td>:</td>
									<td colspan="10">$ves_id</td>
								</tr>
								<tr>
									<td>VOYAGE</td>
									<td>:</td>
									<td colspan="10">$voy_in - $voy_out</td>
								</tr>
								<tr>
									<td>ARRIVAL</td>
									<td>:</td>
									<td>$rta</td>
									<td></td>
									<td>BERTHING</td>
									<td>:</td>
									<td>$rta</td>
									<td></td>
									<td width="80">DEPARTURE</td>
									<td>:</td>
									<td>$rtd</td>
								</tr>
								<tr>
									<td>START WORK</td>
									<td>:</td>
									<td>$rta</td>
									<td></td>
									<td>END WORK</td>
									<td>:</td>
									<td>$rtd</td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td colspan="4"></td></tr>
					<tr>
						<td colspan="4">
										<table border="1" align="center">
										<tr>
											<td width="120">Operation</td>
											<td width="100">Activity</td>
											<td width="80">Shf.By</td> 
											<td width="90">Crane</td>
											<td width="50">Size</td> 
											<td width="50">Type</td> 
											<td width="50">Status</td>
											<td width="20">Ol</td> 
											<td width="50">HAZARD</td>
											<td width="50">IMO</td>
											<td width="40">Boxes</td>
										</tr>  
EOD;
$tb=0;
$query_db 	= "select EI, SIZE_, TYPE_, STATUS, HZ,CASE WHEN HEIGHT='OOG' THEN '/OOG' ELSE '' END HEIGHT, BOX from rbm_d where NO_UKK='$no_ukk' AND EI='I' order by SIZE_,TYPE_,STATUS";
$result_db   = $db->query($query_db);

$ddb      = $result_db->getAll();
foreach($ddb as $rowb)
{
$tbl_a .='<tr>
											<td width="120" class="basic">&nbsp;</td>
											<td width="100" class="basic">DISCH</td>
											<td width="80" class="basic">&nbsp;</td> 
											<td width="90" class="basic">&nbsp;</td>
											<td width="50" class="basic">'.$rowb['SIZE_'].'</td> 
											<td width="50" class="basic">'.$rowb['TYPE_'].''.$rowb['HEIGHT'].'</td> 
											<td width="50" class="basic">'.$rowb['STATUS'].'</td>
											<td width="20" class="basic">O</td> 
											<td width="50" class="basic">'.$rowb['HZ'].'</td>
											<td width="50" class="basic">&nbsp;</td>
											<td width="40" class="basic">'.$rowb['BOX'].'</td>
										</tr>  ';
										$tb=$tb+$rowb['BOX'];
}
$tbl .= <<<EOD
										<tr>
											<td>(I) - DISCHARGING</td>
											<td colspan="9">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										$tbl_a
										<tr>
											<td>&nbsp;</td>
											<td colspan="9"><b>TOTAL DISCHARGE</b></td>
											<td><B>$tb</B></td>
										</tr>
EOD;
$tm=0;
$query_dm 	= "select EI, SIZE_, TYPE_, STATUS, HZ,CASE WHEN HEIGHT='OOG' THEN '/OOG' ELSE '' END HEIGHT, BOX from rbm_d where NO_UKK='$no_ukk' AND EI='E' order by SIZE_,TYPE_,STATUS";
$result_dm   = $db->query($query_dm);

$ddm      = $result_dm->getAll();
foreach($ddm as $rowm)
{
$tbl_b .='<tr>
											<td width="120" class="basic">&nbsp;</td>
											<td width="100" class="basic">LOAD</td>
											<td width="80" class="basic">&nbsp;</td> 
											<td width="90" class="basic">&nbsp;</td>
											<td width="50" class="basic">'.$rowm['SIZE_'].'</td> 
											<td width="50" class="basic">'.$rowm['TYPE_'].''.$rowm['HEIGHT'].'</td> 
											<td width="50" class="basic">'.$rowm['STATUS'].'</td>
											<td width="20" class="basic">O</td> 
											<td width="50" class="basic">'.$rowm['HZ'].'</td>
											<td width="50" class="basic">&nbsp;</td>
											<td width="40" class="basic">'.$rowm['BOX'].'</td>
										</tr>  ';
										$tm=$tm+$rowm['BOX'];
}

$tbl .= <<<EOD
										<tr>
											<td>(E) - LOADING</td>
											<td colspan="9">&nbsp;</td>
											<td>&nbsp;</td>
										</tr>
										$tbl_b		
										<tr>
											<td>&nbsp;</td>
											<td colspan="9"><b>TOTAL LOADING</b></td>
											<td><B>$tm</B></td>
										</tr>
EOD;

	$tbl .= <<<EOD
			
							</table>
						</td>
					</tr>
EOD;
$ttl=$tm+$tb;
	$tbl .= <<<EOD
					<tr><td colspan="4"></td></tr>
					<tr><td  width="200">TOTAL (DISCH+LOAD)</td><td> : <b>$ttl</b></td></tr>
					<tr><td width="200">TOTAL (OPEN + CLOSE PALKA)</td><td> : </td></tr>
					<tr><td  width="200">TOTAL (SHIFTING)</td><td> : </td></tr>
					<tr><td></td><td></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">CABANG PELABUHAN TANJUNG PRIOK</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">An.DEPUTI TERMINAL 3</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">MANAGER OPERASI</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">TENGKU MURSALIN RAHIM</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">NIPP : 271085951</td></tr>
					
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

		  $query2		= "SELECT KOREKSI, NM_KAPAL, VOYAGE_IN, VOYAGE_OUT FROM RBM_H WHERE NO_UKK = '$no_ukk'";
		 $result_q2		= $db->query($query2);
		 $data2			= $result_q2->fetchROw();
		
		 $koreksi 		= $data2['KOREKSI'];
		 $nm_kapal 		= $data2['NM_KAPAL'];
		 
		 $new_kapal2	= str_replace('.','_',$nm_kapal);
		 $new_kapal		= str_replace(' ','_',$new_kapal2);
		 
		 $voy_in 		= $data2['VOYAGE_IN'];
		 $voy_out 		= $data2['VOYAGE_OUT'];
		 
		 $query3		= "SELECT NVL(MAX(COUNTER),0)+1 JML FROM LOG_RBM WHERE NO_UKK = '$no_ukk' AND KETERANGAN = 'RBM_ICT'";
		 $result_q3		= $db->query($query3);
		 $data3			= $result_q3->fetchRow();
		 
		 $jml	 		= $data3['JML'];
		 
		 $date = date('dmy');
		 
		// $nama_file = $no_ukk.'_'.$new_kapal.'_'.$voy_in.'_'.$voy_out.'_'.$date.'_'.$jml;
		
		 $nama_file = $no_ukk;

		 $pdf->Output('/report/'.$nama_file.'.pdf', 'F');

?>