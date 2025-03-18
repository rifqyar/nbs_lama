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
$rs=$_GET['id'];
$db=getDb();
	$qh="select 
				A.*,to_char(A.PPN_,'999,999,999.00') PPN_, 
				to_char(A.TTL_,'999,999,999.00') TTL_, 
				to_char(A.DPP_,'999,999,999.00') as DPP_X,
				TO_DATE(A.ATA,'YYYYMMDDHH24MISS') ATAX,
				TO_DATE(A.ATD,'YYYYMMDDHH24MISS') ATDX, 
				to_char(B.TTL_FARE,'999,999,999.00') as ADM, 
				terbilang(A.TTL_) terbilang,
				to_char(A.KURS,'999,999,999.00') KURS_, 
				to_char(A.KURS_DATE,'dd/mm/rrrr hh24:mi:ss') KURS_DATE_,
                ICTTAX_REFERENCE,
				to_char(TO_DATE(A.ATA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') ATA
		 from 
				bil_ntstv_h A left JOIN bil_ntstv_d B 
				on A.id_ntstv=B.id_ntstv and B.nt_ct='ADM' 
		where 
				A.id_rpstv='$rs'";
	$res=$db->query($qh);
	$rd=$res->fetchRow();
	
$id_nota=$rd['ID_NTSTV'];
$nm_kpl=$rd['VESSEL'];
$voyin=$rd['VOY_IN'];   
$voyout=$rd['VOY_OUT'];
$nm_pmlk=$rd['CUST_NAME'];
$almt=$rd['CUST_ADDR'];
$no_npwp=$rd['CUST_TAX_NO'];
$rta=$rd['ATAX'];
$rtd=$rd['ATDX'];
$date_n=$rd['TRX_DATE'];
$terbilang=$rd['TERBILANG'];
$kurs=$rd['KURS_'];
$kurs_date=$rd['KURS_DATE_'];
$param_tgl=$rd['ATA'];

$ppn_010 = '0.00';
$ppn_030 = '0.00';
$ppn_070 = '0.00';
$ppn_080 = '0.00';
if($rd['ICTTAX_REFERENCE'] == '010') {
  $ppn_010 = $rd['PPN_'];
}
else if($rd['ICTTAX_REFERENCE'] == '030') {
  $ppn_030 = $rd['PPN_'];
}
else if($rd['ICTTAX_REFERENCE'] == '070') {
  $ppn_070 = $rd['PPN_'];
}
else if($rd['ICTTAX_REFERENCE'] == '080') {
  $ppn_080 = $rd['PPN_'];
}


$ttl=$rd['TTL_'];
$adm=$rd['ADM'];
$tag=$rd['DPP_X']; 
$ppn=$rd['PPN_'];
$no_nota=$rd['TRX_NMB'];
$no_faktur=$rd['TRX_NMB'];

/*ipctpk*/
//$q_nama = "select fc_nm_perusahaan('$param_tgl','DISCHLOAD') corporate_name from dual";
$q_nama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc";
/**/
//echo $q_nama; die();
$r_nama =   $db->query($q_nama)->fetchRow();
$corporate_name = $r_nama[CORPORATE_NAME];

//start materai gagat 05 februari 2020
    $qmtr="select to_char(TTL_FARE, '999,999,999.99') AS MATERAI
		FROM bil_ntstv_d 
		WHERE trim(ID_NTSTV)='$id_nota' AND NT_CT='MATERAI' ";
	$res_mtr=$db->query($qmtr);
	$rd_mtr=$res_mtr->fetchRow(); 
	if($rd_mtr['MATERAI']>0){
		$bea_materai = $rd_mtr['MATERAI'];
	}else{
		$bea_materai = 0;
	}
//end get materai 05 februari 2020


$qd="select CASE WHEN NT_CT='Landed' THEN 'SHIFTING LANDED' WHEN NT_CT='Unlanded' then 'SHIFTING UNLANDED' ELSE NT_CT END KETERANGAN, 
		EI,SIZE_CONT,TYPE_CONT,STS_CONT,HZ_CONT,QTY_RPSTV,TY_CC,
		to_char(FARE_, '999,999,999.99') TARIF, VAL, 
		to_char(TTL_FARE, '999,999,999.99') JUMLAH, 
		CASE WHEN HGH_CONT_TERM='OOG' THEN '/OOG' ELSE '' END HEIGHT,
        CASE WHEN VAL = 'USD' THEN 'O' ELSE 'I' END OI, CASE WHEN VAL_REAL = 'USD' THEN VAL_REAL ELSE 'IDR' END VAL_FARE
		FROM bil_ntstv_d 
		WHERE trim(ID_NTSTV)='$id_nota' AND NT_CT NOT IN ('ADM','MATERAI') order by NT_CT,EI DESC";/**gagat modif 05 FEB 2020*/
		//print_r($qd);die;
	$red=$db->query($qd);
	$rdt=$red->getAll();

foreach ($rdt as $row)
{
	$tblx .='	<tr>
					<td colspan="3" widtd="150"><font size="8">'.$row['KETERANGAN'].'</font></td>
                    <td widtd="32" align="left"><font size="8">'.$row['EI'].'</font></td>
                    <td widtd="32" align="left"><font size="8"> '.$row['OI'].'</font></td>
                    <td widtd="32" align="center"><font size="8">'.$row['TY_CC'].'</font></td>
                    <td widtd="32" align="center"><font size="8">'.$row['SIZE_CONT'].'</font></td>
                    <td widtd="32" align="center"><font size="8">'.$row['TYPE_CONT'].''.$row['HEIGHT'].'</font></td>
                    <td widtd="32" align="center"><font size="8">'.$row['STS_CONT'].'</font></td>
                    <td widtd="32" align="center"><font size="8">'.$row['HZ_CONT'].'</font></td>
                    <td widtd="32" align="center"><font size="8">'.$row['QTY_RPSTV'].'</font></td>
                    <td widtd="80" align="right"><font size="8">'.$row['TARIF'].'</font></td>
                    <td widtd="32" align="center"><font size="8">'.$row['VAL_FARE'].'</font></td>
                    <td widtd="180" align="right"><font size="9" >'.$row['JUMLAH'].'</font></td>
                    <td widtd="40" align="center"><font size="8">'.$row['VAL'].'</font></td>
                </tr>';
}


$user= $_SESSION["NAMA_PENGGUNA"];
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil(10/$jum_data_page);	//hasil bagi pembulatan ke atas
if((10%$jum_data_page)>10 || (10%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 9);
	//$hal_1 = $this->getAliasNumPage();
	//$hal_2 = $this->getAliasNbPages();
	$tbl = <<<EOD
			<table border='1'>
                <tr>
                    <td COLSPAN="14" align="left"><b>$corporate_name</b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" align="left"><b></b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" ALIGN="RIGHT">$no_faktur</td>
                    
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. Doc</td>
                    <td COLSPAN="3" align="left">: $id_nota </td>
                </tr>
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">Tgl. Proses</td>
                    <td COLSPAN="3" align="left">: $date_n  </td>
                </tr> 
				<tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">Halaman</td>
                    <td COLSPAN="3" align="left">: 1/1 </td>
                </tr>    				
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. faktur</td>
                    <td COLSPAN="3" align="left">: $no_faktur</td>
                </tr>    
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="6"></td>
                    <td COLSPAN="8" align="right"><font size="11"><b>BONGKAR / MUAT</b></font></td>
                    
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="5" align="left">$nm_pmlk </td>
					<td colspan='7' width="400" align="right"></td>
                </tr>
				<tr>
                    <td></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$no_npwp</td>
					<td colspan='8' width="400" align="right">BONGKAR / MUAT</td>
                </tr>
				
				<tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$$almt </td>
					<td colspan='8' width="400" align="right">BONGKAR / MUAT</td>
                </tr>
				
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left">$nm_kpl / $voyin - $voyout</td>
                    <td colspan="8" width="400" align="right">$rta s/d $rtd</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
				
				<tr>
                <td COLSPAN="2"></td>
                    <td COLSPAN="4" align="left"></td>
                    <td colspan="8" width="400" align="right"></td>
                </tr>
				
                <tr>
                <td></td>
                </tr> 
                <tr>
                <td></td>
                </tr>
                <tr>
                <td></td>
                </tr> 
                <tr>
                    <th colspan="3" width="150"><font size="8"><b> KETERANGAN </b></font></th>
                    <th width="32" align="left"><font size="8"><b> E/I </b></font></th>
                    <th width="32" align="left"><font size="8"><b> O/I </b></font></th>
                    <th width="32" align="center"><font size="8"><b> CR </b></font></th>
                    <th width="32" align="center"><font size="8"><b> SZ </b></font></th>
                    <th width="32" align="center"><font size="8"><b> TY </b></font></th>
                    <th width="32" align="center"><font size="8"><b> ST </b></font></th>
                    <th width="32" align="center"><font size="8"><b> HZ </b></font></th>
                    <th width="32" align="center"><font size="8"><b> BOX </b></font></th>
                    <th width="80" align="right"><font size="8"><b> TARIF </b></font></th>
                    <th width="32" align="center"><font size="8"><b> VAL </b></font></th>
                    <th width="150" align="right"><font size="9" ><b> JUMLAH </b></font></th>
                    <th width="40" align="center"><font size="8"><b> VAL </b></font></th>
                </tr>
				
                <tr>
                    <td colspan="14">
                        <hr style="border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                </tr>
				$tblx
				$tbls
				$tblh
				<tr>
                    <td colspoan="14">
                        <hr style="border: 2px dashed #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                </tr>
				
                </table>
                
EOD;
$tbl .=<<<EOD
<table>                    
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">Discount :</td>
                        <td width="168" colspan="4" align="right">0.00</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">Administrasi :</td>
                        <td width="168" colspan="4" align="right"> $adm</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">Dasar Pengenaan Pajak :</td>
                        <td width="168" colspan="4" align="right"> $tag</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">a. PPN dipungut sendiri :</td>
                        <td width="168" colspan="4" align="right"> $ppn_010</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">b. PPN dipungut Pemungut :</td>
                        <td width="168" colspan="4" align="right"> $ppn_030</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">c. PPN tidak dipungut :</td>
                        <td width="168" colspan="4" align="right"> $ppn_070</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">d. PPN Dibebaskan :</td>
                        <td width="168" colspan="4" align="right"> $ppn_080</td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">Jumlah PPN Subsidi :</td>
                        <td width="168" colspan="4" align="right">0.00</td>
                    </tr>
					<!----gagat modify 05 februari 2020 ---->
					<tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">e. Bea Materai :</td>
                        <td width="168" colspan="5" align="right">$bea_materai</td>
                    </tr>
					<!---gagat end modify 05 februari 2020 --->
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="left">Jumlah Dibayar :</td>
                        <td width="168" colspan="4" align="right">$ttl</td>
                    </tr>
                    </table>
<p>USER :$user</p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                <p></p>
                
                
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

//$pdf->ln();
$pdf->SetFont('courier', '', 8);
$pdf->Write(0, 'Nota sebagai faktur pajak berdasarkan Peraturan Dirjen Pajak', '', 0, 'L', true, 0, false, false, 0);


$pdf->Write(0, 'PER-27/PJ/2011 Tanggal 19 September 2011', '', 0, 'L', true, 0, false, false, 0);
$pdf->ln();
$pdf->Write(0, "#".$terbilang, '', 0, 'L', true, 0, false, false, 0);
//$pdf->ln();
$pdf->ln();
$pdf->SetFont('courier', '', 9);
$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>