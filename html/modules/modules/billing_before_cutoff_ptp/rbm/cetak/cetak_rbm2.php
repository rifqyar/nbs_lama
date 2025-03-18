<?php
//require "login_check.php";
$no_ukk=$_GET["id_vessel"];
$j_s=$_GET["j_s"];
$status=$_GET["sts"];
$prn=$_GET["pranota"];

$user=$_SESSION["PENGGUNA_ID"];
if($status<>'I')
{
	$db=getDB();
	$sql_xpi = "BEGIN gd_rbm_ict.fix_rbm_tarif('$no_ukk','$user','$prn','$j_s'); END;";
	$db->query($sql_xpi);
}
require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

if ($j_s=='O')
{
	$cmt='INTERNATIONAL';
}
ELSE if ($j_s=='I')
{
	$cmt='DOMESTIC';
}

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

//$db = getDB();
$date=date('d F Y H:i');

// jumlah detail barangnya
//$query_jum="SELECT COUNT(1) JUM_DETAIL FROM TB_NOTA_RECEIVING_D A WHERE A.ID_REQ='$no_req'";
$data_jum = 10;
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum/$jum_data_page);	//hasil bagi pembulatan ke atas
$no_ukk=$_GET["id_vessel"];
//$no_ukk='2';
$query_h 	= "select NM_KAPAL, NM_PEMILIK, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, VOYAGE_IN, VOYAGE_OUT, JML_SHIFT, JML_HATCH, to_char(BERTHING,'dd-MON-rr') BERTHING, to_char(START_WORK,'dd-MON-rr') START_WORK, to_char(END_WORK,'dd-MON-rr') END_WORK
				FROM rbm_h 
				where NO_UKK='$no_ukk'";
/*$query_h 	= "select NM_KAPAL, NM_PEMILIK, 
				TO_CHAR (TGL_JAM_TIBA,'DD-MON-YY HH24:MI:SS') TGL_JAM_TIBA, 
				TO_CHAR (TGL_JAM_BERANGKAT,'DD-MON-YY HH24:MI:SS') TGL_JAM_BERANGKAT,
				VOYAGE_IN, VOYAGE_OUT, JML_HATCH FROM rbm_h where NO_UKK='$no_ukk'";
				*/
$result_h   = $db->query($query_h);
$header      = $result_h->fetchrow();
$ves_id = $header['NM_KAPAL'];
$owner = $header['NM_PEMILIK'];
$rta = $header['TGL_JAM_TIBA'];
$rtd = $header['TGL_JAM_BERANGKAT'];
$berth = $header['BERTHING'];
$str = $header['START_WORK'];
$end = $header['END_WORK'];
$voy_in = $header['VOYAGE_IN'];
$voy_out = $header['VOYAGE_OUT'];
$jml_hm = $header['JML_HATCH'];

