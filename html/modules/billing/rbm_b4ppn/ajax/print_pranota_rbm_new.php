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
//$font = $this->addTTFfont("BLUEHIGD.TTF");

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
$id_vsb = $_GET['id'];
$id_kat = $_GET['kategori'];
$id_user = $_SESSION["NAMA_PENGGUNA"];
$q_cek = "select case when OI='O' then 'USD' else 'IDR' end valas from bil_stv_h where id_vsb_voyage = '$id_vsb'";
$r_cek = $db->query($q_cek)->fetchRow();
$valas = $r_cek['VALAS'];

$p_pranota = array("in_idvsb"   => $id_vsb,
                   "in_kat"     => $id_kat,
                   "in_val"     => $valas,
                   "in_user"    => $id_user,
                   "in_pnstv"   => "");

$q_pranota = "declare begin prc_bilstvproforma(:in_idvsb,:in_kat,:in_val,:in_user,:in_pnstv); end;";
$db->query($q_pranota,$p_pranota);
$idpnstv = $p_pranota['in_pnstv'];

if($valas == 'USD') {
    $query_detail = "SELECT NT_CT KEG, EI, REMARKS OI, TY_CC, DECODE(HGH_CONT_TERM,'OOG',SIZE_CONT||'/'||HGH_CONT_TERM, SIZE_CONT) SIZE_CONT, TYPE_CONT, STS_CONT, HZ_LABEL, QTY_RPSTV JML, FARE_, 
    to_char(TTL_FARE_USD,'9,999,999,999,999.00')TTL_FARE,
    VAL, TTL_FARE_USD, CASE WHEN REMARKS = 'O' THEN 'USD' ELSE 'IDR' END VAL_FARE
    from bil_pnstv_d where id_pnstv='$idpnstv' AND NT_CT <> 'ADM'";
}
else {
        $query_detail = "SELECT NT_CT KEG, EI, REMARKS OI, TY_CC, DECODE(HGH_CONT_TERM,'OOG',SIZE_CONT||'/'||HGH_CONT_TERM, SIZE_CONT) SIZE_CONT, TYPE_CONT, STS_CONT, HZ_LABEL, QTY_RPSTV JML, FARE_, 
    to_char(TTL_FARE,'9,999,999,999,999.00')TTL_FARE,
    VAL, TTL_FARE_USD, CASE WHEN REMARKS = 'O' THEN 'USD' ELSE 'IDR' END VAL_FARE
    from bil_pnstv_d where id_pnstv='$idpnstv' AND NT_CT <> 'ADM'";

}


$rquery_detail = $db->query($query_detail);
$rws = $rquery_detail->getAll();
//print_r($rws);
$total_fare = 0;
foreach ($rws as $row) {
     if($row[HZ_CONT]=='Y'){ $hz = $row[HZ_CONT]."(".$row[HZ_LABEL].")";} else {$hz = $row[HZ_CONT];}
     $total_fare += $row[TTL_FARE];
    $tbl2 .=<<<EOD
    <tr>
                    
                    <td colspan="9" width="100" align="left">$row[KEG]</td>
                    <td align="center" width="40">$row[EI]</td>
                    <td align="center" width="30">$row[OI]</td>
                    <td align="center" width="40">$row[TY_CC]</td>
                    <td align="center" width="50">$row[SIZE_CONT]</td>
                    <td  width="40" align="center">$row[TYPE_CONT]</td>
                    <td  width="40" align="center">$row[STS_CONT]</td>
                    <td  width="40" align="center">$hz</td>
                    <td  align="center" width="50">$row[JML]</td>
                    <td colspan="4" width="50" align="right">$row[FARE_]</td>
                    <td  width="50" align="right">$row[VAL_FARE]</td>
                    <td colspan="5" align="right" width="110">$row[TTL_FARE]</td>
                    <td colspan="2" width="50" align="right">$row[VAL_FARE]</td>
                    <td colspan="2"></td>
    </tr>
                
EOD;

}
          $query_header = "select vessel, voyage_in, voyage_out, to_char(to_date(ata,'rrrrmmddhh24miss'),'dd/mm/rrrr hh24:mi:ss') ata  from m_vsb_voyage@dbint_link where id_vsb_voyage = '$id_vsb'";
          $rquery_header = $db->query($query_header);
          $rw = $rquery_header->fetchRow();
          /*PT. PTP*/
  $param_tgl  = $rw['ATA'];
  $qnama = "select nm_perusahaan from date_reference where cut_date <= to_date('$param_tgl','dd/mm/rrrr hh24:mi:ss') order by cut_date desc";
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
          //print_r($rw); die();
