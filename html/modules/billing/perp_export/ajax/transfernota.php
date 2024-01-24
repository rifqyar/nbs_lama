<?php
	$db=getDb();
    $id=$_POST['id_nota'];
	$idrpstv=$_POST['id_rpstv'];
    $userid=$_SESSION["PENGGUNA_ID"];
	
	/**start gagat modify 08 November 2019 **/
require_once SITE_LIB."/esbhelper/class_lib.php";
$esb = new esbclass();
/**end gagat modify 08 November 2019 ***/

$db=getDb();

/*cek apakah sudah go live e-invoice */
	$sqlCheck="SELECT COUNT('X') AS JML FROM BILLING.XEINVC_TERMINAL_LIVE_EINVOICE TE WHERE TE.EINVOICE='Y' AND 
	TRUNC(SYSDATE) BETWEEN TE.START_EINVOICE AND NVL(TE.ENDDATE_EINVOICE,SYSDATE) ";	
	$run_CheckLive=$db->query($sqlCheck);
	$rs_CheckLive=$run_CheckLive->fetchRow();
	if($rs_CheckLive['JML']>0){

	$id_nota=$_POST['NO_NOTA'];
	$id_req=$_POST['NO_REQ'];
	///print_r($id_nota||' id req : '||$id_req );die();
	$q_Header="SELECT H.ID_NTSTV,H.ID_RPSTV NO_REQUEST,
					H.ORG_ID,
					H.TRX_NMB NO_NOTA,
					('IDR') SIGN_CURRENCY,
					H.CUST_NO,
					H.HDR_CTX JENIS_MODUL,
					H.HDR_SUB_CTX KD_MODUL,
					(H.DPP_) TOTAL,
					H.TTL_ KREDIT,H.TRM_NMB KD_CABANG_SIMKEU,
					H.CUST_NAME,H.CUST_ADDR,
					H.CUST_TAX_NO CUST_NPWP,
					H.VESSEL,
					(H.VOY_IN||' - '||H.VOY_OUT) VOYAGE_IN_OUT,
					H.PPN_ AS PPN,
					TO_CHAR(SYSDATE,'RRRR-MM-DD') TANGGAL_TIBA,
                    TO_CHAR(SYSDATE,'RRRR-MM-DD') TANGGAL_BERANGKAT	
		FROM BIL_NTSTE_H H WHERE H.TRX_NMB = '$id' ";
	///print_r($q_Header);die();	
	$run_Header=$db->query($q_Header);
	$rs=$run_Header->fetchRow();
	//bea materai 
	$q_mtr="SELECT D.TTL_FARE AS BEA_MATERAI FROM BIL_NTSTE_D D WHERE D.ID_NTSTV = '".$rs['ID_NTSTV']."' AND D.NT_CT='MATERAI' ";
	$run_mtr=$db->query($q_mtr);
	$rs_mtr=$run_mtr->fetchRow();
	if($rs_mtr['BEA_MATERAI']>0){
		$bea_materai=$rs_mtr['BEA_MATERAI'];
	}else{
		$bea_materai=0;
	}
	//lines transaksi 
	/*$q_lines="SELECT ('".$rs['NO_REQUEST']."') KD_PERMINTAAN,('".$id."') KD_UPER,D.SQ_ARR LINE_NUMBER,
	D.SVC_TY AS TIPE_LAYANAN,D.TAX_FLAG,D.TTL_FARE TOTTARIF,
	ROUND(D.TAX_AMOUNT) AS TAX_AMOUNT,D.NT_CT AS URAIAN,D.EI,
	(D.SIZE_CONT||' '||D.TYPE_CONT||' '||D.STS_CONT||' '||D.HZ_CONT) SIZE_TYPE_STAT_HAZ,
	D.TY_CC CRANE, ST.JUMLAH_CONT AS QTY, ST.TARIF, ST.JUMLAH_HARI TOTHARI,
	TO_CHAR(ST.TGL_START_STACK,'RRRR-MM-DD') AS TGL_AWAL,
	TO_CHAR(ST.TGL_END_STACK,'RRRR-MM-DD') AS TGL_AKHIR
	FROM BIL_NTSTE_D D,BIL_NTSTE_H H,NOTA_STACKEXT_D ST 
	WHERE H.ID_NTSTV=D.ID_NTSTV AND ST.ID_REQ=H.ID_RPSTV AND ST.URUT=D.SQ_ARR AND D.ID_NTSTV = '".$rs['ID_NTSTV']."' ";*/
	
	$q_lines="SELECT H.ID_RPSTV AS KD_PERMINTAAN,(H.TRX_NMB) KD_UPER,D.SQ_ARR LINE_NUMBER,
    D.SVC_TY AS TIPE_LAYANAN,D.TAX_FLAG,D.TTL_FARE TOTTARIF,
    ROUND(D.TAX_AMOUNT) AS TAX_AMOUNT,D.NT_CT AS URAIAN,D.EI,
    (D.SIZE_CONT||' '||D.TYPE_CONT||' '||D.STS_CONT||' '||D.HZ_CONT) SIZE_TYPE_STAT_HAZ,
    D.TY_CC CRANE, D.QTY_RPSTV AS QTY, 
    D.FARE_ AS TARIF, (SELECT SD.JUMLAH_HARI FROM NOTA_STACKEXT_D SD
    WHERE SD.ID_REQ=H.ID_RPSTV AND D.SQ_ARR=SD.URUT
    ) AS TOTHARI,
    (SELECT TO_CHAR(SD.TGL_START_STACK,'RRRR-MM-DD') FROM NOTA_STACKEXT_D SD
    WHERE SD.ID_REQ=H.ID_RPSTV AND D.SQ_ARR=SD.URUT
    ) AS TGL_AWAL,
    (SELECT TO_CHAR(SD.TGL_END_STACK,'RRRR-MM-DD') FROM NOTA_STACKEXT_D SD
    WHERE SD.ID_REQ=H.ID_RPSTV AND D.SQ_ARR=SD.URUT
    ) AS  TGL_AKHIR
    FROM BIL_NTSTE_D D,BIL_NTSTE_H H 
    WHERE  D.ID_NTSTV=H.ID_NTSTV
    AND D.ID_NTSTV = '".$rs['ID_NTSTV']."' 
	ORDER BY D.NT_CT,D.SIZE_CONT,D.TYPE_CONT,D.STS_CONT";
	///print_r($q_lines);die();
	$run_Lines=$db->query($q_lines);
	$rowdetail=$run_Lines->GetAll();
	
		$kirimEsb = $esb->nbsOPUSArInvoice($rs,$bea_materai,$rowdetail,$rsamount);
		$kirimEsbResponese = json_decode($kirimEsb, true);
		$status = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorCode"];
		$erroMessage = $kirimEsbResponese["arResponseDoc"]["esbBody"][0]["errorMessage"];
		//print_r($status);
		//print_r($erroMessage);die();	
		if($status=='S'){
				
				/**update status table **/
				$sql="update bil_ntste_h set sts_rpstv='T', trf_date=sysdate, 
						trf_user_id='".$userid."', 
						trf_response='".$status."', 
						trf_msg = '".$rs['ORG_ID']."' 
						where trim(trx_nmb)=trim('".$id."')";
				$db=getDb();
				$rs=$db->query($sql);
				/**gagat modif 17 feb 2020*/
				$sqlReq="UPDATE NOTA_STACKEXT_H SET STATUS='T' WHERE ID_NOTA=trim('".$id."') ";
				$db=getDb();
				$rsReq=$db->query($sqlReq);
				echo "T";	
		}else{
				echo "F";
		}
		die();
	}else{	
		$param = array(
					in_nota => $id,
					in_userid => $userid,
					out_msg => '',
					out_status => ''        
		);
		//print_r($param); die();
		$query = "declare begin nbs_transferariptk_see(:in_nota, :in_userid, :out_msg, :out_status ); end;";
		$db->query($query,$param);
		$messagetransfer=$param["out_msg"];
		$statustransfer=$param["out_status"];
		echo $param["out_status"];
	}
?>