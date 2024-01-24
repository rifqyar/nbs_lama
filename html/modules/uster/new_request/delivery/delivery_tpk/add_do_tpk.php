<?php

	 // debug($_POST);die;
     $id_yard_	        	= $_SESSION["IDYARD_STORAGE"];
	 $KD_PELANGGAN  		= $_POST["KD_PELANGGAN"]; 
	 $KD_PELANGGAN2  		= $_POST["KD_PELANGGAN2"];
	 $TGL_BERANGKAT 		= $_POST["TGL_BERANGKAT"];
	 $TGL_REQ			   	= $_POST["TGL_REQ"];
	 $PEB        			= $_POST["NO_PEB"];
	 $NPE     			  	= $_POST["NO_NPE"];
	 $KD_PELABUHAN_ASAL     = $_POST["KD_PELABUHAN_ASAL"];
	 $KD_PELABUHAN_TUJUAN   = $_POST["KD_PELABUHAN_TUJUAN"];
	 $NM_KAPAL   			= $_POST["NM_KAPAL"];
	 $CALL_SIGN   			= $_POST["CALL_SIGN"];
	 $VOYAGE_IN 			= $_POST["VOYAGE_IN"];
	 $VOYAGE_OUT 			= $_POST["VOYAGE_OUT"];
	 $NO_BOOKING			= $_POST["NO_BOOKING"];
	 $KETERANGAN			= $_POST["KETERANGAN"];
	 $ID_USER				= $_SESSION["LOGGED_STORAGE"];
	 $NM_USER				= $_SESSION["NAME"];
	 $ID_YARD				= $_SESSION["IDYARD_STORAGE"];
	 $NM_USER				= $_SESSION["NAME"];
	 $NO_UKK				= $_POST["NO_UKK"];
	 $SHIFT_RFR				= $_POST["SHIFT_RFR"];
	 $DI       				= $_POST["DI"];
	 $TGL_MUAT				= $_POST["TGL_MUAT"];
	 $TGL_STACKING			= $_POST["TGL_STACKING"];
	 $NO_RO					= $_POST["NO_RO"];
	 $JN_REPO				= $_POST["JN_REPO"];
	 $NO_STUFF				= $_POST["NO_REQ_STUFF"];
	 $TGL_MULAI				= $_POST["TGL_MULAI"];
	 $TGL_NANTI      		= $_POST["TGL_NANTI"];
	 $NO_ACCOUNT_PBM      	= $_POST["NO_ACCOUNT_PBM"];
	 $KD_KAPAL 				= $_POST["KD_KAPAL"];
	 $ETD 					= $_POST["ETD"];
	 $ETA 					= $_POST["ETA"];
	 $OPEN_STACK 			= $_POST["OPEN_STACK"];
	 $NM_AGEN 				= $_POST["NM_AGEN"];
	 $KD_AGEN 				= $_POST["KD_AGEN"];
	 $CONT_LIMIT 			= $_POST["CONT_LIMIT"];
	 $CLOSING_TIME 			= $_POST["CLOSING_TIME"];
	 $CLOSING_TIME_DOC 		= $_POST["CLOSING_TIME_DOC"];
	 $VOYAGE 				= $_POST["VOYAGE"];
	 
	 
	$db = getDB("storage");
	//Cek tipe delivery TPK, apakah eks stuffing atau empty
	if($JN_REPO == "EMPTY")
	{
		
//-------------------------------------------------- INSERT TO TPK's RECEIVING --------------------------------------------------------//
							
                    $param = array("in_accpbm"=>$NO_ACCOUNT_PBM,
                                   "in_pbm"=>$KD_PELANGGAN,
                                   "in_peb"=>$PEB,
                                   "in_npe"=>$NPE,
                                   "in_noro"=>$NO_RO,
                                   "in_keterangan"=>$KETERANGAN,
                                   "in_jenis"=>$JN_REPO,
                                   "in_user"=>$ID_USER,
                                   "in_di"=>$DI,
                                   "in_vessel"=>$NM_KAPAL,
                                   "in_voyin"=>$VOYAGE_IN,
                                   "in_voyout"=>$VOYAGE_OUT,
                                   "in_idvsb"=>$NO_UKK,
                                   "in_nobooking"=>$NO_BOOKING,
                                   "in_callsign"=>$CALL_SIGN,
                                   "in_pod"=>$KD_PELABUHAN_TUJUAN,
                                   "in_pol"=>$KD_PELABUHAN_ASAL,
                                   "in_shift"=>$SHIFT_RFR,
                                   "in_plin"=>$TGL_MULAI,
                                   "in_plout"=>$TGL_NANTI,
                                   "in_nostuf"=>$NO_STUFF,
                                   "in_kdkapal"=>$KD_KAPAL,
                                   "in_etd"=>$ETD,
                                   "in_eta"=>$ETA,
                                   "in_openstack"=>$OPEN_STACK,
                                   "in_nmagen"=>$NM_AGEN,
                                   "in_kdagen"=>$KD_AGEN,
                                   "in_closingtime"=>$CLOSING_TIME,
                                   "in_closingtimedoc"=>$CLOSING_TIME_DOC,
                                   "in_voyage"=>$VOYAGE,
                                   "out_noreq"=>'',
                                   "out_reqnbs"=>'',
                                   "out_msg"=>''                                 
                    );

                    // echo var_dump($param);
                    // die;

                    $query_int = "declare begin pack_create_req_delivery_repo.create_header_repo_praya(:in_accpbm,:in_pbm,:in_peb,:in_npe,:in_noro,:in_keterangan,:in_jenis ,:in_user,:in_di ,:in_vessel,:in_voyin ,:in_voyout,:in_idvsb,:in_nobooking,:in_callsign,:in_pod,:in_pol,:in_shift,:in_plin,:in_plout,:in_nostuf,:in_kdkapal,:in_etd, :in_eta,:in_openstack,:in_nmagen, :in_kdagen,:in_closingtime,:in_closingtimedoc,:in_voyage,:out_noreq,:out_reqnbs,:out_msg); end;";

                    $db->query($query_int,$param);
                    $msg = $param["out_msg"];
                    $no_req = $param["out_noreq"];
                    $no_req2 = $param["out_reqnbs"];
//-------------------------------------------------- END INSERT TPK's RECEIVING -------------------------------------------------------//	
	}
	else
	{
					
				
				
				
			//-------------------------------------------------- INSERT TO TPK's RECEIVING --------------------------------------------------------//
						  $param = array("in_accpbm"=>$NO_ACCOUNT_PBM,
                                   "in_pbm"=>$KD_PELANGGAN,
                                   "in_peb"=>$PEB,
                                   "in_npe"=>$NPE,
                                   "in_noro"=>$NO_RO,
                                   "in_keterangan"=>$KETERANGAN,
                                   "in_jenis"=>$JN_REPO,
                                   "in_user"=>$ID_USER,
                                   "in_di"=>$DI,
                                   "in_vessel"=>$NM_KAPAL,
                                   "in_voyin"=>$VOYAGE_IN,
                                   "in_voyout"=>$VOYAGE_OUT,
                                   "in_idvsb"=>$NO_UKK,
                                   "in_nobooking"=>$NO_BOOKING,
                                   "in_callsign"=>$CALL_SIGN,
                                   "in_pod"=>$KD_PELABUHAN_TUJUAN,
                                   "in_pol"=>$KD_PELABUHAN_ASAL,
                                   "in_shift"=>$SHIFT_RFR,
                                   "in_plin"=>$TGL_MULAI,
                                   "in_plout"=>$TGL_NANTI,
                                   "in_nostuf"=>$NO_STUFF,
                                   "in_kdkapal"=>$KD_KAPAL,
                                   "in_etd"=>$ETD,
                                   "in_eta"=>$ETA,
                                   "in_openstack"=>$OPEN_STACK,
                                   "in_nmagen"=>$NM_AGEN,
                                   "in_kdagen"=>$KD_AGEN,
                                   "in_closingtime"=>$CLOSING_TIME,
                                   "in_closingtimedoc"=>$CLOSING_TIME_DOC,
                                   "in_voyage"=>$VOYAGE,
                                   "out_noreq"=>'',
                                   "out_reqnbs"=>'',
                                   "out_msg"=>''                                 
                        );

						// echo var_dump($param);
                    	// die;

                        $query_int ="declare begin pack_create_req_delivery_repo.create_header_repo_praya(:in_accpbm,:in_pbm,:in_peb,:in_npe,:in_noro,:in_keterangan,:in_jenis ,:in_user,:in_di ,:in_vessel,:in_voyin ,:in_voyout,:in_idvsb,:in_nobooking,:in_callsign,:in_pod,:in_pol,:in_shift,:in_plin,:in_plout,:in_nostuf,:in_kdkapal,:in_etd, :in_eta,:in_openstack,:in_nmagen, :in_kdagen,:in_closingtime,:in_closingtimedoc,:in_voyage,:out_noreq,:out_reqnbs,:out_msg); end;";
                        $db->query($query_int,$param);
                        $msg = $param["out_msg"];
                        $no_req = $param["out_noreq"];
                        $no_req2 = $param["out_reqnbs"];
                    														
			//-------------------------------------------------- END INSERT TPK's RECEIVING -------------------------------------------------------//	
				
					
					//==== Batas comment
					
               
						
					//==== Batas comment	
						
						
						
	}
	
    
    if($msg=='OK')
    {
        $no_req = $param["out_noreq"];
        header('Location: '.HOME.APPID.'/edit?no_req='.$no_req.'&no_req2='.$no_req2);		
    }
    else {
        echo $msg;
    }
                
					
        
?>