<?php
$vessel = $_GET['vessel'];
$voyage_in = $_GET['vin'];
$voyage_out = $_GET['vot'];
$kg = $_GET['kg'];

$page = isset($_POST['page']) ? $_POST['page'] : 1;  // get the requested page
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx']) ? $_POST['sidx'] : 'id_bprp'; // get index row - i.e. user click to sort
//$sord = $_GET['sord']; // get the direction

if (!$sidx)
    $sidx = 1;
$db = getDB();

if ($kg=='E')
{
    $query = "SELECT 
                    count(1)as NUMBER_OF_ROWS 
              FROM 
                    m_cyc_container@dbint_link 
              WHERE 
                    vessel = '$vessel'
                    and voyage_in = '$voyage_in'
                    and voyage_out = '$voyage_out'
                    and E_I = 'E'";        
}
else if ($kg=='I')
{
    $query = "SELECT 
                    count(1)as NUMBER_OF_ROWS 
              FROM 
                    m_cyc_container@dbint_link 
              WHERE 
                    vessel = '$vessel'
                    and voyage_in = '$voyage_in'
                    and voyage_out = '$voyage_out'
                    and E_I = 'I'";    
   
}

 $res = $db->query($query)->fetchRow();
 $count = $res[NUMBER_OF_ROWS];
 
//echo $kg;
//echo $count;
//echo $query;
//die();




if ($count > 0) {
    $total_pages = ceil($count / $limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit; // do not put $limit*($page - 1)	

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

//Datanya

if($kg=='E'){
$query1 = "SELECT 
                a.no_container, a.carrier, a. iso_code, a.status, a.weight, a.seal_id, b.id_pel_tuj, a.REEFER_TEMP, 
                a.activity, a.cont_location, a.type_cont, a.un_number, a.gate_in_date,a.booking_sl, a.E_I, c.kode_pbm, 
                c.npe, a.imo, a.over_height, a.over_length, a.over_width, a.yd_block, a.yd_slot, a.yd_row, a.yd_tier,a.BAYPLAN_POSITION  
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
                a.activity, a.cont_location, a.type_cont, a.un_number, a.gate_in_date,a.booking_sl, a.E_I, 
                b.emkl, b.no_sppb, a.imo, a.over_height, a.over_length, a.over_width, a.yd_block, a.yd_slot, a.yd_row, a.yd_tier,
                a.BAYPLAN_POSITION
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

//echo $query1;
//die();

$result1 = $db->query($query1);
$baris = $result1->getAll();

//print_r($baris);die();

$i=0;
foreach ($baris as $rownya) {    
    $oog = $rownya[OVER_HEIGHT].'/'.$rownya[OVER_LENGTH].'/'.$rownya[OVER_WIDTH];       
    if($kg=='E'){        
     
    $responce->rows[$i]['id'] = $rownya2[NO_REQUEST];
    $responce->rows[$i]['cell'] = array(
        $rownya[NO_CONTAINER],
        $rownya[E_I],
        $rownya[CARRIER],
        $rownya[ISO_CODE],
        $rownya[STATUS],
        $rownya[WEIGHT],
        $rownya[SEAL_ID],
        $rownya[ID_PEL_TUJ],
        $rownya[REEFER_TEMP],
        $oog, 
        $rownya[IMO], 
        $rownya[UN_NUMBER], 
        $rownya[ACTIVITY], 
        $rownya[CONT_LOCATION],
        $rownya[TYPE_CONT],        
        $rownya[GATE_IN_DATE], 
        $rownya[BOOKING_SL],         
        $rownya[KODE_PBM], 
        $rownya[NPE], 
        $rownya[YD_BLOCK].'/'.$rownya[YD_SLOT].'/'.$rownya[YD_ROW].'/'.$rownya[YD_TIER],
        $rownya[BAYPLAN_POSITION]
                      
        );
    
    $i++;
    }
    
    else if($kg=='I'){
         //$yard_position  = $rownya[YD_BLOCK].'/'.$rownya[YD_SLOT].'/'.$rownya[YD_ROW].'/'.$rownya[YD_TIER];
        
        $responce->rows[$i]['id'] = $rownya2[NO_REQUEST];
    $responce->rows[$i]['cell'] = array(
        $rownya[NO_CONTAINER],
        $rownya[E_I],
        $rownya[CARRIER],
        $rownya[ISO_CODE],
        $rownya[STATUS],
        $rownya[WEIGHT],
        $rownya[SEAL_ID],
        'IDJKT',
        $rownya[REEFER_TEMP],
        $oog, 
        $rownya[IMO], 
        $rownya[UN_NUMBER], 
        $rownya[ACTIVITY], 
        $rownya[CONT_LOCATION],
        $rownya[TYPE_CONT],        
        $rownya[GATE_IN_DATE], 
        $rownya[BOOKING_SL],         
        $rownya[EMKL], 
        $rownya[NO_SPPB], 
        $rownya[YD_BLOCK].'/'.$rownya[YD_SLOT].'/'.$rownya[YD_ROW].'/'.$rownya[YD_TIER], 
        $rownya[BAYPLAN_POSITION]
        );
    
    $i++;
        
        
        
    }
    
}

echo json_encode($responce);
die();
?>