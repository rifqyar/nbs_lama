<?php
/**/
// Auto Collection


if(isset($_POST['mdl'])){
	if($_POST['mdl']=='btn_sts'){
		//$no_uper = $_POST['no_uper'];
		$db 	 = getDB();
		// $row 	 = $db->query("SELECT * FROM UPER_H WHERE NO_UPER = '".$no_uper."'")->fetchRow();
		$row 	 = $db->query("SELECT * FROM UPER_H WHERE ROWNUM=1 ORDER BY NO_UPER DESC ")->fetchRow();
		echo json_encode($row);
	}elseif($_POST['mdl']=='cek_cust'){
		//print_r($_POST['mdl']);die;
		if(cek_cust($_POST['cust_id'])){
			echo json_encode(' Simpan Dan HOLD ');
		}else{
			echo json_encode(' Simpan ');
		}
	}elseif($_POST['mdl']=='callAc'){
		$db 	 = getDB();
		callAc($_POST['no_uper']);
		//$db->query("UPDATE UPER_H SET USER_LUNAS = 'T' WHERE NO_UPER='$_POST[no_uper]'");
		//echo json_encode('1');
	}elseif ($_POST['mdl']=='btn_cancel') {
		$db 	 = getDB();
		cancelAC($_POST['no_uper']);
		$db->query("UPDATE UPER_H SET USER_LUNAS = 'X' WHERE NO_UPER='$_POST[no_uper]'");
		echo json_encode('Batal Uper Sukses');
	}
}

function cek_cust($id){
	$db 	= getDB();
	$sql_cust = $db->query("SELECT * FROM AC_REKENING_USER WHERE CUSTOMER_ID = '".$id."'")->fetchRow();
	if(!empty($sql_cust['NOREK_IDR'])){
		return true;
	}else{
		return false;
	}
}

function test_koneksi(){

	echo "masuk";

	require_once SITE_LIB."nusoap/nusoap.php";
	require_once SITE_LIB."eService/eService.php";
	//die(SITE_LIB);
	$client = new eService();

	print_r($client->test());
	die;
}


