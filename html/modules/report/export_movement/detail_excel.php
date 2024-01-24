<?php
$db = getDB();
$voyage_in = $_GET['voyage_in'];
$voyage_out = $_GET['voyage_out'];
$vessel = $_GET['vessel'];
$kg = $_GET['keg'];

if($kg=='E'){

$query1 = "SELECT 
                a.no_container, a.carrier, a. iso_code, a.status, a.weight, a.seal_id, b.id_pel_tuj, a.REEFER_TEMP, 
                a.activity, a.cont_location, a.type_cont, a.un_number, to_char(to_date(a.gate_in_date, 'yyyy-mm-dd hh24:mi:ss'), 'yyyy-mm-dd hh24:mi:ss') as gate_in,a.booking_sl, a.E_I, c.kode_pbm, 
                c.npe, a.imo, a.over_height, a.over_length, a.over_width,a.yd_block, a.yd_slot, a.yd_row, a.yd_tier,a.BAYPLAN_POSITION   
           FROM 
                m_cyc_container@dbint_link a 
                left join req_receiving_d b
                on (a.no_container = b.no_container and a.vessel=b.vessel)
                left join req_receiving_h c 
                on(b.no_req_anne = c.id_req) 
           WHERE 
                a.vessel = '$vessel' 
                and a.voyage_in = '$voyage_in'
                and a.voyage_out = '$voyage_out'
                and a.E_I = '$kg'";
}

else if ($kg=='I'){
$query1 = "SELECT 
                a.no_container, a.carrier, a. iso_code, a.status, a.weight, a.seal_id, a.REEFER_TEMP, 
                a.activity, a.cont_location, a.type_cont, a.un_number, to_char(to_date(a.gate_in_date, 'yyyy-mm-dd hh24:mi:ss'), 'yyyy-mm-dd hh24:mi:ss') as gate_in,a.booking_sl, a.E_I, 
                b.emkl, b.no_sppb, a.imo, a.over_height, a.over_length, a.over_width,a.yd_block, a.yd_slot, a.yd_row, a.yd_tier,a.BAYPLAN_POSITION   
           FROM 
                m_cyc_container@dbint_link a 
                left join req_delivery_h b
                on (a.billing_request_id = b.id_req)               
           WHERE 
                a.vessel = '$vessel' 
                and a.voyage_in = '$voyage_in'
                and a.voyage_out = '$voyage_out'
                and a.E_I = '$kg'";  
}


$result1 = $db->query($query1);
//echo $query1; 
//die();

$baris = $result1->getAll();
?>

<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=container_movement.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php if($kg=='E'){?>
<h1>Container Movement Export</h1>
<?php } else {?>
<h1>Container Movement Import</h1>
<?php }?> 

<table border="1">
    <tr bgcolor="#6699FF">
    <th>No</th>
    <th>No Container</th>
    <th>E/I</th>
    <th>OPR</th>
    <th>ISO</th>
    <th>F/M</th>
    <th>WGT</th>
    <th>Seal No</th>
    <th>POD</th>
    <th>Temp</th>
    <th>IMDG</th>
    <th>UNNO</th>
    <th>OH/OL/OW</th>
    <th>Status</th>
    <th>Location</th>
    <th>Type</th>    
    <th>Gate In</th>
    <th>Booking No</th>
    <th>Customer Name</th>
    <th>NPE/SPPB</th>
    <th>Yard Position</th>
    <th>BayPlan</th>
    </tr>
    
    <?php $c=1; foreach ($baris as $rownya) {  
        $oog = $rownya[OVER_HEIGHT].'/'.$rownya[OVER_LENGTH].'/'.$rownya[OVER_WIDTH]; ?> 
    
    <?php if($kg=='E'){?>
    <tr>
        <td><?=$c++?></td>
        <td><?=$rownya[NO_CONTAINER]?></td>
        <td><?=$rownya[E_I]?></td>
        <td><?=$rownya[CARRIER]?></td>
        <td><?=$rownya[ISO_CODE]?></td>
        <td><?=$rownya[STATUS]?></td>
        <td><?=$rownya[WEIGHT]?></td>        
        <td><?=$rownya[SEAL_ID]?></td>        
        <td><?=$rownya[ID_PEL_TUJ]?></td>        
        <td><?=$rownya[REEFER_TEMP]?></td>
        <td><?=$rownya[IMO]?></td>
        <td><?=$rownya[UN_NUMBER]?></td>
        <td ><?php echo $oog; ?></td>
        <td><?=$rownya[ACTIVITY]?></td>
        <td><?=$rownya[CONT_LOCATION]?></td>
        <td><?=$rownya[TYPE_CONT]?></td>    
        <td><?=$rownya[GATE_IN]?></td>
        <td><?=$rownya[BOOKING_SL]?></td>        
        <td><?=$rownya[KODE_PBM]?></td>        
        <td><?=$rownya[NPE]?></td>
        <td><?=$rownya[YD_BLOCK].$rownya[YD_SLOT].$rownya[YD_ROW].$rownya[YD_TIER] ?></td>
        <td><?=$rownya[BAYPLAN_POSITION]?></td>        
        
    </tr>
    <?php }
    
    else if ($kg=='I'){?>
    <tr>
        <td><?php echo $c++; ?></td>
        <td><?=$rownya[NO_CONTAINER]?></td>
        <td><?=$rownya[E_I]?></td>
        <td><?=$rownya[CARRIER]?></td>
        <td><?=$rownya[ISO_CODE]?></td>
        <td><?=$rownya[STATUS]?></td>
        <td><?=$rownya[WEIGHT]?></td>        
        <td><?=$rownya[SEAL_ID]?></td>        
        <td><?=$rownya[ID_PEL_TUJ]?></td>        
        <td><?=$rownya[REEFER_TEMP]?></td>
        <td><?=$rownya[IMO]?></td>
        <td><?=$rownya[UN_NUMBER]?></td>
        <td ><?php echo $oog; ?></td>
        <td><?=$rownya[ACTIVITY]?></td>
        <td><?=$rownya[CONT_LOCATION]?></td>
        <td><?=$rownya[TYPE_CONT]?></td>    
        <td><?=$rownya[GATE_IN]?></td>
        <td><?=$rownya[BOOKING_SL]?></td>        
        <td><?=$rownya[EMKL]?></td>        
        <td><?=$rownya[NO_SPPB]?></td>
        <td><?=$rownya[YD_BLOCK].$rownya[YD_SLOT].$rownya[YD_ROW].$rownya[YD_TIER] ?></td>
        <td><?=$rownya[BAYPLAN_POSITION]?></td>   
    </tr>
    
    <?php } 
    
    
    }?>
    
    
    
    
</table>


<?php die(); ?>