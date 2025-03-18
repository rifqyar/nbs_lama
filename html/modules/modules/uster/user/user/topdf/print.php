<?php
	$kd_pbm	= $_GET["kd_pbm"]; 
	$db 	= getDB("storage");
	
	$query_list_ 	= "SELECT CONTAINER_STRIPPING.NO_CONTAINER,CONTAINER_STRIPPING.NO_REQUEST,SIZE_,TYPE_,NO_REQUEST_RECEIVING, BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_, PLACEMENT.ROW_ ROW_, PLACEMENT.TIER_ TIER_,REQUEST_STRIPPING.KETERANGAN,CONTAINER_STRIPPING.TGL_APPROVE FROM
		REQUEST_STRIPPING INNER JOIN   CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
		INNER JOIN MASTER_CONTAINER ON MASTER_CONTAINER.NO_CONTAINER=CONTAINER_STRIPPING.NO_CONTAINER
		INNER JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST AND LUNAS='YES' 
		LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING LEFT JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID                       
		WHERE KD_CONSIGNEE ='$kd_pbm' AND CONTAINER_STRIPPING.NO_CONTAINER NOT IN
		(SELECT  A.NO_CONTAINER FROM HISTORY_CONTAINER A,REQUEST_STRIPPING B WHERE A.NO_REQUEST=B .NO_REQUEST
		AND B.KD_CONSIGNEE ='$kd_pbm' AND KEGIATAN='REALISASI STRIPPING' )  AND (CONTAINER_STRIPPING.STATUS_REQ='PERP' OR CONTAINER_STRIPPING.STATUS_REQ IS NULL) ORDER BY TGL_REALISASI DESC";
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	$q_detail1= "SELECT EMKL,
					(SELECT COUNT(CONTAINER_STRIPPING.NO_CONTAINER) FROM REQUEST_STRIPPING INNER JOIN   CONTAINER_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
					INNER JOIN MASTER_CONTAINER ON MASTER_CONTAINER.NO_CONTAINER=CONTAINER_STRIPPING.NO_CONTAINER
					INNER JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST AND LUNAS='YES' 
					LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER  = PLACEMENT.NO_CONTAINER AND REQUEST_STRIPPING.NO_REQUEST_RECEIVING = PLACEMENT.NO_REQUEST_RECEIVING          
					LEFT JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID                     
					WHERE REQUEST_STRIPPING.KD_CONSIGNEE ='$kd_pbm' AND CONTAINER_STRIPPING.NO_CONTAINER NOT IN
					(SELECT  A.NO_CONTAINER FROM HISTORY_CONTAINER A,REQUEST_STRIPPING B WHERE A.NO_REQUEST=B .NO_REQUEST
					AND B.KD_CONSIGNEE ='$kd_pbm'
					AND KEGIATAN='REALISASI STRIPPING' )  AND (CONTAINER_STRIPPING.STATUS_REQ='PERP' OR CONTAINER_STRIPPING.STATUS_REQ IS NULL))JUMLAH FROM NOTA_STRIPPING WHERE KD_EMKL='$kd_pbm' GROUP BY EMKL";
		$r_detail1 = $db->query($q_detail1);
		$r_de11 = $r_detail1->FetchRow();
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'tcpdf_logo.jpg';
		$this->Image($image_file, 15, 5, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 8);
		// Title
		$this->Cell(0, 10, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
		
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,'Laporan History Alat' ,'DAILY PREVENTIVE MAINTENANCE');

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 6);

// add a page
$pdf->AddPage();

foreach ($row_list as $row){
	$n++;
	$html1 .= "<tr align='center'>
				<td align='center' >	".$n."</td>
				<td align='center' >	".$row["NO_CONTAINER"]."</td>
				<td align='center'>	".$row["SIZE_"]." - ".$row["TYPE_"]."</td>
				<td align='center'>	".$row["NO_REQUEST"]."</td>
				<td align='center'>	".$row["BLOK_"]." - ".$row["SLOT_"]." - ".$row["ROW_"]." - ".$row["TIER_"]."</td>
				<td align='center'>	".$row["KETERANGAN"]."</td>
				<td align='center'>	".$row["TGL_APPROVE"]."</td>
				</tr>";
}

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = '
<br><br>
<p style="font-size:40px;font-weight:bold"></p>
<hr><br><br>
Nama EMKL = '.$r_de11['EMKL'].' <br>
Total container yang belum realiasai stripping : '.$r_de11['JUMLAH'].' BOX <br>
<hr>
<div>
	<table border="1" align="center" cellspacing="1" cellpadding="1">
		
		<tr bgcolor="#006699">
			<th class="grid-header" align="center" width="15" style="font-size:6pt">NO</th>
			<th class="grid-header" align="center"  style="font-size:6pt">NO CONTAINER</th>
			<th class="grid-header" align="center"  style="font-size:6pt">SIZE-TYPE</th>
			<th class="grid-header" align="center"  width="70" style="font-size:6pt">NO REQUEST</th>
			<th class="grid-header" align="center"  style="font-size:6pt">B-S-R-T</th>
			<th class="grid-header" align="center"  style="font-size:6pt">TERTANDA</th>
			<th class="grid-header" align="center"  style="font-size:6pt">TANGGAL APPROVE</th>
		</tr>
		'.$html1.'
    </table>
	
	<br />
	<br />
	Tanggal Cetak : '.date('d M Y h:i:s').'
</div>';
//echo $html; die;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');




// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
		
?>