function cancelAC($NotaId)
{
	$db 	= getDB();
	
	############################ Auto Collection Start ############################
   	require_once SITE_LIB."nusoap/nusoap.php";
	require_once SITE_LIB."eService/eService.php";
	
	$client = new eService();
	
	require_lib('acl.php');	
	$_acl = new ACL();
	$_acl->load();
	$li = $_acl->getLogin();
	$rendal = $li->info["REALNAME"];
	/*$userid = $li->info["USERID"];
	$kd_cabang = $li->info["KD_CABANG"];*/

	$userid 		= $_SESSION['NAMA_PENGGUNA'];
	$kd_cabang 		= '05';
	
	$sql_last_ext = "SELECT DISTINCT(EXTERNAL_ID) EXTERNAL_ID FROM AC_PARAMETER_LOG WHERE NO_UPER='".$NotaId."'";
	$rs_last_ext   = $db->query($sql_last_ext);
	$data_last_ext = $rs_last_ext->fetchRow();
	$externalId = $data_last_ext['EXTERNAL_ID'];
	
	
	/*cek pelanggan ac*/
	$row = $db->query("SELECT * FROM UPER_H WHERE NO_UPER = '".$NotaId."'")->fetchRow();
	
	$COMPANY_ID = $row['COMPANY_ID'];
	
	$sql_param_bank  = "SELECT
						CASE WHEN PERSEN_HOLD IS NULL THEN
							(SELECT to_number(VALUE) FROM AC_GLOBAL_PARAM WHERE PARAM='PERSEN_HOLD')
						ELSE
							PERSEN_HOLD
						END PERSEN_HOLD,NOREK_IDR,BANK_IDR,EMAIL
						FROM
						AC_REKENING_USER A
					WHERE
						CUSTOMER_ID = '".addslashes($row['COMPANY_ID'])."'";
	$rs_param_bank   = $db->query($sql_param_bank);
	$data_param_bank = $rs_param_bank->fetchRow();
	
	$bank_id		= $data_param_bank['BANK_IDR'];
	$service_name	= "releaseamount";
	
	$accountNumber	= $data_param_bank['NOREK_IDR'];
	$hold_persen	= $data_param_bank['PERSEN_HOLD'];
	$email	= $data_param_bank['EMAIL'];
	
	
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
							,to_Char(a.LOG_DATE,'yyyy-mm-dd') LOG_DATE2
						FROM
							AC_BARANG_LOG A JOIN AC_REKENING_USER B ON A.COMPANY_ID = B.CUSTOMER_ID JOIN AC_MST_BANK C ON A.BANK_ID = C.KD_BANK 
							AND C.ORG_ID = '1827' JOIN AC_PARAMETER_LOG D ON A.NOTAJB_ID = D.NO_UPER 
						WHERE
							A.NOTAJB_ID = '".$NotaId."' 
							AND D.RESPOND = 'false'
							order by log_date desc";
	$rs_get_param   = $db->query($sql_get_param);
	$get_param     	= $rs_get_param->fetchRow();
	
	$releaseAmount = $get_param['NOMINAL_HOLD'];
	$NOMINAL_UPER = $get_param['NOMINAL_UPER'];
	$releaseId = $get_param['REFERENCE_ID'];
	$holdDate = $get_param['LOG_DATE2'];
	$email = $get_param['EMAIL'];
	//$PERSEN_HOLD = $get_param['PERSEN_HOLD'];
	
	// Note : ORG_ID temporary hardcode
	$sql_param_bankdata 	 = "SELECT PROD_CODE, CHANNEL_CODE, TRANSACTION_CODE, AID, FID, TERMINAL_CODE, BRANCH_CODE, LOCATION_CODE, CURRENCY_CODE, FEE_ADMIN, LANGUAGE FROM AC_MST_BANK WHERE KD_BANK = '".$bank_id."' AND ORG_ID = '1827'";

	$rs_param_bankdata   = $db->query($sql_param_bankdata);
	$data_param_bankdata = $rs_param_bankdata->fetchRow();
	
	
	$sub_param = array ( "ReleaseAmountRequest" => array (
							"body" => array (
							"externalId" => "$externalId",  //done
							"accountNumber" => "$accountNumber", //done
							"releaseAmount" => "$releaseAmount", //done
							"releaseReference" => "$releaseId", //done
							"holdTransactionDate" => "$holdDate", //DONE
							"holdPeriodInDays" => "3600", 
							"holdCurrency" => "IDR",
							"remark1" => "-",
							"remark2" => "-",
							"remark3" => "$email" //done
							)
						)
					);
	
	$date_trx = date('Ymdhis');
	$parameter = array( "request" =>
						array(
							"language" => $data_param_bankdata['LANGUAGE'], 
							"trxDateTime" => "$date_trx",
							"transmissionDateTime" => "$date_trx",
							"prodCode" => $data_param_bankdata['PROD_CODE'],//mandiri : 46020, niaga 46040
							"channelCode" => $data_param_bankdata['CHANNEL_CODE'],
							"nmrTrx" => array2xml($sub_param),
							"transactionCode" => $data_param_bankdata['TRANSACTION_CODE'],
							"aid" => $data_param_bankdata['AID'], ////mandiri : 008, niaga 022
							"fid" => $data_param_bankdata['FID'], //mandiri : 46020, niaga 46010
							"terminalCode" => $data_param_bankdata['TERMINAL_CODE'],
							"branchCode" => $data_param_bankdata['BRANCH_CODE'],
							"locationCode" => $data_param_bankdata['LOCATION_CODE'],
							"currencyCode" => $data_param_bankdata['CURRENCY_CODE'],
						)
					);
	
	//print_r($parameter);die;
	
	$insert_log_parameter = "INSERT INTO AC_PARAMETER_LOG (LOG_DATE,NO_UPER,KD_SERVICE_CODE,STATEMENTS,USER_ID,RESPOND,MESSAGE,RESPOND_DATE,REFERENCE_ID, EXTERNAL_ID, KD_BANK, KURS, KURS_DATE) VALUES (SYSDATE,'".addslashes($NotaId)."','1','cancel($service_name,$externalId^$accountNumber^$releaseAmount^$releaseId^3600^IDR^)','$userid','','','','',$externalId,'$bank_id','','')";

	if ($db->query($insert_log_parameter)) {
		//echo "log parameter auto collection : Berhasil <br><br>";
		//echo "$insert_log_parameter<br><br>";
	} else {
		//echo "log parameter auto collection : Gagal <br><br>";
		//echo "$insert_log_parameter<br><br>";
	}
	
	if(!$client->call_wsdl_via_file(ILCS_AUTOCOLLECTION_SERVICES,$service_name,$parameter,$result)){
		//do  nothing
		//echo "test";
		/*echo '<pre>';
		echo("UPDATE AC_PARAMETER_LOG 
				SET RESPOND='true', 
				MESSAGE='".$result."' 
				WHERE LOG_DATE = 
				(select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' 
				AND STATEMENTS LIKE '%holdamount%')");
		echo '</pre>';*/

		/*$db->query("UPDATE AC_PARAMETER_LOG 
					SET RESPOND='true', MESSAGE='".$result."' 
					WHERE LOG_DATE = 
					(select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%holdamount%')");*/
		// Set message alert into session
		/*setAlert("true", "WSDL FAULT", $result);*/

		//print_r($result);die;

		//header('Location: '._link(	array(	'sub'=>'edit','NO_UPER'=> $NotaId)));
	} else{

		/*echo 'masuk';
		print_r($result);die;*/

		$data = $result["releaseamountResult"]["billInfo1"];
		if (empty($data)) {
			/*setAlert("true", "X", "Invalid XML Respons | NULL");*/
			header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId)));
		} else {
			$isError = $result["releaseamountResult"]["status"]["isError"];
			$errorCode = trim($result["releaseamountResult"]["status"]["errorCode"]);
			$statusDescription = $result["releaseamountResult"]["status"]["statusDescription"];
			
			//$xml_data = new SimpleXMLElement($data);
			//$holdReference  = $xml_data->body->holdReference;

			$persen_hold = $hold_persen;
			#$persen_hold = $_POST['PERSEN_HOLD'];

			if ($isError == "false") {
				$insert_log_barang = "INSERT INTO AC_BARANG_LOG (EXTERNAL_ID, SERVICE, COMPANY_ID, NOTAJB_ID,NOMINAL_UPER, PERSEN_HOLD, NOMINAL_HOLD, CURRECY,BANK_ID,USER_ID,KD_SERVICE_CODE,PBM_ID,UKK,KD_CABANG,REFERENCE_ID,LOG_DATE,KURS, KURS_DATE) VALUES ('$externalId', 'CANCEL', '".addslashes($row['COMPANY_ID'])."','".addslashes($NotaId)."',$NOMINAL_UPER,'".addslashes($persen_hold)."', '".$releaseAmount."', 'IDR','$bank_id','$userid','01','".addslashes($row['PBM_ID'])."','".$persen_hold."','$kd_cabang','$releaseId',SYSDATE,'','')";
				if ($db->query($insert_log_barang)) {
					$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='".$isError."', MESSAGE='".$statusDescription."', RESPOND_DATE = SYSDATE, REFERENCE_ID = '".$releaseId."' WHERE LOG_DATE = (select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%holdamount%')");
					
					$db->query("UPDATE UPER_H SET USER_LUNAS = 'X' WHERE NO_UPER='$NotaId'");
					
					// Set message alert into session
					/*setAlert($isError, $errorCode, $statusDescription);*/
					//header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId)));	
					// die();	
				} else {
					echo $insert_log_barang;
					echo "log barang parameter auto collection : Gagal <br><br>";
					echo "$insert_log_parameter<br><br>";
				}
			}
			else{
				$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='".$isError."', MESSAGE='".$statusDescription."', RESPOND_DATE = SYSDATE, REFERENCE_ID = '".$releaseId."' WHERE LOG_DATE = (select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%holdamount%')");
				
				
				// Set message alert into session
				/*setAlert($isError, $errorCode, $statusDescription);*/
				//header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId)));
			}
		}
	}
	
	
}


