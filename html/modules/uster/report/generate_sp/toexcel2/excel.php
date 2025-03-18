 <?php
 // print_r('jozz');die();
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportLaporanHarian-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

 <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
            <tr>
                <th colspan="13">Laporan Harian</th>
            </tr>
            <tr>
                <th colspan="13">PT. Multi Terminal Indonesia - CABANG PONTIANAK</th>
            </tr>
           
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No REQUEST</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">No. NOTA</th>
                                   <th valign="top" class="grid-header"  style="font-size:10pt">NO FAKTUR</th>
                                    <th valign="top" class="grid-header"  style="font-size:10pt">TOTAL</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">COSTUMER</th>
                                  <th  valign="top" class="grid-header"  style="font-size:10pt">NO NPWP</th>
                                  <th valign="top" class="grid-header"  style="font-size:10pt">TANGGAL SIMPAN</th>
                              </tr>
                            
                           
                            </tr>
                       