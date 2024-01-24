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
$pdf->SetMargins(5, 9, 8);
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
$p1 = $_GET['id'];
$jn = $_GET['jn'];
if($jn=='BH')
{
	$ket1='&nbsp;';
	$ket2='&nbsp;';
	$ket3='BEHANDLE';
}
else IF($jn=='EXMO')
{
    $ket1='&nbsp;';
    $ket2='&nbsp;';
    $ket3='EXTRA MOVEMENT';
}
else IF($jn=='HICO')
{
	$ket1='&nbsp;';
	$ket2='&nbsp;';
	$ket3='HI CO SCAN';
}
else IF($jn=='TRANS')
{
	$ket1='&nbsp;';
	$ket2='&nbsp;';
	$ket3='TRANSHIPMENT';
}

else IF ($jn=='SP2')
{
    $ket1='TGL AWAL';
    $ket2='TGL AKHIR';
    //$ket3='DELIVERY LIFT ON';
    $ket3='PENUMPUKAN / GERAKAN (DELIVERY PETIKEMAS)';
}
else IF ($jn=='RXP')
{
	$ket1='TGL AWAL';
	$ket2='TGL AKHIR';
	//$ket3='DELIVERY LIFT ON';
    $ket3='REEXPORT';
}
else IF ($jn=='ANNE')
{
	$ket1='TGL AWAL';
	$ket2='TGL AKHIR';
	//$ket3='RECEIVING LIFT OFF';
    $ket3='PENUMPUKAN / GERAKAN (RECEIVING)';

}
$query="select TO_CHAR(SYSDATE,'DD-MON-YYYY HH24:MI') AS TGL_PROSES,a.NO_FAKTUR_PAJAK, A.NO_NOTA AS KD_UPER, a.NO_REQUEST AS KD_PERMINTAAN, A.CUST_NAME NAMA_PELANGGAN, A.CUST_ADDR ALAMAT_PELANGGAN, 
 A.CUST_NPWP AS NPWP_PELANGGAN, NULL AS NO_UKK, A.NO_DO, A.BONGKAR_MUAT,
 TO_CHAR(A.TOTAL,'999,999,999,999.00') TOTAL, TO_CHAR(A.PPN,'999,999,999,999.00') PPN, TANGGAL_TIBA,
 TO_CHAR(A.KREDIT,'999,999,999,999.00') KREDIT, a.VESSEL AS NM_KAPAL, A.VOYAGE_IN, VOYAGE_OUT, TERBILANG(A.KREDIT) AS TERBILANG,
 TO_CHAR(nvl((SELECT D.TOTTARIF FROM TTR_NOTA_ALL d 
 where d.KD_UPER=a.NO_NOTA AND URAIAN='ADM'),0),'999,999,999,999.00') ADM 
 from tth_nota_all2 a WHERE a.NO_NOTA='$p1'";
$data = $db->query($query)->fetchRow();

IF ($jn=='ANNE')
{
    $qrpeb=$db->query("select PEB from req_receiving_h where trim(ID_REQ)='".$data[KD_PERMINTAAN]."'");
    $ftpeb=$qrpeb->fetchRow();
    $peb=$ftpeb['PEB'];
}
else IF ($jn=='SP2')
{
    $qrdo=$db->query("select NO_DO, NO_BL from req_delivery_h where trim(ID_REQ)='".$data[KD_PERMINTAAN]."'");
    $ftdo=$qrdo->fetchRow();
    $dono=$ftdo['NO_DO'];
    $blno=$ftdo['NO_BL'];      
}
 $date=date('d M Y H:i:s');
 //$query3="SELECT NO_CONTAINER from BH_DETAIL_REQUEST A, BH_NOTA B WHERE B.ID_NOTA='$p1'  AND A.ID_REQUEST=B.ID_REQUEST";
 
 $query3="SELECT NO_CONTAINER from BH_DETAIL_NOTA WHERE ID_NOTA='$p1' group by NO_CONTAINER";
 $res3 = $db->query($query3);
 $row3=$res3->getAll();
 	foreach($row3 as $rows2) {
		if($detail2<>'')	$detail2.=',';
		$detail2 .= $rows2[NO_CONTAINER];
	}
    if ($jn == 'BH') {
       $detail3 .= "NO_CONTAINER :".$detail2;
    }
    
 
 $row3=$res3->getAll();
