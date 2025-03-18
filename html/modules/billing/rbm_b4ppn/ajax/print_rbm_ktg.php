<?php
$id_vsb=$_GET["id"];
$kategori=$_GET["kategori"];

$user=$_SESSION["PENGGUNA_ID"];

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

//$db = getDB();
//$date=date('d M Y'); 
$date=date('d F Y H:i');

// jumlah detail barangnya
//$query_jum="SELECT COUNT(1) JUM_DETAIL FROM TB_NOTA_RECEIVING_D A WHERE A.ID_REQ='$no_req'";
$data_jum = 10;
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum/$jum_data_page);	//hasil bagi pembulatan ke atas
$db=getdb();
//$no_ukk='2';
/*$query_h 	= "select VESSEL_CODE,VESSEL, OPERATOR_NAME,to_char(to_date(ATA,'YYYYMMDDHH24MISS'),'dd-MON-rr hh24:mi') ATA,to_char(to_date(ATD,'YYYYMMDDHH24MISS'),'dd-MON-rr hh24:mi') ATD, VOYAGE_IN, VOYAGE_OUT, to_char(to_date(ATA,'YYYYMMDDHH24MISS'),'dd-MON-rr hh24:mi') BERTHING, to_char(to_date(ATA,'YYYYMMDDHH24MISS'),'dd-MON-rr hh24:mi') START_WORK, to_char(to_date(ATD,'YYYYMMDDHH24MISS'),'dd-MON-rr hh24:mi') END_WORK
                FROM bil_stv_h
                where id_vsb_voyage='$id_vsb'";
*/
$query_h    = "SELECT VESSEL,
					   VOYAGE_IN,
					   VOYAGE_OUT,
					   VESSEL_CODE,
					   OPERATOR_NAME,
					   TO_CHAR (TO_DATE (ata, 'yyyymmddhh24miss'), 'dd Mon RR') ATA,
					   TO_CHAR (TO_DATE (atd, 'yyyymmddhh24miss'), 'dd Mon RR') ATD,
					   TO_CHAR (TO_DATE (berthing_time, 'yyyymmddhh24miss'),
								'dd Mon RR')
						  BERTHING,
					   TO_CHAR (TO_DATE (start_work, 'yyyymmddhh24miss'),
								'dd Mon RR')
						  START_WORK,
					   TO_CHAR (TO_DATE (end_work, 'yyyymmddhh24miss'),
								'dd Mon RR')
						  END_WORK
				  FROM m_vsb_voyage@dbint_link
				 WHERE id_vsb_voyage='$id_vsb'";
$result_h   = $db->query($query_h);
$header      = $result_h->fetchrow();

$ves_id 		= $header['VESSEL'];
$owner 			= $header['OPERATOR_NAME'];
$rta 			= $header['ATA'];
$rtd 			= $header['ATD'];
$berth 			= $header['BERTHING'];
//$str 			= $header['START_WORK'];
//$end 			= $header['END_WORK'];
$voy_in 		= $header['VOYAGE_IN'];
$voy_out 		= $header['VOYAGE_OUT'];
$vesscode 		= $header['VESSEL_CODE'];

// adding by dama 14nov2014
// narik start work, end work pnoadm
$query_work 	= "/* Formatted on 14/11/2014 15:33:52 (QP5 v5.163.1008.3004) */
					SELECT TO_CHAR(TO_DATE(VSB_VOY_ESTWKS_DATE || VSB_VOY_ESTWKS_TIME,'yyyymmddhh24miss'),'dd Mon YY') START_WORK,
						   TO_CHAR(TO_DATE(VSB_VOY_ESTWKC_DATE || VSB_VOY_ESTWKC_TIME,'yyyymmddhh24miss'),'dd Mon YY') END_WORK
					  FROM pnoadm.vsb_voyage@dbint_link
					 WHERE VSB_VOY_VESSEL = '$vesscode' AND VSB_VOY_EVOYAGEIN = '$voy_in'";
