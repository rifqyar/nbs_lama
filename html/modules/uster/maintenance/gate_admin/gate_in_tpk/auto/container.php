<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
require_lib('praya.php');
	
/*$query 			= "SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_, c.NO_NOTA, TO_CHAR(c.END_STACK, 'dd/mm/yyyy') END_STACK , e.NAMA
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, NOTA_DELIVERY c, REQUEST_DELIVERY d, MASTER_PBM e
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND a.NO_REQUEST = c.NO_REQUEST
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND d.ID_PEMILIK = e.ID
                            AND b.LOCATION = 'IN_YARD'
                            AND b.NO_CONTAINER LIKE '%$no_cont%'
							AND a.AKTIF = 'Y'
                            ORDER BY a.NO_REQUEST";
*/
/*
$query 			= "SELECT CONTAINER_RECEIVING.NO_REQUEST AS NO_REQUEST, 
						  CONTAINER_RECEIVING.STATUS AS STATUS, 
						  MASTER_CONTAINER.NO_CONTAINER AS NO_CONTAINER, 
						  MASTER_CONTAINER.SIZE_ AS SIZE_, 
						  MASTER_CONTAINER.TYPE_ AS TYPE_ 
				   FROM MASTER_CONTAINER 
				   INNER JOIN CONTAINER_RECEIVING ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_RECEIVING.NO_CONTAINER 
				   WHERE CONTAINER_RECEIVING.NO_CONTAINER LIKE '%$no_cont%' 
				   AND LOCATION LIKE 'GATO' AND CONTAINER_RECEIVING.AKTIF = 'Y'";
*/				   
$id_yard		= $_SESSION["IDYARD_STORAGE"];
// $query			= /*"SELECT b.NO_CONTAINER AS NO_CONTAINER,
//                           b.NO_REQUEST AS NO_REQUEST,
//                           a.SIZE_ AS SIZE_,
//                           a.TYPE_ AS TYPE_,
//                           b.STATUS AS STATUS,
//                           d.NM_PBM AS NM_PBM,
//                           b.DEPO_TUJUAN AS ID_YARD,
//                           TD.BP_ID,
//                           TD.NO_REQ AS NO_REQ_TPK
//                     FROM PETIKEMAS_CABANG.TTD_BP_CONT TD,
//                          MASTER_CONTAINER a INNER JOIN 
//                          CONTAINER_RECEIVING b ON a.NO_CONTAINER = b.NO_CONTAINER JOIN
//                          REQUEST_RECEIVING c ON  b.NO_REQUEST = c.NO_REQUEST JOIN
//                          V_MST_PBM d ON  c.KD_CONSIGNEE = d.KD_PBM               
//                     WHERE c.RECEIVING_DARI = 'TPK'
//                     and td.status_cont = '10U' 
//                     and b.NO_CONTAINER = TD.CONT_NO_BP
//                     and b.NO_CONTAINER LIKE '%$no_cont%'
// 					"*/
					// "SELECT DISTINCT b.NO_CONTAINER AS NO_CONTAINER,
                    //       b.NO_REQUEST AS NO_REQUEST,
                    //       a.SIZE_ AS SIZE_,
                    //       a.TYPE_ AS TYPE_,
                    //       b.STATUS AS STATUS,
                    //       d.NM_PBM AS NM_PBM,
                    //       b.DEPO_TUJUAN AS ID_YARD
                    // FROM MASTER_CONTAINER a INNER JOIN 
                    //      CONTAINER_RECEIVING b ON a.NO_CONTAINER = b.NO_CONTAINER JOIN
                    //      REQUEST_RECEIVING c ON  b.NO_REQUEST = c.NO_REQUEST JOIN
                    //      V_MST_PBM d ON  c.KD_CONSIGNEE = d.KD_PBM               
                    // WHERE c.RECEIVING_DARI = 'TPK'
                    // and b.NO_CONTAINER LIKE '$no_cont%'
                    // AND a.LOCATION = 'GATO'
                    // AND b.aktif = 'Y'
					// ";

/*
$query			= "SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, REQUEST_DELIVERY d
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND b.LOCATION = 'IN_YARD'
						    AND a.AKTIF = 'Y'
							AND a.NO_CONTAINER LIKE '%$no_cont%'
                            ORDER BY a.NO_REQUEST";
							
							*/
// $result			= $db->query($query);
// echo var_dump($result);
// $row			= $result->getAll();	
// echo var_dump($row);
// //echo $query;
// //print_r($row);
try{
    $payload = array(
        "terminalCode" =>'PNK', 
        "containerNo" => $no_cont, 
        "inOut" => 'OUT' //Gate Out dari TPK dan Gate In di USTER (Location GATI) 
    );
    // echo var_dump(json_decode($payload));
    // die;
        // $response = sendDataToPraya($payload, "http://localhost:8020/api/getContainerInOut", 'POST');
        $response = sendDataToPraya($payload, PRAYA_API_INTEGRATION . "/api/getContainerInOut", 'POST');
        // $response = sendDataFromUrl($payload, PRAYA_API_INTEGRATION . "/api/getContainerInOut", 'POST');
        // echo var_dump($response)  ;  
        $response = json_decode($response['response'], true);
        // if ($response['code'] == 1 && !empty($response["dataRec"])) {
            // echo json_encode($response['dataRec']);
        // }; 

        $result = $response['dataRec'];
        echo json_decode($result);
        $row = $result;
} catch (Exception $ex) {
    echo $ex->getMessage();
};



echo json_encode($row);


?>