 <?php
 // print_r('jozz');die();
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportLaporanBeaMaterai-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$db   = getDB("storage");
$tig=3000;
$enm=6000;
  $id_req = $_GET["id_req"];
  
  $id_time= $_GET["id_time"];
  $id_time2= $_GET["id_time2"];
   //echo $id_time2; die();

if ($id_time!=NULL && $id_time2!=NULL && $id_req!=NULL) {
 $query_list_="SELECT A.TGL_SIMPAN, A.NO_NOTA_MTI,C.TARIF, A.NO_FAKTUR_MTI,B.NO_NPWP_PBM, B.NM_PBM,A.ADMINISTRASI,A.TOTAL,A.PPN,A.KREDIT,A.BANK_ID,A.NO_PERATURAN, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_DETAIL C INNER JOIN ITPK_NOTA_HEADER A ON C.NO_NOTA_MTI = A.NO_NOTA_MTI INNER JOIN MST_PELANGGAN B ON
                    A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM  WHERE (C.tipe_layanan ='MATERAI_N_USTER' or C.tarif='3000' or C.tarif='6000') AND A.TGL_SIMPAN BETWEEN TO_DATE('$id_time','YYYY-MM-DD') AND TO_DATE('$id_time2','YYYY-MM-DD') AND A.NO_PERATURAN IS NOT NULL AND A.NO_PERATURAN='$id_req' AND A.STATUS <> 5 ORDER BY A.TGL_SIMPAN DESC";
   // print_r($query_list_);die();   
}else if ($id_time!=NULL && $id_time2!=NULL ) {
  $query_list_="SELECT A.TGL_SIMPAN, A.NO_NOTA_MTI,C.TARIF, A.NO_FAKTUR_MTI,B.NO_NPWP_PBM, B.NM_PBM,A.ADMINISTRASI,A.TOTAL,A.PPN,A.KREDIT,A.BANK_ID,A.NO_PERATURAN, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_DETAIL C INNER JOIN ITPK_NOTA_HEADER A ON C.NO_NOTA_MTI = A.NO_NOTA_MTI INNER JOIN MST_PELANGGAN B ON
                    A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM  WHERE (C.tipe_layanan ='MATERAI_N_USTER' or C.tarif='3000' or C.tarif='6000') AND A.TGL_SIMPAN BETWEEN TO_DATE('$id_time','YYYY-MM-DD') AND TO_DATE('$id_time2','YYYY-MM-DD') AND  A.STATUS <> 5 ORDER BY A.TGL_SIMPAN DESC";//print_r($query_list_);die(); 
}else if ($id_req!=NULL && $id_time !=NULL ) {
  $query_list_="SELECT A.TGL_SIMPAN, A.NO_NOTA_MTI,C.TARIF, A.NO_FAKTUR_MTI,B.NO_NPWP_PBM, B.NM_PBM,A.ADMINISTRASI,A.TOTAL,A.PPN,A.KREDIT,A.BANK_ID,A.NO_PERATURAN, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_DETAIL C INNER JOIN ITPK_NOTA_HEADER A ON C.NO_NOTA_MTI = A.NO_NOTA_MTI INNER JOIN MST_PELANGGAN B ON
                    A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM  WHERE (C.tipe_layanan ='MATERAI_N_USTER' or C.tarif='3000' or C.tarif='6000') AND TO_CHAR(A.TGL_SIMPAN,'YYYY-MM-DD')='$id_time' AND A.NO_PERATURAN IS NOT NULL AND A.NO_PERATURAN='$id_req' AND A.STATUS <> 5 ORDER BY A.TGL_SIMPAN DESC";
}else if ($id_time!=NULL) {
  $query_list_="SELECT A.TGL_SIMPAN, A.NO_NOTA_MTI,C.TARIF, A.NO_FAKTUR_MTI,B.NO_NPWP_PBM, B.NM_PBM,A.ADMINISTRASI,A.TOTAL,A.PPN,A.KREDIT,A.BANK_ID,A.NO_PERATURAN, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_DETAIL C INNER JOIN ITPK_NOTA_HEADER A ON C.NO_NOTA_MTI = A.NO_NOTA_MTI INNER JOIN MST_PELANGGAN B ON
                    A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM  WHERE (C.tipe_layanan ='MATERAI_N_USTER' or C.tarif='3000' or C.tarif='6000') AND TO_CHAR(A.TGL_SIMPAN,'YYYY-MM-DD')='$id_time' AND A.NO_PERATURAN IS NOT NULL AND A.STATUS <> 5 ORDER BY A.TGL_SIMPAN DESC";
   // print_r($query_list_);die();      
}elseif ($id_req!=NULL) {
 $query_list_="SELECT A.TGL_SIMPAN, A.NO_NOTA_MTI,C.TARIF, A.NO_FAKTUR_MTI,B.NO_NPWP_PBM, B.NM_PBM,A.ADMINISTRASI,A.TOTAL,A.PPN,A.KREDIT,A.BANK_ID,A.NO_PERATURAN, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
     FROM ITPK_NOTA_DETAIL C INNER JOIN ITPK_NOTA_HEADER A ON C.NO_NOTA_MTI = A.NO_NOTA_MTI INNER JOIN MST_PELANGGAN B ON
                    A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM  WHERE (C.tipe_layanan ='MATERAI_N_USTER' or C.tarif='3000' or C.tarif='6000') AND A.NO_PERATURAN='$id_req' AND A.NO_PERATURAN IS NOT NULL AND A.STATUS <> 5 ORDER BY A.TGL_SIMPAN DESC"; 
   // print_r($query_list_);die();   
}else{

      $query_list_=" SELECT A.TGL_SIMPAN, A.NO_NOTA_MTI,C.TARIF, A.NO_FAKTUR_MTI,B.NO_NPWP_PBM, B.NM_PBM,A.ADMINISTRASI,A.TOTAL,A.PPN,A.KREDIT,
                    A.BANK_ID,A.NO_PERATURAN, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
                    FROM ITPK_NOTA_DETAIL C INNER JOIN ITPK_NOTA_HEADER A ON C.NO_NOTA_MTI = A.NO_NOTA_MTI INNER JOIN MST_PELANGGAN B ON
                    A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM WHERE (C.tipe_layanan ='MATERAI_N_USTER' or C.tarif='3000' or C.tarif='6000') AND 
                    A.STATUS <> 5 ORDER BY A.TGL_SIMPAN DESC";  
}



        
  $result_list_ = $db->query($query_list_);
 $row_list   = $result_list_->getAll();
 // print_r($result_list_);die();
