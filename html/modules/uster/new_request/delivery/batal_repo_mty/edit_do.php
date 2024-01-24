<?php
	        //	debug ($_POST);die;
		 $KD_PELANGGAN  		= $_POST["KD_PELANGGAN"]; 
		 $KD_PELANGGAN2  		= $_POST["KD_PELANGGAN2"];
		 $NO_REQUEST2  			= $_POST["NO_REQUEST2"];
		 $NO_REQUEST  			= $_POST["NO_REQUEST"];
		 $TGL_BERANGKAT 		= $_POST["TGL_BERANGKAT"];
	     $TGL_REQ				= $_POST["TGL_REQ"];
	     $PEB        			= $_POST["NO_PEB"];
		 $NPE     				= $_POST["NO_NPE"];
		 $KD_PELABUHAN_ASAL     = $_POST["KD_PELABUHAN_ASAL"];
		 $KD_PELABUHAN_TUJUAN  	= $_POST["KD_PELABUHAN_TUJUAN"];
		 $NM_KAPAL   			= $_POST["NM_KAPAL"];
		 $VOYAGE_IN 			= $_POST["VOYAGE_IN"];
	     $NO_BOOKING			= $_POST["NO_BOOKING"];
		 $KETERANGAN			= $_POST["KETERANGAN"];
		 $ID_USER				= $_SESSION["LOGGED_STORAGE"];
		 $NM_USER				= $_SESSION["NAME"];
	     $ID_YARD				= $_SESSION["IDYARD_STORAGE"];
		 $NM_USER				= $_SESSION["NAME"];
		 $NO_UKK				= $_POST["NO_UKK"];
		 $SHIFT_RFR				= $_POST["SHIFT_RFR"];
		 $TGL_MUAT				= $_POST["TGL_MUAT"];
		 $TGL_STACKING			= $_POST["TGL_STACKING"];
	
		$db = getDB("storage");
			
			//==================update tpk==========================
	$sqlcek3 = "SELECT TO_CHAR (TGL_JAM_CLOSE,'DD-MM-YYYY HH24:MI:SS')TGL_JAM_CLOSE  FROM PETIKEMAS_CABANG.TTH_CONT_BOOKING WHERE NO_BOOKING = '$NO_BOOKING'";
	
	$rs3 = $db->query($sqlcek3);
	$rowcek3 = $rs3 -> FetchRow();
	$datedoc = $rowcek3["TGL_JAM_CLOSE"];
	
	$sqldate = "SELECT TO_CHAR (SYSDATE,'DD-MM-YYYY HH24:MI:SS')HARINI FROM DUAL";
	
	$rsdate = $db->query($sqldate);
	$rowdate = $rsdate -> FetchRow();
	$sysdate = $rowdate["HARINI"];
	
	
	if ($datedoc  <= $sysdate)
	{
		echo "Masa Closing Time Sudah Habis, Tidak Dapat Melakukan Perubahan Data"; exit;
	}else
	{
			
			$sqlhead	= 	"UPDATE PETIKEMAS_CABANG.TTH_CONT_EXBSPL SET
									NO_UKK				= '$NO_UKK',
									TGL_MUAT			= to_date('$TGL_MUAT','DD/Mon/YY'),
									TGL_STACK			= to_date('$TGL_STACKING','DD/Mon/YY'),
									TGL_SIMPAN			= SYSDATE,
									PELABUHAN_TUJUAN 	= '$KD_PELABUHAN_TUJUAN',
									KD_PELANGGAN		= '$KD_PELANGGAN2',
									KETERANGAN			= '$KETERANGAN',
									NO_PEB				= '$PEB',
									NO_NPE				= '$NPE',
									KD_PMB_LAMA			= '$NO_REQUEST2',
									USER_ID				= '$NM_USER',	
									NO_BOOKING			= '$NO_BOOKING',
									SHIFT_RFR			= '$SHIFT_RFR',
									FOREIGN_DISC		= '$KD_PELABUHAN_ASAL',
									KD_PELANGGAN2 		= '$KD_PELANGGAN2'
									WHERE KD_PMB = '$NO_REQUEST2'";
			
            //==================update uster=========================
			$query_req 	= "UPDATE request_delivery SET
							KD_EMKL 				= '$KD_PELANGGAN',
							KD_EMKL2 				= '$KD_PELANGGAN2', 
							KETERANGAN 				= '$KETERANGAN', 
							ID_USER 				= '$ID_USER', 
							VESSEL					= '$NM_KAPAL',
							VOYAGE					= '$VOYAGE_IN',
							PEB 					= '$PEB', 
							NPE 					= '$NPE' ,
							TGL_MUAT 				= to_date('$TGL_MUAT','DD/Mon/YY'), 
							TGL_STACKING			= to_date('$TGL_STACKING','DD/Mon/YY')
							WHERE NO_REQUEST = '$NO_REQUEST'";
	}
	//echo $sqlhead; echo $query_req; die;

	if (($db->query($query_req)) && ($db->query($sqlhead)))
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQUEST.'&no_req2='.$NO_REQUEST2);	
	}


                
        
        
?>