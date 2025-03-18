<?php
/**start gagat modify 19 september 2019 **/
require_once SITE_LIB . "esbhelper/class_lib.php";
$esb = new esbclass();
/**end gagat modify 19 september 2019 ***/
$id_nota=$_POST['IDN'];
$id_req=$_POST['IDR'];
$jenis=$_POST['JENIS'];
$via=$_POST['VIA'];
$cms=$_POST['CMS'];
$kd_pelunasan=$_POST['KD_PELUNASAN'];
$vessel=$_POST['VESSEL'];
$voyin=$_POST['VOYIN'];
$voyout=$_POST['VOYOUT'];
$user=$_SESSION['PENGGUNA_ID'];
if ($jenis=='ANNE')
 $v_flag='REC';
else if ($jenis=='SP2'){
	if(substr($id_req, 0, 3) == 'SP2'){
		$v_flag='SEI';
	} else {
		$v_flag='DEL';
	}
}
else if ($jenis=='HICO')
 $v_flag='HICO';
else if ($jenis=='TRANS')
 $v_flag='TRS';
else if ($jenis=='BH')
 $v_flag='BHD';
else if ($jenis=='REEX')
 $v_flag='REX';
else if ($jenis=='RXP')
 $v_flag='RXP';
else if ($jenis=='MONREEF')
 $v_flag='MONREEF';

/* start patch validasi running by Deto Feb2020 */
/* fatal error handling */
function T_PAYMENT_LOG_END_ERROR($id_req) { 
	$error = error_get_last();
	// fatal error, E_ERROR === 1
	if ($error['type'] === E_ERROR) {
		$error = error_get_last();
		// print_r($error);
		$db=getDb();
		$param= array(
					"v_no_request"=>$id_req,
					"v_no_nota"=>'',
					"v_res_status"=>'ER',
					"v_res_msg"=>'ERROR '.$id_req.': '.$error['message'].' on line '.$error['line']
					);
		$query = "BEGIN
					T_PAYMENT_LOG_END(:v_no_request,:v_no_nota,:v_res_status,:v_res_msg);
				END;";
		$db->query($query,$param);
		// echo 'error DB: '.$id_req;
	}
}
register_shutdown_function('T_PAYMENT_LOG_END_ERROR', $id_req);

$v_nota = '';
$db=getDb();

/* check running */
$q_valid = "SELECT COUNT(*) JML FROM T_PAYMENT_LOG WHERE NO_REQUEST = '$id_req' AND STATUS_LOG = 0";
$r_valid = $db->query($q_valid);
$rw_val  = $r_valid->fetchRow();

