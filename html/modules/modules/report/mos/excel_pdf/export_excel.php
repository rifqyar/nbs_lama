<?php
$tgl = date('d F Y H:i');
$tanggal = date("dmY");
$pilihan = $_GET['pilihan'];
$param1 = $_GET['param1'];


echo "Nilai Pilihan:". $pilihan. "<br>";
echo "Nilai Param :". $param1. "<br>";

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=mom.xls");
header("Pragma: no-cache");
header("Expires: 0");
 
$db = getDB();

//Mengakses Proc Anonymous PL/SQL
$param_b_var = array(
    "v_pilihan" => "$pilihan",
    "v_param1" => "$param1",
    "v_outseq" => ""    
);

// var_dump($param_b_var);

$query = "declare begin proc_mom(:v_pilihan,:v_param1,:v_outseq); end;";
$db->query($query, $param_b_var);

$seq = $param_b_var['v_outseq'];
print_r($param_b_var['v_outseq']);
//print_r('sequence: ' . $seq);
//die;

$query_p = "SELECT * from rp_mom where id_mom=$seq";
$resultnya = $db->query($query_p);
$rownya = $resultnya->fetchRow();

// var_dump($rownya);

$bulan = $rownya['MONTHS']; 
if ($bulan == 1) {$bulannyo = "Januari";}
if ($bulan == 2) {$bulannyo = "February";}
if ($bulan == 3) {$bulannyo = "Maret";}
if ($bulan == 4) {$bulannyo = "April";}
if ($bulan == 5) {$bulannyo = "Mei";}
if ($bulan == 6) {$bulannyo = "Juni";}
if ($bulan == 7) {$bulannyo = "July";}
if ($bulan == 8) {$bulannyo = "Agustus";}
if ($bulan == 9) {$bulannyo = "September";}
if ($bulan == 10){$bulannyo = "Oktober";}
if ($bulan == 11){$bulannyo = "November";}
if ($bulan == 12){$bulannyo = "Desember";}
?>