$pdf->AddPage();
    // set font
    $pdf->SetFont('courier', '', 9);

    //getADM
    if($valas == 'USD') {
        $query_adm = "select TTL_FARE_USD TTL_FARE from bil_pnstv_d where id_pnstv='$idpnstv' AND NT_CT = 'ADM'";
    }
    else{
        $query_adm = "select TTL_FARE from bil_pnstv_d where id_pnstv='$idpnstv' AND NT_CT = 'ADM'";
    }
    $rwadm = $db->query($query_adm)->fetchRow();


  $total_adm = $rwadm['TTL_FARE'];
  $total_fare = $total_fare+$rwhatch[TTL_FARE]+$rwadm[TARIF];
  $total_ppn = round($total_fare*0.1,2);
  $total_all = $total_ppn+$total_fare;
if($valas == 'USD') {
  $query_sum="select to_char(dpp_usd,'9,999,999,999,999.00') DPP_,
      to_char(ppn_usd,'9,999,999,999,999.00') PPN_,
      to_char(ttl_usd,'9,999,999,999,999.00') TTL_,
      to_char(ttl_,'9,999,999,999,999.00') TTL_IDR,
      to_char('$total_adm','9,999,999,999,999.00') ADM_,
      to_char(KURS,'9,999,999,999,999.00') KURS,
      to_char(KURS_DATE,'dd/mm/rrrr hh24:mi:ss') KURS_DATE
      from bil_pnstv_h where id_vsb_voyage = '$id_vsb' and sts_rpstv = '$id_kat'";
}
else {
    $query_sum="select to_char(dpp_,'9,999,999,999,999.00') DPP_,
      to_char(ppn_,'9,999,999,999,999.00') PPN_,
      to_char(ttl_,'9,999,999,999,999.00') TTL_,
      to_char('$total_adm','9,999,999,999,999.00') ADM_,
      to_char(KURS,'9,999,999,999,999.00') KURS,
      to_char(KURS_DATE,'dd/mm/rrrr hh24:mi:ss') KURS_DATE
      from bil_pnstv_h where id_vsb_voyage = '$id_vsb' and sts_rpstv = '$id_kat'";
}
  $rwsum = $db->query($query_sum)->fetchRow();
if($valas == 'USD') {
    $lbl_kurs = 'Kurs';
    $ket_kurs = 'Rp. '.$rwsum[KURS].'*';
    $disclaimer = "<p>(*) Kurs dapat berubah mengikuti tanggal cetak pranota</p>";
    $label_idr = "Jumlah IDR :";
    $ket_ttlidr = "Rp. ".$rwsum[TTL_IDR];
}
else{
    $lbl_kurs = '';
    $ket_kurs = '';
    $disclaimer = '';
    $label_idr = '';
    $ket_ttlidr = '';
}
	
	$date = date('d-M-Y H:i');
	$tbl = <<<EOD
			<table>
                <tr height="25">
                    <td COLSPAN="29" align="left"></td>
                </tr>
                        <tr>
                    <td COLSPAN="29" align="left"><b>$corporate_name</b></td>
                </tr>
                <tr>
                    <td COLSPAN="29" align="left"><b>$branch_name</b></td>
                </tr>
                <tr>
                    <td colspan="17"></td>
                    <td colspan="4" align="right">No. Invoice</td>
                    <td align="right">:</td>
                    <td colspan="7" align="left"></td>
                </tr>
                <tr>
                    <td colspan="17"></td>
                    <td colspan="4" align="right">No. Doc</td>
                    <td  align="right">:</td>
                    <td colspan="7" align="left">$id_vsb</td>
                </tr>
                
                <tr>
                    <td COLSPAN="17"></td>
                    <td COLSPAN="4" align="right">Tgl.Proses</td>
                    <td  align="right">:</td>
                    <td COLSPAN="7" align="left">$date</td>
                </tr>
                <tr>
                    <td COLSPAN="17"></td>
                    <td COLSPAN="4" align="right">Halaman</td>
                    <td align="right">:</td>
                    <td COLSPAN="7" align="left">1/1</td>
                </tr>
                <tr height="30px">
                    <td COLSPAN="29" align="left"></td>
                </tr>
                <tr>
                    <td COLSPAN="29" align="center"><font size="10"><b>PRANOTA PERHITUNGAN PELAYANAN JASA<br>
