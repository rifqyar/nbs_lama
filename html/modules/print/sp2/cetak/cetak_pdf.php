<?php
//require "login_check.php";
//ivan ganteng

require_once(SITE_LIB.'tcpdf/config/lang/eng.php');
require_once(SITE_LIB.'tcpdf/tcpdf.php');

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {

	// Page footer
	public function Footer() {
		// Position at 10 mm from bottom
		$this->SetY(-20);
		// Set font
		$this->SetFont('courier', 'I', 22);
		// Page number
		//$this->Cell(0, 25, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// $pdf = new MYPDF('P', 'mm', 'A7', true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(1, 15, 1);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);


// ---------------------------------------------------------

$db = getDB();
$db2 = getDB("dbint");
$id_nota   = TRIM($_GET["pl"]);
$no_req   = TRIM($_GET["no_req"]);
$user = $_SESSION["NAMA_PENGGUNA"];

 $query_check = "SELECT a.status
          FROM NOTA_DELIVERY_H a
         WHERE a.ID_NOTA = '$id_nota'";
  $result_check = $db->query($query_check);
  $rowcheck   = $result_check->fetchRow();
  if($rowcheck['STATUS']!='P'){
    echo "Request Not Paid";
    die();
    header("location:".HOME);
  }

date_default_timezone_set('Asia/Jakarta');
$date=date('d M Y H:i:s');

    $query_dtl="SELECT A.ID_NOTA,
       A.ID_REQ,
       B.NO_CONTAINER ID_CONTAINER,
       C.TGL_DO,
       A.EMKL,
       A.VESSEL,
       B.VOYAGE_IN,
     a.VOYAGE_IN,
       a.VOYAGE_OUT,
       B.SIZE_CONT UKURAN,
       B.TYPE_CONT TYPE,
       B.STATUS_CONT STATUS,
       CASE WHEN A.TIPE_REQ='EXT' THEN
       b.TGL_END_STACK+1
       ELSE
       B.TGL_START_STACK END AS DISCH_DATE,
     A.NO_BL,
     A.NO_DO,
       TO_CHAR(CASE WHEN A.TIPE_REQ='EXT' THEN B.PLUG_OUT_EXT ELSE B.PLUG_OUT END,'DD/MM/RRRR HH24:MI:SS') PLUG_OUT,
       (SELECT C.TGL_JAM_TIBA
          FROM rbm_h C
         WHERE TRIM (C.NO_UKK) = TRIM (A.NO_UKK))
          TGL_START_STACK,
       B.TGL_DELIVERY TGL_END_STACK, B.PIN_NUMBER
  FROM REQ_DELIVERY_D B, NOTA_DELIVERY_H A, REQ_DELIVERY_H C
 WHERE TRIM (A.ID_REQ) = TRIM (B.ID_REQ)
        AND A.ID_REQ = C.ID_REQ
       AND TRIM (A.ID_NOTA) ='$id_nota'";
    
	$res = $db->query($query_dtl);
    $i = 0;
    $row2 = $res->getAll();

// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM REQ_DELIVERY_D A WHERE A.ID_REQ='".$no_req."'";
$data_jum = $db->query($query_jum)->fetchRow();
// $jum_data_page = 18;	//jumlah data dibatasi per page 18 data
// $jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas

// if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
// // $jum_page=1;
$total = $data_jum['JUM_DETAIL'];
$nourut = 1;

foreach($row2 as $rows)
{
    $nocont = strtoupper($rows[ID_CONTAINER]);
    $current = date('d-m-y h:i');
    $pinnumber = $rows['PIN_NUMBER'];

    $row = $db2->query("select OPERATOR_NAME, 
                                TO_CHAR(TO_DATE(ATA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_TIBA, 
                                TO_CHAR(TO_DATE(ETD,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_BERANGKAT 
                        from m_vsb_voyage WHERE VESSEL='".TRIM($rows[VESSEL])."' 
                          AND VOYAGE_IN='".TRIM($rows[VOYAGE_IN])."' 
                          AND VOYAGE_OUT='".TRIM($rows[VOYAGE_OUT])."'");
    $rowdtl = $row->fetchRow();

    // add a page
    $pdf->AddPage();
    // set font
    $pdf->SetFont('courier', '', 22);    
    $style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 4,
    'stretchtext' => 4
);
    $params = $pdf->serializeTCPDFtagParameters(array($pinnumber, 'C128C', '', '', 0, 0, '', $style, 'N'));
    //$params = $pdf->serializeTCPDFtagParameters(array('40144399300102444888207482244309', 'C128C', '', '', 0, 0, 0.2, array('position'=>'S', 'border'=>false, 'padding'=>4, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>false, 'font'=>'helvetica', 'fontsize'=>8, 'stretchtext'=>2), 'N'));

 $tbl = <<<EOD
<div style="width:767px; height:998px; border:1px solid #fff; font-family:Arial font-size:30px">
	<table width="100%" cellspacing="0" cellpadding="0" style="margin:0px; margin-top:5px; margin-bottom:10px; font-size:30px">
		<tbody>
			<tr>
			<td height="30" colspan="7"></td>
			</tr>
			<tr>
				<td width="15%">&nbsp;</td>
				<td width="39%">&nbsp;</td>
				<td width="14%" colspan="3"></td>
				<td width="17%" align="right"></td>
				<td width="17%">&nbsp;&nbsp; </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td rowspan="3">&nbsp;<tcpdf method="write1DBarcode" params="$params" /></td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2" align="center">$rows[ID_NOTA]</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td>&nbsp;</td>
				<td align="center"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td align="right">NO REQ :</td>
				<td>$rows[ID_REQ]</td>
			</tr>
			<tr>
				<td height="82" colspan="7">&nbsp;</td>
			</tr>
			<tr>
				<td height="20">&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td style="padding-left:45px" colspan="2"></td>
			</tr>
			<tr>
				<td height="25">&nbsp;</td>
				<td>
				<b style="font-size:46px">$rows[ID_CONTAINER]</b> <br/><b style="font-size:48px;">{ $rows[PIN_NUMBER] }</b>
				</td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2">
				<p align="center" style="font:Arial; font-size:46px">[O P U S S Y S T E M]</p>
				</td>
			</tr>
			<tr>
				<td height="20">&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>$rows[UKURAN] / $rows[TYPE] / $rows[STATUS]</td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2"> </td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>$rows[VESSEL]/$rows[VOYAGE] $rows[VOYAGE_OUT]</td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2">$vessel[TGL_TIBA] - $vessel[TGL_BERANGKAT]</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>$vessel[OPERATOR_NAME]</td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2">$rows[NO_BL]</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">&nbsp;</td>
				<td colspan="2">$rows[NO_DO]</td>

			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="4">$rows[EMKL]</td>
				<td colspan="2">$rows[DISCH_DATE]</td>
			</tr>
			<tr>
				<td height="15">&nbsp;</td>
				<td>$rows[EMKL]</td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2">$rows[TGL_END_STACK]</td>
			</tr>
			<tr height='30' valign='top'> <!--56-->
				<td height="15">&nbsp;</td>
				<td>Date Do:$rows[TGL_DO]</td>

				<td ></td>
				<td colspan="3"></td>
				<td colspan="2"></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td> <? if($rows[TYPE] == 'RFR') {?> <font style="font-size:34px">PLUG OUT</font> : <br/> <font style="font-size:34px">$rows[PLUG_OUT] </font> <? }?></td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td><?= date('d-m-y h:i');?></td>
				<td></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td height="39">&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
			</tr>
		</tbody>
	</table>
</div>
EOD;


/*echo $tbl;
die();*/

$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 12,    
    'stretchtext' => 4
);

$style2 = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    //'border' => true,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 12,    
    'stretchtext' => 4
);                 
        
                //Menampilkan Barcode dari nomor nota
                //$pdf->write1DBarcode("$notanya", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
                //Logo IPC
                //$pdf->Image('images/ipcblack.png', 19, 16, 9, 6, '', '', '', true, 72);
                // $pdf->write1DBarcode("$nocont", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
                $pdf->writeHTML($tbl, true, false, false, false, '');   
                $pdf->SetDrawColor(0);
                $pdf->SetTextColor(0);
                // Start Transformation
                $pdf->StartTransform();
                // Rotate 20 degrees counter-clockwise centered by (70,110) which is the lower left corner of the rectangle
                $pdf->Rotate(270); 
                //$pdf->write1DBarcode("$nocont", 'C128', -60, 55, '', 14, 0.4, $style, 'N'); 
                //$pdf->write1DBarcode("$nocont", 'C128', -60, -60, '', 14, 0.4, $style, 'N');  
                $pdf->StopTransform();  
                //$pdf->write1DBarcode("$nocont", 'C128', 137, 62, '', 8, 0.2, $style2, 'N');  
    $nourut++;
}


// for($pg=1; $pg<=$jum_page; $pg++) {

// }


$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 9);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>