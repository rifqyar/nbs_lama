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

/*$query_detail = "SELECT DET.*, case when hz='Y' 
        then case when floor(imo_class) = 1 or floor(imo_class) = 7 then tarif*2 
                       when imo_class is null then tarif*3
                else tarif end  
          else tarif end as fare_,
    case when hz='Y' then case when floor(imo_class) = 1 or floor(imo_class) = 7 then tarif*jml*2
        when imo_class is null then tarif*jml*3
        else tarif*jml end    else tarif*jml end ttl_fare, tar.val, tar.ID_CONT FROM (SELECT ID_VSB_VOYAGE,
       ID_KATEGORI,
       SIZE_CONT,
       TYPE_CONT,
       STATUS_CONT,
       HZ,
       JML,
       E_I,
       'O' OI,
       M.HEIGHT_CONT,
       HEIGHT_CONT_TERM,
       TY_CC,
       KODE_BARANG,
       IMO_CLASS,
       CASE
          WHEN E_I = 'E' THEN 'LOAD'
          WHEN E_I = 'I' THEN 'DISCH'
          ELSE 'TRANS'
       END
          keg,
       ''
  FROM    (  SELECT a.id_vsb_voyage,
                    a.id_kategori,
                    a.e_i,
                    a.size_cont,
                    a.type_cont,
                    a.status_cont,
                    a.hz,
                    a.height_cont,
                    A.extra_tools,
                    CASE
                       WHEN a.specialtype = 'OOG' THEN 'OOG'
                       WHEN a.extra_tools = 'Y' THEN 'OOG'
                       ELSE 'BIASA'
                    END
                       AS HEIGHT_CONT_TERM,
                    a.imo_class,
                    b.ty_cc,
                    COUNT (1) jml
               FROM bil_stv_dtmp a JOIN bil_stv_jenisalat b ON a.alat = b.alat
              WHERE a.id_vsb_voyage = '$id_vsb' and a.id_kategori = '$id_kat'
           GROUP BY a.id_vsb_voyage,
                    a.id_kategori,
                    a.e_i,
                    a.size_cont,
                    a.type_cont,
                    a.status_cont,
                    a.hz,
                    a.height_cont,
                    A.extra_tools,
                    a.specialtype,
                    a.imo_class,
                    b.ty_cc
           ORDER BY a.e_i,
                    a.size_cont,
                    a.type_cont,
                    a.status_cont) m
       JOIN
          master_barang n
       ON     m.size_cont = n.ukuran
          AND m.type_cont = n.TYPE
          AND CASE
                 WHEN m.status_cont = 'Full' THEN 'FCL'
                 WHEN m.status_cont = 'Empty' THEN 'MTY'
              END = n.STATUS
          AND m.HEIGHT_CONT_TERM = n.HEIGHT_CONT) det LEFT JOIN master_tarif_cont tar
          ON det.KODE_BARANG = tar.ID_CONT AND tar.JENIS_BIAYA = 'BM' AND JENIS_BIAYA2 = det.ty_cc
          ORDER BY det.KEG";*/

/*$query_detail  = "SELECT DET.*, case when hz_CONT='Y' 
        then case when floor(HZ_LABEL) = 1 or floor(HZ_LABEL) = 7 then tarif*2 
                       when HZ_LABEL is null then tarif*3
                else tarif end  
          else tarif end as fare_,
    case when hz_CONT='Y' then case when floor(HZ_LABEL) = 1 or floor(HZ_LABEL) = 7 then tarif*jml*2
        when HZ_LABEL is null then tarif*jml*3
        else tarif*jml end    else tarif*jml end ttl_fare, tar.val, tar.ID_CONT FROM (
        SELECT ID_VSB_VOYAGE,
       ID_KATEGORI,
       SIZE_CONT,
       TYPE_CONT,
       STS_CONT,
       HZ_CONT,
       JML,
       EI,
       'O' OI,
       M.HGH_CONT,
       HGH_CONT_TERM,
       NVL(TY_CC,'-') TY_CC,
       nvl(KODE_BARANG,'-') KODE_BARANG,
       hz_label,
       m.keg,
       ''
  FROM    (  SELECT a.id_vsb_voyage,
                    a.id_kategori,
                    a.keg,
                    a.ei,
                    a.size_cont,
                    a.type_cont,
                    a.sts_cont,
                    a.hz_cont,
                    a.hgh_cont,
                    a.HGH_CONT_TERM,
                    a.hz_label,
                    a.ty_cc,
                    A.QTY_RPSTV jml
               FROM bil_stv_dtmp a
              WHERE a.id_vsb_voyage = '$id_vsb' and a.id_kategori = '$id_kat'
           ORDER BY a.ei,
                    a.size_cont,
                    a.type_cont,
                    a.sts_cont) m
       LEFT JOIN
          master_barang n
       ON     m.size_cont = n.ukuran
          AND m.type_cont = n.TYPE
          AND CASE
                 WHEN m.sts_cont = 'Full' THEN 'FCL'
                 WHEN m.sts_cont = 'Empty' THEN 'MTY'
              END = n.STATUS
          AND m.HGH_CONT_TERM = n.HEIGHT_CONT) det LEFT JOIN master_tarif_cont tar
          ON det.KODE_BARANG = NVL(tar.ID_CONT,'-') AND (tar.JENIS_BIAYA = 'BM' or tar.JENIS_BIAYA = 'HATCH') AND NVL(tar.JENIS_BIAYA2,'-') = det.ty_cc
          ORDER BY det.KEG";*/
