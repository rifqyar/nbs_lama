<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_nota	= $_POST["NO_NOTA"];
$no_truck	= $_POST["NO_TRUCK"]; 
$kode_truck	= $_POST["KD_TRUCK"]; 
$no_seal	= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan	= $_POST["KETERANGAN"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];
$id_yard        = $_SESSION["IDYARD_STORAGE"];

$selisih        = "SELECT TRUNC(TO_DATE('$masa_berlaku','DD/MM/YYYY') - SYSDATE) SELISIH FROM dual";
$result_cek	= $db->query($selisih);
$row_cek	= $result_cek->fetchRow();
$selisih_tgl	= $row_cek["SELISIH"];

$peralihan_     = "SELECT DELIVERY_KE, PERALIHAN FROM request_delivery WHERE NO_REQUEST = '$no_req'";
//echo $peralihan_;die;
$result_cek	= $db->query($peralihan_);
$row_cek	= $result_cek->fetchRow();
$peralihan	= $row_cek["PERALIHAN"];
$delivery_ke	= $row_cek["DELIVERY_KE"];

$cek_gate        = "SELECT NO_CONTAINER FROM GATE_OUT WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
//echo $peralihan_;die;
$result_cek	= $db->query($cek_gate);
$row_cek	= $result_cek->fetchRow();
$cek_gate	= $row_cek["NO_CONTAINER"];

//echo $selisih_tgl;
//echo $peralihan;
//echo $delivery_ke;
//echo $no_nota;

