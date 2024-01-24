<?php
    $db = getDB();
    
    $id_req = $_POST['id_req'];
    $total  = $_POST['total'];
    $NO_CONT = $_POST['nocont'];
    $TRUCK_ID =$_POST['truckid'];
    $PIN_NUM =$_POST['pinnumber'];
    $TERMINAL =$_POST['terminal'];

    //print_r($NO_CONT); 
    //print_r($TRUCK_ID); die();
    
    $selectedcont = count($NO_CONT);
    
    for($j=0;$j<$selectedcont;$j++){
        $param = array(
                    in_truck => $TRUCK_ID[$j],
                    in_nocont => $NO_CONT[$j],
                    in_pinnumber => $PIN_NUM[$j],
                    in_noreq => $id_req,
                    in_terminal => $TERMINAL,
                    out_rsp  => "",
                    out_msg  => ""
            );
        //print_r($param); die();
        $query = "declare begin PROC_SAVE_TCA(:in_truck,:in_nocont,:in_pinnumber,:in_noreq,:in_terminal,:out_rsp,:out_msg); end;";
        $db->query($query,$param);
        $param_msg[$j] = array(
            out_rsp => $param['out_rsp'],
            out_msg => $param['out_msg']
        );
    }
    echo json_encode($param_msg); die();
    
?>