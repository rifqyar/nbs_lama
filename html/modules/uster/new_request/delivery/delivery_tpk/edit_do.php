<?php
	     $ID_USER				= $_SESSION["LOGGED_STORAGE"];
		 $NM_USER				= $_SESSION["NAME"];
	     $ID_YARD				= $_SESSION["IDYARD_STORAGE"];
		 $NM_USER				= $_SESSION["NAME"];
		 $KD_PELANGGAN  		= $_POST["KD_PELANGGAN"]; 
		 $KD_PELANGGAN2  		= $_POST["KD_PELANGGAN2"];
		 $NO_REQUEST2  			= $_POST["NO_REQUEST2"];
		 $NO_REQUEST  			= $_POST["NO_REQUEST"];
		 $NO_BOOKING			= $_POST["NO_BOOKING"];
		 $TGL_REQ				= $_POST["TGL_REQ"];
		 $PEB        			= $_POST["NO_PEB"];
		 $NPE     				= $_POST["NO_NPE"];
		 $NO_RO     			= $_POST["NO_RO"];
		 $KETERANGAN			= $_POST["KETERANGAN"];
		 $SHIFT_RFR				= $_POST["SHIFT_RFR"];
		 $TGL_MUAT				= $_POST["TGL_MUAT"];
		 $TGL_STACKING			= $_POST["TGL_STACKING"];
		 //kapal
		 $TGL_BERANGKAT 		= $_POST["TGL_BERANGKAT"];
		 $KD_PELABUHAN_ASAL     = $_POST["KD_PELABUHAN_ASAL"];
		 $KD_PELABUHAN_TUJUAN  	= $_POST["KD_PELABUHAN_TUJUAN"];
		 $NM_KAPAL   			= $_POST["NM_KAPAL"];
		 $KD_KAPAL 				= $_POST["KD_KAPAL"];
		 $VOYAGE_IN 			= $_POST["VOYAGE_IN"];
		 $VOYAGE_OUT 			= $_POST["VOYAGE_OUT"];
		 $NO_UKK				= $_POST["NO_UKK"];
		 $ETD 					= $_POST["ETD"];
	 	 $ETA 					= $_POST["ETA"];
	 	 $CALL_SIGN   			= $_POST["CALL_SIGN"];
	 	 $NM_AGEN 				= $_POST["NM_AGEN"];
	 	 $KD_AGEN 				= $_POST["KD_AGEN"];
	 	 $VOYAGE 				= $_POST["VOYAGE"];
		
		$db = getDB("storage");
			
					
            //==================update uster=========================
			$query_req 	= "UPDATE request_delivery SET
							KD_EMKL 				= '$KD_PELANGGAN2',
							KD_EMKL2 				= '$KD_PELANGGAN2', 
							KETERANGAN 				= '$KETERANGAN', 
							ID_USER 				= '$ID_USER', 
							PEB 					= '$PEB', 
							NPE 					= '$NPE' ,
							NO_RO 					= '$NO_RO'
							WHERE NO_REQUEST = '$NO_REQUEST'";

			$query_update_vessel = "UPDATE V_PKK_CONT SET
									KD_KAPAL			= '$KD_KAPAL',
									NM_KAPAL			= '$NM_KAPAL',
			                        VOYAGE_IN			= '$VOYAGE_IN',
			                        VOYAGE_OUT			= '$VOYAGE_OUT',
			                        TGL_JAM_TIBA		=  TO_DATE('$ETA', 'DD-MM-YYYY HH24:MI:SS'),
			                        TGL_JAM_BERANGKAT	=  TO_DATE('$ETD', 'DD-MM-YYYY HH24:MI:SS'),
			                        NO_UKK				= '$NO_UKK',
			                        NM_AGEN				= '$NM_AGEN',
			                        KD_AGEN				= '$KD_AGEN',
			                        PELABUHAN_ASAL  	= '$KD_PELABUHAN_ASAL',
			                        PELABUHAN_TUJUAN 	= '$KD_PELABUHAN_TUJUAN',
			                        VOYAGE 				= '$VOYAGE',
			                        CALL_SIGN			= '$CALL_SIGN'
			                        WHERE NO_BOOKING = '$NO_BOOKING'";

			 echo var_dump($query_update_vessel);

	
	if ($db->query($query_req) && $db->query($query_update_vessel))
	{
			
			header('Location: '.HOME.APPID.'/edit?no_req='.$NO_REQUEST.'&no_req2='.$NO_REQUEST2);	
		
	}
	

                
        
        
?>