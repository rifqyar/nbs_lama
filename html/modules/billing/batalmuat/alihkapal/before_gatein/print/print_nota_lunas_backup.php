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
$no_req = $_GET['no_req'];
$id_user=$_SESSION['PENGGUNA_ID'];
$query="SELECT ID_PROFORMA, ID_REQ, TO_CHAR(TGL_REQ,'dd-mm-yyyy') TGL_REQUEST FROM nota_batalmuat_h WHERE TRIM(ID_REQ) = TRIM('$no_req') and status!='X'";
$hasil=$db->query($query);
$hasil_=$hasil->fetchRow();
$notanya=$hasil_['ID_PROFORMA'];
$param_tgl=$hasil_['TGL_REQUEST'];
/*PT. PTP*/
    /*ipctpk*/
	//$qnama = "select fc_nm_perusahaan('$param_tgl', 'BM') NM_PERUSAHAAN from dual";
    $qnama = "select nm_perusahaan from date_reference where to_date('$param_tgl','dd/mm/rrrr') between cut_date and off_date order by cut_date desc";
	/**/
	//ECHO $qnama;die;
    $rnama = $db->query($qnama)->fetchRow();
    $rname=$rnama['NM_PERUSAHAAN'];
/*PT. PTP*/
$query="UPDATE NOTA_BATALMUAT_H SET TGL_CETAK_NOTA=SYSDATE WHERE ID_PROFORMA='$notanya'";
$db->query($query);

//query container
$string_container="";
$query_container="SELECT NO_CONTAINER, JNS_CONT FROM REQ_BATALMUAT_D WHERE ID_REQ='$no_req' ORDER BY NO_CONTAINER";
$res_container = $db->query($query_container);
$i=0;
$row_container=$res_container->getAll();
foreach($row_container as $row_containers) {
	$string_container.="(".($i+1).") ".$row_containers['NO_CONTAINER']." ".$row_containers['JNS_CONT']." ";
	$i++;
}

$query= "SELECT b.ID_PROFORMA,b.ID_REQ, b.EMKL, b.ALAMAT, b.NPWP, b.TGL_REQ, b.JENIS, b.VESSEL, b.VOYAGE_IN, b.VOYAGE_OUT,TO_CHAR(b.BAYAR, '999,999,999,999') AS TOTAL, TO_CHAR(b.ADM, '999,999,999,999') AS ADM_NOTA, TO_CHAR(b.PPN, '999,999,999,999') AS PPN, TO_CHAR(b.TOTAL, '999,999,999,999') AS TAGIHAN,
		(SELECT NAME FROM TB_USER a WHERE b.PENGGUNA = a.ID) ID_USER
		 FROM NOTA_batalmuat_h b
		 WHERE b.ID_PROFORMA='$notanya' and status!='X'";

$data = $db->query($query)->fetchRow();
$date=date('d M Y H:i:s');

$kegiatan = $data["JENIS"];

$sql_keg = "SELECT KEGIATAN FROM MASTER_PROSES_KEGIATAN WHERE ID_KEG = TRIM('$kegiatan')";
$keg = $db->query($sql_keg)->fetchRow();
$kegiat = $keg['KEGIATAN'];

//print_r("SELECT * from TB_NOTA_DELIVERY_D A, TB_NOTA_DELIVERY_H B, MASTER_BARANG C WHERE A.ID_NOTA='$p1'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_CONT");die;

$query_dtl="SELECT TO_CHAR(a.TARIF, '999,999,999,999') AS TARIF, 
                             TO_CHAR(a.TOTAL, '999,999,999,999') AS SUB_TOTAL, 
                             a.KETERANGAN, 
                             a.HZ, 
                             a.JUMLAH_CONT JUMLAH_CONT, 
                             TO_DATE(a.TGL_START_STACK,'dd/mm/yyyy') START_STACK, 
                             TO_DATE(a.TGL_END_STACK,'dd/mm/yyyy') END_STACK, 
                             b.UKURAN SIZE_, 
                             b.TYPE TYPE, 
                             b.STATUS 
                      FROM NOTA_BATALMUAT_D_TMP a, master_barang b 
                      WHERE a.ID_CONT = b.KODE_BARANG and a.ID_REQ = '$no_req' AND KETERANGAN <> 'ADM'";
	$res = $db->query($query_dtl);
	//print_R($res);die;
	$i=0;
	//unset($detail);
	 $row2=$res->getAll();
	foreach($row2 as $rows) {
            
            if($rows[TGL_START_STACK]<>''){
                        $detail .='
					
			<tr><td colspan="3" width="50"><font size="6">'.'<b>'.$rows[KETERANGAN].'</b>'.'</font></td>                        
                        <td width="15" align="center"><font size="6">'.'<b>'.$rows[JUMLAH_CONT].'</b>'.'</font></td>                              
                        <td width="50" align="left"><font size="6">'.'<b>'.$rows[SIZE_]."".$rows[TYPE]."".$rows[STATUS].'</b>'.'</font></td>                                
                        <td width="10" align="center"><font size="6">'.'<b>'.$rows[HZ].'</b>'.'</font></td>                        
                        <td width="43" align="right"><font size="6">'.'<b>'.$rows[TARIF].'</b>'.'</font></td>
                        <td width="20" align="center"><b>IDR</b></td>
                        <td width="50" align="right"><font size="6">'.'<b>'.$rows[SUB_TOTAL].'</b>'.'</font></td>        
                        </tr>  
                        <tr>
                        <td colspan="8"><font size="6"><i><b>('.$rows[TGL_START_STACK].' s/d '.$rows[TGL_END_STACK].')'.$rows[JUMLAH_HARI].' hari</i></b></font></td></td>
                        </tr>

		';
            }
            
            else
                
                
            $detail .='
					
			<tr><td colspan="3" width="50"><font size="6">'.'<b>'.$rows[KETERANGAN].'</b>'.'</font></td>                        
                        <td width="15" align="center"><font size="6">'.'<b>'.$rows[JUMLAH_CONT].'</b>'.'</font></td>                               
                        <td width="50" align="left"><font size="6">'.'<b>'.$rows[SIZE_]."".$rows[TYPE]."".$rows[STATUS].'</b>'.'</font></td>                                
                        <td width="10" align="center"><font size="6">'.'<b>'.$rows[HZ].'</b>'.'</font></td>                        
                        <td width="43" align="right"><font size="6">'.'<b>'.$rows[TARIF].'</b>'.'</font></td>
                        <td width="20" align="center"><b>IDR</b></td>
                        <td width="50" align="right"><font size="6">'.'<b>'.$rows[SUB_TOTAL].'</b>'.'</font></td>                           
                        </tr> ';                                                                                     
                        
		$i++;
	}