$query_detail = "SELECT det.id_vsb_voyage,
            det.id_kategori,
            det.size_cont,
            det.type_cont,
            det.sts_cont,
            det.hz_cont,
            sum(det.jml) JML,
            det.hgh_cont_term,
            det.ty_cc,
            det.keg,
--case when hz_CONT='Y' 
--        then case when floor(HZ_LABEL) = 1 or floor(HZ_LABEL) = 7 then tarif*2 
--                       when HZ_LABEL is null then tarif*3
--                else tarif end  
--          else tarif end as fare_,
--    case when hz_CONT='Y' then case when floor(HZ_LABEL) = 1 or floor(HZ_LABEL) = 7 then tarif*jml*2
--        when HZ_LABEL is null then tarif*jml*3
--        else tarif*jml end    else tarif*jml end ttl_fare, 
        tarif FARE_,
        case when substr(hz_label,0,1) = 1 or substr(hz_label,0,1) = 7 
        then substr(hz_label,0,1)
        else '-' end 
        hz_label,
        sum(tarif*jml) ttl_fare,
        tar.val
        FROM (
        SELECT ID_VSB_VOYAGE,
       ID_KATEGORI,
       SIZE_CONT,
       TYPE_CONT,
       STS_CONT,
       HZ_CONT,
       JML,
       EI,
       'O' OI,
       M.HGH_CONT,
       HGH_CONT_TERM,
       NVL(TY_CC,'-') TY_CC,
       nvl(KODE_BARANG,'-') KODE_BARANG,
       hz_label,
       m.keg,
       ''
  FROM    (  SELECT a.id_vsb_voyage,
                    a.id_kategori,
                    a.keg,
                    a.ei,
                    a.size_cont,
                    a.type_cont,
                    a.sts_cont,
                    a.hz_cont,
                    a.hgh_cont,
                    a.HGH_CONT_TERM,
                    a.hz_label,
                    a.ty_cc,
                    A.QTY_RPSTV jml
               FROM bil_stv_dtmp a
              WHERE a.id_vsb_voyage = '$id_vsb' and a.id_kategori = '$id_kat'
           ORDER BY a.ei,
                    a.size_cont,
                    a.type_cont,
                    a.sts_cont) m
       LEFT JOIN
          master_barang n
       ON     m.size_cont = n.ukuran
          AND m.type_cont = n.TYPE
          AND CASE
                 WHEN m.sts_cont = 'Full' THEN 'FCL'
                 WHEN m.sts_cont = 'Empty' THEN 'MTY'
              END = n.STATUS
          AND m.HGH_CONT_TERM = n.HEIGHT_CONT) det LEFT JOIN master_tarif_cont tar
          ON det.KODE_BARANG = NVL(tar.ID_CONT,'-') AND (tar.JENIS_BIAYA = 'BM' or tar.JENIS_BIAYA = 'HATCH') AND NVL(tar.JENIS_BIAYA2,'-') = det.ty_cc
          GROUP BY det.id_vsb_voyage,
            det.id_kategori,
            det.size_cont,
            det.type_cont,
            det.sts_cont,
            det.hz_cont,det.hgh_cont_term,
            det.ty_cc,
            det.keg, tarif, 
            case when substr(hz_label,0,1) = 1 or substr(hz_label,0,1) = 7 
            then substr(hz_label,0,1)
            else '-' end
            , tar.val
          ORDER BY det.KEG";
/*$query_hatch = "select 'HATCH' KEG, 'O' OI, SUM(JUMLAH) JML,tarif FARE_, val, tarif*COUNT(JUMLAH) TTL_FARE from bil_stv_hm 
          a left join master_tarif_cont on jenis_biaya = 'HATCH' 
   WHERE a.id_vsb_voyage='$id_vsb' AND ACTIVITY='O' 
   GROUP BY '','','',val,tarif";
$rwhatch = $db->query($query_hatch)->fetchRow();  

 $tbl3 =<<<EOD
    <tr>
                    
                    <td colspan="9" width="100" align="left">$rwhatch[KEG]</td>
                    <td align="center" width="40"></td>
                    <td align="center" width="40">$rwhatch[OI]</td>
                    <td align="center" width="60"></td>
                    <td align="center" width="20"></td>
                    <td  width="40" align="center"></td>
                    <td  width="40" align="center"></td>
                    <td  width="80" align="center"></td>
                    <td  align="center" width="50">$rwhatch[JML]</td>
                    <td colspan="4" width="50" align="right">$rwhatch[FARE_]</td>
                    <td colspan="2" width="50" align="right">$rwhatch[VAL]</td>
                    <td colspan="5" align="right" width="80">$rwhatch[TTL_FARE]</td>
                    <td colspan="2"></td>
    </tr>
                
EOD;*/

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
                    <td align="center" width="40">$row[OI]</td>
                    <td align="center" width="60">$row[TY_CC]</td>
                    <td align="center" width="20">$row[SIZE_CONT]</td>
                    <td  width="40" align="center">$row[TYPE_CONT]</td>
                    <td  width="40" align="center">$row[STS_CONT]</td>
                    <td  width="80" align="center">$hz</td>
                    <td  align="center" width="50">$row[JML]</td>
                    <td colspan="4" width="50" align="right">$row[FARE_]</td>
                    <td colspan="2" width="50" align="right">$row[VAL]</td>
                    <td colspan="5" align="right" width="80">$row[TTL_FARE]</td>
                    <td colspan="2"></td>
    </tr>
                
