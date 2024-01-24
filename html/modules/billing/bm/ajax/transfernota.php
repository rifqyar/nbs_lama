<?php
/**start gagat modify 01 November 2019 **/
require_once SITE_LIB."/esbhelper/class_lib.php";
///$esb = new esbclass();
/**end gagat modify 01 November 2019 ***/

	require_lib ('mzphplib.php');	
	outputRaw();
	date_default_timezone_set('Asia/Jakarta');

	$db=getDb();
    $id=$_POST['id_nota'];
    $NoNota=$_POST['id_nota'];
	$idrpstv=$_POST['id_rpstv'];
    $userid=$_SESSION["PENGGUNA_ID"];
	//print_r($_SESSION);die;
	

function genInvoice($NoNota,$NoUper)
{
	$esb = new esbclass();
	$userid=$_SESSION["PENGGUNA_ID"];
	$param = array(
                in_nota => $NoNota,
                in_userid => $userid,
                out_msg => '',
                out_status => ''        
    );
    //print_r($param); die();
	$db=getDb(); 
	/*cek apakah sudah go live e-invoice */
	$sqlCheck="SELECT COUNT('X') AS JML FROM BILLING.XEINVC_TERMINAL_LIVE_EINVOICE TE WHERE TE.EINVOICE='Y' AND 
	TRUNC(SYSDATE) BETWEEN TE.START_EINVOICE AND NVL(TE.ENDDATE_EINVOICE,SYSDATE) ";	
	$run_CheckLive=$db->query($sqlCheck);
	$rs_CheckLive=$run_CheckLive->fetchRow();
	if($rs_CheckLive['JML']>0){
		
		//gagat modify 20 februari 2020 
		$q_Header="SELECT H.ID_NTSTV,H.TRX_NMB NO_REQUEST,
					H.ORG_ID,
					H.TRX_NMB NO_NOTA,
					('IDR') SIGN_CURRENCY,
					H.CUST_NO,
					H.HDR_CTX JENIS_MODUL,
					H.HDR_SUB_CTX KD_MODUL,
					(H.DPP_) TOTAL,
					H.TTL_ KREDIT,('050') KD_CABANG_SIMKEU,
					H.CUST_NAME,H.CUST_ADDR,
					H.CUST_TAX_NO CUST_NPWP,
					H.VESSEL,
					(H.VOY_IN||' - '||H.VOY_OUT) VOYAGE_IN_OUT,
					TO_CHAR(TO_DATE(H.ATA,'YYYYMMDDHH24MISS'),'RRRR-MM-DD') TANGGAL_TIBA,
					TO_CHAR(TO_DATE(H.ATD,'YYYYMMDDHH24MISS'),'RRRR-MM-DD') TANGGAL_BERANGKAT,
					H.ID_RPSTV,
					(H.PPN_) AS PPN,					
					(CASE WHEN SUBSTR(H.TRX_NMB,1,3)='010' THEN 
                    H.PPN_ 
                    ELSE 0 END )AS PPN_DIPUNGUT_SENDIRI,
                     (CASE WHEN SUBSTR(H.TRX_NMB,1,3)='030' THEN 
                    H.PPN_ 
                    ELSE 0 END )AS PPN_DIPUNGUT_PEMUNGUT,
                      (CASE WHEN SUBSTR(H.TRX_NMB,1,3)='070' THEN 
                    H.PPN_ 
                    ELSE 0 END )AS PPN_TIDAK_DIPUNGUT,
                     (CASE WHEN SUBSTR(H.TRX_NMB,1,3)='080' THEN 
                    H.PPN_ 
                    ELSE 0 END )AS PPN_DIBEBASKAN /**modif gagat 21 SEP 2020*/	
		FROM BIL_NTSTV_H H WHERE H.TRX_NMB = '$NoNota' ";
		///print_r($q_Header);die();
	$run_Header=$db->query($q_Header);
	$rs=$run_Header->fetchRow();
	
	//lines transaksi 
	$q_lines="select ('".$NoNota."') KD_PERMINTAAN,('".$NoNota."') KD_UPER,SQ_ARR LINE_NUMBER,
	D.SVC_TY AS TIPE_LAYANAN,D.TAX_FLAG,D.TTL_FARE TOTTARIF,NVL(D.TAX_AMOUNT,0) TAX_AMOUNT,D.NT_CT AS URAIAN,D.EI,
	D.FARE_ AS TARIF,D.QTY_RPSTV AS QTY,
	(D.SIZE_CONT||' '||D.TYPE_CONT||' '||D.STS_CONT||' '||D.HZ_CONT) SIZE_TYPE_STAT_HAZ,
	D.TY_CC CRANE from BIL_NTSTV_D D where D.ID_NTSTV = '".$rs['ID_NTSTV']."' ";
	//print_r($q_lines);
	$run_Lines=$db->query($q_lines);
	$rowdetail=$run_Lines->GetAll();
	
	
	/**start modif get materai gagat 27 JUL 2020*/
	$sqlMaterai="SELECT TTL_FARE AS BEA_MATERAI from BIL_NTSTV_D D where D.ID_NTSTV = '".$rs['ID_NTSTV']."' AND NT_CT='MATERAI'";
	
	
	$db=getDb(); 
	$run_Materai=$db->query($sqlMaterai);
	$rs_materai=$run_Materai->fetchRow();
	if($rs_materai['BEA_MATERAI']>0){
		$beamaterai = $rs_materai['BEA_MATERAI'];		
	}else{
		$beamaterai = 0;
	}
	/**end modif get materai gagat 27 JUL 2020*/
	
	
		$kirimEsb = $esb->nbsOPUSArInvoice($rs,$beamaterai,$rowdetail,$rsamount);
		$kirimEsbResponese = json_decode($kirimEsb, true);
		$status = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
		$erroMessage = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];
		//print_r($status);
		//print_r($erroMessage); die();
		if($status=='S'){
				/**gagat update status transfer 05 januari 2020 **/
				/**update bil_ntstv_h*/
				$q_ntstv="UPDATE BIL_NTSTV_H SET STS_RPSTV='T', 
						TRF_DATE=SYSDATE, TRF_USER_ID='".$userid."', 
						TRF_RESPONSE='Generate Invoice Success (invoice : ".$id.")' 
					WHERE TRX_NMB='$id' ";
				$run_ntstv=$db->query($q_ntstv);	
				/**update tabel bil_rpstv_h**/
				$q_rpstv="UPDATE BIL_RPSTV_H SET STS_RPSTV='T' WHERE ID_RPSTV='".$rs['ID_RPSTV']."' ";
				$run_rpstv=$db->query($q_rpstv);
				/*end update status transfer */
				echo ('T');
				
		}else{
				echo ('F');
		}
       
   
	
    }else{
		
		$query = "declare begin nbs_transferariptk_bm(:in_nota, :in_userid, :out_msg, :out_status ); end;";
		$db->query($query,$param);
		$messagetransfer=$param["out_msg"];
		$statustransfer=$param["out_status"];
		echo $param["out_status"];
		
		//echo 'T';
	}
}


