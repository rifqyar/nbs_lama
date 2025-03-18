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
$mti_nota = $_POST["MTI"];

// adding by firman 25 nov 2020
$no_mat= $_POST["NO_PERATURAN"];
//print_r($no_mat); die();
$flag_opus = 0;
//echo 'jenisnya: '.$jenis;die;




// save_payment_uster($id_req, $jenis, $bank_id);
// die();

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
        // if($jenis == 'STRIPPING'){
        //     save_payment_uster($id_req, $jenis, $bank_id);
        // }
    }
    else if($jenis == 'STUFFING' || $jenis == 'PERP_PNK'){
        $q = "select asal_cont from container_stuffing WHERE no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['ASAL_CONT'] == 'TPK'){
            $flag_opus = 1;
            $ropus = "select o_reqnbs from request_stuffing where no_request = '$id_req'";
            $mopus = $db->query($ropus)->fetchRow();
            $reqopus = $mopus['O_REQNBS'];

            // if($jenis == 'STUFFING'){
            //     save_payment_uster($id_req, $jenis, $bank_id);
            // }
        }
    }
    else if($jenis == 'DELIVERY'){
        $q = "select delivery_ke, no_req_ict from request_delivery where no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['DELIVERY_KE'] == 'TPK'){
            $flag_opus = 1;
            $reqopus = $r['NO_REQ_ICT'];

            // save_payment_uster($id_req, $jenis, $bank_id);
        }
    }
    else if($jenis == 'BATAL_MUAT'){
        $q = "select status_gate,o_reqnbs from request_batal_muat where no_request = '$id_req'";
        $r = $db->query($q)->fetchRow();
        if($r['STATUS_GATE'] == '2'){
            $flag_opus = 1;
            $reqopus = $r['O_REQNBS'];

            // save_payment_uster($id_req, $jenis, $bank_id);
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
    // if ($rw_val['JUM'] == 0) {       */
    //  /*$param_payment= array(
    //                   "ID_REQ"=>$id_req,
    //                   "IN_PROFORMA"=>$id_nota,
    //                   "IN_MODUL"=>$jenis,
    //                   "IN_EMKL"=>$emkl,
    //                   "IN_KOREKSI"=>$koreksi,
    //                   "IN_BANKID"=>$bank_id,
    //                   "IN_BAYAR"=>$via,
    //                   "IN_USER"=>$user
    //                  );
    //  $query="declare begin pack_payment.generate_header(:ID_REQ,:IN_PROFORMA,:IN_MODUL,:IN_EMKL,:IN_KOREKSI,:IN_BANKID,:IN_BAYAR,:IN_USER); end;";
    //  $db->query($query,$param_payment);*/
    
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
        "IN_JUM" =>$jum,
        "MTI_NOTA" =>$mti_nota,
        "IN_MAT" =>$no_mat,
        "INOUT_TRXNUMBER" => ''
       );//print_r($param_payment);die();
    $sql_xpi = "BEGIN USTER.ITPK_POPULATE_STAGING.INSERT_NOTA_ITPK ( :ID_REQ, :IN_MODUL, :IN_PROFORMA, :IN_IDNOTA, :IN_KOREKSI, :IN_USER, :IN_BANKID, :IN_BAYAR, :IN_EMKL,:IN_JUM, :MTI_NOTA,:IN_MAT ,:INOUT_TRXNUMBER); 
    END; ";
    
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

    //    echo "S";

        // $kirimreceipt = $esb->usterReceipt($rs);
        // $kirimEsbreceiptResponese = json_decode($kirimreceipt, true);
        // $responseReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
        // $erroMessageReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];        
        
        $responseReceipt = 'S';
        $erroMessageReceipt = 'Sukses';

        if ($responseReceipt == "S") {
            // echo "-S";
            if ($jenis=='STRIPPING' || $jenis=='DELIVERY' || $jenis=='STUFFING' || $jenis == 'BATAL_MUAT') {
                // echo "--S";
                save_payment_uster($id_req, $jenis, $bank_id);
            }  
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

function save_payment_uster($id_request, $jenis_payment, $bank_id){
    set_time_limit(360);

    try {

        $curl = curl_init();
        /* set configure curl */
        // $authorization = "Authorization: Bearer $token";
        $payload_request = array(
            "ID_REQUEST" => $id_request,
            "JENIS" => $jenis_payment,
            "BANK_ACCOUNT_NUMBER" => $bank_id,
            "PAYMENT_CODE" => ""
        );
        // echo json_encode($payload_request) . '<<payload_req';
        $url = HOME.APPID."/save_payment_external";
        // echo var_dump($url) . "<< test";
        // die();
        curl_setopt_array(
        $curl,
        array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => json_encode($payload_request),
            CURLOPT_HTTPHEADER      => array(
            "Content-Type: application/json"
            ),
        )
        );


        $response = curl_exec($curl);

        // echo $response . "<< aftercurl";

        if ($response === false) {
            throw new Exception(curl_error($curl));
        }

        // Get HTTP status code
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        //Success
        if ($httpCode >= 200 && $httpCode < 300) {
            $response_curl = array(
                'status'   => 'success',
                'httpCode' => $httpCode,
                'response' => $response
            );
        } else if ($httpCode >= 400 && $httpCode < 500) {
            //Client Error
            $response_curl = array(
                'status'   => 'error',
                'httpCode' => $httpCode,
                'response' => $response
            );
        } else {
            //Server Error
            throw new Exception('HTTP Server Error: ' . $httpCode);
        }

        /* execute curl */
        curl_close($curl);

        return $response_curl;

    } catch (Exception $e) {
        // echo $e . "<< error-aftercurl";
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $response_curl = array(
            'status'   => 'error',
            'httpCode' => $httpCode,
            'response' => "cURL Error # " . $e->getMessage()
        );

        return $response_curl;
    }
}
