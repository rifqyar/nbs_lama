<?php
        $TGL_REQ	= $_POST["tgl_dev"];
        $DEV_KE 	= $_POST["dev_ke"];

	//$ID_PBM	= $_POST["ID_PBM"];
		$EMKL		= $_POST["ID_EMKL"];
		$ALMT_PBM	= $_POST["ALMT_PBM"];
		$NPWP		= $_POST["NPWP"];
        $ASAL       = $_POST["ASAL"];
		$TUJUAN 	= $_POST["TUJUAN"];
        $PEB        = $_POST["peb"];
		$NPE     	= $_POST["npe"];
        $NO_BOOKING	= $_POST["NO_BOOKING"];
        $NO_RO		= $_POST["NO_RO"];
        $AC_EMKL		= $_POST["AC_EMKL"];		
        $KD_AGEN		= $_POST["KD_AGEN"];
		$KETERANGAN	= $_POST["keterangan"];
		$ID_USER	= $_SESSION["LOGGED_STORAGE"];
        $ID_YARD	= $_SESSION["IDYARD_STORAGE"];
	
		$db = getDB("storage");
		
			$query_cek	= "SELECT LPAD(NVL(MAX(SUBSTR(NO_REQUEST,8,13)),0)+1,6,0) AS JUM , 
				   TO_CHAR(SYSDATE, 'MM') AS MONTH, 
				   TO_CHAR(SYSDATE, 'YY') AS YEAR 
				   FROM REQUEST_DELIVERY
				   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_req	= "DEL".$month.$year.$jum;
	