function callAc($NotaId){


	//print_r("Berhasil");die;

	$db 	= getDB();
	$ac     = 'N';
	// die($NotaId);
	// print_r($_POST['AC']);die;

	/*cek pelanggan ac*/
	$row = $db->query("SELECT * FROM UPER_H WHERE NO_UPER = '".$NotaId."'")->fetchRow();
	
	//print_r($row);die;
	
	$sql_cust = $db->query("SELECT * FROM AC_REKENING_USER WHERE CUSTOMER_ID = '".$row['COMPANY_ID']."'")->fetchRow();
	if(!empty($sql_cust['NOREK_IDR'])){
		$ac = 'Y';
	}

	//$ac
	//die;

    if ($ac == 'Y' && $row['TOTAL'] > 0) {
	############################ Auto Collection Start ############################
   	require_once SITE_LIB."nusoap/nusoap.php";
	require_once SITE_LIB."eService/eService.php";
	//die(SITE_LIB);
	$client = new eService();

	//$client->test();
	/*print_r($client->test());
	die;*/

	require_lib('acl.php');	
	$_acl = new ACL();
	$_acl->load();
	$li = $_acl->getLogin();
	$rendal = $li->info["REALNAME"];
	/*$userid = $li->info["USERID"];
	$kd_cabang = $li->info["KD_CABANG"];*/

	$userid 		= $_SESSION['NAMA_PENGGUNA'];
	$kd_cabang 		= '05';


	//print_r($_acl);
	//die;

	// Get data bank by customer ID
	$sql_param_bank  = "SELECT
						CASE WHEN PERSEN_HOLD IS NULL THEN
							(SELECT to_number(VALUE) FROM AC_GLOBAL_PARAM WHERE PARAM='PERSEN_HOLD')
						ELSE
							PERSEN_HOLD
						END PERSEN_HOLD,NOREK_IDR,BANK_IDR,EMAIL
						FROM
						AC_REKENING_USER A
					WHERE
						CUSTOMER_ID = '".addslashes($row['COMPANY_ID'])."'";
	$rs_param_bank   = $db->query($sql_param_bank);
	$data_param_bank = $rs_param_bank->fetchRow();

	date_default_timezone_set('Asia/Jakarta');    	
	$date = date('dm');

	$sql_last_ext = "SELECT DISTINCT(EXTERNAL_ID) EXTERNAL_ID FROM AC_PARAMETER_LOG WHERE NO_UPER='".$NotaId."'";
	$rs_last_ext   = $db->query($sql_last_ext);
	$data_last_ext = $rs_last_ext->fetchRow();
	$last_extid = $data_last_ext['EXTERNAL_ID'];

	if ($last_extid !='' or $last_extid	!= NULL) {
		$externalId		= $last_extid;
	} else {
		//$externalId		= "1001".str_replace('-', '', $NotaId).$date;
		$externalId		= $NotaId ;//"1001".str_replace('-', '', $NotaId).$date;
	}
	
	//print_r($externalId);die;
	
	$accountNumber	= $data_param_bank['NOREK_IDR'];
	$hold_persen	= $data_param_bank['PERSEN_HOLD'];
	$email	= $data_param_bank['EMAIL'];

	// set nominal uper	& holdamount
	$nominaluper 	= $row['TOTAL'];
	$nominaluper 	= str_replace(',', '' , $nominaluper);
	//$holdamount 	= round((int)$nominaluper * $data_param_bank['PERSEN_HOLD'] / 100, 0);
	$holdamount 	= round((int)$nominaluper * $hold_persen / 100, 0);

	$bank_id		= $data_param_bank['BANK_IDR'];
	$service_name	= "holdamount";

	// Note : ORG_ID temporary hardcode
	$sql_param_bankdata 	 = "SELECT PROD_CODE, CHANNEL_CODE, TRANSACTION_CODE, AID, FID, TERMINAL_CODE, BRANCH_CODE, LOCATION_CODE, CURRENCY_CODE, FEE_ADMIN, LANGUAGE FROM AC_MST_BANK WHERE KD_BANK = '".$bank_id."' AND ORG_ID = '1827'";

	$rs_param_bankdata   = $db->query($sql_param_bankdata);
	$data_param_bankdata = $rs_param_bankdata->fetchRow();

	//print_r($accountNumber);die;
	/*
		BILLER ID 
		UNTUK KEBUTUHAN RELEASE
	*/
	$query = "SELECT BILLER_ID FROM AC_REKENING_USER WHERE CUSTOMER_ID='IPCTPK' AND ROWNUM=1";
	$rs_biller  = $db->query($query);
	$data_biller = $rs_biller->fetchRow();
	$id_biller = $data_biller['BILLER_ID'];
	

	$sub_param = array ( "HoldAmountRequest" => array (
							"body" => array (
							"externalId" => "$externalId", // 1001141117122725106 1001141117154305119 1001201170213390413 $externalId
							"billerId" => "$id_biller",
							"accountNumber" => "$accountNumber",
							"holdAmount" => "$holdamount",
							"holdPeriodInDays" => "3600", 
							"holdCurrency" => "IDR",
							"remark1" => "-",
							"remark2" => "-",
							"remark3" => "$email"
							)
						)
					);
	
	$date_trx = date('Ymdhis');
	$parameter = array( "request" =>
						array(
							"language" => $data_param_bankdata['LANGUAGE'], 
							"trxDateTime" => "$date_trx",
							"transmissionDateTime" => "$date_trx",
							"prodCode" => $data_param_bankdata['PROD_CODE'],//mandiri : 46020, niaga 46040
							"channelCode" => $data_param_bankdata['CHANNEL_CODE'],
							"nmrTrx" => array2xml($sub_param),
							"transactionCode" => $data_param_bankdata['TRANSACTION_CODE'],
							"aid" => $data_param_bankdata['AID'], ////mandiri : 008, niaga 022
							"fid" => $data_param_bankdata['FID'], //mandiri : 46020, niaga 46010
							"terminalCode" => $data_param_bankdata['TERMINAL_CODE'],
							"branchCode" => $data_param_bankdata['BRANCH_CODE'],
							"locationCode" => $data_param_bankdata['LOCATION_CODE'],
							"currencyCode" => $data_param_bankdata['CURRENCY_CODE'],
							"feeAdmin" => $data_param_bankdata['FEE_ADMIN']
						)
					);
	//print_r($parameter);die;
	
	// Insert log
	$insert_log_parameter = "INSERT INTO AC_PARAMETER_LOG (LOG_DATE,NO_UPER,KD_SERVICE_CODE,STATEMENTS,USER_ID,RESPOND,MESSAGE,RESPOND_DATE,REFERENCE_ID, EXTERNAL_ID, KD_BANK, KURS, KURS_DATE) VALUES (SYSDATE,'".addslashes($NotaId)."','1','call($service_name,$externalId^$accountNumber^$holdamount^3600^IDR^)','$userid','','','','',$externalId,'$bank_id','','')";

	if ($db->query($insert_log_parameter)) {
		//echo "log parameter auto collection : Berhasil <br><br>";
		//echo "$insert_log_parameter<br><br>";
	} else {
		//echo "log parameter auto collection : Gagal <br><br>";
		//echo "$insert_log_parameter<br><br>";
	}


	/*echo "<pre>";
	print_r(ILCS_AUTOCOLLECTION_SERVICES);
	echo "</pre>";
	die;*/

	if(!$client->call_wsdl_via_file(ILCS_AUTOCOLLECTION_SERVICES,$service_name,$parameter,$result)){
		
		//echo "test";
		/*echo '<pre>';
		echo("UPDATE AC_PARAMETER_LOG 
				SET RESPOND='true', 
				MESSAGE='".$result."' 
				WHERE LOG_DATE = 
				(select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' 
				AND STATEMENTS LIKE '%holdamount%')");
		echo '</pre>';*/

		/*$db->query("UPDATE AC_PARAMETER_LOG 
					SET RESPOND='true', MESSAGE='".$result."' 
					WHERE LOG_DATE = 
					(select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%holdamount%')");*/
		// Set message alert into session
		/*setAlert("true", "WSDL FAULT", $result);*/
		echo json_encode("error;HOLDGAGAL".$result);

		//print_r($result);die;

		//header('Location: '._link(	array(	'sub'=>'edit','NO_UPER'=> $NotaId)));
	} else{

		/*echo 'masuk';
		print_r($result);die;*/

		$data = $result["holdamountResult"]["billInfo1"];
		if (empty($data)) {
			/*setAlert("true", "X", "Invalid XML Respons | NULL");*/
			echo json_encode("true;HOLDGAGAL;Services return null value, Invalid XML Format;");
			header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId)));
		} else {
			$isError = $result["holdamountResult"]["status"]["isError"];
			$errorCode = trim($result["holdamountResult"]["status"]["errorCode"]);
			$statusDescription = $result["holdamountResult"]["status"]["statusDescription"];
			
			$xml_data = new SimpleXMLElement($data);
			$holdReference  = $xml_data->body->holdReference;

			$persen_hold = $hold_persen;
			#$persen_hold = $_POST['PERSEN_HOLD'];

			//print_r($isError);
			//print_r($statusDescription);die;
			
			if ($isError == "false") {
				$insert_log_barang = "INSERT INTO AC_BARANG_LOG (EXTERNAL_ID, SERVICE, COMPANY_ID, NOTAJB_ID,NOMINAL_UPER, PERSEN_HOLD, NOMINAL_HOLD, CURRECY,BANK_ID,USER_ID,KD_SERVICE_CODE,PBM_ID,UKK,KD_CABANG,REFERENCE_ID,LOG_DATE,KURS, KURS_DATE) VALUES ('$externalId', 'HOLD', '".addslashes($row['COMPANY_ID'])."','".addslashes($NotaId)."',$nominaluper,'".addslashes($persen_hold)."', '".$holdamount."', 'IDR','$bank_id','$userid','01','".addslashes($row['PBM_ID'])."','".$persen_hold."','$kd_cabang','$holdReference',SYSDATE,'','')";
				if ($db->query($insert_log_barang)) {
					$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='".$isError."', MESSAGE='".$statusDescription."', RESPOND_DATE = SYSDATE, REFERENCE_ID = '".$holdReference."' WHERE LOG_DATE = (select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%holdamount%')");
					
					$query = "UPDATE uper_h SET PAYMENT_VIA='PTK ".$bank_id." IDR ".$accountNumber."',USER_LUNAS = 'T' WHERE NO_UPER='".$NotaId."'";
					$db->query($query);
					
					//$db->query("UPDATE UPER_H SET USER_LUNAS = 'T' WHERE NO_UPER='$_POST[no_uper]'");
					// Set message alert into session
					/*setAlert($isError, $errorCode, $statusDescription);*/
					//header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId)));	
					// die();	
				
					echo json_encode("HOLDSUKSES");
				} else {
					//echo $insert_log_barang;
					echo json_encode("GAGAL_ACBARANGLOG");
					//echo "log barang parameter auto collection : Gagal <br><br>";
					//echo "$insert_log_parameter<br><br>";
				}
			}
			else{
				
				
				
				$db->query("UPDATE AC_PARAMETER_LOG SET RESPOND='".$isError."', MESSAGE='".$statusDescription."', RESPOND_DATE = SYSDATE, REFERENCE_ID = '".$holdReference."' WHERE LOG_DATE = (select max(LOG_DATE) from AC_PARAMETER_LOG WHERE STATEMENTS LIKE '%". $externalId ."%' AND STATEMENTS LIKE '%holdamount%')");
				
				echo json_encode("true;HOLDMINIM;".$statusDescription.";");
				//header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId)));
				
				// Set message alert into session
				/*setAlert($isError, $errorCode, $statusDescription);*/
				//header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId)));
			}
		}
	}
		// Auto Collection OK
		//header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId )));
		############################ Auto Collection Stop ############################ 
	}


	//echo json_encode('1');
	//else{
		//header('Location: '._link(	array(	'sub'=>'edit','NOTAJB_ID'=> $NotaId )));
		// echo "NON AC";
		// die();
		############################ NON Auto Collection Start ############################ 
		//saveData();
		############################ NON Auto Collection Stop ############################ 
	//}
}
?>