function callACRelease($NoUper, $NoNota, $to_account){
	//print_r('a');die;
	$db = getDB();   	
	$date = date('dm');

	$sql_get_param	= "SELECT
							A.EXTERNAL_ID,
							A.COMPANY_ID,
							A.NOTAJB_ID,
							A.NOMINAL_UPER,
							A.PERSEN_HOLD,
							A.NOMINAL_HOLD,
							A.CURRECY,
							A.LOG_DATE,
							A.REFERENCE_ID,
							A.PBM_ID,
							A.UKK,
							B.NOREK_IDR,
							B.BANK_IDR,
							C.*,
							D.STATEMENTS,
                            D.RESPOND,
                            D.MESSAGE
							,B.EMAIL
						FROM
							AC_BARANG_LOG A JOIN AC_REKENING_USER B ON A.COMPANY_ID = B.CUSTOMER_ID JOIN AC_MST_BANK C ON A.BANK_ID = C.KD_BANK 
							AND C.ORG_ID = '1827' JOIN AC_PARAMETER_LOG D ON A.NOTAJB_ID = D.NO_UPER 
						WHERE
							A.NOTAJB_ID = '".$NoUper."' 
							AND D.RESPOND = 'false'
							order by log_date desc";

	$rs_get_param   = $db->query($sql_get_param);
	$get_param     	= $rs_get_param->fetchRow();
	$accountNumber	= $get_param['NOREK_IDR'];
	$bank_id		= $get_param['BANK_IDR'];
	$valueDate		= date('Y-m-d', strtotime($get_param['LOG_DATE']));
	
	// $cek_currency_ipc 	 	= "SELECT NOREK_IDR FROM AC_REKENING_USER WHERE KD_AGEN = '".$to_account."' AND ORG_ID='1827' AND BANK_IDR='".$get_param['BANK_IDR']."'";
	$cek_currency_ipc 	 	= "SELECT NOREK_IDR FROM AC_REKENING_USER WHERE CUSTOMER_ID ='IPCTPK' AND BANK_IDR='".$bank_id."'";
	$cek_info_curr_ipc  	= $db->query($cek_currency_ipc);
	$hasil_curr_ipc		 	= $cek_info_curr_ipc->fetchRow();
	$rekeningipc 			= $hasil_curr_ipc['NOREK_IDR'];

	// start calculate deductamount refer to prn_dtl_notapkt.php 
	// $sql_get_amount 		= "SELECT n.*,(n.V_TOBEPAID1-n.V_TOBEPAID) as SISA, S.NAME AS SHIPPERNAME, S.ADDRESS, S.COMPANY_ID, S.TAX_ID,x.TERMINAL ,SYSDATE , (n.optr || '/' || to_char (n.dedit, 'DDMMYYHH24MISS')) as MAGICNO FROM TTM_NOTAJB n, TR_COMPANY S,TTM_ORDER x where n.ORDER_ID=x.ORDER_ID and n.COMPANY_ID=s.COMPANY_ID and n.NOTAJB_ID='".$NoUper."'" ;
	$sql_get_amount			= "SELECT
								A.*,
								A.CUST_NAME AS SHIPPERNAME,
								A.CUST_ADDR AS ADDRESS,
								C.COMPANY_ID,
								A.CUST_TAX_NO,
								A.CUST_NO AS TERMINAL,
								SYSDATE,
								('/'|| TO_CHAR (A.SV_DATE,'DDMMYYHH24MISS')) AS MAGICNO
								FROM 
								BIL_NTSTV_H A
								INNER JOIN OPUS_REPO.M_VSB_VOYAGE B ON A.ID_VSB_VOYAGE = B.ID_VSB_VOYAGE
								INNER JOIN UPER_H C ON C.NO_UKK = to_char(B.ID_VSB_VOYAGE)
								WHERE TRX_NMB = '$NoNota'";
	//print_r($sql_get_amount);
	$rs_get_amount 			= $db->query($sql_get_amount);
	$row_get_amount 		= $rs_get_amount->fetchRow();
	
	// $nominal_payment = $nominal_tagihan - $nominal_sharing;
	$nominal_payment =  $row_get_amount['TTL_'];
	// stop calculate deductamount refer to prn_dtl_notapkt.php 

	// $releaseAmount = round($get_param['NOMINAL_UPER'] * $get_param['PERSEN_HOLD'] / 100);
	// 27 Maret 2018 Dedi - Perubahan nominal release
	$releaseAmount = $get_param['NOMINAL_HOLD'];
	$email = $get_param['EMAIL'];

	// 02 Agustus 2018 Dedi - Perubahan external id menggunakan no nota
	// $externalId	= $get_param['EXTERNAL_ID'];
	$externalId = preg_replace('/[^A-Za-z0-9]/', '', $NoNota);
	$date_trx = date('Ymdhis');

	require_once SITE_LIB."/eService/eService.php";
	$client = new eService();

	/*require_lib('acl.php');	
	$_acl = new ACL();
	$_acl->load();
	$li = $_acl->getLogin();
	$rendal = $li->info["REALNAME"];
	$userid = $li->info["USERID"];
	$kd_cabang = $li->info["KD_CABANG"];*/


	$userid 		= $_SESSION['NAMA_PENGGUNA'];
	$kd_cabang 		= '05';
	$service_name	= "paymentrelease";

	//genInvoice($NoNota,$NoUper);
	
	$sub_param = array ( "BulkFTORequest" => array (
						"body" => array (
							"externalId" => "$externalId", 
							"debitAccount" => "$accountNumber", // 0115476117 $accountNumber
							"creditAccount" => "$rekeningipc",
							"valueAmount" => "$nominal_payment", // jumlah bayar
							"valueCurrency" => $get_param['CURRECY'],
							"releaseAmount" => "$releaseAmount", // jumlah release
							"releaseReference" => $get_param['REFERENCE_ID'],
							"valueDate" => "$valueDate",
							"remark1" => "$NoNota",
							"remark2" => $get_param['COMPANY_ID'],
							"remark3" => "$email",
							"remark4" => "-"
							)
						)
					);	
	
	$parameter = array( "request" =>
							array(
								"language" => $get_param['LANGUAGE'], 
								"trxDateTime" => "$date_trx",
								"transmissionDateTime" => "$date_trx",
								"prodCode" => $get_param['PROD_CODE'],
								"channelCode" => $get_param['CHANNEL_CODE'],
								"nmrTrx" => array2xml($sub_param),
								"transactionCode" => $get_param['TRANSACTION_CODE'],
								"aid" => $get_param['AID'],
								"fid" => $get_param['FID'],
								"terminalCode" => $get_param['TERMINAL_CODE'],
								"branchCode" => $get_param['BRANCH_CODE'],
								"locationCode" => $get_param['LOCATION_CODE'],
								"currencyCode" => $get_param['CURRENCY_CODE'],
								"feeAdmin" => $get_param['FEE_ADMIN']
							)
						);

	//var_dump($parameter);die;
	
	// Insert log
	$insert_log_parameter = "INSERT INTO AC_PARAMETER_LOG (LOG_DATE,NO_UPER,KD_SERVICE_CODE,STATEMENTS,USER_ID,RESPOND,MESSAGE,RESPOND_DATE,REFERENCE_ID, EXTERNAL_ID, KD_BANK, KURS, KURS_DATE) VALUES (SYSDATE,'".$NoUper."','1','call($service_name,$externalId^$accountNumber^$releaseAmount^3600^IDR^$nominal_payment^$to_account^$rekeningipc^)','$userid','','','','','$externalId','$bank_id','','')";

	//print_r($insert_log_parameter);	
	
	if ($db->query($insert_log_parameter)) {
		// echo "log parameter auto collection : Berhasil <br><br>";
		// echo "$insert_log_parameter<br><br>";
	} else {
		// echo "log parameter auto collection : Gagal <br><br>";
		// echo "$insert_log_parameter<br><br>";
	}
	
	$response = $client->call_wsdl_via_file(ILCS_AUTOCOLLECTION_SERVICES,$service_name,$parameter,$result);

	//print_r('response: '.$response);

	if(!$response)
	{
		//echo "gagal";
		
		/*$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='true', MESSAGE='".$result."' WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%paymentrelease%' ");*/
		// set alert
		//genInvoice($NoNota,$NoUper);
		
		echo "error;RELEASEGAGAL;$result;";
		//print_r('aaa');
		die();
	}
	else
	{
		//print_r('bbb');
		//print_r($NoUper);
		//print_r($result["paymentreleaseResult"]);
		$data = $result["paymentreleaseResult"]["billInfo1"];
		//print_r($data);die;
		//die;
		if (empty($data)) {
			echo "true;RELEASEGAGAL;Services return null value, Invalid XML Format;";
			//print_r($data['status']['statusDescription']);
			die();
		} else {
			$isError = $result["paymentreleaseResult"]["status"]["isError"];
			$errorCode = trim($result["paymentreleaseResult"]["status"]["errorCode"]);
			$statusDescription = str_replace('doesn\'t', 'does not', $result["paymentreleaseResult"]["status"]["statusDescription"]);

			$xml_data = new SimpleXMLElement($data);
			// error_reporting(E_ERROR | E_WARNING | E_PARSE);
			$releaseReference  = $xml_data->body->holdReference;
			
			if ($isError == "false") {
				$insert_log_barang = "INSERT INTO AC_BARANG_LOG (EXTERNAL_ID, SERVICE, COMPANY_ID, NOTAJB_ID, NOMINAL_UPER, PERSEN_HOLD, NOMINAL_HOLD, NOMINAL_TAGIHAN, NOMINAL_SHARING, NOMINAL_PAYMENT, NOMINAL_RELEASE,CURRECY,BANK_ID,USER_ID,KD_SERVICE_CODE,PBM_ID,UKK,KD_CABANG,REFERENCE_ID,LOG_DATE,KURS, KURS_DATE, NO_NOTA, CREDIT_ACCOUNT) VALUES ('$externalId', 'RELEASE', '".$get_param['COMPANY_ID']."','".$get_param['NOTAJB_ID']."', '".$get_param['NOMINAL_UPER']."', '".$get_param['PERSEN_HOLD']."', '".$get_param['NOMINAL_HOLD']."', '$nominal_tagihan', '$nominal_sharing', '$nominal_payment', '$releaseAmount','IDR','$bank_id','$userid','01', '".$get_param['PBM_ID']."','".$get_param['UKK']."','$kd_cabang','$releaseReference',SYSDATE,'','', '$NoNota', '$to_account' )";
				if ($db->query($insert_log_barang)) {
					$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='".$isError."', MESSAGE='".$statusDescription."', RESPOND_DATE = SYSDATE, REFERENCE_ID = '".$releaseReference."' WHERE LOG_DATE = (select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%paymentrelease%')");

					// insert status payment
					$sql_statpayment = "INSERT INTO AC_PAYMENT_STATUS  (NOTAJB_ID, NO_NOTA, JENIS_NOTA, STATUS, USER_ID, KD_CABANG, LOG_DATE)
						VALUES ('".$NoUper."', '".$NoNota."', 'BONGKAR MUAT', 'PAID', '".$userid."', '01', SYSDATE)";
					$db->query($sql_statpayment);
					
					// generate invoice after success release bank
					//genInvoice($NoNota,$NoUper,$errorCode, $statusDescription);
					genInvoice($NoNota,$NoUper);
					
					// genInvoiceByPass($NoNota, $errorCode, $statusDescription);
					// echo "$errorCode;RELEASEOK;$statusDescription;";
				} else{
					$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='".$isError."', MESSAGE='".$statusDescription."', RESPOND_DATE = SYSDATE, REFERENCE_ID = '".$releaseReference."' WHERE LOG_DATE = (select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%paymentrelease%')");
					// set alert
					//echo "$errorCode;RELEASEGAGAL;$statusDescription;";
					//die();
				}
			}
			else{
				$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='".$isError."', MESSAGE='".$statusDescription."', RESPOND_DATE = SYSDATE, REFERENCE_ID = '".$releaseReference."' WHERE LOG_DATE = (select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%paymentrelease%')");
				// set alert
				//echo "$errorCode;RELEASEGAGAL;$statusDescription;";
				//die();
			}
		}
	}	
}

	

#cek autocolection
$sql_CekAc = "SELECT
 C.NO_UPER,
    C.COMPANY_ID,
    ( SELECT 'Y' FROM AC_REKENING_USER WHERE CUSTOMER_ID = C.COMPANY_ID ) AC,
    A.ID_VSB_VOYAGE	
FROM
    BIL_NTSTV_H A
INNER JOIN opus_repo.M_VSB_VOYAGE B ON A.ID_VSB_VOYAGE = B.ID_VSB_VOYAGE
INNER JOIN UPER_H C ON C.NO_UKK = to_char(B.ID_VSB_VOYAGE)
WHERE A.TRX_NMB = '".$NoNota."'";

//print_r($sql_CekAc);die;
$rs_CekAc 	= $db->query($sql_CekAc);
$row_CekAc  = $rs_CekAc->fetchRow();
$cek_Ac		= $row_CekAc['AC'];
$NoUper     = $row_CekAc['NO_UPER'];
$company_id = $row_CekAc['COMPANY_ID'];
$id_user    = $_SESSION['PENGGUNA_ID'];
$ID_VSB_VOYAGE    = $row_CekAc['ID_VSB_VOYAGE'];

$get_header = "SELECT * FROM BIL_NTSTV_H WHERE TRX_NMB = '".$NoNota."'";
$row_header = $db->query($get_header)->fetchRow();

//$query_cek = "SELECT COUNT(1) JML FROM BIL_NTSTV_H WHERE ID_VSB_VOYAGE = '$ID_VSB_VOYAGE' AND STS_RPSTV IN ('X','T')";
/*$query_cek = "select count(*) JML 
				from  ac_barang_log a
				join uper_h b on b.no_uper = a.NOTAJB_ID 
				where no_ukk='$ID_VSB_VOYAGE' and service='RELEASE' and a.NOTAJB_ID= '$NoUper'";*/
$query_cek = "select count(*) JML 
				from AC_PAYMENT_STATUS
				where NOTAJB_ID= '$NoUper' and status='PAID'";				

$query_cek = "SELECT COUNT(1) JML FROM BIL_NTSTV_H WHERE ID_VSB_VOYAGE = '$ID_VSB_VOYAGE' AND STS_RPSTV IN ('X','T')";

//$query_cek = "SELECT COUNT(1) JML FROM BIL_NTSTV_H WHERE ID_VSB_VOYAGE = '$ID_VSB_VOYAGE' AND STS_RPSTV IN ('X','T')";
/*$query_cek = "select count(*) JML 
				from  ac_barang_log a
				join uper_h b on b.no_uper = a.NOTAJB_ID 
				where no_ukk='$ID_VSB_VOYAGE' and service='RELEASE' and a.NOTAJB_ID= '$NoUper'";*/
$query_cek = "select count(*) JML 
				from AC_PAYMENT_STATUS
				where NOTAJB_ID= '$NoUper' and status='PAID'";	

$rs_Cek 	= $db->query($query_cek);
$row_Cek  = $rs_Cek->fetchRow();

if ($row_Cek['JML'] > 0)
{
	//print_r('c');
	$cek_Ac = 'N';
}
$query_cek_bypass = "SELECT COUNT(1) JML FROM AC_BYPASS_LOG A
        JOIN UPER_H B ON A.NOTAJB_ID = B.NO_UPER AND (USER_LUNAS NOT IN ('X') or USER_LUNAS is null)
        WHERE B.NO_UKK ='$ID_VSB_VOYAGE'";
$rs_Cek_bypass 	= $db->query($query_cek_bypass);
$row_Cek_bypass = $rs_Cek_bypass->fetchRow();
if ($row_Cek_bypass['JML'] > 0)
{
	//print_r('c');
	$cek_Ac = 'N';
}
	
if($cek_Ac == 'Y'){
	
	//print_r('a');die;
	callACRelease($NoUper, $NoNota,'IPCTPK');
	
}	
else
{
	//print_r('b');die;
	genInvoice($NoNota,$NoUper);
	die();
}


//print_r($cek_Ac);die;
	
	/*$param = array(
                in_nota => $id,
                in_userid => $userid,
                out_msg => '',
                out_status => ''        
    );
    //print_r($param); die();
    $query = "declare begin nbs_transferariptk_bm(:in_nota, :in_userid, :out_msg, :out_status ); end;";
    $db->query($query,$param);
	$messagetransfer=$param["out_msg"];
    $statustransfer=$param["out_status"];
	echo $param["out_status"];*/
	
	
	
	
?>