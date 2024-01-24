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

    $query = "SELECT 
                    count(1)as NUMBER_OF_ROWS 
              FROM 
                    OPUS_REPO.m_cyc_container 
              WHERE 
                    vessel = '$vessel'
                    and voyage_in = '$voyage_in'
                    and voyage_out = '$voyage_out'
                    and E_I = 'E'";        



 $res = $db->query($query)->fetchRow();
 $count = $res[NUMBER_OF_ROWS];
 


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
$query1 = "SELECT 
                a.no_container,
                b.id_req,               
                a.type_cont, 
                a. size_cont, 
                a.status, 
                a.weight, 
                a.pod,
                '56' as sebagai,  
                c.KODE_PBM,
                d.nm_commodity,          
                a.activity, 
                a.cont_location,                 
                a.gate_in_date,
               c.NPE,                
                a.E_I,
                b.kd_comodity,
				'RCV' FITRI   				
           FROM 
                OPUS_REPO.m_cyc_container a left join req_receiving_d b on (a.no_container=b.no_container and a.vessel=b.vessel and trim(a.voyage_in) = trim(b.voyage_in) and trim(a.voyage_out) = trim(b.voyage_out))
                left join req_receiving_h c on (b.id_req = c.id_req)
                left join master_commodity d on (b.kd_comodity = d.kd_commodity)
           WHERE 
                a.vessel = '$vessel' 
                and a.voyage_in = '$voyage_in'
                and a.voyage_out = '$voyage_out'
                and a.E_I = 'E'
                and a.cont_location = 'Vessel'";

//echo $query1;
//die();
				
$result1 = $db->query($query1);
$baris = $result1->getAll();

//print_r($baris);die();

$i=0;
foreach ($baris as $rownya) {    
         
    $responce->rows[$i]['id'] = $rownya2[NO_REQUEST];
    $responce->rows[$i]['cell'] = array(
        $rownya[NO_CONTAINER],
        $rownya[TYPE_CONT],
        $rownya[SIZE_CONT],
        $rownya[STATUS],        
        $rownya[WEIGHT],        
        $rownya[POD],
        $rownya[SEBAGAI],        
        $rownya[KODE_PBM], 
        $rownya[NM_COMMODITY],        
        $rownya[GATE_IN_DATE],		
		$rownya[FITRI],
		$rownya[NPE],
        $rownya[ID_REQ]                     
        );    
    $i++;
    }    

echo json_encode($responce);
die();
?>