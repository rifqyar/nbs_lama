<?php
	session_start();
	//outputRaw();
	$_err = false;
		$db 	= getDB("ora");
		require_lib('acl.php');	
		$acl = new ACL();
		$acl->load();
		$aclist = $acl->getLogin()->info;	
		$KD_CABANG = ($aclist['KD_CABANG'] == '')?'00':$aclist['KD_CABANG'];			
		if($KD_CABANG=='00'){
			require_lib('acl.php');	
			$_acl = new ACL();
			$_acl->doLogout();
			session_destroy();
			header('location: '.HOME."main/login");
			ob_end_flush(); die();		
		}
/*		$sqlval = "SELECT NO_CONTAINER FROM TTH_CONT_EXBSPL TH,TTD_CONT_EXBSPL TD WHERE TH.KD_PMB=TD.KD_PMB AND TH.NO_UKK='".$_POST['NO_UKK']."' AND TD.NO_CONTAINER='".$_POST['PBS_NOCONT'][0]."'" ;
		$rsval = $db->query( $sqlval ) ;
		if($rsval->RecordCount()>=1){
				$idGen = "?&crott=".$IDKDPMB;
				header('Location: '._link(	array(	'sub'=>'add_ok','id'=>$IDKDPMB)));
				ob_end_flush(); die();		
		}
		*/
		/*$param_no= array(
						"KD_CABANG"=>$KD_CABANG
						"IDKDPMB"=>""
							);
		$sqlsp = "BEGIN SP_GEN_KD_PMB(:IDKDPMB); END;"; 
		$db->query($sqlsp,$param_no);
		$IDKDPMB = $param_no["IDKDPMB"];
		*/
		$param_no_kode_lunas= array(
			 "KD_CABANG"=>$KD_CABANG,
			 "KD_MODULE"=>"00",
			 "OUT_NO_NOTA_LUNAS"=>"",
			 "MESS_NO_NOTA_LUNAS"=>""
			);
			
			$sql_no_kd_lunas = "BEGIN SP_KDPMB_NEW(:KD_CABANG,:KD_MODULE,:OUT_NO_NOTA_LUNAS,:MESS_NO_NOTA_LUNAS); END;"; 
			$db->query($sql_no_kd_lunas,$param_no_kode_lunas);
			$IDKDPMB = $param_no_kode_lunas["OUT_NO_NOTA_LUNAS"];
			
		//echo $IDKDPMB;exit; //to_date('".date("Y-m-d H:i:s")."','YYYY-MM-DD HH24:MI:SS')
		$sql 	= "INSERT INTO TTH_CONT_EXBSPL(KD_PMB,KD_CABANG,NO_UKK,TGL_MUAT,TGL_STACK,TGL_SIMPAN,PELABUHAN_TUJUAN,KD_PELANGGAN,KETERANGAN,STATUS_CONT_EXBSPL,STATUS_KARTU,NO_PEB,KD_PMB_LAMA,USER_ID,NO_NPE,NO_BOOKING,SHIFT_RFR,FOREIGN_DISC,KD_PELANGGAN2,NO_BOOKING_SHIP) VALUES(
						'".addslashes($IDKDPMB)."',
						'".addslashes($KD_CABANG)."',
						'".addslashes(strtoupper($_POST['NO_UKK']))."',
						to_date('".$_POST['TGL_MUAT']."','YYYY-MM-DD HH24:MI:SS'),
						to_date('".$_POST['TGL_STACK']."','YYYY-MM-DD HH24:MI:SS'),
						SYSDATE ,
						'".addslashes(strtoupper($_POST['PELABUHAN_TUJUAN']))."',
						'".addslashes(strtoupper($_POST['KD_PELANGGAN']))."',
						'".addslashes(strtoupper($_POST['PEB_KETERANGAN']))."',
						'0',
						'0',
						'".addslashes(strtoupper($_POST["NO_PEB"]))."',
						'".addslashes($IDKDPMB)."',
						'".addslashes($aclist["USERID"])."',
						'".addslashes(strtoupper($_POST['NO_NPE']))."',
						'".addslashes(strtoupper($_POST['NO_BOOKING']))."',
						".addslashes($_POST['SHIFT_RFR']).",
						'".addslashes(strtoupper($_POST['KD_FOREIGN_DISC']))."',
						'".addslashes(strtoupper($_POST['KD_PELANGGAN2']))."',
						'".addslashes(strtoupper($_POST['NO_BOOKING_SHIP']))."'
					)";

		//			to_date('".createIsoDate($_POST['TGL_MUAT'])."','YYYY-MM-DD HH24:MI:SS'),
		//			to_date('".createIsoDate($_POST['TGL_STACK'])."','YYYY-MM-DD HH24:MI:SS'),
		//echo $sql;exit;
		if ($db->query( $sql )) {
			for($i=0;$i<count($_POST["PBS_NOCONT"]);$i++){
				$yp  = '';
				$yp2 = '';
				if($_POST["NO_BOOKING"]==""){
					//$sqlxx  = "SELECT MCY.ARE_ID,MCY.ARE_TIER,MCB.ARE_BLOK,MCY.ARE_ROW,MCY.ARE_SLOT FROM MST_YARD_CONT_LAPANGAN_EX MCY,MST_CONT_BLOCK_EX MCB WHERE MCY.ARE_BLOK=MCB.ARE_BLOK AND MCB.AREA_CODE='E' AND KD_STATUS_CY='0' ORDER BY ARE_TIER,MCB.ARE_BLOK,ARE_ROW,ARE_SLOT ASC";
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
							//$sqlxx  = "SELECT MCY.ARE_ID,MCY.ARE_TIER,MCB.ARE_BLOK,MCY.ARE_ROW,MCY.ARE_SLOT FROM MST_YARD_CONT_LAPANGAN_EX MCY,MST_CONT_BLOCK_EX MCB WHERE MCY.ARE_BLOK=MCB.ARE_BLOK AND MCB.AREA_CODE='E' AND MCY.KD_STATUS_CY='0' AND MCB.ARE_BLOK='".$rsbook["ARE_BLOK"]."' AND  MCY.ARE_ROW BETWEEN '".$rsbook["M_ROW"]."' AND '".$rsbook["S_ROW"]."' AND  MCY.ARE_SLOT BETWEEN '".$rsbook["M_SLOT"]."' AND '".$rsbook["S_SLOT"]."' AND  MCY.ARE_TIER BETWEEN '".$rsbook["M_TIER"]."' AND '".$rsbook["S_TIER"]."' ORDER BY MCY.ARE_TIER,MCY.ARE_ROW,MCY.ARE_SLOT ASC";
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
					if( $KD_CABANG=='01' ){
						$sqlx4   = "INSERT INTO TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
							(
								SEQ_TTD_CONT_EXBSPL.CURRVAL,
								'".addslashes($_POST['NO_NPE'])."',
								SYSDATE, 
								'".addslashes($aclist["USERID"])."',
								'".addslashes($KD_CABANG)."',
								SYSDATE,
								'".	addslashes($IDKDPMB)."',
								'',
								'".	addslashes($_POST['NO_UKK'])."'
							)";
						$db->query( $sqlx4 );
					}else{
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
			
			if($_err != true){
				//$idGen = $db->generatedId();
				$idGen = "?&crott=".$IDKDPMB;
				header('Location: '._link(	array(	'sub'=>'add_ok','id'=>$IDKDPMB)));
				ob_end_flush(); die();		
			}
	

		//}
	}
	$_SESSION['__postback'][APPID] = $_POST;
	header('Location: '._link(	array(	'sub'=>'add','error'=>'error'	)));
?>