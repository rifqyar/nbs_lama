<?php 
$tanggal = $_GET[param];
//echo $tanggal;
//die();
?>

<table>
    <tr>
        <th colspan=29>
            Report Daily Production Volume & Productivity Report <?= $period; ?>
        </th>
    </tr>
    <tr>
        <th colspan=29>
            TERMINAL OPERASI III
        </th>
    </tr>
</table>
<br>
<table border="1">
    <tr>
        <th rowspan="4">No.</th>
        <th rowspan="4">Day</th>
        <th colspan='13'>Volume</th>
        <th colspan='7'>Ops Hour</th>
        <th colspan='5'>Productivity</th>
        <th rowspan="4">BOR</th>
        <th rowspan="4">YOR</th>
    </tr>
    <tr>
        <th colspan='3'>Disch</th>
        <th colspan='3'>Load</th>
        <th colspan='2' rowspan="2">Disch</th>
        <th colspan='2' rowspan="2">Load</th>
        <th colspan='2' rowspan="2">Shifting</th>
        <th rowspan="3">HC</th>
        <th rowspan="3">Gross Berth Time</th>
        <th rowspan="3">Net Berth Time</th><th rowspan="3">Gross Crane Hours</th>
        <th rowspan="3">BWT</th>
        <th rowspan="3">Idle Time</th>
        <th rowspan="3">Not Operation Time</th>
        <th rowspan="3">Effective Time</th>
        <th rowspan="3">BP</th>
        <th rowspan="3">BSH</th>
        <th rowspan="3">BCH</th>
        <th rowspan="3">VOR</th>
        <th rowspan="3">GCR</th>
    </tr>
    <tr>
        <th>20</th>
        <th>40</th>
        <th>45</th>
        <th>20</th>
        <th>40</th>
        <th>45</th>
    </tr>
    <tr>
        <th>BOX</th>
        <th>BOX</th>
        <th>BOX</th>
        <th>BOX</th>
        <th>BOX</th>
        <th>BOX</th>
        <th>BOX</th>
        <th>TEU</th>
        <th>BOX</th>
        <th>TEU</th>
        <th>BOX</th>
        <th>TEU</th>
    </tr>
</tr>
<!--  -->
<?php
$db = getDb('dbint');
$query_h = "SELECT tanggalnya, SUM (DUALOAD)DL, SUM(empatload)EL, sum(emlimaload)ELL, sum(duadisc)DD, sum(empatdisc)ED, sum(emlimadisc)ELD 
    FROM (SELECT tanggalnya,
                 CASE WHEN E_I = 'E' AND size_cont = '20' THEN jumlah END AS duaload,
                 CASE WHEN E_I = 'E' AND size_cont = '40' THEN jumlah END AS empatload,
                 CASE WHEN E_I = 'E' AND size_cont = '45' THEN jumlah END AS emlimaload,
                 CASE WHEN E_I = 'I' AND size_cont = '20' THEN jumlah END AS duadisc,
                 CASE WHEN E_I = 'I' AND size_cont = '40' THEN jumlah END AS empatdisc,
                 CASE WHEN E_I = 'I' AND size_cont = '45' THEN jumlah END AS emlimadisc
            FROM (  SELECT COUNT (no_container) AS jumlah,
                           size_cont,
                           E_I,
                           activity,
                           tanggalnya
                      FROM (SELECT a.no_container,
                                   b.size_cont,
                                   b.E_I,
                                   b.activity,
                                   TO_CHAR (TO_DATE (b.activity_date,'yyyymmddhh24miss'),'yyyymmdd') AS tanggalnya
                              FROM    m_coarri a
                                   LEFT JOIN
                                      m_cyc_container b
                                   ON (a.no_container = b.no_container))
                     WHERE tanggalnya LIKE '$tanggal%'
                  GROUP BY size_cont,
                           E_I,
                           activity,
                           tanggalnya
                )
         )
GROUP BY tanggalnya
ORDER BY tanggalnya asc";

//echo $query_h;
//die();
$result_h = $db->query($query_h);
$res = $result_h->getAll();
$i = 1;
foreach ($res as $row) {
    ?>
    <tr>

        <td><?= $i ?></td>
        <td><?= $row['TANGGALNYA'] ?></td>
        <td><?= $row['DD'] ?></td>
        <td><?= $row['ED'] ?></td>
        <td><?= $row['ELD'] ?></td>
        <td><?= $row['DL'] ?></td>
        <td><?= $row['EL'] ?></td>
        <td><?= $row['ELL'] ?></td>
        <td><?= $row['DALLBOX'] ?></td>
        <td><?= $row['DALLTEU'] ?></td>
        <td><?= $row['LALLBOX'] ?></td>
        <td><?= $row['LALLTEU'] ?></td>
        <td><?= $row['SALLBOX'] ?></td>
        <td><?= $row['SALLTEU'] ?></td>
        <td><?= $row['HC'] ?></td>
        <td><?= $row['GROSSBERTH'] ?></td>
        <td><?= $row['NETBERTH'] ?></td>
        <td><?= $row['GROSSCRANE'] ?></td>
        <td><?= $row['BWT'] ?></td>
        <td><?= $row['IDLETIME'] ?></td>
        <td><?= $row['NOTOPTIME'] ?></td>
        <td><?= $row['EFFTIME'] ?></td>
        <td><?= $row['BP'] ?></td>
        <td><?= $row['BSH'] ?></td>
        <td><?= $row['BCH'] ?></td>
        <td><?= $row['VOR'] ?></td>
        <td><?= $row['GCR'] ?></td>
        <td><?= $row['BOR'] ?></td>
        <td><?= $row['YOR'] ?></td>
    </tr>
    <?php
    $i++;
}
?>
<!--  -->		  
</table>
<?php die(); ?>