$query_dtl="select URAIAN,TGL_AWAL,TGL_AKHIR,QTY, SIZE_,TYPE_,STATUS_,HZ, TOTHARI, TO_CHAR(TARIF,'999,999,999,999.00') TARIF, TO_CHAR(TOTTARIF,'999,999,999,999.00') TOTTARIF from ttR_nota_all WHERE KD_UPER='$p1' AND URAIAN<>'ADM'";
	$res = $db->query($query_dtl);
	//print_R($res);die;
	$i=0;
	//unset($detail);
	 $row2=$res->getAll();
	foreach($row2 as $rows) {
					
		$detail .= '
		<tr><td colspan="3" width="150"><font size="10">'.$rows[URAIAN].'</font></td>
                        <td width="70" align="center"><font size="9">'.$rows[TGL_AWAL].'</font></td>
                        <td width="70" align="center"><font size="9">'.$rows[TGL_AKHIR].'</font></td>
                        <td width="32" align="center"><font size="10">'.$rows[QTY].'</font></td>    
                        <td width="32" align="center"><font size="10">'.$rows[SIZE_].'</font></td>
                        <td width="32" align="center"><font size="10">'.$rows[TYPE_].'</font></td>    
                        <td width="32" align="center"><font size="10">'.$rows[STATUS_].'</font></td>    
                        <td width="32" align="center"><font size="10">'.$rows[HZ].'</font></td>
                        <td width="32" align="center"><font size="10">'.$rows[TOTHARI].'</font></td>
                        <td width="85" align="right"><font size="10">'.$rows[TARIF].'</font></td>
                        <td width="35" align="center">IDR</td>
                        <td width="115" align="right"><font size="10">'.$rows[TOTTARIF].'</font></td>        
                        </tr>                        
						
		';
		$i++;
	}


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM BH_DETAIL_NOTA A WHERE A.ID_NOTA='$p1'";
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
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
                    <td COLSPAN="14" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" align="left"><b>CABANG TANJUNG PRIOK</b></td>
                </tr>
                </tr>
				<tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. Nota</td>
                    <td COLSPAN="3" align="left">: $data[KD_UPER]  </td>
                </tr>
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. Doc</td>
                    <td COLSPAN="3" align="left">: $data[KD_PERMINTAAN]  </td>
                </tr>
                <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">Tgl. Proses</td>
                    <td COLSPAN="3" align="left">: $data[TGL_PROSES]  </td>
                </tr>    
              <tr>
                    <td COLSPAN="9"></td>
                    <td COLSPAN="2" align="left">No. faktur</td>
                    <td COLSPAN="3" align="left">: $data[NO_FAKTUR_PAJAK]  </td>
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="2"></td>
                    <td COLSPAN="12" align="right"><font size="14"><b>$ket3 $data[TIPE_REQUEST] </b>  </font></td>
                    
                </tr>
                <tr>
                <td></td>
                </tr>
                <tr>
                    <td COLSPAN="3"></td>
                    <td COLSPAN="5" align="left">$data[NAMA_PELANGGAN]</td>
                    <td colspan="2" ></td>
                    <td colspan="4" align="center" >  $dono $data[NO_DO]</td>
                </tr>
                <tr>
                    <td COLSPAN="3"></td>
                    <td COLSPAN="5" align="left">$data[NPWP_PELANGGAN]</td>
                    <td colspan="3"></td>
                    <td colspan="3" align="center" >$peb  $blno </td>
                </tr>
                <tr>
                    <td COLSPAN="3"></td>
                    <td COLSPAN="5" align="left">$data[ALAMAT_PELANGGAN]</td>
                    <td colspan="3"></td>
                    <td colspan="3" align="center" > $data[BONGKAR_MUAT]</td>
                </tr>
                <tr>
                    <td COLSPAN="3"></td>
                    <td COLSPAN="6" align="left">$data[NM_KAPAL] / $data[VOYAGE_IN] - $data[VOYAGE_OUT]</td>
                    <td colspan="4" ALIGN="right">$data[TANGGAL_TIBA] &nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                <td></td>
                </tr> 
                <tr>
                    <td width="150" align="left"> &nbsp;</td>
                    <td width="10">&nbsp;</td>
                    <td colspan="4">&nbsp;</td>
                    <td colspan="8"></td>
                </tr>    
                
                <tr>
                <td colspan="12"> $detail3</td>
                </tr>
                <tr>
                <td></td>
                </tr> 
                <tr>
                    <th colspan="3" width="150"><font size="8"><b>KETERANGAN</b></font></th>
                    <th width="65" align="left"><font size="8"><b>$ket1</b></font></th>
                    <th width="65" align="left"><font size="8"><b>$ket2</b></font></th>
                    <th width="32" align="center"><font size="8"><b>BOX</b></font></th>
                    <th width="32" align="center"><font size="8"><b>SIZE</b></font></th>
                    <th width="32" align="center"><font size="8"><b>TYPE</b></font></th>
                    <th width="32" align="center"><font size="8"><b>STS</b></font></th>
                    <th width="32" align="center"><font size="8"><b>HZ</b></font></th>
                    <th width="32" align="center"><font size="8"><b>HARI/SHIFT</b></font></th>
                    <th width="80" align="center"><font size="8"><b>TARIF</b></font></th>
                    <th width="40" align="center"><font size="8"><b>VAL</b></font></th>
                    <th width="115" align="center"><font size="8" ><b>JUMLAH</b></font></th>
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
$tbl .=<<<EOD
<table>                    
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right"><font size="10">Discount :</font></td>
                        <td width="120" colspan="2" align="right"><font size="10">0.00</font></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right"><font size="10">Administrasi :</font></td>
                        <td width="120" colspan="2" align="right"><font size="10">$data[ADM]</font></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right"><font size="10">Dasar Pengenaan Pajak :</font></td>
                        <td width="120" colspan="2" align="right"><font size="10">$data[TOTAL]</font></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right"><font size="10">Jumlah PPN :</font></td>
                        <td width="120" colspan="2" align="right"><font size="10">$data[PPN]</font></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right"><font size="10">Jumlah PPN Subsidi :</font></td>
                        <td width="120" colspan="2" align="right"><font size="10">0.00</font></td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                        <td width="180" colspan="3" align="right"><font size="10">Jumlah Dibayar :</font></td>
                        <td width="120" colspan="2" align="right"><font size="10">$data[KREDIT]</font></td>
                    </tr>
                    </table>
<table>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
</tr>

<tr>
<td>
<p>USER : $_SESSION[NAMA_PENGGUNA]</p>
</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>

<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>

<tr height="20">
<td >
<p>Nota sebagai faktur pajak berdasarkan Peraturan Dirjen Pajak</p>
</td>
</tr>
<tr>
<td>
<p>PER-33/PJ/2014</p>
</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</tr>
<tr height="200">
<td>
<p># $data[TERBILANG] Rupiah</p>
</td>
</tr>
<tr height="50">
<td align="right">
</td>
</tr>
<tr height="50">
<td align="right">
<p>$data[TGL_PROSES] </p>
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

/*$pdf->ln();
$pdf->SetFont('courier', '', 8);
$pdf->Write(0, 'Nota sebagai faktur pajak berdasarkan Peraturan Dirjen Pajak', '', 0, 'L', true, 0, false, false, 0);
$pdf->Write(0, 'Nomor 10/PJ/2010 tanggal 9 Maret 2010', '', 0, 'L', true, 0, false, false, 0);$pdf->ln();
$pdf->ln();
$pdf->SetFont('courier', '', 9);
$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);*/

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>