$result_work   	= $db->query($query_work);
$work          	= $result_work->fetchrow();
$str 			= $work['START_WORK'];
$end			= $work['END_WORK'];


/*PT. PTP*/
	$param_tgl  = $header['ATA'];
	$qnama = "select nm_perusahaan from date_reference where cut_date <= to_date('$param_tgl','dd-MON-rr hh24:mi') order by cut_date desc";
	$rnama = $db->query($qnama)->fetchRow();
	$corporate_name = $rnama["NM_PERUSAHAAN"];
	if ($corporate_name == "PT. PELABUHAN INDONESIA II (PERSERO)") {
		$branch_name = "CABANG TANJUNG PRIOK";
		$ttd = $branch_name;
		$jab = "An.GENERAL MANAGER";
	}
	else {
		$ttd = $corporate_name;
		$jab = "An.DIREKTUR OPERASI";
	}
/*PT. PTP*/
$q 	= "select QTY_RPSTV as JUMLAH
				FROM bil_stv_dtmp
				where id_vsb_voyage='$id_vsb' and keg='HATCH' and id_kategori='$kategori' ";
$res   = $db->query($q);
$resl      = $res->fetchrow();

$jm_ht = $resl[JUMLAH];

$q 	= "select NVL(SUM(QTY_RPSTV),0) as JUMLAH
				FROM bil_stv_dtmp
				where id_vsb_voyage='$id_vsb' and KEG='Landed' and id_kategori='$kategori'";
				//echo $q;die;
$res   = $db->query($q);
$resl      = $res->fetchrow();

$jm_sh = $resl[JUMLAH];
$q 	= "select SUM(QTY_RPSTV) as JUMLAH
				FROM bil_stv_dtmp
				where id_vsb_voyage='$id_vsb' and KEG='UnLanded' and id_kategori='$kategori'";
$res   = $db->query($q);
$resl      = $res->fetchrow();

$jm_shu = $resl[JUMLAH];

//KATEGORI
$q 	= "select OPERATOR_ID, OPERATOR_NAME
				FROM bil_stv_list
				where id_vsb_voyage='$id_vsb' and id_kategori='$kategori' group by OPERATOR_ID, OPERATOR_NAME order by operator_id";

$resop   = $db->query($q);
$resopid      = $resop->getAll();

$opid='';
$nox=0;
foreach($resopid as $row)
{
	if($nox=='0')
	{
		$opid=$opid.$row['OPERATOR_ID'].' ['.$row['OPERATOR_NAME'].']';
	}
	else
	{
		$opid=$opid.' & '.$row['OPERATOR_ID'].' ['.$row['OPERATOR_NAME'].']';
	}
	$nox++;
}

//echo $opid; die;
//$jm_sh = $header['JML_SHIFT'];
//$jm_ht = $header['JML_HATCH'];

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
					<tr><td colspan="2">$corporate_name</td><td></td><td>No.Form : FM.02/03/01/24</td></tr>
					<tr><td>$branch_name</td><td></td><td></td><td>Refisi : 03</td></tr>
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
									<td width="10">:</td>
									<td colspan="10">$ves_id</td>
								</tr>
								<tr>
									<td>VOYAGE</td>
									<td width="10">:</td>
									<td colspan="10">$voy_in - $voy_out</td>
								</tr>
								<tr>
									<td width="80">ARRIVAL</td>
									<td width="10">:</td>
									<td width="100">$rta</td>
									<td width="30"></td>
									<td width="80">BERTHING</td>
									<td width="10">:</td>
									<td width="100">$berth</td>
									<td width="30"></td>
									<td width="80">DEPARTURE</td>
									<td width="10">:</td>
									<td width="100">$rtd</td>
								</tr>
								<tr>
									<td width="80">START WORK</td>
									<td width="10">:</td>
									<td width="100">$str</td>
									<td width="30"></td>
									<td width="80">END WORK</td>
									<td width="10">:</td>
									<td width="100">$end</td>
									<td width="30"></td> 
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td width="80">OPERATOR</td>
									<td width="10">:</td>
									<td colspan="10">$opid</td>
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
											<td width="50">STATUS_CONT</td>
											<td width="20">Ol</td> 
											<td width="50">HAZARD</td>
											<td width="50">IMO</td>
											<td width="40">Boxes</td>
										</tr>  
