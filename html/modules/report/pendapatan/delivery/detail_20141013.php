<?php
$db = getDB();

$periode_awal = $_GET['awal']; 
$periode_akhir = $_GET['akhir']; 

$query1 = "select * from (select a.kd_permintaan, a.uraian, a.tottarif, b.date_paid, to_char(date_paid, 'yyyymmdd') as tgl_bayar from ttr_nota_all a left join tth_nota_all2 b on (a.kd_permintaan = b.no_request and a.kd_uper = b.no_nota))
where tgl_bayar < '$periode_akhir' and tgl_bayar > '$periode_awal'"; 
 
$result1 = $db->query($query1);
$row = $result1->getAll();


foreach ($row as $rownya){ 
    $datanya[$rownya[KD_PERMINTAAN]][$rownya[URAIAN]]=$rownya[TOTTARIF];     
}

$query2 = " select 
                * 
            from 
                (select 
                        no_request, 
                        no_nota, 
                        cust_name, 
                        cust_npwp, 
                        total, 
                        ppn, 
                        kredit, 
                        vessel, 
                        voyage_in, 
                        voyage_out,
						date_paid,						
                        to_char(date_paid, 'yyyymmddhh') as tgl_bayar 
                from 
                        tth_nota_all2)
			where 
				tgl_bayar < '$periode_akhir' and tgl_bayar > '$periode_awal'
				and (no_request like 'REQ%' or no_request like 'SP2%') 
                ";

echo $query2;
$result2 = $db->query($query2);
$row2 = $result2->getAll();

//die();

?>

<table ID="table-alumni" class="table-alumni">
    <tr>
        <th>No</th>
        <th>No Req</th>
        <th>No Nota</th>
        <th>Cust Name</th>
        <th>NPWP</th>
        <th>Total</th>
        <th>PPN</th>
        <th>Kredit</th>
        <th>Vessel</th>
        <th>Voyage In</th>
        <th>Voyage Out</th>
		<th>Date Paid</th>        
        <th>Kebersihan</th>
        <th>Adm</th>
        <th>Lift Off</th>
        <th>Penumpukan Masa I.1</th>             
      
    </tr>  
    
    <?php $c =1; foreach ($row2 as $rownya2)  { ?>
        <tr>
            <td><?php echo $c++ ?></td>
            <td><?php echo $rownya2[NO_REQUEST];?></td>
            <td><?php echo $rownya2[NO_NOTA];?></td>
            <td><?php echo $rownya2[CUST_NAME];?></td>
            <td><?php echo $rownya2[CUST_NPWP];?></td>
            <td><?php echo $rownya2[TOTAL];?></td>
            
            <td><?php echo $rownya2[PPN];?></td>
            <td><?php echo $rownya2[KREDIT];?></td>
            <td><?php echo $rownya2[VESSEL];?></td>
            
            <td><?php echo $rownya2[VOYAGE_IN];?></td>
            <td><?php echo $rownya2[VOYAGE_OUT];?></td>
            <td><?php echo $rownya2[DATE_PAID];?></td>
            
            
            <td><?php echo $datanya[$rownya2[NO_REQUEST]]['KEBERSIHAN']?></td>
            <td><?php echo $datanya[$rownya2[NO_REQUEST]]['ADM']?></td>
            <td><?php echo $datanya[$rownya2[NO_REQUEST]]['LIFT OFF']?></td>
            <td><?php echo $datanya[$rownya2[NO_REQUEST]]['PENUMPUKAN MASA I.1']?></td>  
        </tr>



    <?php } ?> 

</table>


<?php die(); ?>