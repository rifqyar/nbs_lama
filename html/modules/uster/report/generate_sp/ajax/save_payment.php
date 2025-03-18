<?php
//ESB Implementasi Include Class
include 'esbhelper/class_lib.php';
$esb = new esbclass();
//===END ESB===

$id_nota=$_POST['IDN'];
$id_req=$_POST['IDR'];
$jenis=$_POST['JENIS'];
$emkl=$_POST['EMKL'];
$koreksi=$_POST['KOREKSI'];
$bank_id = $_POST['BANK_ID'];
$via = $_POST['VIA'];
$user = $_SESSION['PENGGUNA_ID'];
$jum = $_POST["JUM"];
$flag_opus = 0;
//echo 'jenisnya: '.$jenis;die;
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
        $q = "select delivery_ke, no_req_ict from request_delivery where no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['DELIVERY_KE'] == 'TPK'){
            $flag_opus = 1;
            $reqopus = $r['NO_REQ_ICT'];
        }
    }
    else if($jenis == 'BATAL_MUAT'){
        $q = "select status_gate,o_reqnbs from request_batal_muat where no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['STATUS_GATE'] == '2'){
            $flag_opus = 1;
            $reqopus = $r['O_REQNBS'];
        }
    }
    
    
    if($flag_opus == 1){
        //echo $reqopus; die();
        $db2=getDb('dbint');
        $param_payment2= array(
                             "ID_NOTA"=>$id_nota,
                             "ID_REQ"=>$reqopus,
                             "OUT"=>'',
                             "OUT_MSG"=>''
                            );
        $query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";

        $db2->query($query2,$param_payment2);
    }
    else{
        $param_payment2["OUT"]='S';
    }
}
else
{
	$param_payment2["OUT"]='S';
}


if($param_payment2["OUT"]=='S')
{
	$db=getDb("storage");
	// /*$q_valid = "SELECT COUNT(NO_NOTA) JUM FROM TTH_NOTA_ALL2 WHERE NO_NOTA = '$id_nota'";
	// $r_valid = $db->query($q_valid);
	// $rw_val  = $r_valid->fetchRow();
	// if ($rw_val['JUM'] == 0) {		*/
	// 	/*$param_payment= array(
	// 					 "ID_REQ"=>$id_req,
	// 					 "IN_PROFORMA"=>$id_nota,
	// 					 "IN_MODUL"=>$jenis,
	// 					 "IN_EMKL"=>$emkl,
	// 					 "IN_KOREKSI"=>$koreksi,
	// 					 "IN_BANKID"=>$bank_id,
	// 					 "IN_BAYAR"=>$via,
	// 					 "IN_USER"=>$user
	// 					);
	// 	$query="declare begin pack_payment.generate_header(:ID_REQ,:IN_PROFORMA,:IN_MODUL,:IN_EMKL,:IN_KOREKSI,:IN_BANKID,:IN_BAYAR,:IN_USER); end;";
	// 	$db->query($query,$param_payment);*/
    
    // $sql_xpi = "BEGIN
    //           USTER.ITPK_POPULATE_STAGING.INSERT_NOTA_ITPK ( '$id_req', '$jenis', '$id_nota', '$id_nota', '$koreksi', '$user','$bank_id','$via','$emkl',$jum ); 
    //         END; ";
    //          // echo $sql_xpi;die;
    // $db->query($sql_xpi);
    // /*}*/

    //ESB Implementasi
    
    $param_payment= array(
        "ID_REQ"=>$id_req,
        "IN_MODUL"=>$jenis,
        "IN_PROFORMA"=>$id_nota,
        "IN_IDNOTA"=>$id_nota,
        "IN_KOREKSI"=>$koreksi,
        "IN_USER"=>$user,
        "IN_BANKID"=>$bank_id,
        "IN_BAYAR"=>$via,
        "IN_EMKL"=>$emkl,
        "IN_JUM" =>$jum
       );
    $sql_xpi = "BEGIN USTER.ITPK_POPULATE_STAGING.INSERT_NOTA_ITPK ( :ID_REQ, :IN_MODUL, :IN_PROFORMA, :IN_IDNOTA, :IN_KOREKSI, :IN_USER, :IN_BANKID, :IN_BAYAR, :IN_EMKL,:IN_JUM); 
    END; ";
    // echo $sql_xpi;die;
    $db->query($sql_xpi,$param_payment);

    $trx_number = $param_payment["OUT_TRX_NUMBER"];
        
    $sql_header = "select distinct * from itpk_nota_header 
		where status in ('2','4a','4b')
			and status_nota=0
			and trx_number = '". $trx_number ."'";
		$rsheader = $db->query($sql_header);
        $rs = $rsheader->fetchRow();

    // $sql_header = "select distinct * from itpk_nota_header where trx_number = '". $trx_number ."'";
    // $rsheader = $db->query($sql_header);
    // $rs = $rsheader->fetchRow();

    $sql_detail = "select * from itpk_nota_detail where trx_number = '" . $trx_number . "'";
    $rsLine = $db->query($sql_detail);
    $rsLines = $rsLine->GetAll();
    
    //$kirimesb = $esb->usterAr($rs,$rsLines);
    //$kirimEsbResponese = json_decode($kirimesb, true);

    //$response = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
    //$erroMessage = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];
	
	$response = 'S';
    $erroMessage = 'Succes';
	

    if ($response == "S"){

        // $kirimreceipt = $esb->usterReceipt($rs);
        // $kirimEsbreceiptResponese = json_decode($kirimreceipt, true);
        // $responseReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
        // $erroMessageReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];        
		
		$responseReceipt = 'S';
        $erroMessageReceipt = 'Sukses';

        if ($responseReceipt == "S") {
            //$kirimApply = $esb->usterApply($rs,$user);
        } else {
            // $del_sql="DELETE FROM ITPK_NOTA_HEADER where TRX_NUMBER = '". $trx_number. "'";
            // $deleteStaging = $db->query($del_sql);
            //$erroMessage = $erroMessageReceipt;
        }
    } else {
        // $del_sql="DELETE FROM ITPK_NOTA_HEADER where TRX_NUMBER = '". $trx_number. "'";
        // $deleteStaging = $db->query($del_sql);
    }
    echo $erroMessage;
    die;
    //END ESB
}
ELSE
{
	echo 'failed '.$param_payment2["OUT_MSG"];
}


?>