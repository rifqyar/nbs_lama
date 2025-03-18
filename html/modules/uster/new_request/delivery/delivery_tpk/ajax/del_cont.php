<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"];
$no_req2	= $_POST["NO_REQ2"];
$ex_bp		= $_POST["EX_BP"];

//echo $no_req2.$no_cont.$no_req;
//die();

$query_del_nbs	= "DELETE FROM BILLING_NBS.req_receiving_d WHERE NO_CONTAINER = '$no_cont' AND ID_REQ = '$no_req2'";

// $qves = "select o_vessel, o_voyin, o_voyout from request_delivery where no_request = '$no_req'";
$qves = "SELECT
			rd.o_vessel,
			rd.o_vessel,
			rd.o_voyin,
			rd.o_voyout,
			vpc.VOYAGE,
			vpc.KD_KAPAL as VESSEL_CODE
		FROM request_delivery rd
		INNER JOIN V_PKK_CONT vpc ON vpc.NO_BOOKING = rd.NO_BOOKING
		WHERE
			rd.NO_REQUEST = '$no_req'";
$rves = $db->query($qves)->fetchRow();
$vessel  = $rves['O_VESSEL'];
$voyage_in  = $rves['O_VOYIN'];
$voyage_out  = $rves['O_VOYOUT'];
/*
$qvcode = "SELECT VESSEL_CODE, VOYAGE, OPERATOR_ID FROM M_VSB_VOYAGE@DBINT_LINK WHERE VESSEL = '$vessel' AND VOYAGE_IN = '$voyage_in' AND VOYAGE_OUT = '$voyage_out'";
$rvcode = $db->query($qvcode)->fetchRow();
*/
$vessel_code = $rves['VESSEL_CODE'];
$voyage = $rves['VOYAGE'];
$qcekop = "SELECT CARRIER FROM BILLING_NBS.REQ_RECEIVING_D WHERE ID_REQ = '$no_req2' AND NO_CONTAINER = '$no_cont'";
$rcekop = $db->query($qcekop)->fetchRow();
$operatorid = $rcekop['CARRIER'];
$param_b_var= array(	
							"v_nocont"=>TRIM($no_cont),
							"v_req"=>TRIM($no_req2),
							"flag"=>"REC",
							"vessel"=>"$vessel_code",
							"voyage"=>"$voyage",
							"operatorId"=>"$operatorid",
							"v_response"=>"",
							"v_msg"=>""
							);

// echo var_dump($param_b_var);die;
$query_ops = "declare begin BILLING_NBS.proc_delete_cont(:v_nocont, :v_req, :flag, :vessel, :voyage, :operatorId, :v_response, :v_msg); end;";

$query_del	= "DELETE FROM CONTAINER_DELIVERY WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";

$history        = "DELETE FROM history_container 
							WHERE NO_CONTAINER = '$no_cont' 
								AND NO_REQUEST = '$no_req' 
								AND KEGIATAN = 'REQUEST DELIVERY'";
if($db->query($query_ops,$param_b_var)) {
    $cekmsg = $param_b_var['v_response'];
    if($cekmsg == 'OK'){
        $db->query($query_del);
        $db->query($history);
        if($db->query($query_del_nbs))
        {
           echo 'OK';
        }
    }    
    else 
    { 
        echo "GAGAL DEL TPK"; exit;
    }

}
else 
{ 
    echo "GAGAL DEL TPK"; exit;
}
?>