if ($rw_val['JML'] == 0) { /* no running process */
	
	/* start log */
	$param= array(
				"v_no_request"=>$id_req,
				"v_no_nota"=>'',
				"v_no_proforma"=>$id_nota,
				"v_payment_code"=>'',
				"v_reference_code"=>'',
				"v_payment_mode"=>'PAYCASH',
				"v_user_id"=>$user
			);
	$query = "BEGIN
				T_PAYMENT_LOG_START(:v_no_request,:v_no_nota,:v_no_proforma,:v_payment_code,:v_reference_code,:v_payment_mode,:v_user_id);
			END;";
	$db->query($query,$param);
/* end patch validasi running by Deto Feb2020 */


	///echo 'jenisnya: '.$jenis;die;
	if ($jenis!='RNM' && $jenis !='MONREEF') {
		$db=getDb('dbint');
		$param_payment2= array(
							 "ID_NOTA"=>$id_nota,
							 "ID_REQ"=>$id_req,
							 "OUT"=>'',
							 "OUT_MSG"=>''
							);
		$query2="declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";

		$db->query($query2,$param_payment2);
	}
	else
	{
		$param_payment2["OUT"]='S';
	}

	if($param_payment2["OUT"]=='S')
	{
		$db=getDb();
		$q_valid = "SELECT COUNT(NO_NOTA) JUM FROM TTH_NOTA_ALL2 WHERE NO_NOTA = '$id_nota'";
		$r_valid = $db->query($q_valid);
		$rw_val  = $r_valid->fetchRow();
		if ($rw_val['JUM'] == 0) {		
			$param_payment= array(
							 "ID_NOTA"=>$id_nota,
							 "ID_REQ"=>$id_req,
							 "JENIS"=>$jenis,
							 "VIA"=>$via,
							 "KD_PELUNASAN"=>$kd_pelunasan,
							 "USER"=>$user,
							 "OUT"=>'',
							 "OUT_MSG"=>'',
							 "VESSEL"=>$vessel,
							 "VOYIN"=>$voyin,
							 "VOYOUT"=>$voyout
							);
			$query="declare begin pack_payment_new_ipctpk.proc_payment_new(:ID_NOTA,:ID_REQ,:JENIS,:VIA,:KD_PELUNASAN,:USER,:VESSEL,:VOYIN,:VOYOUT,:OUT,:OUT_MSG); end;";
			//echo($query);
					//print_r($param_payment);
					//die();
					$db->query($query,$param_payment);
		}
		
		$db=getDb();
		/**gagat modif 30 JAN 2020*/
		if($jenis=='ANNE'){
			/*check apakah nota receiving dan status_ar IS NULL **/
			$sqlCheckRec="SELECT COUNT('X') AS JML,ID_NOTA AS TRX_NUMBER FROM NOTA_RECEIVING_H 
				WHERE ID_REQ='$id_req' AND ID_PROFORMA='$id_nota' GROUP BY ID_NOTA";
			$runCheck=$db->query($sqlCheckRec);
			$rsCheck=$runCheck->fetchRow();
						
			
			/*check apakah nota receiving dan status_ar IS NULL **/
			$sqlCheckRecPEN="SELECT COUNT('X') AS JML,ID_NOTA AS TRX_NUMBER FROM NOTA_RECEIVING_H_PEN 
			WHERE ID_REQ='$id_req' AND ID_PROFORMA='$id_nota' GROUP BY ID_NOTA";
			$runCheckPEN=$db->query($sqlCheckRecPEN);
			$rsCheckPEN=$runCheckPEN->fetchRow();
				
			if($rsCheck['JML']>0){				
					///print_r(" nota_receiving_h ");
					$getNomorNota = " TNH.NO_NOTA='".$rsCheck['TRX_NUMBER']."' ";									
				
			}elseif($rsCheckPEN['JML']>0){
					///print_r(" nota_receiving_h_pen ");	
					$getNomorNota = " TNH.NO_NOTA='".$rsCheckPEN['TRX_NUMBER']."' ";
			}
			
		}elseif($jenis=='BMP'){
				$sqlCheckBMP="SELECT ID_NOTA AS TRX_NUMBER FROM NOTA_BATALMUAT_H_PENUMPUKAN WHERE ID_REQ='$id_req'";
				$runCheckBMP=$db->query($sqlCheckBMP);
				$rsCheckBMP=$runCheckBMP->fetchRow();
				$getNomorNota = " TNH.NO_NOTA='".$rsCheckBMP['TRX_NUMBER']."' ";					
			
		}elseif($jenis=='BM'){
				$sqlCheckBM="SELECT ID_NOTA AS TRX_NUMBER FROM NOTA_BATALMUAT_H WHERE ID_REQ='$id_req'";
				$runCheckBM=$db->query($sqlCheckBM);
				$rsCheckBM=$runCheckBM->fetchRow();
				$getNomorNota = " TNH.NO_NOTA='".$rsCheckBM['TRX_NUMBER']."' ";
		}elseif($jenis=='SPEP'){
				$sqlCheckDelP="SELECT NO_FAKTUR AS TRX_NUMBER FROM NOTA_DELIVERY_H_PEN WHERE ID_REQ='$id_req'";
				$runCheckDelP=$db->query($sqlCheckDelP);
				$rsCheckDelP=$runCheckDelP->fetchRow();
				$getNomorNota = " TNH.NO_NOTA='".$rsCheckDelP['TRX_NUMBER']."' ";
		}elseif($jenis=='SP2'){
				$sqlCheckDel="SELECT NO_FAKTUR AS TRX_NUMBER FROM NOTA_DELIVERY_H WHERE ID_REQ='$id_req'";
				$runCheckDel=$db->query($sqlCheckDel);
				$rsCheckDel=$runCheckDel->fetchRow();
				$getNomorNota = " TNH.NO_NOTA='".$rsCheckDel['TRX_NUMBER']."' ";
		}else{
			$getNomorNota="TNH.NO_REQUEST = '$id_req'";
		}
					
		/*end gagat modif 30 JAN 2020*/	
					
			
			
		$sqlCheck="SELECT COUNT('X') AS JML FROM BILLING_NBS.XEINVC_TERMINAL_LIVE_EINVOICE TE WHERE TE.EINVOICE='Y' AND 
		TRUNC(SYSDATE) BETWEEN TE.START_EINVOICE AND NVL(TE.ENDDATE_EINVOICE,SYSDATE) ";	
		$run_CheckLive=$db->query($sqlCheck);
		$rs_CheckLive=$run_CheckLive->fetchRow();
		if($rs_CheckLive['JML']>0){
			$db=getDb();
			$q_Header="SELECT SYSDATE AS TRX_DATE, KD_MODUL,NO_NOTA,NO_REQUEST,NO_FAKTUR_PAJAK,
			  CUST_NO,CUST_NAME,CUST_ADDR,CUST_NPWP,SIGN_CURRENCY,TOTAL,PPN,KREDIT,STATUS_NOTA,KD_CABANG,
			  KD_CABANG_SIMKEU ,NO_UKK ,DATE_CREATED,USER_CREATED,STATUS_CREATED,DATE_PAID,USER_PAID,
			  STATUS_PAID,PAID_VIA,('BANK') AS RECEIPT_METHOD,RECEIPT_ACCOUNT,DATE_TRANSFER,USER_TRANSFER ,STATUS_TRANSFER,KD_LOCATION,
			  VESSEL,(VOYAGE_IN||' - '||VOYAGE_OUT) VOYAGE_IN_OUT,BANK_ACCOUNT_ID,NOTAPREV,JENIS_MODUL,PENUMPUKAN_FROM, PENUMPUKAN_TO,
			  NOMOR_BL_PEB,NO_DO,BONGKAR_MUAT ,ORG_ID,RECEIPT_NAME,ADMINISTRASI,STATUS_NOTA2 ,
			  KD_PELUNASAN,KETERANGAN,NO_BA,STATUS_AR,STATUS_ARMSG,STATUS_RECEIPT,STATUS_RECEIPTMSG ,ARPROCESS_DATE  ,
			  RECEIPTPROCESS_DATE,'PNK' AS TERMINAL_ID ,NO_FAKTUR_PAJAK AS NO_JKM,PAYMENT_CODE ,
			  REFERENCE_CODE ,APPROVAL_CODE,TO_CHAR(SYSDATE,'RRRR-MM-DD') TGL_PELUNASAN,
			  TO_CHAR(TANGGAL_TIBA,'RRRR-MM-DD') TANGGAL_TIBA,
			  TO_CHAR(TANGGAL_TIBA,'RRRR-MM-DD') TANGGAL_BERANGKAT 
						FROM TTH_NOTA_ALL2 TNH WHERE $getNomorNota";
			///print_r($q_Header);die();
			$run_Header=$db->query($q_Header);
			$rs=$run_Header->fetchRow();
			
			/**gagat modify 28 oktober 2019 get materai***/
			$q_Materai="SELECT TOTTARIF AS BEA_MATERAI FROM TTR_NOTA_ALL WHERE KD_PERMINTAAN ='$id_req' AND URAIAN='MATERAI' 
				AND KD_UPER='".$rs['NO_NOTA']."' ";
			///print_r($q_Header);die();
			$run_Materai=$db->query($q_Materai);
			$rs_materai=$run_Materai->fetchRow();
			if($rs_materai['BEA_MATERAI']>0){
				$beamaterai=$rs_materai['BEA_MATERAI'];
				
			}else{
				$beamaterai=0;
				
			}
			/**gagat end modify 28 oktober get materai **/
			
			$q_lines="SELECT KD_MODUL,KD_UPER,KD_PERMINTAAN,QTY,SIZE_,TYPE_,STATUS_,TARIF,TOTTARIF,URAIAN,TOTHARI,
			  HZ,EI,OI,CRANE,PLUG_IN,PLUG_OUT,HOURS,SHIFT,SHIFT_BAYAR,
			  TO_CHAR(TGL_AWAL,'RRRR-MM-DD') TGL_AWAL,TO_CHAR(TGL_AKHIR,'RRRR-MM-DD') TGL_AKHIR
			  ,KETERANGAN, (CASE WHEN TAX_FLAG='Y' THEN PPNTARIF ELSE 0 END) AS TAX_AMOUNT,
			  TOTHR,CURRENCY_CODE,LINE_NUMBER,SEQ_ID_CFACC,TAX_FLAG,SIZE_TYPE_STAT_HAZ  ,
			  IMO_CLASS,DISCOUNT,TON,
			  M3,TIPE_LAYANAN FROM TTR_NOTA_ALL WHERE KD_PERMINTAAN ='$id_req' AND KD_UPER='".$rs['NO_NOTA']."' ";
			$run_Lines=$db->query($q_lines);
			$rowdetail=$run_Lines->GetAll();
			
			$kirimEsb = $esb->nbsOPUSArInvoice($rs,$beamaterai,$rowdetail,$rsamount);
			$kirimEsbResponese = json_decode($kirimEsb, true);
			$status = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
			$erroMessage = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];
				
			if($status=="S"){
				/**update status AR e-invoice*/
				$sqlUpd="UPDATE TTH_NOTA_ALL2 SET STATUS_AR='".$status."',STATUS_ARMSG='Generate Stg Invoice Success (".$rs['NO_NOTA'].") ' 
						WHERE NO_NOTA='".$rs['NO_NOTA']."' ";
				$db->query($sqlUpd);		
				/*end update status AR e-invoice*/	
				$kirimreceipt = $esb->NBSOpusReceipt($rs);
				$kirimEsbreceiptResponese = json_decode($kirimreceipt, true);
				$responseReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
				$erroMessageReceipt = $kirimEsbreceiptResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];
				if ($responseReceipt == "S") {
					print_r("Sukses");
					$kirimApply = $esb->nbsOPUSApply($rs,$user);
				}else{
					echo "Error Apply Receipt";
				}	
			}
		}	
		
	}
	/*remark gagat
	ELSE
	{
		echo 'failed '.$param_payment2["OUT_MSG"];
	}*/
	
	/* start patch validasi running by Deto Feb2020 */
	/* end log */
	$v_nota = $rs['NO_NOTA'];
	$db=getDb();
	$param= array(
				"v_no_request"=>$id_req,
				"v_no_nota"=>$v_nota,
				"v_res_status"=>'',
				"v_res_msg"=>substr($param_payment2["OUT_MSG"],0,2000)
			);
	$query = "BEGIN
				T_PAYMENT_LOG_END(:v_no_request,:v_no_nota,:v_res_status,:v_res_msg);
			END;";
	$db->query($query,$param);
	/* end patch validasi running by Deto Feb2020 */
	
/* start patch validasi running by Deto Feb2020 */
}else{ /* process already running */
	$db=getDb();
	$query = "INSERT INTO T_PAYMENT_LOG (
			   NO_REQUEST, NO_NOTA, NO_PROFORMA, 
			   PAYMENT_CODE, REFERENCE_CODE, PAYMENT_MODE, 
			   USER_ID, START_LOG, END_LOG, 
			   STATUS_LOG, RES_STATUS, RES_MESSAGE) 
			VALUES ( '$id_req'/* NO_REQUEST */,
			 ''/* NO_NOTA */,
			 '$id_nota'/* NO_PROFORMA */,
			 ''/* PAYMENT_CODE */,
			 ''/* REFERENCE_CODE */,
			 'PAYCASH'/* PAYMENT_MODE */,
			 '$user'/* USER_ID */,
			 SYSDATE/* START_LOG */,
			 SYSDATE/* END_LOG */,
			 1/* STATUS_LOG */,
			 '02'/* RES_STATUS */,
			 'Process is Running, Please Wait'/* RES_MESSAGE */ )";
	$db->query($query);
	echo 'failed : Process is Running, Please Wait';
}
/* end patch validasi running by Deto Feb2020 */

?>