EOD;

}
          $query_header = "select vessel, voyage_in, voyage_out, to_char(to_date(ata,'rrrrmmddhh24miss'),'dd/mm/rrrr hh24:mi:ss') ata  from m_vsb_voyage@dbint_link where id_vsb_voyage = '$id_vsb'";
          $rquery_header = $db->query($query_header);
          $rw = $rquery_header->fetchRow();

          //print_r($rw); die();
$pdf->AddPage();
    // set font
    $pdf->SetFont('courier', '', 9);

    //getADM
    $query_adm = "select tarif 
    from master_tarif_cont where jenis_biaya='ADMBM' AND val='USD'";
    $rwadm = $db->query($query_adm)->fetchRow();


  $total_adm = $rwadm[TARIF];
  $total_fare = $total_fare+$rwhatch[TTL_FARE]+$rwadm[TARIF];
  $total_ppn = round($total_fare*0.1,2);
  $total_all = $total_ppn+$total_fare;
  $query_sum="select to_char($total_fare,'9,999,999,999,999.00') DPP_,
      to_char($total_ppn,'9,999,999,999,999.00') PPN_,
      to_char($total_all,'9,999,999,999,999.00') TTL_,
      to_char($total_adm,'9,999,999,999,999.00') ADM_
      from dual";
  $rwsum = $db->query($query_sum)->fetchRow();

	
	$date = date('d-M-Y H:i');
	$tbl = <<<EOD
			<table>
                <tr height="25">
                    <td COLSPAN="29" align="left"></td>
                </tr>
                        <tr>
                    <td COLSPAN="29" align="left"><b>PT. PELABUHAN INDONESIA II (PERSERO)</b></td>
                </tr>
                <tr>
                    <td COLSPAN="29" align="left"><b>CABANG TANJUNG PRIOK</b></td>
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
                    <td COLSPAN="29" align="center"><font size="10"><b>NOTA PERHITUNGAN PELAYANAN JASA<br>
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
                   
               <tr height="30">
                    <td COLSPAN="29" align="left"><hr></td>
                </tr>
                
                

                <tr>
                    <td colspan="9" width="100"><b>KETERANGAN</b></td>
                    <td align="center" width="40"><b>E/I</b></td>
                    <td align="center" width="40"><b>O/I</b></td>
                    <td align="center" width="60"><b>CRANE</b></td>
                    <td width="20"><b>SZ</b></td>
                    <td width="40" align="center"><b>TY</b></td>
                    <td width="40" align="center"><b>ST</b></td>
                    <td width="80" align="center" colspan="4"><b>HZ</b></td>
                    <td align="center" width="50"><b>BOX</b></td>
                    <td colspan="4" width="50" align="right"><b>TARIF</b></td>
                    <td colspan="2" width="50" align="right"><b>VAL</b></td>
                    <td colspan="5" align="right" width="80"><b>JUMLAH</b></td>
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
                  <td colspan="7"  align="right">Discount :</td>
                            <td colspan="7" align="right"></td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="7"  align="right">Administrasi :</td>
                            <td colspan="7" align="right">$rwsum[ADM_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="7"  align="right">Dasar Pengenaan Pajak :</td>
                            <td colspan="7" align="right">$rwsum[DPP_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="7"  align="right">Jumlah PPN :</td>
                            <td colspan="7" align="right">$rwsum[PPN_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="7"  align="right">Jumlah PPN Subsidi :</td>
                            <td colspan="7" align="right">0.00</td>
                  <td colspan="2"></td>
                </tr>
                <tr>
                  <td colspan="13"></td>
                  <td colspan="7"  align="right">Jumlah Dibayar :</td>
                            <td colspan="7" align="right">$rwsum[TTL_]</td>
                  <td colspan="2"></td>
                </tr>
                <tr height="100"><td></td></tr>
                
    </table>
EOD;


// echo $tbl;
// die();


	//$pdf->Image('images/ipc2.jpg', 50, 7, 20, 10, '', '', '', true, 72);
	$pdf->writeHTML($tbl, true, false, false, false, '');





//$pdf->SetFont('courier', '', 6);
//$pdf->Write(0, $data[TGL_NOTA], '', 0, 'R', true, 0, false, false, 0);

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 6);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>