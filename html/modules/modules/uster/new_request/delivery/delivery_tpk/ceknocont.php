<?

	$arrPost = $_POST;

	$NO_CONTAINER = $arrPost["NO_CONTAINER"];

	$NO_BOOKING   = $arrPost["NO_BOOKING"];

	$TYPE   	  = $arrPost["TYPE"];

	$JENIS		  = $arrPost["JENIS"];
	
	$NO_UKK		  = $arrPost["NO_UKK"];
	
	$SIZE		  = $arrPost["SIZE"];

	outputraw();


	$db = getDB('ora');

	require_lib('acl.php');	

	$acl = new ACL();

	$acl->load();

	$aclist = $acl->getLogin()->info;	

	$KD_CABANG = ($aclist['KD_CABANG'] == '')?'00':$aclist['KD_CABANG'];	
//	echo "Y";exit;		

//	if($KD_CABANG == '03'){

		$sql = "SELECT NO_CONTAINER FROM TTH_CONT_EXBSPL,TTD_CONT_EXBSPL WHERE TTH_CONT_EXBSPL.KD_PMB=TTD_CONT_EXBSPL.KD_PMB AND TTD_CONT_EXBSPL.STATUS_PMB_DTL!='4' AND TTD_CONT_EXBSPL.NO_CONTAINER='".$NO_CONTAINER."' AND TTD_CONT_EXBSPL.STATUS_PP='T' AND TTH_CONT_EXBSPL.KD_CABANG='".$KD_CABANG."'";

	///	 echo $sql;

		$rsx = $db->query($sql);


		if($rsx->RecordCount()>0)
		{

			

				echo "T"; exit; //KALO AKTIF DI SIKLUS MUAT, GA BOLEH REQUEST (EDZ)

			

		}else{ 
		/*KALO GA AKTIF DI SIKLUS MUAT, CEK DLU DI SIKLUS BONGKAR
			
			$sql2 = "SELECT MAX (BP_ID) BP_ID FROM
				(SELECT TM.BP_ID, TD.CONT_NO_BP 
				FROM TTM_BP_CONT TM, TTD_BP_CONT TD 
				WHERE TM.BP_ID = TD.BP_ID AND TD.CONT_NO_BP = '".$NO_CONTAINER."'
				AND TD.STATUS_CONT != '09' AND TM.KD_CABANG = '".$KD_CABANG."')";
//			echo $sql2;
			$rs2 = $db->query($sql2);
			if($rs2->RecordCount()>0) 
				
				{
				 echo "G"; exit; //KALO ADA DI SIKLUS BONGKAR,GA BOLEH REQUEST
				}
				
					else // KALO UDAH LOLOS SIKLUS RECEIVING MAUPUN DELIVERY CEK BOOKING STACKNYA
				
					{
*/					
				if ($NO_BOOKING != "BSJ171200114")
				{										
									$sqlb  = "select sum (jumlah_teus) as TEUS from V_dry_BOOKING_tlb where no_booking = '".$NO_BOOKING."'";
									$rsb = $db->query($sqlb);
									$rowb = $rsb->FetchRow();
									$booked = $rowb["TEUS"];		
									
									$sqlu = "SELECT count (no_container) as jml_counteiner,Sum( kd_size_teus) teus
											 FROM(
											 SELECT no_container,CASE WHEN kd_size ='3' THEN '2'
											 ELSE kd_size
											 END kd_size_teus,kd_size
											 from ttd_cont_exbspl a,tth_cont_exbspl b
											 where a.kd_pmb = b.kd_pmb and b.no_booking = '".$NO_BOOKING."') ";
									$rsu   = $db->query($sqlu);
									$rowu = $rsu->FetchRow();
									$used   = $rowu["JML"];
					
							if($used > $booked)
							{
								echo "X"; exit;		
							}				
				}

					

					if($TYPE == '07'){
					
					$sql = "SELECT TO_CHAR(DOC_CLOSING_DATE_REEFER,'YYYY-MM-DD HH24:MI:SS') AS DOCDATE FROM TTD_VESSEL_SCHEDULE 
					WHERE NO_UKK='".$NO_UKK."' ";

					}else{
					
					$sql = "SELECT TO_CHAR(DOC_CLOSING_DATE_DRY,'YYYY-MM-DD HH24:MI:SS') AS DOCDATE FROM TTD_VESSEL_SCHEDULE 
					WHERE NO_UKK='".$NO_UKK."' ";
						}
//					echo $sql; exit;	
					$rsx = $db->query($sql);
					
					if($rsx->RecordCount()>0){
						
					$row = $rsx->FetchRow();
					$datedoc = $row["DOCDATE"];
					
					$sql2 = "SELECT 
					CASE
					WHEN TO_DATE('".$datedoc."','YYYY-MM-DD HH24:MI:SS') > SYSDATE  THEN
					'Y'
					ELSE
					'Z'
					END AS VALIDX
					FROM 
					DUAL";
							$rsd = $db->query($sql2);
							$rows = $rsd->FetchRow();
							$result = $rows["VALIDX"];
//				echo $sql2; exit;
							//echo $result;
							echo "Y";
				
												}else{
												echo "Z"; 
												}
	

					}	

		/*}*/


?>