<?php
    $db = getDB();
    
    $id_req = $_POST['id_req'];
    $NO_CONT = $_POST['nocont'];
    $TRUCK_ID =$_POST['truckid'];

    
    $param = array(
                in_truck => $TRUCK_ID,
                in_nocont => $NO_CONT,
                in_noreq => $id_req,
                out_rsp  => "",
                out_msg  => ""
        );
    //print_r($param); die();
    $query = "declare begin PROC_CANCEL_TCA(:in_truck,:in_nocont,:in_noreq,:out_rsp,:out_msg); end;";
    $db->query($query,$param);
    $param_msg = array(
        out_rsp => $param['out_rsp'],
        out_msg => $param['out_msg']
    );
    echo json_encode($param_msg); die();
    
?>