// jumlah detail barangnya
$query_jum="SELECT COUNT(1) JUM_DETAIL FROM NOTA_BATALMUAT_D A WHERE A.ID_REQ='$no_req'";
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
			<table border='0'>                              
                
                <tr>
                    <td colspan="2" align="left"><font size="8"><b>$data[ID_PROFORMA]</b></font></td>                    
                <td colspan="2" width="200" align="left"><font size="8"><b>$date</b></font></td>
                </tr>
                
		<tr>                    
                    <td COLSPAN="2" align="left"><font size="8"><b>$data[ID_REQ]</b></font></td>
                
                </tr>                                                                    
                
                   
                <tr>
                <td></td>
                </tr>
                <tr>                    
                    <td COLSPAN="2" align="left"><font size="6"><b>$rows[KETERANGAN]</b></font></td>
                    
                </tr>
                
                <tr>                    
                    <td width ="200" COLSPAN="2" align="left"><font size="6"><b>$data[EMKL]</b></font></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="2" align="left"><font size="6"><b>$data[NPWP]</b></font></td>                    
                </tr>
                <tr>                    
                    <td COLSPAN="2" width ="250" align="left"><font size="6"><b>$data[ALAMAT]</b></font></td>					
                </tr>
                <tr>                    
                    <td COLSPAN="2" align="left"><font size="6"><b>$data[VESSEL] / $data[VOYAGE_IN]-$data[VOYAGE_OUT]</b></font></td>                    
                </tr>    			                
                
                <tr>
                <td></td>
                </tr>
                
                <tr>
                    <th colspan="3" width="50"><font size="6"><b>KETERANGAN</b></font></th>
                    
                    <th width="15" align="center"><font size="6"><b>BX</b></font></th>
                
                    <th width="50" align="left"><font size="6"><b>CONTENT</b></font></th>
                    
                
                    <th width="10" align="center"><font size="6"><b>HZ</b></font></th>
                    
                    <th width="43" align="center"><font size="6"><b>TARIF</b></font></th>
                    <th width="20" align="center"><font size="6"><b>VAL</b></font></th>
                    <th width="43" align="center"><font size="6" ><b>JUMLAH</b></font></th>
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
                        <td colspan="6" align="right"><b>Discount :</b></td>
                        <td width="50" colspan="2" align="right"><b>0.00</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="6" align="right"><b>Administrasi :</b></td>
                        <td colspan="2" align="right"><b>$data[ADM_NOTA]</b></td>
                    </tr>
                    <tr>
                      
                        <td colspan="6" align="right"><b>Dasar Peng. Pajak :</b></td>
                        <td colspan="2" align="right"><b>$data[TAGIHAN]</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="6" align="right"><b>Jumlah PPN :</b></td>
                        <td colspan="2" align="right"><b>$data[PPN]</b></td>
                    </tr>
                    <tr>
                        
                        <td colspan="6" align="right"><b>Jumlah PPN Subsidi :</b></td>
                        <td colspan="2" align="right"><b>0.00</b></td>
                    </tr>
                    <tr>
                     
                        <td colspan="6" align="right"><b>Jumlah Dibayar :</b></td>
                        <td colspan="2" align="right"><b>$data[TOTAL]</b></td>
                    </tr>  
        
        
        
                    </table>
                    <br>
                    <br>
					<b>No. Container:</b>
					<br>
					<b>$string_container</b>
                    <br>
                    <br>
                    <br><font size="8"><b>$rname<b></font><br>

                
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
                        <td colspan="4" align="left"><font size="8"> Rp. <b>$data[TOTAL]</b></font></td>
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


        $pdf->write1DBarcode("$notanya", 'C128', 0, 0, '', 18, 0.4, $style, 'N');
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

$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();$pdf->ln();
$pdf->SetFont('helvetica', 'B', 6);
//Close and output PDF document
$pdf->Output('sample.pdf', 'I');
?>