<?php

	$tgl_awal	= $_GET["tgl_awal"]; 
	$tgl_akhir	= $_GET["tgl_akhir"]; 
	$jenis		= $_GET["jenis"];
	//echo $tgl_awal;die;
	$db 	= getDB("storage");

	$query_list_ 	= "SELECT  * FROM (
					SELECT NOTA_DELIVERY.NO_NOTA NO_NOTA, NOTA_DELIVERY.NO_REQUEST, TGL_NOTA, 'DELIVERY'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, DELIVERY_KE ASAL,
					ND.KETERANGAN KETERANGAN, ND.START_STACK TGL_AWAL, ND.END_STACK TGL_AKHIR, ND.TARIF, ND.BIAYA, ND.COA
					FROM NOTA_DELIVERY INNER JOIN REQUEST_DELIVERY ON NOTA_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
					LEFT JOIN NOTA_DELIVERY_D ND ON NOTA_DELIVERY.NO_NOTA = ND.ID_NOTA
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')
					UNION
					SELECT NOTA_RECEIVING.NO_NOTA NO_NOTA, NOTA_RECEIVING.NO_REQUEST, TGL_NOTA, 'RECEIVING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, RECEIVING_DARI ASAL,
					ND.KETERANGAN KETERANGAN, TO_DATE('') TGL_AWAL, TO_DATE('') TGL_AKHIR, ND.TARIF, ND.BIAYA, ND.COA
					FROM NOTA_RECEIVING INNER JOIN REQUEST_RECEIVING ON NOTA_RECEIVING.NO_REQUEST = REQUEST_RECEIVING.NO_REQUEST
					LEFT JOIN NOTA_RECEIVING_D ND ON NOTA_RECEIVING.NO_NOTA = ND.NO_NOTA
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')  
					UNION
					SELECT NOTA_STRIPPING.NO_NOTA NO_NOTA, NOTA_STRIPPING.NO_REQUEST, TGL_NOTA, 'STRIPPING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, STRIPPING_DARI ASAL,
					ND.KETERANGAN KETERANGAN, ND.START_STACK TGL_AWAL, ND.END_STACK TGL_AKHIR, ND.TARIF, ND.BIAYA, ND.COA
					FROM NOTA_STRIPPING INNER JOIN REQUEST_STRIPPING ON NOTA_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST
					LEFT JOIN NOTA_STRIPPING_D ND ON NOTA_STRIPPING.NO_NOTA = ND.NO_NOTA
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd')  
					UNION
					SELECT NOTA_STUFFING.NO_NOTA NO_NOTA, NOTA_STUFFING.NO_REQUEST, TGL_NOTA, 'STUFFING'  AS KEGIATAN, EMKL, TO_CHAR(TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN, STUFFING_DARI ASAL,
					ND.KETERANGAN KETERANGAN, ND.START_STACK TGL_AWAL, ND.END_STACK TGL_AKHIR, ND.TARIF, ND.BIAYA, ND.COA
					FROM NOTA_STUFFING INNER JOIN REQUEST_STUFFING ON NOTA_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
					LEFT JOIN NOTA_STUFFING_D ND ON NOTA_STUFFING.NO_NOTA = ND.NO_NOTA
					WHERE TRUNC(TGL_NOTA) > TO_DATE('$tgl_awal','yyyy-mm-dd') AND TRUNC(TGL_NOTA) < TO_DATE('$tgl_akhir','yyyy-mm-dd'))
					WHERE KEGIATAN LIKE  '%$jenis%' ";
					
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 



require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'tcpdf_logo.jpg';
		$this->Image($image_file, 15, 5, 30, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 15);
		// Title
		//$this->Cell(0, 10, '', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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
$pdf = new MYPDF('L', 'mm', 'a4', true, 'UTF-8', false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,'Laporan History Alat' ,'DAILY PREVENTIVE MAINTENANCE');

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// ---------------------------------------------------------

// set font
$pdf->SetMargins('10px', '21px', '10px','21px');
	//$pdf->SetHeaderMargin('21');
	$pdf->SetFooterMargin('10px');
	
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, '20px');  
	// set font
	$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$n=0;
