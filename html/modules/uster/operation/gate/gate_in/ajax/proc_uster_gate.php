<?php
$username = $_SERVER['HTTP_USERNAME'];
$password = $_SERVER['HTTP_PASSWORD'];

// echo var_dump(json_encode($username . " " . $password));
if ($username == "uster" && $password == "uster") {
  $db = getDB("storage");
  $json = file_get_contents('php://input');
  // Converts it into a PHP object 
  $payload_uster_gate = json_decode($json, true);


  $in_tipe = $payload_uster_gate['inOut']; //status di TPK
  $in_nocont = $payload_uster_gate['containerNo'];
  $in_vessel = $payload_uster_gate['vessel'];
  $in_voyin = $payload_uster_gate['voyIn'];
  $in_voyout = $payload_uster_gate['voyOut'];
  $in_user = $payload_uster_gate['user'];
  $in_notruck = $payload_uster_gate['truckId'];
  $in_status = $payload_uster_gate['containerStatus'];
  $in_seal = $payload_uster_gate['seal'];
  $gateDate = $payload_uster_gate['date'];
  $requestId = $payload_uster_gate['requestId'];
  $serviceName = $payload_uster_gate['serviceName']; //peralihan

  $out_msg = null;
  $v_noreq_stuf = null;
  $v_noreq_strip = null;
  $v_nobooking = null;
  $v_counter = null;
  $v_status = null;
  $v_user = 'opus';

  if ($in_tipe == null || $in_nocont == null || $in_status == null || $gateDate == null || $serviceName == null) {

    echo ('Request is not complete');
  } else {
    $get_status_container = $db->query("SELECT STATUS_CONT
    FROM HISTORY_CONTAINER WHERE NO_REQUEST = '$requestId' AND NO_CONTAINER = '$in_nocont' AND ROWNUM = 1
    ORDER BY TGL_UPDATE DESC");
		$status_cont = $get_status_container->fetchRow();
    // $row = oci_fetch_assoc($get_status_container);
    if ($status_cont) {
    $latest_status_container = $status_cont['STATUS_CONT'];
    // echo $latest_status_container;
      if ($in_tipe == 'OUT') {
        echo ('gate in USTER ');
        // if ($in_status == "FULL") {
        //   $v_status = 'FCL';
        // } else {
        //   $v_status = 'MTY';
        // };

        // Check if data exists 
        $queryCek = "SELECT COUNT(1) AS COUNT FROM BORDER_GATE_IN WHERE NO_CONTAINER = '$in_nocont' AND NO_REQUEST = '$requestId' AND TO_CHAR(TGL_IN, 'YYYY-MM-DD HH24:mi:ss') = TO_CHAR(TO_DATE('$gateDate', 'YYYY-MM-DD HH24:mi:ss'), 'YYYY-MM-DD HH24:mi:ss')";
        $exec = $db->query($queryCek);
        $count = $exec->fetchRow();
        $count = $count['COUNT'];

        if ($count > 0) {
          echo ('Data Exists');
          $out_msg = 'Failed';
        } else {
          if ($in_nocont != null) {
            $db->query("INSERT INTO BORDER_GATE_IN (NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, ID_YARD, VIA) 
            VALUES ('$requestId', '$in_nocont', '$v_user', TO_DATE('$gateDate', 'YYYY-MM-DD HH24:mi:ss'), '$in_notruck', '$latest_status_container', '$in_seal', '46', 'TRIG_OPUS')");

            if ($serviceName == 'STRIPPING') {
              $result_strip = $db->query("SELECT NO_REQUEST FROM REQUEST_STRIPPING WHERE NO_REQUEST_RECEIVING = '$requestId' ORDER BY TGL_REQUEST DESC");
              $v_noreq_strip = $result_strip -> getAll();
              $db->query("UPDATE CONTAINER_STRIPPING SET TGL_GATE = '$gateDate' WHERE NO_CONTAINER = '$in_nocont' AND NO_REQUEST = '$v_noreq_strip[0]'");
              } else if ($serviceName == 'STUFFING') {
                $result_stuf = $db->query("SELECT NO_REQUEST FROM REQUEST_STUFFING WHERE NO_REQUEST_RECEIVING = '$requestId' ORDER BY TGL_REQUEST DESC") ;
                $v_noreq_stuf = $result_stuf -> getAll();
              $db->query("UPDATE CONTAINER_STUFFING SET TGL_GATE = '$gateDate' WHERE NO_CONTAINER = '$in_nocont' AND NO_REQUEST = '$v_noreq_stuf[0]'");
            }
            ;
            $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$in_nocont'");

            $result = $db->query("SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$in_nocont' ORDER BY COUNTER DESC");
            $row			= $result->getAll();
            $v_nobooking = $row[0]['NO_BOOKING'];
            $v_counter = $row[0]['COUNTER'];

            $db->query("INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
            VALUES ('$in_nocont', '$requestId', 'BORDER GATE IN', SYSDATE, '$v_user', 46, '$latest_status_container', '$v_nobooking', '$v_counter')");
            $out_msg = 'SUCCESS';
          } else {
            echo ('Container is not found ');
            $out_msg = 'Failed';
          }
          ;
        }
      } else if ($in_tipe == 'IN') {
        echo ('gate out USTER ');
        // if ($in_status == 'FULL') {
        //   $v_status = "FCL";
        // } else {
        //   $v_status = 'MTY';
        // };

        // Check if data exists 
        $queryCek = "SELECT COUNT(1) AS COUNT FROM BORDER_GATE_OUT WHERE NO_CONTAINER = '$in_nocont' AND NO_REQUEST = '$requestId' AND TO_CHAR(TGL_IN, 'YYYY-MM-DD HH24:mi:ss') = TO_CHAR(TO_DATE('$gateDate', 'YYYY-MM-DD HH24:mi:ss'), 'YYYY-MM-DD HH24:mi:ss')";
        $exec = $db->query($queryCek);
        $count = $exec->fetchRow();
        $count = $count['COUNT'];
        if ($count > 0) {
          echo ('Data Exists');
          $out_msg = 'Failed';
        } else {
          if ($in_nocont != NULL) {

            $res = $db->query("INSERT INTO BORDER_GATE_OUT (NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL,TRUCKING,ID_YARD,VIA) 
                          VALUES(  '$requestId', 
                                    '$in_nocont', 
                                    '$v_user', 
                                    TO_DATE('$gateDate', 'YYYY-MM-DD HH24:mi:ss'), 
                                    '$in_notruck', 
                                    '$latest_status_container',
                                    '$in_seal',
                                    '$in_notruck',
                                    46,
                                    'TRIG_OPUS')
              "); 
            // echo var_dump($res); die;
            $db->query(" DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$in_nocont' ");
            if ($serviceName == 'STRIPPING') {
              $result_strip = $db->query("SELECT NO_REQUEST FROM REQUEST_STRIPPING WHERE NO_REQUEST_RECEIVING = '$requestId' ORDER BY TGL_REQUEST DESC");
              $v_noreq_strip = $result_strip -> getAll();
              $db->query("UPDATE CONTAINER_STRIPPING SET TGL_GATE = '$gateDate' WHERE NO_CONTAINER = '$in_nocont' AND NO_REQUEST = '$v_noreq_strip[0]'");
            } else if ($serviceName == 'STUFFING') {
              $result_stuf = $db->query("SELECT NO_REQUEST FROM REQUEST_STUFFING WHERE NO_REQUEST_RECEIVING = '$requestId' ORDER BY TGL_REQUEST DESC") ;
              $v_noreq_stuf = $result_stuf -> getAll();
            $db->query("UPDATE CONTAINER_STUFFING SET TGL_GATE = '$gateDate' WHERE NO_CONTAINER = '$in_nocont' AND NO_REQUEST = '$v_noreq_stuf[0]'");
            }

            $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$in_nocont'");
            
            $db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$in_nocont' AND NO_REQUEST = '$requestId'");

            $result = $db->query(" SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$in_nocont' ORDER BY COUNTER DESC ");
            $row			= $result->getAll();	
            // echo ($row[0]['NO_BOOKING']);
            // die;
            // echo var_dump($result[0]['COUNTER']);
            $v_nobooking = $row[0]['NO_BOOKING'];
            $v_counter = $row[0]['COUNTER'];
            // echo var_dump($v_counter);
            $db->query("INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
            VALUES ('$in_nocont', '$requestId', 'BORDER GATE OUT',SYSDATE, '$v_user', '46', '$latest_status_container', '$v_nobooking', '$v_counter')");
            $out_msg = 'SUCCESS';
          } else {
            echo ('Container is not found');
            $out_msg = 'Failed';
          }
          ;
        }
      } else {
        echo ('Type Only IN/OUT');
        $out_msg = 'Failed';
      };

    }else{
      echo ("Container Status Not Found");
    };
  };
  echo ($out_msg);
} else {
  echo ("Not Authorized");
  die();
};



?>