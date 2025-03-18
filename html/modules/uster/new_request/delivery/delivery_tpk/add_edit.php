<?php


        
        $NO_REQ         = $_POST["NO_REQ"];
        $TGL_REQ	= $_POST["tgl_dev"];
	$ID_EMKL	= $_POST["ID_EMKL"];
	$KETERANGAN	= $_POST["keterangan"];
	$ID_USER	= $_SESSION["LOGGED_STORAGE"];
	
	$db = getDB("storage");
//--------------------------------------- UPDATE HEADER TPK -------------------------------------------------------//
	$sql 	= "	UPDATE TTH_CONT_EXBSPL SET 
					NO_UKK = '".addslashes($_POST['NO_UKK'])."',
					PELABUHAN_TUJUAN = '".addslashes($_POST['PELABUHAN_TUJUAN'])."',
					FOREIGN_DISC = '".addslashes($_POST['KD_FOREIGN_DISC'])."',
					KD_PELANGGAN = '".addslashes($_POST['KD_PELANGGAN'])."',
					KETERANGAN = '".addslashes($_POST['PEB_KETERANGAN'])."',
					NO_PEB = '".addslashes($_POST['NO_PEB'])."',
					NO_NPE = '".addslashes($_POST['NO_NPE'])."',
					SHIFT_RFR = '".addslashes($_POST['SHIFT_RFR'])."',
					TGL_MUAT = TO_DATE('".addslashes($_POST['TGL_MUAT'])."','YYYY-MM-DD'),
					TGL_STACK = TO_DATE('".addslashes($_POST['TGL_STACK'])."','YYYY-MM-DD'),
					KD_PELANGGAN2 = '".addslashes($_POST['KD_PELANGGAN2'])."',
					NO_BOOKING_SHIP = '".addslashes($_POST['NO_BOOKING_SHIP'])."'
				    WHERE KD_PMB='".addslashes($_POST['KD_PMB'])."'";

	if ($db->query( $sql )) {

			for($i=0;$i<count($_POST["KD_PMB_DTL"]);$i++){
				$sqlx = "UPDATE  TTD_CONT_EXBSPL SET 
						NO_CONTAINER = '".addslashes($_POST["NO_CONTAINER"][$i])."',
						KD_SIZE = '".addslashes($_POST["SIZE_CONT"][$i])."',
						KD_TYPE_CONT = '".addslashes($_POST["KD_TYPE_CONT"][$i])."',
						KD_JENIS_PEMILIK  = '".addslashes($_POST["KD_JENIS_PEMILIK"][$i])."',
						HZ  = '".addslashes($_POST["HZ"][$i])."',
						KD_COMMODITY  = '".addslashes($_POST["KD_COMMODITY"][$i])."',
						GROSS = ".addslashes($_POST["GROSS"][$i]).",
						TRUCK_NO = '".addslashes($_POST["TRUCK_NO"][$i])."',
						KETERANGAN = '".addslashes($_POST["KETERANGAN"][$i])."',  
						VIA = '".addslashes($_POST['KD_VIA'][$i])."'
						WHERE KD_PMB_DTL='".addslashes($_POST['KD_PMB_DTL'][$i])."'";
														  }
							}
							
//----------------------------------------END UPDATE HEADER TPK ---------------------------------------------------//
	$query_req 	= "UPDATE REQUEST_DELIVERY SET ID_EMKL = '$ID_EMKL', TGL_REQUEST = SYSDATE, TGL_REQUEST_DELIVERY = TO_DATE('".$TGL_REQ."','dd/mm/yyyy'), KETERANGAN = '$KET', ID_USER = '$ID_USER' WHERE NO_REQUEST = '$NO_REQ'";
//        echo $query_req;

	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQ);		
	} else {
                header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQ);	
            
        }
        


                
        
        
?>