if(($data_jum%$jum_data_page)>10 || ($data_jum%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) 
{
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 8);

	$tbl = <<<EOD
			<table border="0">
					<tr><td colspan="2">PT. PELABUHAN INDONESIA II (PERSERO)</td><td></td><td>$date</td></tr>
					<tr><td>CABANG JAMBI</td><td></td><td></td><td>&nbsp;</td></tr>
					<tr><td></td><td></td><td></td><td>&nbsp;</td></tr>
					<tr><td>Print by : DATIN</td><td></td><td></td><td>Halaman : 1/1</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4" align="center"><font size="12pt"><b><i>REALISASI BONGKAR MUAT</i></b></font></td></tr>
					<tr><td colspan="4"><hr></td></tr>
					<tr>
						<td colspan="4">
							<table border="0" >
								<tr>
									<td width="80">VESSEL ID</td>
									<td>:</td>
									<td colspan="10">$ves_id </td>
								</tr>
								<tr>
									<td>VOYAGE</td>
									<td>:</td>
									<td colspan="10">$voy_in - $voy_out</td>
								</tr>
								<tr>
									<td width="80">OWNER</td>
									<td>:</td>
									<td colspan="10">$owner</td>
								</tr>
								<tr>
									<td width="80">ARRIVAL</td>
									<td>:</td>
									<td colspan="10">$rta</td>
								</tr>
								<tr>
									<td width="80">DEPARTURE</td>
									<td>:</td>
									<td colspan="10">$rtd</td>
								</tr>
								<tr>
									<td width="80">BERTHING</td>
									<td>:</td>
									<td colspan="10">$berth</td>
								</tr>
								<tr>
									<td width="80">ROUTE</td>
									<td>:</td>
									<td colspan="10">$cmt</td>
								</tr>
								<tr>
									<td width="80">SHIPPING</td>
									<td>:</td>
									<td colspan="10">$owner</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr><td colspan="4"></td></tr>
					<tr>
						<td colspan="4">
										<table border="1" align="center" class="basic">
										<tr>
											<td width="105" rowspan="3" colspan="3"><b>SIZE - TYPE - STATUS</b></td>
											<td width="70" colspan="2" ><font size="7pt"><b>DISCHARGE</FONT></b></td> 
											<td width="70" colspan="2" rowspan="2"><font size="7pt"><b>TOTAL DISCHARGE</FONT></b></td> 
											<td width="70" colspan="2" ><font size="7pt"><b>LOADING</FONT></b></td> 
											<td width="70" colspan="2" rowspan="2"><b>TOTAL LOADING</b></td> 
											<td width="70" colspan="2" rowspan="2" style="vertical-align:middle;"><font size="7pt"><b>TRANSHIPMENT</font></b></td> 
											<td width="70" colspan="2" rowspan="2"><b>TOTAL TRANS</b></td>
											<td width="70" colspan="2" rowspan="2"><b>SHIFTING</b></td> 
											<td width="70" colspan="2" rowspan="2"><b>TOTAL SHIFTING</b></td>
											<td width="40" rowspan="2"><b>HATCH</b></td>
										</tr>  
										<TR>
											<td width="70" colspan"2"><b>HAZARD</b></td>
											<td width="70" colspan"2"><b>HAZARD</b></td>
										</TR>
										<tr>
											<td width="35" ><b>Y</b></td>
											<td width="35" ><b>T</b></td>
											<td width="35" ><b>BOX</b></td>
											<td width="35" ><b>TEUS</b></td>
											<td width="35" ><b>Y</b></td>
											<td width="35" ><b>T</b></td>
											<td width="35" ><b>BOX</b></td>
											<td width="35" ><b>TEUS</b></td>
											<td width="35" ><b>DISCH</b></td>
											<td width="35" ><b>LOAD</b></td>
											<td width="35" ><b>BOX</b></td>
											<td width="35" ><b>TEUS</b></td>
											<td width="35" ><b><font size="7pt">LANDED</FONT></b></td>
											<td width="35" ><b><font size="7pt">UN LANDED</FONT></b></td>
											<td width="35" ><b>BOX</b></td>
											<td width="35" ><b>TEUS</b></td>
											<td width="40" ><b>PALKA</b></td>
											
										</tr>
EOD;
$query_d 	= "select 
CASE WHEN rd.SIZE_ LIKE '%40%' AND rd.TYPE_='OVD' AND rd.STATUS='UC' THEN '' ELSE  TO_CHAR(rd.SIZE_) END SIZE_,
CASE WHEN rd.TYPE_='OVD' AND rd.STATUS='UC' THEN '' ELSE rd.TYPE_ END TYPE_,
rd.STATUS,CASE WHEN rd.HEIGHT='OOG' THEN '/OOG' ELSE '' END HEIGHT,
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='I' and r1.HEIGHT=rd.HEIGHT and r1.NO_UKK='$no_ukk' AND HZ='Y'),0) AS BOX_DH,
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='I' and r1.HEIGHT=rd.HEIGHT and 
r1.NO_UKK='$no_ukk' AND HZ='T'),0) AS BOX_DHT, 
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='I' and r1.NO_UKK='$no_ukk'
AND r1.HEIGHT=rd.HEIGHT
),0) AS BOX_D,
case when rd.SIZE_='20' then nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='I' and 
r1.HEIGHT=rd.HEIGHT and r1.NO_UKK='$no_ukk'),0) else 
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='I' and r1.HEIGHT=rd.HEIGHT and 
r1.NO_UKK='$no_ukk'),0)*2 end TEUS_D,
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='E' and r1.HEIGHT=rd.HEIGHT and 
r1.NO_UKK='$no_ukk' AND HZ='Y'),0) AS BOX_EH,
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='E' and r1.NO_UKK='$no_ukk' AND r1.HEIGHT=rd.HEIGHT and HZ='T'),0) AS BOX_EHT,
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='E' and r1.HEIGHT=rd.HEIGHT and r1.NO_UKK='$no_ukk'),0) AS BOX_E, 
case when rd.SIZE_='20' then nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='E' and r1.HEIGHT=rd.HEIGHT and r1.NO_UKK='$no_ukk'),0) else
nvl((select sum(r1.BOX) from rbm_d r1 where r1.SIZE_=rd.SIZE_ and r1.TYPE_=rd.TYPE_ and r1.STATUS=rd.STATUS and r1.EI='E' and r1.HEIGHT=rd.HEIGHT and r1.NO_UKK='$no_ukk'),0)*2 end TEUS_E,
nvl((select sum(rs1.JUMLAH_SHIFT) from rbm_shift rs1 where rs1.SIZE_C=rd.SIZE_ and rs1.TYPE_C=rd.TYPE_ and rs1.STATUS_C=rd.STATUS and rs1.NO_UKK='$no_ukk' and JENIS_SHIFT='Landed' and 
case when rs1.HEIGHT_C='8,6' then 'BIASA' when rs1.HEIGHT_C='9,6' then 'BIASA' ELSE 'OOG' END=rd.HEIGHT),0) LANDED, 
nvl((select sum(rs1.JUMLAH_SHIFT) from rbm_shift rs1 where rs1.SIZE_C=rd.SIZE_ and rs1.TYPE_C=rd.TYPE_ and rs1.STATUS_C=rd.STATUS and rs1.NO_UKK='$no_ukk' and JENIS_SHIFT='Unlanded' 
and 
case when rs1.HEIGHT_C='8,6' then 'BIASA' when rs1.HEIGHT_C='9,6' then 'BIASA' ELSE 'OOG' END=rd.HEIGHT),0) UNLANDED 
from rbm_d rd where rd.NO_UKK='$no_ukk' 
group by rd.SIZE_,rd.TYPE_,rd.STATUS,rd.HEIGHT ORDER BY rd.SIZE_,rd.TYPE_,rd.STATUS";
$result_d   = $db->query($query_d);
$detail      = $result_d->getAll();
$box_m=0;
$box_b=0;
$box_bh=0;
$box_bth=0;
$box_mh=0;
$box_mth=0;
$teus_m=0;
$teus_b=0;
$s_l=0;
$s_ul=0;