EOD;
$tb=0;

$query_db	= "select EI, SIZE_CONT, TYPE_CONT, 
case when HGH_CONT_TERM='BIASA' then '' else 'OOG' END AS HEIGHT
,STS_CONT AS STATUS_CONT,HZ_CONT AS HZ, QTY_RPSTV BOX,HZ_LABEL AS LABEL,TY_CC AS ALAT
from bil_stv_dtmp where id_vsb_voyage='$id_vsb' and id_kategori='$kategori' and EI='I' and keg='DISCH'"; 
//print_r($query_db);die;
$result_db   = $db->query($query_db);

$ddb      = $result_db->getAll();
foreach($ddb as $rowb)
{
$tbl_a .='<tr>
											<td width="120" class="basic">&nbsp;</td>
											<td width="100" class="basic">DISCH</td>
											<td width="80" class="basic">&nbsp;</td> 
											<td width="90" class="basic">'.$rowb['ALAT'].'</td>
											<td width="50" class="basic">'.$rowb['SIZE_CONT'].'</td> 
											<td width="50" class="basic">'.$rowb['TYPE_CONT'].''.$rowb['HEIGHT'].'</td> 
											<td width="50" class="basic">'.$rowb['STATUS_CONT'].'</td>
											<td width="20" class="basic">O</td> 
											<td width="50" class="basic">'.$rowb['HZ'].'</td>
											<td width="50" class="basic">'.$rowb['LABEL'].'</td>
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

$query_dm 	= "select EI, SIZE_CONT, TYPE_CONT, 
case when HGH_CONT_TERM='BIASA' then '' else 'OOG' END AS HEIGHT
,STS_CONT AS STATUS_CONT,HZ_CONT AS HZ, QTY_RPSTV BOX,HZ_LABEL AS LABEL,TY_CC AS ALAT, KEG
from bil_stv_dtmp where id_vsb_voyage='$id_vsb' and id_kategori='$kategori' and EI in('E','T') and keg IN ('LOAD','TRANS') ORDER BY KEG"; 
$result_dm   = $db->query($query_dm);

$ddm      = $result_dm->getAll();
foreach($ddm as $rowm)
{
$tbl_b .='<tr>
											<td width="120" class="basic">&nbsp;</td>
											<td width="100" class="basic">'.$rowm['KEG'].'</td>
											<td width="80" class="basic">&nbsp;</td> 
											<td width="90" class="basic">'.$rowm['ALAT'].'</td>
											<td width="50" class="basic">'.$rowm['SIZE_CONT'].'</td> 
											<td width="50" class="basic">'.$rowm['TYPE_CONT'].''.$rowm['HEIGHT'].'</td> 
											<td width="50" class="basic">'.$rowm['STATUS_CONT'].'</td>
											<td width="20" class="basic">O</td> 
											<td width="50" class="basic">'.$rowm['HZ'].'</td>
											<td width="50" class="basic">'.$rowm['LABEL'].'</td>
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
					<tr><td width="200">TOTAL (OPEN + CLOSE PALKA)</td><td> : <b>$jm_ht</b></td></tr>
					<tr><td  width="200">TOTAL (SHIFTING - LANDED)</td><td> : <b>$jm_sh</b></td></tr>
					<tr><td  width="200">TOTAL (SHIFTING - UNLANDED)</td><td> : <b>$jm_shu</b></td></tr>
					<tr><td></td><td></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">$ttd</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">$jab</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">MANAGER OPERASI</td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="4"></td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">Hendri Purnomo</td></tr>
					<tr><td colspan="2"></td><td colspan="2" align="center">NIPP : 264096749</td></tr>
					
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