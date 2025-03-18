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
$query			= "SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_, c.NO_NOTA, TO_CHAR(c.END_STACK, 'dd/mm/yyyy') END_STACK , e.NM_PBM NAMA, TO_CHAR(d.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, NOTA_DELIVERY c, REQUEST_DELIVERY d, v_mst_pbm e
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND d.NO_REQUEST = c.NO_REQUEST(+)
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND d.KD_EMKL = e.KD_PBM
                            AND b.LOCATION = 'IN_YARD'
                            AND b.NO_CONTAINER LIKE '%$no_cont%'
                           AND d.NO_REQUEST IN (SELECT MAX(d.NO_REQUEST) FROM container_delivery d WHERE d.NO_CONTAINER LIKE '%$no_cont%' GROUP BY d.NO_CONTAINER)
                            ORDER BY a.NO_REQUEST";
							
*/							
// $q_cek = "SELECT a.NO_CONTAINER, 
//                     a.NO_REQUEST, 
//                     b.SIZE_, 
//                     a.STATUS, 
//                     b.TYPE_, 
//                     --c.NO_NOTA,
//                     --TO_CHAR (c.end_stack, 'dd/mm/yyyy') END_STACK, 
//                     e.nm_pbm NAMA,
//                      TO_CHAR (a.tgl_delivery, 'dd/mm/yyyy') TGL_REQUEST_DELIVERY,
//                     'AUTO' CEK,
// 					D.STATUS status_req,
// 					d.NO_REQ_ICT
//   FROM master_container b,
//        container_delivery a,
//        --nota_delivery c,
//        request_delivery d,
//        v_mst_pbm e
//  WHERE a.no_container = b.no_container
//    --AND d.no_request = c.no_request
//    AND a.no_request = d.no_request
//    AND d.kd_emkl = e.kd_pbm
//    AND e.KD_CABANG = '05'
//    AND b.LOCATION = 'IN_YARD'
//    AND b.no_container LIKE '%$no_cont%'
//    AND A.AKTIF = 'Y'";
//    $rcek = $db->query($q_cek);
//    $tcek = $rcek->fetchRow();
//    $st_req = $tcek["STATUS_REQ"];
   
//    if($st_req == "AUTO_REPO"){
		
// 		$query = "SELECT a.NO_CONTAINER, 
//                     a.NO_REQUEST, 
//                     b.SIZE_, 
//                     a.STATUS, 
//                     b.TYPE_, 
//                     --c.NO_NOTA,
//                     --TO_CHAR (c.end_stack, 'dd/mm/yyyy') END_STACK, 
//                     e.nm_pbm NAMA,
//                      TO_CHAR (a.tgl_delivery, 'dd/mm/yyyy') TGL_REQUEST_DELIVERY,
//                     'AUTO' CEK,
// 					D.STATUS status_req,					
// 					d.NO_REQ_ICT
// 				  FROM master_container b,
// 					   container_delivery a,
// 					   --nota_delivery c,
// 					   request_delivery d,
// 					   v_mst_pbm e
// 				 WHERE a.no_container = b.no_container
// 				   --AND d.no_request = c.no_request
// 				   AND a.no_request = d.no_request
// 				   AND d.kd_emkl = e.kd_pbm
// 				   AND e.KD_CABANG = '05'
// 				   AND b.LOCATION = 'IN_YARD'
// 				   AND b.no_container LIKE '%$no_cont%'
// 				   AND A.AKTIF = 'Y'";
//    }
//    else {
   
// 	$query =	"SELECT a.NO_CONTAINER, 
// 					a.NO_REQUEST, 
// 					b.SIZE_, 
// 					a.STATUS, 
// 					b.TYPE_, 
// 					c.NO_FAKTUR NO_NOTA,
// 					TO_CHAR (c.end_stack, 'dd/mm/yyyy') END_STACK, 
// 					e.nm_pbm NAMA,
// 					 TO_CHAR (a.tgl_delivery, 'dd/mm/yyyy') TGL_REQUEST_DELIVERY,
// 					'AUTO' CEK,
// 					d.NO_REQ_ICT
// 		  FROM master_container b,
// 			   container_delivery a,
// 			   nota_delivery c,
// 			   request_delivery d,
// 			   v_mst_pbm e
// 		 WHERE a.no_container = b.no_container
// 		   AND d.no_request = c.no_request
// 		   AND a.no_request = d.no_request
// 		   AND d.kd_emkl = e.kd_pbm
// 		   AND e.KD_CABANG = '05'
// 		   AND b.LOCATION = 'IN_YARD'
// 		   AND b.no_container LIKE '%$no_cont%'
// 		   AND A.AKTIF = 'Y' 
// 		   AND c.LUNAS = 'YES'
// 		   AND C.STATUS <> 'BATAL'";
//    }
   
// $result			= $db->query($query);
// $row			= $result->getAll();	
// //echo $query;
// //print_r($row);

// echo json_encode($row);


try{
  $payload = array(
      "terminalCode" =>'PNK', 
      "containerNo" => $no_cont, 
      "inOut" => 'IN' //Gate Out dari USTER (Location GATO) dan Gate In di TPK 
  );
  // echo var_dump($payload );
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