?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
            <tr>
                <th colspan="13">LAPORAN BEA MATERAI</th>
            </tr>
            <tr>
                <th colspan="13">PT. MULTI TERMINAL INDONESIA</th>
            </tr>
           
                              <tr style=" font-size:10pt">
                                 <th valign="top" class="grid-header"  style="font-size:10pt">No</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">INVOICE DATE</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">NO NOTA</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">NO FAKTUR</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">NPWP</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">COSTUMER</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">ADMINISTRASI</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">DPP</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">PPN</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">MATERAI</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">TOTAL</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">BANK</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">NO PERATURAN</th>
                                

                              </tr>
                              <?php $i=0;$n=0;
                foreach($row_list as $rows){ $i++;?>
                              <tr>
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows["TGL_SIMPAN"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_NOTA_MTI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_FAKTUR_MTI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_NPWP_PBM"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NM_PBM"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["ADMINISTRASI"]; ?></td>    
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TOTAL"]; ?></td>
                                   <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["PPN"]; ?></td>
                                   <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TARIF"]; ?></td>
                                   <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["KREDIT"]; ?></td>
                                   <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php if ($rows["BANK_ID"] == '14004' ){echo "BNI";}else{echo "MANDIRI";} ?></td>
                                   <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_PERATURAN"]; ?></td>
                                
                                   <?php  $total_mat1+=$rows["TARIF"];
                                    ?>
                           
              </tr>
              <? }?>
              <tr>
                <td></td>
                <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555">Total</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php   echo $total_mat1; ?></td>
                <td></td>
                <td></td>
                <td></td>
            
              </tr>
        </table>
 </div>

