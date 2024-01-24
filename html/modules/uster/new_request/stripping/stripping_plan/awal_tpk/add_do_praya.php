<?php	

// --TAMBAH REQUEST STRIPPING
// --Model Dokumentasi
// -- Daftar Isi

// [1] - Insert ke tabel PETIKEMAS_CABANG.TTM_DEL_REQ ( header container bongkaran tpk ICT )
// 	     input ke data header request delivery TPK
// [2] - Insert ke tabel REQUEST_RECEIVING 
// [3] - Insert ke tabel PLAN_REQUEST_STRIPPING 
// [4] - Insert ke tabel REQUEST_STRIPPING
	
	$ID_CONSIGNEE	= $_POST["ID_CONSIGNEE"];
	$ID_PENUMPUKAN	= $_POST["ID_PENUMPUKAN"];
	$NM_PENUMPUKAN	= $_POST["PENUMPUKAN"];
	$NO_ACC_PBM		= $_POST["NO_ACC_CONS"];
	$TYPE_S			= $_POST["TYPE_S"];
	$NO_DO			= $_POST["NO_DO"];
	$NO_BL			= $_POST["NO_BL"];
	$tgl_awal		= $_POST["TGL_AWAL"];
	$NO_SPPB		= $_POST["NO_SPPB"];
	$tgl_sppb		= $_POST["TGL_SPPB"];
	$tgl_akhir		= $_POST["TGL_AKHIR"];
	$KETERANGAN		= $_POST["keterangan"];
	$CONSIGNEE_PERSONAL		= $_POST["CONSIGNEE_PERSONAL"];
	$NM_KAPAL		= $_POST["NM_KAPAL"];
	$voyage_in		= $_POST["VOYAGE_IN"];
	$voyage_out		= $_POST["VOYAGE_OUT"];
	$id_vsb    		= $_POST["IDVSB"];
	$callsign    		= $_POST["CALLSIGN"];
	$no_booking    		= $_POST["NO_BOOKING"];
	$vessel_code    		= $_POST["VESSEL_CODE"];
	$tanggal_jam_tiba = $_POST["TANGGAL_JAM_TIBA"];
	$tanggal_jam_berangkat = $_POST["TANGGAL_JAM_BERANGKAT"];
	$operator_name = $_POST["OPERATOR_NAME"];
	$operator_id = $_POST["OPERATOR_ID"];
	$pod = $_POST["POD"];
	$pol = $_POST["POL"];
	$voyage = $_POST["VOYAGE"];
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$nm_user		= $_SESSION["NAME"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];

	if($tgl_sppb == NULL){
		$tgl_sppb = '';
	}
	$db = getDB("storage");
	//$db2 = getDB("ora");
	
	$param = array(
                    "in_accpbm" => $NO_ACC_PBM,
                    "in_pbm"    => $ID_CONSIGNEE,
                    "in_personal" => $CONSIGNEE_PERSONAL,
                    "in_do" => $NO_DO,
                    "in_datesppb" => $tgl_sppb,
                    "in_nosppb" => $NO_SPPB,       
                    "in_keterangan" => $KETERANGAN,
                    "in_user" => $ID_USER,
                    "in_di" => $TYPE_S,
                    "in_vessel" => $NM_KAPAL,
                    "in_voyin" => $voyage_in,
                    "in_voyout" => $voyage_out,
                    "in_idvsb" => $id_vsb,
                    "in_nobooking" => $no_booking,
                    "in_callsign" => $callsign,
                    "in_bl" => $NO_BL,
                    "in_vessel_code" => $vessel_code,
                    "in_tanggal_jam_tiba" => $tanggal_jam_tiba,
                    "in_tanggal_jam_berangkat" => $tanggal_jam_berangkat,
                    "in_operator_name" => $operator_name,
                    "in_operator_id" => $operator_id,
                    "in_pod" => $pod,
                    "in_pol" => $pol,
                    "in_voyage" => $voyage,
                    "out_noreq"=>"",
                    "out_msg" => ""
                );
    $query_ins = "declare 
                begin pack_create_req_stripping.create_header_strip_praya(:in_accpbm,:in_pbm,:in_personal,:in_do,:in_datesppb,:in_nosppb,:in_keterangan,
    :in_user,:in_di,:in_vessel,:in_voyin,:in_voyout,:in_idvsb,:in_nobooking,:in_callsign,:in_bl,:in_vessel_code,:in_tanggal_jam_tiba,:in_tanggal_jam_berangkat,:in_operator_name,:in_operator_id,:in_pod,:in_pol,:in_voyage,:out_noreq,:out_msg); end;";
    //print_r($param); die();
    $db->query($query_ins,$param);
    $msg = $param["out_msg"];
    $no_request = $param["out_noreq"];
    
    if($msg == "S"){
        echo $no_request;
        exit();
    }
    else {
        echo "F";
        exit();
    }
    die();
?>		