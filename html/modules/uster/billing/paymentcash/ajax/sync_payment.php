<?php
$id_nota=$_POST['IDN'];
$id_req=$_POST['IDR'];
$jenis = $_POST['KG'];
//echo $jenis; die();
$db=getDb('dbint');
$db2=getDb('storage');
if ($jenis=='STRIPPING' || $jenis=='DELIVERY' || $jenis=='STUFFING' || $jenis=='PERP_STRIP' || $jenis=='PERP_PNK'|| $jenis == 'BATAL_MUAT') {
    $db=getDb("storage");
    if($jenis == 'STRIPPING' || $jenis == 'PERP_STRIP'){
        $q = "select via from container_stripping WHERE no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        //if($r['VIA'] == 'TPK'){
            $flag_opus = 1;
            $ropus = "select o_reqnbs from request_stripping where no_request = '$id_req'";
            $mopus = $db->query($ropus)->fetchRow();
            $reqopus = $mopus['O_REQNBS'];
        //}
        //echo $reqopus; die();
    }
    else if($jenis == 'STUFFING' || $jenis == 'PERP_PNK'){
        $q = "select asal_cont from container_stuffing WHERE no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['ASAL_CONT'] == 'TPK'){
            $flag_opus = 1;
            $ropus = "select o_reqnbs from request_stuffing where no_request = '$id_req'";
            $mopus = $db->query($ropus)->fetchRow();
            $reqopus = $mopus['O_REQNBS'];
        }
    }
    else if($jenis == 'DELIVERY'){
        $q = "select delivery_ke, o_reqnbs no_req_ict from request_delivery where no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['DELIVERY_KE'] == 'TPK'){
            $flag_opus = 1;
            $reqopus = $r['NO_REQ_ICT'];
        }
        //echo $reqopus; die();
    }
    else if($jenis == 'BATAL_MUAT'){
        $q = "select status_gate,o_reqnbs from request_batal_muat where no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['STATUS_GATE'] == '2'){
            $flag_opus = 1;
            $reqopus = $r['O_REQNBS'];
        }
    }

//echo $reqopus; die();
    if($flag_opus == 1){
        $db3=getDb('dbint');
        $param_payment2= array(
                     "ID_NOTA"=>$id_nota,
                     "ID_REQ"=>$reqopus,
                     "OUT"=>'',
                     "OUT_MSG"=>''
                    );
        $query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";

    $db3->query($query2,$param_payment2);
    }else{
        $param_payment2["OUT"]='S';
    }
}
if($param_payment2["OUT"]=='S')
{
	echo "Sukses";
}
ELSE
{
	echo 'failed '.$param_payment2["OUT_MSG"];
}
?>