<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- TemplateBeginEditable name="doctitle" -->

        <title>Untitled Document</title>
        <!-- TemplateEndEditable -->
        <!-- TemplateBeginEditable name="head" -->
        <!-- TemplateEndEditable -->
    </head>

    <body>

        <h1>T1 Monthly Operations Summary</h1>

        <table border="1" cellspacing="0" width="100%">
            <tr>
                <td width="77" rowspan="5">No# of Call</td>
                <td width="142">&nbsp;</td>
                <td colspan="2">&nbsp;</td>
                <td width="222">Bulan <?php echo $bulannyo ?></td>
            </tr>
            <tr>
                <td>&lt;120</td>
                <td colspan="2">&nbsp;</td>
                <td><?php echo $rownya['VSMALL']; ?></td>
            </tr>
            <tr>
                <td>&gt; 120M <= 200M</td>
                <td colspan="2">&nbsp;</td>
                <td><?php echo $rownya['VMED']; ?></td>
            </tr>
            <tr>
                <td>&gt; 200M</td>
                <td colspan="2">&nbsp;</td>
                <td><?php echo $rownya['VLONG']; ?></td>
            </tr>
            <tr>
                <td>Total</td>
                <td colspan="2">&nbsp;</td>
                <td><?php echo $rownya['VTOT'];?></td>
            </tr>
            <tr>
                <td rowspan="16"><p>Business</p>
                    <p>Volume</p></td>
                <td rowspan="6">W/O Shifting</td>
                <td colspan="2">Box (Total)</td>
                <td><?php echo $rownya['BOX_TOT_WO'];?></td>
            </tr>
            <tr>
                <td colspan="2">Teu (Total)</td>
                <td><?php echo $rownya['TEU_TOT_WO'];?></td>
            </tr>
            <tr>
                <td colspan="2">Disch Box</td>
                <td><?php echo $rownya['DISC_BOX_WO']?></td>
            </tr>
            <tr>
                <td colspan="2">Load Box</td>
                <td><?php echo $rownya['LOAD_BOX_WO']?></td>
            </tr>
            <tr>
                <td colspan="2">Disch Teu</td>
                <td><?php echo $rownya['DISC_TEU_WO'];?></td>
            </tr>
            <tr>
                <td colspan="2">Load Teu</td>
                <td><?php echo $rownya['LOAD_TEU_WO'];?></td>
            </tr>
            <tr>
              <td rowspan="2">Shifting 
              Included</td>
              <td colspan="2">Box</td>
              <td><?php echo $rownya['BOX_INC'] ;?></td>
            </tr>
            <tr>
              <td colspan="2">Teu</td>
              <td><?php echo $rownya['TEU_INC'] ;?></td>
            </tr>
            <tr>
              <td colspan="3">Hatch Cover Moves</td>
              <td><?php echo $rownya['HATCH_COVER_MOVES']; ?></td>
            </tr>
            <tr>
              <td rowspan="2">Volume/Call</td>
              <td colspan="2">Box</td>
              <td><?php echo $rownya['BOX_VOL']; ?></td>
            </tr>
            <tr>
              <td colspan="2">Teu</td>
              <td><?php echo $rownya['TEU_VOL']; ?></td>
            </tr>
            <tr>
              <td>Forecast</td>
              <td colspan="2">Teu</td>
              <td><?php echo $rownya['TEU_FCST']; ?></td>
            </tr>
            <tr>
              <td>Actual</td>
              <td colspan="2">Teu</td>
              <td><?php echo $rownya['TEU_ACT']; ?></td>
            </tr>
            <tr>
              <td>Realization</td>
              <td colspan="2">(%)</td>
              <td><?php echo $rownya['REALIZATION_ACT']; ?>%</td>
            </tr>
            <tr>
              <td colspan="3">Box Ratio</td>
              <td><?php echo $rownya['BOX_RATIO']; ?></td>
            </tr>
            <tr>
              <td colspan="3">Empty Ratio (%)</td>
              <td><?php echo $rownya['EMPTY_RATIO']; ?>%</td>
            </tr>
            <tr>
              <td rowspan="4">Crane Hour</td>
              <td rowspan="2">Berting Hrs</td>
              <td colspan="2">Gross Berth Time</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Net Berth Time</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="2">Crane Working Hrs</td>
              <td colspan="2">Gross Berth Time</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Net Berth Time</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="35">Performance</td>
              <td rowspan="4">Productivity  (mph)</td>
              <td colspan="2">GBP</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">VOR</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">GCR</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">NCR</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="2">YC Rate <br>
              (mph)</td>
              <td colspan="2">RTGC</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">FL</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="4">Occupancy <br>
              Ratio (%)</td>
              <td width="65" rowspan="3">Berth</td>
              <td width="130">North</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>West</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Overal</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Yard</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="3">Gate Movement</td>
              <td rowspan="2">Moves</td>
              <td>Pickup</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Grounding</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Total</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="5"><p>External Truck TRT (Min)</p></td>
              <td rowspan="2">Pickup</td>
              <td>Full</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Empty</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="2">Grounding</td>
              <td>Full</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Empty</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Overall</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>HT Turn Time (Min)</td>
              <td colspan="2">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="2">Deployment <br>
              Ratio</td>
              <td colspan="2">HT/QC</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">QC/VSL</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="3">Marshalling Movement</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="4">Twin Moves (%) on North Berth</td>
              <td colspan="2">Disch</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Load</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Moves</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Twin Moves</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="2">Shuffling (%)</td>
              <td colspan="2">Before Loading</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Overall Yard Moves</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="7">Dwell Time (Day)</td>
              <td rowspan="3">IB</td>
              <td>Full</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Empty</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Overall</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td rowspan="3">OB</td>
              <td>Full</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Empty</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Overall</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Overall</td>
              <td>&nbsp;</td>
            </tr>
        </table>
        <p>&nbsp;</p>


    </body>
</html>