$transfer = 0;
$before = " ";
$rowspan = 0;
foreach ($row_list as $row){
	
	//print_r($n);die;
	if($row["ASAL"] == "TPK"){
		
		$no_request = $row["NO_REQUEST"];
		//echo $no_request; die;
		//$cont_ungate = get_detail($no_request);
		$query_cek_ = "SELECT * FROM (
								SELECT CONTAINER_DELIVERY.NO_REQUEST NO_REQUEST, CONTAINER_DELIVERY.NO_CONTAINER NO_CONTAINER, BORDER_GATE_IN.TGL_IN TGL
								FROM CONTAINER_DELIVERY LEFT JOIN BORDER_GATE_IN ON CONTAINER_DELIVERY.NO_REQUEST = BORDER_GATE_IN.NO_REQUEST
								UNION
								SELECT CONTAINER_RECEIVING.NO_REQUEST NO_REQUEST, CONTAINER_RECEIVING.NO_CONTAINER NO_CONTAINER, BORDER_GATE_IN.TGL_IN TGL
								FROM CONTAINER_RECEIVING LEFT JOIN BORDER_GATE_IN ON CONTAINER_RECEIVING.NO_REQUEST = BORDER_GATE_IN.NO_REQUEST
								UNION
								SELECT CONTAINER_STRIPPING.NO_REQUEST NO_REQUEST, CONTAINER_STRIPPING.NO_CONTAINER NO_CONTAINER, BORDER_GATE_IN.TGL_IN TGL
								FROM CONTAINER_STRIPPING LEFT JOIN BORDER_GATE_IN ON CONTAINER_STRIPPING.NO_REQUEST = BORDER_GATE_IN.NO_REQUEST
								UNION
								SELECT CONTAINER_STUFFING.NO_REQUEST NO_REQUEST, CONTAINER_STUFFING.NO_CONTAINER NO_CONTAINER, BORDER_GATE_IN.TGL_IN TGL
								FROM CONTAINER_STUFFING LEFT JOIN BORDER_GATE_IN ON CONTAINER_STUFFING.NO_REQUEST = BORDER_GATE_IN.NO_REQUEST)
								WHERE NO_REQUEST LIKE '$no_request'";
					$res_ungate = $db->query($query_cek_);
					$row_ungate = $res_ungate->getAll();				
		
		$jumlah = count($row_ungate);
		foreach($row_ungate as $rowsa){
			$html3 .= " ".$rowsa["NO_CONTAINER"]." ";
		}
		$html2 = $jumlah." container belum Gate IN (".$html3.") <br/>";
		//echo $html2; die;
		$status = "Not Ready to Transfer";
		 if($jumlah == 0){
			$status = "Ready to Transfer";
			 $html2 = " ";
		 }
	}
	else{
		$status = "Ready to Transfer";
	}
	
	
	$html1 .= "";
	//echo $row["NO_NOTA"]."-".$before."<br/>";
	//$rowspan++;
	if($row["NO_NOTA"] != $before){
		$n++;
		$html1 .= '<tr><td>'.$n.'</td>
				<td>	'.$row["NO_NOTA"].'</td>
				<td>	'.$row["NO_REQUEST"].'</td>
				<td>	'.$row["KEGIATAN"].'</td>
				<td>'.$row["EMKL"].'</td>
				<td></td><td></td><td></td><td></td><td></td><td></td>
				<td>	'.$row["TOTAL_TAGIHAN"].'</td>
				<td>	'.$status.'</td>		
				<td>	'.$html2.'</td></tr>';
				if($status == "Ready to Transfer"){
					$transfer++;
				}
				
	}	
	$before = $row["NO_NOTA"];
	$html1 .= ' <tr>
				<td></td><td></td><td></td><td></td><td></td>
				<td>	'.$row["KETERANGAN"].'</td>
				<td>	'.$row["TGL_AWAL"].'</td>
				<td>	'.$row["TGL_AKHIR"].'</td>
				<td>	'.$row["TARIF"].'</td>
				<td>	'.$row["BIAYA"].'</td>
				<td>	'.$row["COA"].'</td></tr>';
	//echo $html1; die;
	
	$html2 = "";
	$html3 = "";
}

$tglawal = date("d-M-Y", strtotime($tgl_awal));
$tglakhir = date("d-M-Y", strtotime($tgl_akhir));

$html = '
<p style="font-size:30px;font-weight:bold"> Transfer Nota ke SIMKEU </p>
<hr>
<div>
Total Kegiatan Periode : '.$tglawal.' s/d '.$tglakhir.' = '.$n.' Kegiatan <br/>
Total Nota Yang ditransfer Periode : '.$tglawal.' s/d '.$tglakhir.' = '.$transfer.' Kegiatan <br/>
</div>
<hr>
<div>
	<table border="1" style="font-size:20px">
    	<tr>
        	<th width="3%">
            	NO
            </th>
            <th width="10%">
            	No. Nota
            </th>
            <th width="9%">
            	No. Request
            </th>
			<th width="6%">
            	Kegiatan
            </th>
			<th width="9%">
            	Pemilik Barang
            </th>
            <th width="9%">
            	Komponen Nota
            </th>
            <th width="6%">
            	Tgl Awal
            </th>
			<th width="6%">
            	Tgl Akhir
            </th>
            <th width="6%">
            	Tarif
            </th>
			<th width="6%">
            	Biaya
            </th>
			<th width="4%">
            	COA
            </th>
			<th width="8%">
            	Total Tagihan
            </th>
			<th width="6%">
            	Status
            </th>
            <th width="13%">
            	Keterangan
            </th>
        </tr>
		'.$html1.'
    </table>
</div>';
//echo $html; die;
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


// output some RTL HTML content
$html = '<div style="text-align:center">Indonesia Port Corporation</div>';
$pdf->writeHTML($html, true, false, true, false, '');

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
		
?>