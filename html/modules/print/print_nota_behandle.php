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
		//$this->Cell(0, 10, 'hal '.$this->getAliasNumPage().' dari '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf = new MYPDF('P', 'mm', 'A7', true, 'UTF-8', false);

// set header and footer fonts
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(1, 3, 0);
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->setPrintHeader(false);

//set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(TRUE, 10);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

$db = getDB();
$p1 = $_GET['pl'];
//var_dump($_GET);
//die();




$query="SELECT a.*, b.*, SYSDATE AS tgl_proses,
       TO_CHAR (a.invoice_date, 'DD-MM-YYYY') tgl_nota2,
       TO_CHAR (a.adm_nota, '999,999,999,999,990.00') adm_notan,
       TO_CHAR (a.ppn, '999,999,999,999,999.00') ppnn,
       TO_CHAR (a.jumlah, '999,999,999,999,999.00') tagihann,
       TO_CHAR (a.total, '999,999,999,999,999.00') totaln,
       UPPER (b.tipe_req) tipe_request, UPPER (c.NAME) AS nama,
       TO_CHAR (b.tgl_request, 'DD-MM-YYYY') tgl_request
  FROM nota_behandle_h a JOIN req_behandle_h b
       ON a.id_proforma = '$p1' AND TRIM (a.id_req) = TRIM (b.id_req)
       LEFT JOIN tb_user c ON c.ID = a.user_id";
	   
$data = $db->query($query)->fetchRow();
$param_tgl=$data['TGL_REQUEST'];
 $date=date('d M Y H:i:s');
/*PTP*/
	/*ipctpk*/
    //$query = "select fc_nm_perusahaan('$param_tgl','BH') NM_PERUSAHAAN from dual";
    // echo $query; die();
    //$rq    = $db->query($query)->fetchRow();
	$qnama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc";
    $rq    = $db->query($qnama)->fetchRow();
	/**/
    $nm_perusahaan = $rq['NM_PERUSAHAAN'];
/*PTP*/
 //$query3="SELECT NO_CONTAINER from BH_DETAIL_REQUEST A, NOTA_BEHANDLE_H B WHERE B.ID_NOTA='$p1'  AND A.ID_REQUEST=B.ID_REQUEST";
 $query3="SELECT NO_CONTAINER from NOTA_BEHANDLE_D WHERE ID_PROFORMA='$p1' group by NO_CONTAINER";
 $res3 = $db->query($query3);
 $row3=$res3->getAll();
 	foreach($row3 as $rows2) {
		if($detail2<>'')	$detail2.=',';
		$detail2 .= $rows2[NO_CONTAINER];
	}
 
 $row3=$res3->getAll();
//print_r("SELECT * from TB_NOTA_DELIVERY_D A, TB_NOTA_DELIVERY_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_CONT");die;

/**Fauzan start modify amount materai 17 Oktober 2019 **/
	$query_materai="SELECT SUB_TOTAL AS TOTAL FROM NOTA_BEHANDLE_D WHERE ID_PROFORMA='$p1' AND KETERANGAN='MATERAI'";
	$hasil_materai=$db->query($query_materai);
	$hasil_materai_=$hasil_materai->fetchRow();
	if($hasil_materai_['TOTAL']>0){
		$bea_materai=$hasil_materai_['TOTAL'];
	}else{
		$bea_materai=0;
	}	
	
/**Fauzan end modify amount materai 17 Oktober 2019 **/

$query_dtl="SELECT A.*,C.*, to_char(A.TARIF,'999,999,999,999,999.00') AS TARIFN, to_char(A.SUB_TOTAL,'999,999,999,999,999.00') AS SUB_TOTALN 
			from NOTA_BEHANDLE_D A, NOTA_BEHANDLE_H B, MASTER_BARANG C WHERE A.ID_PROFORMA='$p1'  AND A.ID_PROFORMA=B.ID_PROFORMA AND C.KODE_BARANG=A.ID_BARANG";
	$res = $db->query($query_dtl);
	//print_R($res);die;
	$i=0;
	//unset($detail);
	 $row2=$res->getAll();
	foreach($row2 as $rows) {
					
		$detail .= '
		<tr>    <td width="50" align="left"><font size="6">'.'<b>'.$rows[KETERANGAN].'</b>'.'</font></td>                        
                        <td width="10" align="center"><font size="6">'.'<b>'.$rows[JUMLAH_CONT].'</b>'.'</font></td>    
                        <td width="40" align="left"><font size="6">'.'<b>'.$rows[UKURAN]."".$rows[TYPE]."".$rows[STATUS].'</b>'.'</font></td> 
                            
                        <td width="8" align="left"><font size="6">'.'<b>'.$rows[HZ].'</b>'.'</font></td>                        
                        <td width="65" align="right"><font size="6">'.'<b>'.$rows[TARIFN].'</b>'.'</font></td>
                        <td width="15" align="center"><b>IDR</b></td>
                        <td width="55" align="right"><font size="6">'.'<b>'.$rows[SUB_TOTALN].'</b>'.'</font></td>        
                        </tr>                        
						
		';
		$i++;
	}

// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM NOTA_BEHANDLE_D A WHERE A.ID_PROFORMA='$p1' " ;
$data_jum = $db->query($query_jum)->fetchRow();
$jum_data_page = 18;	//jumlah data dibatasi per page 18 data
$jum_page = ceil($data_jum['JUM_DETAIL']/$jum_data_page);	//hasil bagi pembulatan ke atas
if(($data_jum[JUM_DETAIL]%$jum_data_page)>10 || ($data_jum[JUM_DETAIL]%$jum_data_page)==0)	$jum_page++;	//jika pada page terakhir jumlah data melebihi 12, tambah 1 page lagi
$jum_page=1;
for($pg=1; $pg<=$jum_page; $pg++) {
	// add a page
	$pdf->AddPage();
	// set font
	$pdf->SetFont('helvetica', '', 6);
	
	$tbl = <<<EOD
       
			<table>
                
		<tr>                    
                    <td COLSPAN="2" align="left"><font size="8"><b>$data[ID_PROFORMA]</b></FONT></td>
                 <td><font size="8"><b>$data[ID_USER] <b>|</b> $date</b></font></td>
                </tr> 
                <tr>                    
                    <td COLSPAN="3" align="left"><font size="8"><b>$data[ID_REQUEST]</b></font>  </td>
                </tr>                  
                
                <tr><td></td></tr>
                
                <tr>                    
                    <td COLSPAN="1" align="left"><font size="6"><b>BEHANDLE $data[TIPE_REQUEST]</b></font></td>                    
                </tr>
                                                             
                <tr>                    
                    <td COLSPAN="4" align="left"><font size="6"><b>$data[EMKL]</b></font></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left"><font size="6"><b>$data[NPWP]</b></font></td>                    
                </tr>
                <tr>                    
                    <td COLSPAN="6" align="left"><font size="6"><b>$data[ALAMAT_EMKL]</b></font></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="4" align="left"><font size="6"><b>$data[VESSEL] / $data[VOYAGE]</b></font></td>                    
                </tr>                 
                
                <tr>
                <td></td>
                </tr>
                               
                                
                <tr>                    
                    <td width="200"><b>NO. CONTAINER : $detail2</b></td>                                        
                </tr>
                
                
                <tr>
                    <th width="50" align="left"><font size="6"><b>KETERANGAN</b></font></th>                    
                    <th width="10" align="center"><font size="6"><b>BX</b></font></th>                                   
                    <th width="40" align="left"><font size="6"><b>CONTENT</b></font></th>                               
                    <th width="10" align="left"><font size="6"><b>HZ</b></font></th>                    
                    <th width="65" align="center"><font size="6"><b>TARIF</b></font></th>
                    <th width="15" align="center"><font size="6"><b>VAL</b></font></th>
                    <th width="65" align="center"><font size="6" ><b>JUMLAH</b></font></th>
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
                        <td colspan="2" align="right"><b>Discount :</b></td>
                        <td width="75" align="right"><b>0.00</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="2" align="right"><b>Administrasi :</b></td>
                        <td align="right"><b>$data[ADM_NOTAN]</b></td>
                    </tr>
                    <tr>
                      
                        <td colspan="2" align="right"><b>Dsr Peng. Pajak :</b></td>
                        <td  align="right"><b>$data[TAGIHANN]</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="2" align="right"><b>Jumlah PPN :</b></td>
                        <td align="right"><b>$data[PPNN]</b></td>
                    </tr>
                    <tr>                        
                        <td colspan="2" align="right"><b>Jumlah PPN Subsidi :</b></td>
                        <td align="right"><b>0.00</b></td>
                    </tr>
					<tr>
                        
                        <td colspan="2" align="right"><b>Jumlah Materai :</b></td>
                        <td align="right"><b>$bea_materai</b></td>
                    </tr>
                    <tr>
                     
                        <td colspan="2" align="right"><font size="8"><b>Jumlah Dibayar :</b></font></td>
                        <td align="right"><font size="8"><b>$data[TOTALN]</b></font></td>
                    </tr>            
        
                    </table>
                    <br><font size="8"><b>$nm_perusahaan<b></font><br>
                
EOD;

$tbl .=<<<EOD
<table> 
                    <tr>
                    <td colspan="8">
                        <hr style="border: dashed 2px #C0C0C0" color="#FFFFFF" size="6" width="700">
                    </td>
                    </tr>
                    <tr>
                    <td colspan="8">
                        <i>form untuk Bank</i>
                    </td>
                    </tr>                   
                    <tr>
                    <td colspan="8">
                        &nbsp;
                    </td>
                    </tr>                   
                    <tr>
                    <td colspan="8">
                        &nbsp;
                    </td>
                    </tr>                   
                    <tr>
                        <td colspan="3" align="right"><font size="8"><b>Nomor Proforma :</b></font></td>
                        <td colspan="4" align="left"><font size="8"> <b>$data[ID_PROFORMA]</b></font></td>
                    </tr>               
                    <tr>
                        <td colspan="3" align="right"><font size="8"><b>Customer :</b></font></td>
                        <td colspan="4" align="left"><font size="8"> <b>$data[EMKL]</b></font></td>
                    </tr>               
                    <tr>
                     
                        <td colspan="3" align="right"><font size="8"><b>Jumlah Dibayar :</b></font></td>
                        <td colspan="4" align="left"><font size="8"> Rp. <b>$data[TOTALN]</b></font></td>
                    </tr>               
        
                    </table>                                      
          
                
EOD;

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

        //Menampilkan Barcode dari nomor nota
        $pdf->write1DBarcode("$data[ID_PROFORMA]", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
        //Logo IPC
        //$pdf->Image('images/ipc2.jpg', 50, 7, 20, 10, '', '', '', true, 72);	


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