if (($no_nota == NULL) && ($peralihan == 'RELOKASI') && ($selisih_tgl < 0) && ($delivery_ke == NULL)) {
    echo "EXPIRED";
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl >= 0) && ($delivery_ke <> 'TPK')){ 
    echo "BLM LUNAS";
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl < 0) && ($delivery_ke <> 'TPK')){ 
    echo "EXPIRED";
} else if (($no_nota == NULL) && ($peralihan == 'RELOKASI') && ($selisih_tgl >= 0) && ($delivery_ke == NULL)) {
    $query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN, ID_YARD) VALUES('$no_req', '$no_cont', '$id_user', SYSDATE, '$no_truck', '$status','$no_seal','$kode_truck','$keterangan','$id_yard')";
   // echo $query_insert;
    $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
    
    $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER) 
                                                      VALUES ('$no_cont','$no_req','GATE OUT',SYSDATE,'$id_user')";
           // echo $history;die;
              $db->query($history);
    
    $selisih        = "SELECT a.NO_REQUEST, a.PERALIHAN, a.RECEIVING_DARI FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING b WHERE a.NO_REQUEST = b.NO_REQUEST AND b.NO_CONTAINER = '$no_cont' AND b.AKTIF = 'Y'";
    $result_cek     = $db->query($selisih);
    $row_cek        = $result_cek->fetchRow();
    $no_req_rec     = $row_cek["NO_REQUEST"];
    //$peralihan      = $row_cek["PERALIHAN"];
    //$rec_dari       = $row_cek["RECEIVING_DARI"];

    $db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_rec'");
    
    if ($peralihan == 'RELOKASI'){
        $data = "INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST) VALUES 
            ('$no_cont','LIFT OFF','$status',SYSDATE,'$no_req')";
        $db->query($data);
        $data2 ="INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST) VALUES 
            ('$no_cont','HAULAGE','$status',SYSDATE,'$no_req')";
        $db->query($data2);
       // echo $data; echo $data2; die;
    }    
    
    $db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    $db->query("UPDATE CONTAINER_DELIVERY SET KELUAR = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    if($db->query($query_insert))
    {
	echo "OK";
    }
} else if (($no_nota == NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl < 0) && ($delivery_ke == 'TPK')) {
      echo "EXPIRED";
} else if (($no_nota == NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl >= 0) && ($delivery_ke == 'TPK')) {
     $query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN, ID_YARD) VALUES('$no_req', '$no_cont', '$id_user', SYSDATE, '$no_truck', '$status','$no_seal','$kode_truck','$keterangan','$id_yard')";
   // echo $query_insert;
    $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
    
    //$db->query("UPDATE HISTORY_CONTAINER SET GATE_OUT = SYSDATE WHERE NO_CONTAINER = '$no_cont' AND NOREQ_DEL = '$no_req'");
      $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER) 
                                                      VALUES ('$no_cont','$no_req','GATE OUT',SYSDATE,'$id_user')";
       
    $selisih        = "SELECT a.NO_REQUEST, a.PERALIHAN, a.RECEIVING_DARI FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING b WHERE a.NO_REQUEST = b.NO_REQUEST AND b.NO_CONTAINER = '$no_cont' AND b.AKTIF = 'Y'";
    $result_cek     = $db->query($selisih);
    $row_cek        = $result_cek->fetchRow();
    $no_req_rec     = $row_cek["NO_REQUEST"];
    $peralihan      = $row_cek["PERALIHAN"];
    $rec_dari       = $row_cek["RECEIVING_DARI"];

    $db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_rec'");
    
    if (($peralihan == 'RELOKASI') && ($rec_dari == '')){
        $db->query("INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST) VALUES 
            ('$no_cont','LIFT OFF','$status',SYSDATE,'$no_req')");
        $db->query("INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, NO_REQUEST, STATUS_CONT, TANGGAL, NO_REQUEST) VALUES 
            ('$no_cont','HAULAGE','$status',SYSDATE,'$no_req')");
    }    
    
    $db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    $db->query("UPDATE CONTAINER_DELIVERY SET KELUAR = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    if($db->query($query_insert))
    {
	echo "OK";
    }
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl < 0) && ($delivery_ke == 'TPK')) {
      echo "EXPIRED";
} else if (($no_nota <> NULL) && ($peralihan <> 'RELOKASI') && ($selisih_tgl >= 0) && ($delivery_ke == 'TPK')) {
     $query_insert	= "INSERT INTO GATE_OUT( NO_REQUEST, NO_CONTAINER, ID_USER, TGL_IN, NOPOL, STATUS, NO_SEAL, TRUCKING, KETERANGAN, ID_YARD) VALUES('$no_req', '$no_cont', '$id_user', SYSDATE, '$no_truck', '$status','$no_seal','$kode_truck','$keterangan','$id_yard')";
  //  echo $query_insert;die;
    $db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
    
    //$db->query("UPDATE HISTORY_CONTAINER SET GATE_OUT = SYSDATE WHERE NO_CONTAINER = '$no_cont' AND NOREQ_DEL = '$no_req'");
      $history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER) 
                                                      VALUES ('$no_cont','$no_req','GATE OUT',SYSDATE,'$id_user')";
       
    $selisih        = "SELECT a.NO_REQUEST, a.PERALIHAN, a.RECEIVING_DARI FROM REQUEST_RECEIVING a, CONTAINER_RECEIVING b WHERE a.NO_REQUEST = b.NO_REQUEST AND b.NO_CONTAINER = '$no_cont' AND b.AKTIF = 'Y'";
    $result_cek     = $db->query($selisih);
    $row_cek        = $result_cek->fetchRow();
    $no_req_rec     = $row_cek["NO_REQUEST"];
    $peralihan      = $row_cek["PERALIHAN"];
    $rec_dari       = $row_cek["RECEIVING_DARI"];

    $db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req_rec'");
    
    if (($peralihan == 'RELOKASI') && ($rec_dari == '')){
        $db->query("INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, KEGIATAN, STATUS_CONT, TANGGAL, NO_REQUEST) VALUES 
            ('$no_cont','LIFT OFF','$status',SYSDATE,'$no_req')");
        $db->query("INSERT INTO HANDLING_PIUTANG (NO_CONTAINER, NO_REQUEST, STATUS_CONT, TANGGAL, NO_REQUEST) VALUES 
            ('$no_cont','HAULAGE','$status',SYSDATE,'$no_req')");
    }    
    
    $db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    $db->query("UPDATE CONTAINER_DELIVERY SET KELUAR = 'Y' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    if($db->query($query_insert))
    {
	echo "OK";
    }
    
}


?>