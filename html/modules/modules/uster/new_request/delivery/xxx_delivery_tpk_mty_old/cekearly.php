<?
//$arrPost    = $_POST;
//debug ($_POST);die;
$NO_BOOKING = $_POST["NO_BOOKING"];
$KD_PBM     = $_POST["KD_PBM"];
$NO_UKK		= $_POST["NO_UKK"];
//$TYPE	    = $_POST["TYPE"];
$_HARI      = 4;

//echo $NO_BOOKING;echo $KD_PBM; exit;
outputraw();
	$db = getDB('ora');
	require_lib('acl.php');	
	$acl = new ACL();
	$acl->load();
	$aclist = $acl->getLogin()->info;	
	$KD_CABANG = ($aclist['KD_CABANG'] == '')?'00':$aclist['KD_CABANG'];			
	//if($KD_CABANG == '03'){
//		if($TYPE == '07'){
//	$sql = "SELECT TO_CHAR(DOC_CLOSING_DATE_REEFER,'YYYY-MM-DD HH24:MI:SS') AS DOCDATE FROM TTD_VESSEL_SCHEDULE WHERE NO_UKK='".$NO_UKK."' AND VS_STATUS='P'";
//}else{
	$sql = "SELECT TO_CHAR(DOC_CLOSING_DATE_DRY,'YYYY-MM-DD HH24:MI:SS') AS DOCDATE FROM TTD_VESSEL_SCHEDULE WHERE NO_UKK='".$NO_UKK."' ";
//}
$rsx = $db->query($sql);
if($rsx->RecordCount()>0)
{
		$row = $rsx->FetchRow();
		$datedoc = $row["DOCDATE"];
			$sql2 = "SELECT 
						CASE
						WHEN TO_DATE('".$datedoc."','YYYY-MM-DD HH24:MI:SS') > SYSDATE  THEN
								'Y'
							ELSE
								'T'
						END AS VALIDX
						FROM 
						DUAL";
			$rsd = $db->query($sql2);
			$rows = $rsd->FetchRow();
			$result = $rows["VALIDX"];
		  	if ($result == 'T')
			{
				echo "T"; exit;
			}  
			
} else {
$sql = "SELECT JENIS, TO_CHAR(TGL_JAM_TIBA,'YYYY-MM-DD') AS TGL_JAM_TIBA FROM TTH_CONT_BOOKING,V_PKK_CONT WHERE TTH_CONT_BOOKING.NO_UKK=V_PKK_CONT.NO_UKK AND TTH_CONT_BOOKING.NO_BOOKING='".$NO_BOOKING."'";
		$rsx = $db->query($sql);
		if($rsx->RecordCount()>0){
			$row = $rsx->FetchRow();
			if( $row["JENIS"]=='1' ){
				$sqljam = "SELECT TRUNC((((86400*(TO_DATE('".$row["TGL_JAM_TIBA"]."','YYYY-MM-DD HH24:MI:SS')-SYSDATE))/60)/60)/24) AS JMLHARI FROM DUAL";
				if($rsj = $db->query($sqljam)){
					$rowj = $rsj->FetchRow();
					if( $rowj["JMLHARI"] >  $_HARI ){
						$sqlck = "SELECT * FROM TTD_BOOKING_PBM WHERE NO_BOOKING='".$NO_BOOKING."' AND KD_PBM='".$KD_PBM."'";
						$rsck = $db->query($sqlck);
						if( $rsck->RecordCount() > 0 ){
							echo "Y";
						}else{ 
							echo "N";
						}
					}else{
						echo "Y";
					}
				}
			}else{
				echo "Y";
			}
		}else{
			echo "N";
		}
}
		
		
	/*}else{
		echo "Y";
	}*/
?>