//-------------------------------------------------- INSERT TO TPK's RECEIVING --------------------------------------------------------//
/*
	$IDKDPMB 	= 'UST-DEL'.$month.$year.$jum;

						$sql 	= 	"INSERT INTO TTH_CONT_EXBSPL (KD_PMB,
								 	KD_CABANG,
									NO_UKK,
									TGL_MUAT,
									TGL_STACK,
									TGL_SIMPAN,
									PELABUHAN_TUJUAN,
									KD_PELANGGAN,
									KETERANGAN,
									STATUS_CONT_EXBSPL,
									STATUS_KARTU,
									NO_PEB,
									KD_PMB_LAMA,
									USER_ID,
									NO_NPE,
									NO_BOOKING,
									SHIFT_RFR,
									FOREIGN_DISC,
									KD_PELANGGAN2,
									NO_BOOKING_SHIP) VALUES(
									'$IDKDPMB',
									'05',
									'".addslashes(strtoupper($_POST['NO_UKK']))."',
									to_date('".$_POST['TGL_MUAT']."','YYYY-MM-DD HH24:MI:SS'),
									to_date('".$_POST['TGL_STACK']."','YYYY-MM-DD HH24:MI:SS'),
									SYSDATE ,
									'".addslashes(strtoupper($_POST['PELABUHAN_TUJUAN']))."',
									'".addslashes(strtoupper($_POST['KD_PELANGGAN']))."',
									'$KETERANGAN',
									'0',
									'0',
									'$PEB',
									'".addslashes($IDKDPMB)."',
									'".addslashes($aclist["USERID"])."',
									'$NPE',								
									'".addslashes(strtoupper($_POST['NO_BOOKING']))."',
									".addslashes($_POST['SHIFT_RFR']).",
									'".addslashes(strtoupper($_POST['KD_FOREIGN_DISC']))."',
									'".addslashes(strtoupper($_POST['KD_PELANGGAN2']))."',
									'".addslashes(strtoupper($_POST['NO_BOOKING_SHIP']))."'
					)";
					if ($db->query( $sql )) {
			for($i=0;$i<count($_POST["PBS_NOCONT"]);$i++){
				$yp  = '';
				$yp2 = '';
				if($_POST["NO_BOOKING"]==""){
					
					$sqlxx  = "SELECT ARE_ID,ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT FROM V_MST_YARD_EX WHERE KD_STATUS_CY='0' AND KD_CABANG='".$KD_CABANG."' ORDER BY ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
				}else{
					if($_POST['I_CONT_TYPE_NAME'][$i]=='07'){
						$sqlbook = "SELECT * FROM V_DATA_BOOKING_RFR WHERE NO_BOOKING='".$_POST["NO_BOOKING"]."'";
					}else{
						$sqlbook = "SELECT * FROM V_DATA_BOOKING WHERE NO_BOOKING='".$_POST["NO_BOOKING"]."'";
					}
					
					if($_POST['I_JENIS_PEMILIK'][$i]=='3'){
						$sqlbook = "SELECT * FROM V_DATA_BOOKING_MTY WHERE NO_BOOKING='".$_POST["NO_BOOKING"]."'";
					}
					
					if($rsx = $db->selectLimit( $sqlbook,0,1 )){
							$rsbook  = $rsx->FetchRow();
							$blk_id = $rsbook["ARE_BLOK"];
							
							$sqlxx  = "SELECT ARE_ID,ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT FROM V_MST_YARD_EX WHERE KD_STATUS_CY='0' AND KD_CABANG='".$KD_CABANG."' AND MCB.ARE_BLOK='".$rsbook["ARE_BLOK"]."' AND  MCY.ARE_ROW BETWEEN '".$rsbook["M_ROW"]."' AND '".$rsbook["S_ROW"]."' AND  MCY.ARE_SLOT BETWEEN '".$rsbook["M_SLOT"]."' AND '".$rsbook["S_SLOT"]."' AND  MCY.ARE_TIER BETWEEN '".$rsbook["M_TIER"]."' AND '".$rsbook["S_TIER"]."' ORDER BY ARE_TIER,ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
						
					}
				}
				
				if($rsx = $db->selectLimit( $sqlxx,0,1 )){
					$rscon  = $rsx->FetchRow();
					$yp     = $rscon["ARE_ID"];
					$slott     = $rscon["ARE_SLOT"] + 1;
					if($_POST['I_KD_SIZE'][$i]!='1'){
						//$sql2 = "SELECT ARE_ID FROM MST_YARD_CONT_LAPANGAN_EX WHERE ARE_TIER='".$rscon["ARE_TIER"]."' AND ARE_BLOK='".$rscon["ARE_BLOK"]."' AND ARE_ROW='".$rscon["ARE_ROW"]."' AND ARE_SLOT='".$slott."'";
						$sql2 = "SELECT ARE_ID FROM V_MST_YARD_EX WHERE ARE_TIER='".$rscon["ARE_TIER"]."' AND ARE_BLOK='".$rscon["ARE_BLOK"]."' AND ARE_ROW='".$rscon["ARE_ROW"]."' AND ARE_SLOT='".$slott."' AND KD_CABANG='".$KD_CABANG."'";
						if( $rss2 = $db->selectLimit( $sql2,0,1 ) ){
							$rscon2   = $rss2->FetchRow();
							$yp2      = $rscon2["ARE_ID"];
						}
					}
				
					$sqlxxx = "UPDATE MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp."'";
					$db->startTransaction();
					$xxxxxx = $db->query($sqlxxx);
					$db->endTransaction();
					
					$sqlxxzz = "UPDATE MST_YARD_CONT_LAPANGAN_EX SET KD_STATUS_CY='1' WHERE ARE_ID='".$yp2."'";
					$db->startTransaction();
					$xxxxzz = $db->query($sqlxxzz);
					$db->endTransaction();
				}
					if($yp==''){
						$yp = 'XXXX';
					}
					$sqlx   = "INSERT INTO TTD_CONT_EXBSPL(KD_PMB_DTL,KD_PMB,ARE_ID,NO_CONTAINER,KD_SIZE,KD_TYPE_CONT,KD_JENIS_PEMILIK,KD_COMMODITY,GROSS,NO_SEAL,KETERANGAN,STATUS_PMB_DTL,STATUS_KARTU,USER_ID,VIA,HZ,ARE_ID2,ARE_BLOK,STATUS_PP,TEMP,CLASS) 
					  VALUES
					 (
						SEQ_TTD_CONT_EXBSPL.NEXTVAL,
						'".addslashes($IDKDPMB)."',
						'".addslashes($yp)."',
						'".addslashes(strtoupper($_POST['PBS_NOCONT'][$i]))."',
						'".addslashes(strtoupper($_POST['I_KD_SIZE'][$i]))."',
						'".addslashes(strtoupper($_POST['I_CONT_TYPE_NAME'][$i]))."',
						'".addslashes(strtoupper($_POST['I_JENIS_PEMILIK'][$i]))."',
						'".addslashes(strtoupper($_POST['I_COMMODITY'][$i]))."',
						 ".addslashes(strtoupper($_POST['PBS_GROSS'][$i])).",
						'".addslashes(strtoupper($_POST['PBS_SEAL'][$i]))."',
						'".addslashes(strtoupper($_POST['PBS_KETERANGAN'][$i]))."',
						'0',
						'0',
						'".addslashes($aclist["USERID"])."',
						'".addslashes(strtoupper($_POST['I_KD_VIA'][$i]))."',
						'".addslashes(strtoupper($_POST['HZ'][$i]))."',
						'".addslashes(strtoupper($yp2))."',
						'".addslashes(strtoupper($blk_id))."',
						'T',
						'".addslashes(strtoupper($_POST['PBS_TEMP'][$i]))."',
						'".addslashes(strtoupper($_POST['PBS_CLASS'][$i]))."'
					)";
					//echo $sqlx;exit();
					if(!$db->query($sqlx)){
						$_err	= true;
					}
					
						if($_POST['I_JENIS_PEMILIK'][$i]=='3'){
							$sqlx3   = "INSERT INTO TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
								(
									SEQ_TTD_CONT_EXBSPL.CURRVAL,
									'-',
									SYSDATE, 
									'".addslashes($aclist["USERID"])."',
									'".addslashes($KD_CABANG)."',
									SYSDATE,
									'".	addslashes($IDKDPMB)."',
									'',
									'".	addslashes($_POST['NO_UKK'])."'
								)";
							$db->query($sqlx3);
						}
					}
					
			}
*/
					
//-------------------------------------------------- END INSERT TPK's RECEIVING -------------------------------------------------------//	
	
	
	
	//echo $query_cek."<br/>";
	//echo $no_req.$jum."<br/>";

				$query_req 	= "INSERT INTO request_delivery(NO_REQUEST, REQ_AWAL, TGL_REQUEST, TGL_REQUEST_DELIVERY, KETERANGAN, DELIVERY_KE, CETAK_KARTU, ID_USER,  PERALIHAN, ID_YARD, STATUS,KD_EMKL, NO_RO, KD_AGEN) 
                                                      VALUES ('$no_req', '$no_req',SYSDATE, TO_DATE('".$TGL_REQ."','yyyy/mm/dd'),'$KETERANGAN', 'LUAR','0', $ID_USER,'T','$ID_YARD','NEW','$EMKL', '$NO_RO', '$KD_AGEN')";
              $db->query($history);
//echo "$query_req";die;
	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$no_req);		
	}


                
					
        
?>