foreach ($detail as $rowd)
{
	$box_m=$box_m+$rowd['BOX_E'];
	$box_mh=$box_mh+$rowd['BOX_EH'];
	$box_mth=$box_mth+$rowd['BOX_EHT'];
	$box_b=$box_b+$rowd['BOX_D'];
	$box_bh=$box_bh+$rowd['BOX_DH'];
	$box_bth=$box_bth+$rowd['BOX_DHT'];
	$teus_m=$teus_m+$rowd['TEUS_E'];
	$teus_b=$teus_b+$rowd['TEUS_D'];
	$s_l=$s_l+$rowd['LANDED'];
	$s_ul=$s_ul+$rowd['UNLANDED'];
	$tamb_tbl	.='<tr>
				<td colspan="3" ALIGN="center">'.$rowd['SIZE_'].' '.$rowd['TYPE_'].''.$rowd['HEIGHT'].' '.$rowd['STATUS'].'</td>
				<td>'.$rowd['BOX_DH'].'</td>
				<td>'.$rowd['BOX_DHT'].'</td>
				<td>'.$rowd['BOX_D'].'</td>
				<td>'.$rowd['TEUS_D'].'</td>
				<td>'.$rowd['BOX_EH'].'</td>
				<td>'.$rowd['BOX_EHT'].'</td>
				<td>'.$rowd['BOX_E'].'</td>
				<td>'.$rowd['TEUS_E'].'</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
				<td>'.$rowd['LANDED'].'</td>
				<td>'.$rowd['UNLANDED'].'</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
			</tr>';
}

$tbl .= <<<EOD
									$tamb_tbl
										<tr>
											
											<td colspan="3" ALIGN="RIGHT"><b>TOTAL &nbsp;</b></td>
											<td>$box_bh</td>
											<td>$box_bth</td>
											<td>$box_b</td>
											<td>$teus_b</td>
											<td>$box_mh</td>
											<td>$box_mth</td>
											<td>$box_m</td>
											<td>$teus_m</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td>$s_l</td>
											<td>$s_ul</td>
											<td>0</td>
											<td>0</td>
											<td>$jml_hm</td>
										</tr>
EOD;
$ttl_box=$box_b+$box_m;
$ttl_teus=$teus_b+$teus_m;
$tbl .= <<<EOD
										<tr>
											
											<td colspan="3" ALIGN="RIGHT"><b>GRAND TOTAL (BOX) &nbsp;</b></td>
											<td colspan="2">$ttl_box</td>
											<td colspan="11">&nbsp;</td>
											
										</tr>
										<tr>
											
											<td colspan="3" ALIGN="RIGHT"><b>GRAND TOTAL (TEUS) &nbsp;</b></td>
											<td colspan="2">$ttl_teus</td>
											<td colspan="11">&nbsp;</td>
											
										</tr>
EOD;

	$tbl .= <<<EOD
							</table>
						</td>
					</tr>
EOD;

	$tbl .= <<<EOD
					<tr><td colspan="4"></td></tr>
					<tr><td  width="200">REMARKS : </td></tr>
					<tr><td width="200">&nbsp;</td></tr>
					<tr><td  width="200">&nbsp;</td></tr>
					<tr><td></td><td></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">CABANG PELABUHAN JAMBI</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">An.GENERAL MANAGER</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">MANAGER OPERASI</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">        </td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">NIPP :        </td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4" align="center">DOKUMEN INI DITERBITKAN DARI SISTEM DAN TIDAK MEMERLUKAN TANDA TANGAN</td></tr>
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
$pdf->Output('sample.pdf', 'I');
?>