BONGKAT / MUAT</b></font></td>
                </tr>
               <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
                <tr>
                    <td colspan="4">Customer</td>
                    <td>:</td>
                    <td colspan="3" align="left"></td>
                    <td colspan="9"></td>
                    <td colspan="3">No DO</td>
                    <td colspan="3">:</td>
                    <td colspan="4" align="left"></td>
                </tr> 
                <tr>
                    <td colspan="4">NPWP</td>
                    <td colspan="0">:</td>
                    <td colspan="3" align="left"></td>
                    <td  colspan="9"></td>
                    <td colspan="3">No BL/PEB</td>
                    <td colspan="3">:</td>
                    <td colspan="4" align="left"></td>
                </tr>
                <tr>
                    <td colspan="4">Alamat</td>
                    <td colspan="0">:</td>
                    <td colspan="3" align="left"></td>
                    <td  colspan="9"></td>
                    <td colspan="3">Disch/Load</td>
                    <td colspan="3">:</td>
                    <td colspan="4" align="left">Disch/Load</td>
                </tr>
                <tr>
                    <td colspan="4">Vessel/Voyage</td>
                    <td >:</td>
                    <td colspan="9" align="left">$rw[VESSEL] / $rw[VOYAGE_IN] - $rw[VOYAGE_OUT]</td>
                    <td colspan="3" ></td>
                    <td colspan="3">Tgl Tiba</td>
                    <td colspan="3">:</td>
                    <td colspan="4" align="left">$rw[ATA]</td>
                </tr>
                <tr>
                    <td colspan="4">$lbl_kurs</td>
                    <td >:</td>
                    <td colspan="9" align="left">$ket_kurs</td>
                    <td colspan="3" ></td>
                    <td colspan="3"></td>
                    <td colspan="3">:</td>
                    <td colspan="4" align="left"></td>
                </tr>
                   
               <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
                
                

                <tr>
                    <td colspan="9" width="100"><b>KETERANGAN</b></td>
                    <td align="center" width="40"><b>E/I</b></td>
                    <td align="center" width="30"><b>O/I</b></td>
                    <td align="center" width="40"><b>CRANE</b></td>
                    <td align="center" width="50"><b>SZ</b></td>
                    <td width="40" align="center"><b>TY</b></td>
                    <td width="40" align="center"><b>ST</b></td>
                    <td width="40" align="center"><b>HZ</b></td>
                    <td align="center" width="50"><b>BOX</b></td>
                    <td colspan="4" width="50" align="right"><b>TARIF</b></td>
                    <td width="50" align="right"><b>VAL</b></td>
                    <td colspan="5" align="right" width="110"><b>JUMLAH</b></td>
                    <td colspan="2" width="50" align="right"><b>VAL</b></td>
                    <td colspan="2"></td>
                </tr>
                    <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
                $tbl2

                
                <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                        </tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr><td></td></tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="8"  align="right">Discount :</td>
                            <td colspan="7" align="right"></td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="8"  align="right">Administrasi :</td>
                            <td colspan="7" align="right">$rwsum[ADM_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="8"  align="right">Dasar Pengenaan Pajak :</td>
                            <td colspan="7" align="right">$rwsum[DPP_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="8"  align="right">Jumlah PPN :</td>
                            <td colspan="7" align="right">$rwsum[PPN_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                    <td colspan="8"  align="right">Jumlah PPN Subsidi :</td>
                            <td colspan="7" align="right">0.00</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="8"  align="right">Jumlah Dibayar :</td>
                            <td colspan="7" align="right">$rwsum[TTL_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="8"  align="right">$label_idr</td>
                            <td colspan="7" align="right">$ket_ttlidr</td>
                  <td colspan="2"></td>
                </tr>
                <tr height="100"><td></td></tr>
        </table>
        <br/>
        $disclaimer
EOD;


 //echo $tbl;
 //die();


	//$pdf->Image('images/ipc2.jpg', 50, 7, 20, 10, '', '', '', true, 72);
	$pdf->writeHTML($tbl, true, false, false, false, '');





//$pdf->SetFont('courier', '', 6);
//$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 6);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>