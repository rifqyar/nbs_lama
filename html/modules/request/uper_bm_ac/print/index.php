<?php
//require "login_check.php";
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
$pdf->SetMargins(PDF_MARGIN_LEFT, 30, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// add a page
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', '', 8);
$pdf->Write(0, "Tanggal Cetak ".date('d-M-Y'), '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Write(0, 'PERHITUNGAN UPER BONGKAR MUAT', '', 0, 'C', true, 0, false, false, 0);
$pdf->ln();

$db = getDB();
$p1 = $_GET['p1'];
$query="SELECT * FROM UPER_H WHERE NO_UPER='$p1'";
$data = $db->query($query)->fetchRow();
$param_tgl = $data[TGL_ENTRY];

/*$query2="SELECT NM_PEMILIK, ALAMAT, COA, TGL_JAM_TIBA, TGL_JAM_BERANGKAT, NO_NPWP, NM_KAPAL, VOYAGE_IN, VOYAGE_OUT
					FROM RBM_H WHERE NO_UKK='".$data[NO_UKK]."'";
$data2 = $db->query($query2)->fetchRow();*/

/*$query_head = "SELECT
	C.NAME AS PEMAKAI_JASA,
	C.ADDRESS AS ALAMAT,
	B.ACCOUNT_NUMBER NO_ACCOUNT,
	C.TAX_ID AS NPWP,
	A.VESSEL_NAME||' / '||A.VOY_IN||' - '||A.VOY_OUT||' / '||A.ETD AS VVD,
	A.ETA||' s/d '||A.ETD AS PERIODE,
	B.ACCOUNT_NUMBER,
	D.KADE_NAME AS NAMA_KADE
FROM
	ITOS_OP.VES_VOYAGE A
	JOIN ITOS_OP.M_STEVEDORING_COMPANIES B ON A.STV_COMPANY = B.ID_COMPANY
	JOIN ITOS_BILLING.TR_COMPANY C ON TO_CHAR(C.COMPANY_ID) = TO_CHAR(B.ACCOUNT_NUMBER)
	JOIN ITOS_OP.M_KADE D ON D.ID_KADE = A.ID_KADE
WHERE
	ID_VES_VOYAGE = (
	SELECT
		NO_UKK
	FROM
		UPER_H
	WHERE
		NO_UPER = '$p1')";*/
		
$query_head = "select 
         C.NAMA_PERUSAHAAN AS PEMAKAI_JASA,
        C.ALAMAT_PERUSAHAAN ALAMAT,
        A.COMPANY_ID NO_ACCOUNT,
        C.NO_NPWP NPWP,
        B.VESSEL||' / '||B.VOYAGE_IN||' - '||B.VOYAGE_OUT||' / '|| TO_DATE(SUBSTR(B.ETA,0,8),'YYYYMMDD') AS VVD,
       -- TO_DATE(SUBSTR(B.ETA,0,8),'YYYYMMDD') ,
        TO_DATE(SUBSTR(B.ETA,0,8),'YYYYMMDD') ||' s/d '|| TO_DATE(SUBSTR(B.ETD,0,8),'YYYYMMDD')  AS PERIODE,
        A.company_id ACCOUNT_NUMBER,
        '' AS NAMA_KADE 
        from uper_h a
        join opus_repo.m_VSb_VOYAGE b on a.no_ukk = b.id_vsb_voyage
        join mst_pelanggan c on TO_CHAR(C.kd_pelanggan) = to_char(a.company_id)
        where a.NO_UPER = '$p1'";
$data2 = $db->query($query_head)->fetchRow();

// $arr_term2 = array('TO1'=>'I','TO2'=>'II','TO3'=>'III');
// $terminal = $arr_term2[$data[TERMINAL]];
if($data[VAL_REAL]=='USD')	$perdagangan="Luar Negeri";
else 						$perdagangan="Dalam Negeri";

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="18%"></td>
		<td valign="top" width="25%">No Uper</td>
		<td valign="top" width="2%">:</td>
		<td width="55%">$p1</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Pemakai Jasa</td>
		<td valign="top">:</td>
		<td>$data2[PEMAKAI_JASA]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Alamat</td>
		<td valign="top">:</td>
		<td>$data2[ALAMAT]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">No Account</td>
		<td valign="top">:</td>
		<td>$data2[NO_ACCOUNT]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">NPWP</td>
		<td valign="top">:</td>
		<td>$data2[NPWP]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Kapal / Voyage / Tanggal</td>
		<td valign="top">:</td>
		<td>$data2[VVD]</td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Gudang / Lapangan / Kade</td>
		<td valign="top">:</td>
		<td> $data2[NAMA_KADE] </td>
	</tr>
	<tr>
		<td></td>
		<td valign="top">Jenis Perdagangan</td>
		<td valign="top">:</td>
		<td>$perdagangan</td>
	</tr>	
	<tr>
		<td></td><td valign="top">Periode Kegiatan</td>
		<td valign="top">:</td>
		<td>$data2[PERIODE]</td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

$query_dtl="SELECT NO_URUT, SIZE_, TYPE_, STATUS, HEIGHT_CONT, DECODE(HZ,'T','Tidak','Y','Ya') AS HZ, BONGKAR, MUAT, TARIF, JUMLAH, VALUTA, FLAG_OI, KEGIATAN, SUBKEG FROM UPER_D WHERE NO_UPER='$p1' ORDER BY NO_URUT";
$res = $db->query($query_dtl);
$i=$j=1;

function get_tarif_dermaga_kebersihan($no_uper,$size,$type,$status,$bongkar,$muat,$hz){

	$hz = ($hz == 'Tidak') ? 'T' : 'Y';

	$db= getDB();
	$sql = "SELECT
			B.KODE_BARANG,
			A.NO_URUT,
			A.SIZE_,
			A.TYPE_,
			A.STATUS,
			A.HEIGHT_CONT,
			A.HZ,
			A.VALUTA,
			A.FLAG_OI,
			SUM(C.TARIF) AS TARIF
		FROM
			UPER_D A
			JOIN MASTER_BARANG B ON B.UKURAN = A.SIZE_ AND B.TYPE = A.TYPE_ AND B.STATUS = A.STATUS AND B.HEIGHT_CONT = A.HEIGHT_CONT
			JOIN MASTER_TARIF_CONT C ON C.ID_CONT = B.KODE_BARANG AND C.JENIS_BIAYA IN ('DERMAGA','KEBERSIHAN')
		WHERE
			A.NO_UPER   = '$no_uper'
		AND A.SIZE_ 	= '$size'
		AND A.TYPE_ 	= '$type'
		AND A.STATUS 	= '$status'
		AND A.BONGKAR 	= '$bongkar'
		AND A.MUAT 		= '$muat'
		AND A.HZ 		= '$hz'
		GROUP BY
			B.KODE_BARANG,
			A.NO_URUT,
			A.SIZE_,
			A.TYPE_,
			A.STATUS,
			A.HEIGHT_CONT,
			A.HZ,
			A.VALUTA,
			A.FLAG_OI
		ORDER BY NO_URUT";
	//print_r($sql);die;
	$row = $db->query($sql)->fetchRow();
	return $row;

}

/*$rows = $res->getAll();
print_r($rows);*/
$tarif  = 0;
$jumlah = 0;
while($data_dtl = $res->fetchRow()) {
	unset($height);
	if($data_dtl[HEIGHT_CONT]=='OOG')	$height = ' / '.$data_dtl[HEIGHT_CONT];
	if($data_dtl[KEGIATAN]=='BM')
		$jumbm = '<td valign="top" align="center">'.$data_dtl[BONGKAR].'</td><td align="center">'.$data_dtl[MUAT].'</td>';
	else if($data_dtl[KEGIATAN]=='SHIFT') {
		if($data_dtl[SUBKEG]=='CRDMG1' || $data_dtl[SUBKEG]=='CRKPL1')
			$jumbm = '<td valign="top" colspan="2">shifting unlanded : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';
		else
			$jumbm = '<td valign="top" colspan="2">shifting landed : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';
	}
	else if($data_dtl[KEGIATAN]=='TRANS'){
		$jumbm = '<td colspan="2">Transhipment : '.($data_dtl[BONGKAR]+$data_dtl[MUAT]).'</td>';		
	}


	/*start kebershihan dan dermaga*/
	/*$no_uper,$size,$type,$status,$bongkar,$muat,$hz*/
	// $tarif = get_tarif_dermaga_kebersihan($p1,$data_dtl[SIZE_],$data_dtl[TYPE_],$data_dtl[STATUS],$data_dtl[BONGKAR],$data_dtl[MUAT],$data_dtl[HZ]);
	//$jumbm = 0;

	$tarif 		 = $data_dtl[TARIF];// + $tarif[TARIF];
	$total_tarif = $data_dtl['JUMLAH'];

	//print_r($tarif);

	/*end kebersihan dan dermaga*/


	$detail .= '<tr>
					<td valign="top">'.$i.'</td>
					<td valign="top">Container '.$data_dtl[SIZE_].'-'.$data_dtl[TYPE_].'-'.$data_dtl[STATUS].$height.'</td>
					<td valign="top" align="center">Petikemas</td>
					<td valign="top" align="center">'.$data_dtl[HZ].'</td>
					'.$jumbm.'
					<td valign="top" align="right">'.number_format($tarif).'</td>
					<td valign="top" align="right">'.number_format($total_tarif,2).'</td>
				</tr>';
	$i++;

	$jumlah+=$total_tarif;
}


/*alat*/
$sql_alat = "SELECT
	A.SIZE_,
	A.TYPE_,
	A.STATUS,
	A.HEIGHT_CONT,
	DECODE(A.HZ,'T','Tidak','Y','Ya') AS HZ,
	SUM(A.BONGKAR + A.MUAT) AS JML,
	A.SUBKEG,
	B.KODE_BARANG,
	C.TARIF,
	(C.TARIF * SUM(A.BONGKAR + A.MUAT)) AS TOTAL_TARIF
FROM
	UPER_D A
	JOIN MASTER_BARANG B ON B.UKURAN = A.SIZE_ AND B.TYPE = A.TYPE_ AND B.STATUS = A.STATUS AND B.HEIGHT_CONT = A.HEIGHT_CONT
	JOIN MASTER_TARIF_CONT C ON C.ID_CONT = B.KODE_BARANG AND C.JENIS_BIAYA = 'ALAT' AND C.JENIS_BIAYA2 = A.SUBKEG AND SYSDATE BETWEEN C.START_PERIOD
	AND C.END_PERIOD
WHERE
	A.NO_UPER = '$p1'
GROUP BY 
A.SIZE_,
A.TYPE_,
A.STATUS,
A.HEIGHT_CONT,
A.HZ,
A.SUBKEG,
B.KODE_BARANG,
C.TARIF";

$row_alat = $db->query($sql_alat)->getAll();

$keg = "";
foreach ($row_alat as $ra) {

	$keg = ($ra[SUBKEG] == 'SC') ? 'HMC' : $ra[SUBKEG];

	$detail .= '<tr>
					<td valign="top">'.$i.'</td>
					<td valign="top">Jasa Alat ('.$keg.')</td>
					<td valign="top" align="center">Petikemas</td>
					<td valign="top" align="center">'.$ra[HZ].'</td>
					<td colspan="2" valign="top" align="center">'.$ra[JML].'</td>
					<td valign="top" align="right">'.number_format($ra[TARIF]).'</td>
					<td valign="top" align="right">'.number_format($ra[TOTAL_TARIF]).'</td>
				</tr>';
	$i++;

	$jumlah+=$ra[TOTAL_TARIF];
}


/*alat*/

$pdf->SetFont('helvetica', '', 10);
$tbl = <<<EOD
<table cellpadding="1" border="1" width="100%">
	<tr>
		<th width="4%" rowspan="2" valign="center" align="center"><b>No</b></th>		
		<th width="27%" rowspan="2" valign="center" align="center"><b>Jenis Barang</b></th>
		<th width="12%" rowspan="2" valign="center" align="center"><b>Kemasan</b></th>
		<th width="8%" rowspan="2" valign="center" align="center"><b>Hz</b></th>
		<th width="20%" colspan="2" valign="center" align="center"><b>Jumlah</b></th>
		<th width="12%" rowspan="2" valign="center" align="center"><b>Tarif</b></th>
		<th width="17%" rowspan="2" valign="center" align="center"><b>Biaya</b></th>
	</tr>
	<tr>
		<th width="10%" valign="center" align="center"><b>Bongkar</b></th>
		<th width="10%" valign="center" align="center"><b>Muat</b></th>
	</tr>
	$detail
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

//print_r($jumlah);die;

if($data[VALUTA]=='IDR')	$val="Rp";
else 						$val="US$";
/*test*/
//CR PPN 11%
$query_cut_off1 = "SELECT COUNT(1) JML FROM MASTER_CUT_OFF
WHERE JENIS_CUT_OFF = 'CUT_OFF_PPN11' AND (TO_CHAR(TO_DATE('$param_tgl'),'yyyymmdd') >= TO_CHAR(START_DATE,'yyyymmdd'))
AND (TO_CHAR(TO_DATE('$param_tgl'),'yyyymmdd') <= TO_CHAR(END_DATE,'yyyymmdd') or END_DATE is NULL)";
$res_cutoff 	= $db->query($query_cut_off1);
$row_cutoff 	= $res_cutoff->fetchRow();

//echo $row_cutoff['JML']; die;
if($row_cutoff['JML'] > 0)
{
	$query_cut_off = "SELECT VARIABLE from  MASTER_CUT_OFF
						WHERE JENIS_CUT_OFF ='CUT_OFF_PPN11' 
						AND ( to_char(to_date('$param_tgl'),'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) 
						AND ( to_char(to_date('$param_tgl'),'yyyymmdd') <= to_char(end_date,'yyyymmdd') 
						or end_date is null)  ";
	$result_cut_off		= $db->query($query_cut_off);
	$row_cut_off		= $result_cut_off->fetchRow();

	$var 	= $row_cut_off['VARIABLE'];
	$ppn1 	= ($var/100);
} else {
	$ppn1 = 0.1;		
}



$persen 	= round((float)($jumlah+$data[BIAYA_ADM]) * $ppn1, 2);
//CR PPN 11%
$ttl 		= $persen + $jumlah + $data[BIAYA_ADM];

$jmlh = number_format($jumlah,2);
$adm = number_format($data[BIAYA_ADM],2);
$ppn = number_format($persen,2);
$total = number_format($ttl,2);


/*update ttl*/
$db->query("UPDATE UPER_H SET JUMLAH = '$jumlah', TOTAL = '$ttl' WHERE NO_UPER = '$p1'");

$tbl = <<<EOD
<table cellpadding="1">
	<tr>
		<td width="50%"></td>
		<td align="right" width="22%"><b>Jumlah</b></td>
		<td align="right" width="8%"><b>$val</b></td>
		<td align="right" width="20%"><b>$jmlh</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>Administrasi</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$adm</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>PPN</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$ppn</b></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><b>Jumlah Uper</b></td>
		<td align="right"><b>$val</b></td>
		<td align="right"><b>$total</b></td>
	</tr>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>