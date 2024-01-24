<?php	

	// debug ($_POST);die;		
	//$ID_PEMILIK		= $_POST["ID_PEMILIK"];
	//$ID_PEMILIK		= $_POST["ID_PEMILIK"];ACC_EMKL
	$ACC_EMKL		= $_POST["ACC_EMKL"];
	$ID_EMKL		= $_POST["ID_EMKL"];
	$ACC_PNKN		= $_POST["ACC_PNKN"];
	$ID_PNKN_BY		= $_POST["ID_PNKN_BY"];
	$ID_PENUMPUKAN	= $_POST["ID_PENUMPUKAN"];
	$NM_PENUMPUKAN	= $_POST["PENUMPUKAN"];
	$ALMT_PENUMPUKAN = $_POST["ALMT_PENUMPUKAN"];
	$NPWP_PENUMPUKAN = $_POST["NPWP_PENUMPUKAN"];
	$NO_DOC			= $_POST["NO_DOC"];
	$NO_JPB			= $_POST["NO_JPB"];
	$NO_DO			= $_POST["NO_DO"];
	$NO_BL			= $_POST["NO_BL"];
	$NO_SPPB		= $_POST["NO_SPPB"];
	$TGL_SPPB		= $_POST["TGL_SPPB"];
	$BPRP			= $_POST["BPRP"];
	$KETERANGAN		= $_POST["keterangan"];
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	 $TGL_BERANGKAT 		= $_POST["TGL_BERANGKAT"];
     $TGL_REQ				= $_POST["TGL_REQ"];
     $PEB        			= $_POST["NO_PEB"];
	 $NPE     				= $_POST["NO_NPE"];
	 $KD_PELABUHAN_ASAL     = $_POST["KD_PELABUHAN_ASAL"];
	 $KD_PELABUHAN_TUJUAN  	= $_POST["KD_PELABUHAN_TUJUAN"];
	 $NM_KAPAL   			= $_POST["NM_KAPAL"];
	 $VOYAGE_IN 			= $_POST["VOYAGE_IN"];
	 $VOYAGE_OUT 			= $_POST["VOYAGE_OUT"];
     $NO_BOOKING			= $_POST["NO_BOOKING"];
     $CALL_SIGN			   	= $_POST["CALL_SIGN"];
	 $NM_USER				= $_SESSION["NAME"];
     $ID_YARD				= $_SESSION["IDYARD_STORAGE"];
	 $NM_USER				= $_SESSION["NAME"];
	 $NO_UKK				= $_POST["NO_UKK"];
	 $SHIFT_RFR				= $_POST["SHIFT_RFR"];
	 $TGL_MUAT				= $_POST["TGL_MUAT"];
	 $TGL_STACKING			= $_POST["TGL_STACKING"];
	 $DI			 		= $_POST["DI"];
	 $OPEN_STACK 			= $_POST["OPEN_STACK"];
	 $NM_AGEN 				= $_POST["NM_AGEN"];
	 $KD_AGEN 				= $_POST["KD_AGEN"];
	 $CONT_LIMIT 			= $_POST["CONT_LIMIT"];
	 $KD_KAPAL 				= $_POST["KD_KAPAL"];
	 $ETD 					= $_POST["ETD"];
	 $ETA 					= $_POST["ETA"];
	 $VOYAGE 				= $_POST["VOYAGE"];
	 $YARD_STACK			= $_POST["YARD_STACK"];
	
	if($TGL_SPPB == NULL){
		$TGL_SPPB = '';
	}
	
	$db = getDB("storage");
	
										
    $param_req = array("in_accpbm"  =>$ACC_EMKL,
                        "in_pbm"     =>$ID_EMKL,
                        "in_accpbm_pnkn" =>$ACC_PNKN,
                        "in_pbm_pnkn" =>$ID_PNKN_BY,
                        "in_do" =>$NO_DO,
                        "in_doc" =>$NO_DOC,
                        "in_datesppb" =>$TGL_SPPB,
                        "in_nosppb" =>$NO_SPPB,       
                        "in_keterangan" =>$KETERANGAN,
                        "in_peb" =>$PEB,
                        "in_npe" =>$NPE,
                        "in_bl" =>$NO_BL,
                        "in_jpb" =>$NO_JPB,
                        "in_bprp" =>$BPRP,
                        "in_user" =>$ID_USER,
                        "in_di" =>$DI,
                        "in_vessel" =>$NM_KAPAL,
                        "in_voyin" =>$VOYAGE_IN,
                        "in_voyout" =>$VOYAGE_OUT,
                        "in_idvsb" =>$NO_UKK,
                        "in_nobooking" =>$NO_BOOKING,
                        "in_callsign" =>$CALL_SIGN,
                        "in_kdkapal"=>$KD_KAPAL,
	                    "in_etd"=>$ETD,
	                    "in_eta"=>$ETA,
	                    "in_openstack"=>$OPEN_STACK,
	                    "in_nmagen"=>$NM_AGEN,
	                    "in_kdagen"=>$KD_AGEN,
	                    "in_pod"=>$KD_PELABUHAN_TUJUAN,
                        "in_pol"=>$KD_PELABUHAN_ASAL,
                        "in_voyage"=>$VOYAGE,
                        "in_yardstack"=>$YARD_STACK,
                        "out_noreq" =>"",
                        "out_msg"    =>"");

    // echo var_dump($param_req);
    // die;
																	
    $query_req_s = "declare begin pack_create_req_stuffing.create_header_stuf_praya(:in_accpbm,:in_pbm ,:in_accpbm_pnkn,:in_pbm_pnkn,:in_do,:in_doc,:in_datesppb,:in_nosppb,:in_keterangan ,:in_peb ,:in_npe ,:in_bl ,:in_jpb ,:in_bprp ,:in_user ,:in_di ,:in_vessel ,:in_voyin ,:in_voyout ,:in_idvsb ,:in_nobooking ,:in_callsign,:in_kdkapal,:in_etd,:in_eta,:in_openstack,:in_nmagen,:in_kdagen,:in_pod,:in_pol,:in_voyage,:in_yardstack,:out_noreq ,:out_msg); end;";
    $db->query($query_req_s,$param_req);
    $no_req_s = $param_req["out_noreq"];
    $msg      = $param_req["out_msg"];

    if($msg == 'OK')
    {
        header('Location: '.HOME.APPID.'/view?no_req='.$no_req_s.'&no_req2='.$autobp_del);
    }
    else
    {
        echo "Insert USTER Req Header Stuffing gagal";exit;
    }


					
					
			
			
?>		