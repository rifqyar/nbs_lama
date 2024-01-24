<?php
//require "login_check.php";
$datasegment=$_GET['segment'];
$segment=explode("^",$datasegment);

$nocont=$segment[0];
$point=$segment[1];
//echo $nocont;die;
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
$db2 = getDB('dbint');
$p1 = $_GET['id'];
$jn = $_GET['jn'];

//ipctpk
//echo $p1; die;





// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM cyr_history WHERE CYR_HIST_CONTNO='$nocont' and CYR_HIST_POINT='$point'";
$data_jum = $db2->query($query_jum)->fetchRow();

$dthead="select b.NO_CONTAINER,b.SIZE_CONT||' / '||b.TYPE_CONT||' / '||b.STATUS as CONTSPEK,
b.VESSEL||' '|| b.VOYAGE_IN||' / '||B.VOYAGE_OUT as CONTVVD,
    to_char(to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'),'dd Mon yyyy hh24:mi') as CONTPLUGIN,
    to_char(to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss'),'dd Mon yyyy hh24:mi') as CONTPLUGOUT,
    a.CYR_PLUG_PINTEMP||'/'||a.CYR_PLUG_POUTTEMP AS TEMPS,
    b.E_I,
	b.POINT,
	b.BILLING_FLAG,
	b.BILLING_REQUEST_ID,
   round( (to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss')-to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'))*24,3 ) ||' / '||ceil((to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss')-to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'))*3) as SHIFT,
     b.VESSEL, b.VOYAGE_IN,B.VOYAGE_OUT,
    ceil((to_date(a.CYR_PLUG_POUTDATE||a.CYR_PLUG_POUTTIME,'yyyymmddhh24miss')-to_date(a.CYR_PLUG_PINDATE||a.CYR_PLUG_PINTIME,'yyyymmddhh24miss'))*3) as SHIFT_REAL, c.customer_name
 from pnoadm.cyr_pluginout a join opus_repo.m_cyc_container b on A.CYR_PLUG_CONTNO=B.NO_CONTAINER and A.CYR_PLUG_POINT=B.POINT 
 left join m_billing c on b.no_container =c.no_container and b.billing_request_id = c.no_request
 where a.cyr_plug_contno='$nocont' and a.CYR_PLUG_POINT='$point'";
 //echo $dthead;die;
 $qrhead=$db2->query($dthead);
 $rwhd=$qrhead->fetchRow();
 $conspek=$rwhd['CONTSPEK'];
 $convvd=$rwhd['CONTVVD'];
 $contemp=$rwhd['TEMPS'];
 $conplugin=$rwhd['CONTPLUGIN'];
 $conplugout=$rwhd['CONTPLUGOUT'];
 $conei=$rwhd['E_I'];
 $conshift=$rwhd['SHIFT'];
 $nocont = $rwhd['NO_CONTAINER'];
 $vessel = $rwhd['VESSEL'];
 $voyagein = $rwhd['VOYAGE_IN'];
 $voyageout = $rwhd['VOYAGE_OUT'];
 $shift_real = $rwhd['SHIFT_REAL'];
 
 
 // CARI NAMA PERUSAHAAN
 $billing_flag = $rwhd['BILLING_FLAG'];
 $billing_request_id = $rwhd['BILLING_REQUEST_ID'];
 
 $qnama = "select fc_nm_perusahaan('$billing_request_id', '$billing_flag') NM_PERUSAHAAN from dual";    
        $rnama = $db->query($qnama)->fetchRow();
        $corporate_name=$rnama['NM_PERUSAHAAN'];

		$query_stat="SELECT shift_reefer
  	FROM req_receiving_h a, req_receiving_d b, master_tarif_cont c
 	WHERE a.id_req = b.id_req and b.ID_CONT = c.ID_CONT and  jenis_biaya = 'PLUGIN_REFFER' and b.no_container = '$nocont' and a.vessel = '$vessel' and a.voyage_in='$voyagein' and a.voyage_out = '$voyageout'";
$resutl = $db->query($query_stat);
$rowstat = $resutl->fetchRow();

		
 //$query_stat="SELECT shift_reefer,
   //    CASE
     //     WHEN shift_reefer < $shift_real THEN 'KURANG BAYAR '|| ($shift_real-shift_reefer)||' SHIFT'
      //    WHEN shift_reefer > $shift_real THEN 'LEBIH BAYAR'
       //END
        //  STATUS_PAYMENT,
         // $shift_real-shift_reefer selisih,
          //($shift_real-shift_reefer)*tarif nominal
  	//FROM req_receiving_h a, req_receiving_d b, master_tarif_cont c
 	//WHERE a.id_req = b.id_req and b.ID_CONT = c.ID_CONT and  jenis_biaya = 'PLUGIN_REFFER' and b.no_container = '$nocont' and a.vessel = '$vessel' and a.voyage_in='$voyagein' and a.voyage_out = '$voyageout'";
