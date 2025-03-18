 <?php
 // print_r('jozz');die();
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportLaporanHarian-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$db   = getDB("storage");

  $id_req = $_GET["id_req"];
  $id_time= $_GET["id_time"];
  //echo ($id_req); echo $id_time; die();

if ($id_req!=NULL && $id_time !=NULL ) {
  $query_list_="SELECT A.NO_REQUEST, A.KREDIT, A.NO_NOTA_MTI, A.NO_FAKTUR_MTI, A.TGL_SIMPAN,A.SP_MTI, B.NM_PBM ,B.NO_NPWP_PBM, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
  FROM ITPK_NOTA_HEADER A INNER JOIN  MST_PELANGGAN B ON A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM WHERE TO_CHAR(A.TGL_SIMPAN,'DD-MM-YYYY')='$id_time' AND A.NO_NOTA_MTI IS NOT NULL AND A.NO_REQUEST='$id_req' ORDER BY A.TGL_SIMPAN DESC";
}else if ($id_time!=NULL) {
  $query_list_="SELECT A.NO_REQUEST, A.KREDIT, A.NO_NOTA_MTI, A.NO_FAKTUR_MTI, A.TGL_SIMPAN,A.SP_MTI, B.NM_PBM ,B.NO_NPWP_PBM, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_HEADER A INNER JOIN  MST_PELANGGAN B ON A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM WHERE TO_CHAR(A.TGL_SIMPAN,'DD-MM-YYYY')='$id_time' AND A.NO_NOTA_MTI IS NOT NULL ORDER BY A.TGL_SIMPAN DESC";
   // print_r($query_list_);die();      
}elseif ($id_req!=NULL) {
 $query_list_="SELECT A.NO_REQUEST, A.KREDIT, A.NO_NOTA_MTI, A.NO_FAKTUR_MTI, A.TGL_SIMPAN,A.SP_MTI, B.NM_PBM ,B.NO_NPWP_PBM, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_HEADER A INNER JOIN  MST_PELANGGAN B ON A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM WHERE A.NO_REQUEST='$id_req' AND A.NO_NOTA_MTI IS NOT NULL ORDER BY A.TGL_SIMPAN DESC";
   // print_r($query_list_);die();   
}else{
      $query_list_="SELECT A.NO_REQUEST, A.KREDIT, A.NO_NOTA_MTI, A.NO_FAKTUR_MTI, A.TGL_SIMPAN,A.SP_MTI, B.NM_PBM ,B.NO_NPWP_PBM, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_HEADER A INNER JOIN  MST_PELANGGAN B ON A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM WHERE A.NO_NOTA_MTI IS NOT NULL ORDER BY A.TGL_SIMPAN DESC";
   // print_r($query_list_);die();   
}



        
  $result_list_ = $db->query($query_list_);
 $row_list   = $result_list_->getAll();
 // print_r($result_list_);die();
?>

 <div id="list">
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
            <tr>
                <th colspan="9">LAPORAN HARIAN</th>
            </tr>
            <tr>
                <th colspan="9">PT. MULTI TERMINAL INDONESIA</th>
            </tr>
           
                              <tr style=" font-size:10pt">
                                 <th valign="top" class="grid-header"  style="font-size:10pt">No</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No REQUEST</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">NO NOTA</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">NO FAKTUR</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">TOTAL</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">COSTUMER</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">NPWP</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">TANGGAL SIMPAN</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">NO SP</th>
                              </tr>
                              <?php $i=0;
                foreach($row_list as $rows){ $i++;?>
                              <tr>
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000;  font-size:9pt"><?php echo $i; ?></td>
                                  <td width="22%" align="center" valign="middle" class="grid-cell"   style=" font-size:11pt; color:#555555"><b><?php echo $rows["NO_REQUEST"]; ?></b></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_NOTA_MTI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_FAKTUR_MTI"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["KREDIT"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NM_PBM"]; ?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["NO_NPWP_PBM"]; ?></td>    
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["TGL_SIMPAN"]; ?></td>
                                   <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?php echo $rows["SP_MTI"]; ?></td>
                           
              </tr>
              <? }?>
        </table>
 </div>