//$resutl = $db->query($query_stat);
//$rowstat = $resutl->fetchRow();

 //$db2=
 $qrds="select to_char(to_date(a.CYR_HIST_DATE,'yyyymmdd'),'dd Mon yyyy') as CYR_HIST_DATE,
 to_char(to_date(a.CYR_HIST_TIME,'hh24miss'),'hh24:mi') CYR_HIST_TIME, CYR_HIST_TEMP, USR_N1ST_NM||' '||USR_LST_NM UNAME  from pnoadm.cyr_history A JOIN pnoadm.tcm_usr B ON a.CYR_HIST_USERID=b.USR_ID where CYR_HIST_CONTNO='$nocont' AND CYR_HIST_POINT='$point' ORDER BY CYR_HIST_DATE||CYR_HIST_TIME ASC";
 $dbs=$db2->query($qrds);
 $rdbs=$dbs->getAll();
 $i=1;
 $sdtl="";
 foreach ($rdbs as $rowds)
 {
	$ndate=$rowds['CYR_HIST_DATE'];
	$ndatetime=$rowds['CYR_HIST_TIME'];
	$ntemp=$rowds['CYR_HIST_TEMP'];
	$nuname=$rowds['UNAME'];
	
	$dtl="<tr>
			<td>$i</td>
			<td>$ndate</td>
			<td>$ndatetime</td>
			<td>$ntemp</td>
			<td>$nuname</td>
	</tr>";
	$sdtl=$sdtl.$dtl;
	$i++;
 }
 $ndates=date('d M Y H:i');
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('courier', '', 9);
	
	$tbl = <<<EOD
			<html>
			<body>
			<table>
                <tr>
                    <td COLSPAN="14" align="left"><b>$corporate_name</b></td>
                </tr>
                <tr>
                    <td COLSPAN="14" align="left"><b></b></td>
                </tr>
				<tr>
                    <td COLSPAN="14" align="left"><b></b></td>
                </tr>
				<tr>
                    <td COLSPAN="14" align="left"><b></b></td>
                </tr>
                <tr>
                <td COLSPAN="14" align="center"><b>REPORT POWER SUPPLY & REEFER MONITORING </b></td>
                </tr>
			</table>
			<br>
			<br>
			<center>
			<table>
				<tr><td align="right" >CONTAINER NO </td>
					<td>: $nocont</td>
				</tr>
				<tr><td align="right">SIZE / TYPE / STATUS </td>
					<td>: $conspek</td>
				</tr>
				<tr><td align="right">VESSEL / VOYAGE </td>
					<td >: $convvd</td>
				</tr>
				<tr><td align="right">SET TEMP (C) </td>
					<td >: $contemp</td>
				</tr>
				<tr><td align="right">PLUG IN </td>
					<td>: $conplugin</td>
				</tr>
				<tr><td align="right"> PLUG OUT </td>
					<td>: $conplugout</td>
				</tr>
				<tr><td align="right">EXPORT (E) / IMPORT (I) </td>
					<td>: $conei</td>
				</tr>
				<tr><td align="right">HOURS / SHIFT </td>
					<td>: $conshift</td>
				</tr>
				<tr><td align="right">Sudah Bayar (Shift) </td>
					<td>: $rowstat[SHIFT_REEFER]</td>
				</tr>
				<!--tr><td align="right">Status Payment </td>
					<td>: $rowstat[STATUS_PAYMENT]</td>
				</tr>
				<tr><td align="right">Nominal (Rp) </td>
					<td>: $rowstat[NOMINAL]</td-->
				</tr>
				<tr><td align="right">Customer Name </td>
					<td>: $rwhd[CUSTOMER_NAME]</td>
				</tr>
			</table>
				
                   </center>        
<BR><BR><BR>				   
				   <b>MONITORING REEFER</b>
				   <BR>
				   <table border="1" align="center">
				   <tr>
					<td width="10%"><b>No.</b></td>
					<td width="20%"><b>Date</b></td>
					<td width="20%"><b>Time</b></td>
					<td width="20%"><b>Temp</b></td>
					<td width="30%"><b>User</b></td>
				   </tr>
					$sdtl
				   </table>
				   <BR><BR><BR><BR><BR><BR>
				   
				   <table ALIGN="CENTER">
				   <tr>
					<td>&nbsp;<BR><BR>Pengguna Jasa<br><br><br><br><br><br>(.......................)</td><td>Pontianak,$ndates<br>Asisten Manager Perencanaan, Pengendalian & Operasi Pontianak<br><br><br><br><BR><BR><u>Yoca Vita Putra</u><br>NIPP. 282107332 </td>
				   </tr>
				   </table>
				   </body>
				   </html>
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