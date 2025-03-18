<?php
//$tl 	=  xliteTemplate('cont_list.htm');

// --TAMBAH CONTAINER REQUEST STRIPPING
// --Model Dokumentasi
// -- Daftar Isi
// -- Ada 2 kondisi awal
// [1] - Insert ke tabel PLAN_CONTAINER_STRIPPING
// [2] - Insert ke tabel MASTER_CONTAINER 
// [3] - Insert ke tabel HISTORY_CONTAINER
// [4] - Update SUB_COUNTER ke tabel HISTORY_CONTAINER
			
			$db 			= getDB("storage");
			$nm_user		= $_SESSION["NAME"];
			$id_user		= $_SESSION["LOGGED_STORAGE"];
			$id_yard		= $_SESSION["IDYARD_STORAGE"];
			$tgl_approve 	= $_POST["tgl_approve"];
			$tgl_app_selesai = $_POST["tgl_app_selesai"];
			$no_req_rec		= $_POST["NO_REQ_REC"]; 
			$no_cont 		= $_POST["no_cont"];
			$no_req 		= $_POST["no_req"];
			$no_req2		= $_POST["NO_REQ2"]; 
			$no_do			= $_POST["NO_DO"];
			$no_bl			= $_POST["NO_BL"];
			$sp2			= $_POST["SP2"];
			$remark			= $_POST["REMARK"];
			$asal_contstrip	= $_POST["ASAL_CONT"];
			$kd_consignee	= $_POST["KD_CONSIGNEE"];
			$container_size	= $_POST["CONTAINER_SIZE"];
			$container_type	= $_POST["CONTAINER_TYPE"];
			$container_status	= $_POST["CONTAINER_STATUS"];
			$container_hz	= $_POST["CONTAINER_HZ"];
			$container_imo	= $_POST["CONTAINER_IMO"];
			$container_iso_code	= $_POST["CONTAINER_ISO_CODE"];
			$container_height	= $_POST["CONTAINER_HEIGHT"];
			$container_carrier	= $_POST["CONTAINER_CARRIER"];
			$container_reefer_temp	= $_POST["CONTAINER_REEFER_TEMP"];
			$container_booking_sl	= $_POST["CONTAINER_BOOKING_SL"];
			$container_over_width	= $_POST["CONTAINER_OVER_WIDTH"];
			$container_over_length	= $_POST["CONTAINER_OVER_LENGTH"];
			$container_over_height	= $_POST["CONTAINER_OVER_HEIGHT"];
			$container_over_front	= $_POST["CONTAINER_OVER_FRONT"];
			$container_over_rear	= $_POST["CONTAINER_OVER_REAR"];
			$container_over_left	= $_POST["CONTAINER_OVER_LEFT"];
			$container_over_right	= $_POST["CONTAINER_OVER_RIGHT"];
			$container_un_number	= $_POST["CONTAINER_UN_NUMBER"];
			$container_pod	= $_POST["CONTAINER_POD"];
			$container_pol	= $_POST["CONTAINER_POL"];
			$container_vessel_confirm	= $_POST["CONTAINER_VESSEL_CONFIRM"];
			$container_comodity_type_code	= $_POST["CONTAINER_COMODITY_TYPE_CODE"];

			$q_cek_double = "select count(no_container) jum from container_stripping where no_request = REPLACE('$no_req','P','S') and no_container = '$no_cont' ";
			$rcek_d = $db->query($q_cek_double);
			$rcekd = $rcek_d->fetchRow();
			if($rcekd["JUM"] > 0){
				$q_update_p = "UPDATE PLAN_CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd'), TGL_APP_SELESAI = TO_DATE('$tgl_app_selesai','yy-mm-dd') WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
				$db->query($q_update_p);
				$q_update_r = "UPDATE CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd'), TGL_APP_SELESAI = TO_DATE('$tgl_app_selesai','yy-mm-dd') WHERE NO_REQUEST = REPLACE('$no_req','P','S') AND NO_CONTAINER = '$no_cont'";
				$db->query($q_update_r);
				
				echo 'EXIST';
				exit();
			}
			$query_cx 		= "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.HZ,
											   PLAN_CONTAINER_STRIPPING.COMMODITY, 
											   PLAN_CONTAINER_STRIPPING.UKURAN, 
											   PLAN_CONTAINER_STRIPPING.TYPE, 
											   PLAN_CONTAINER_STRIPPING.NO_BOOKING, 
											   PLAN_CONTAINER_STRIPPING.ID_YARD
								   FROM PLAN_CONTAINER_STRIPPING 
								   WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req' 
								   AND PLAN_CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
			$result_cx 		= $db->query($query_cx);
			$row_cx			= $result_cx->fetchRow();
			$hz 			= $row_cx['HZ'];
			$komoditi 		= $row_cx['COMMODITY'];
			$size 			= $row_cx['UKURAN'];
			$type 			= $row_cx['TYPE'];
			$no_booking_ 	= $row_cx['NO_BOOKING'];
			$depo_tujuan 	= $row_cx['ID_YARD'];
			
if($asal_contstrip == "TPK"){
			
			
	//---------------------------------------------------Interface Ke OPUS&NBS----------------------------------------------------------------------------------------------
			$opus_param = array("in_nocont" => $no_cont,
                               "in_planreq" => $no_req,
                               "in_reqnbs" => $no_req2,
                               "in_asalcont" => $asal_contstrip,
                               "in_container_size" => $container_size != 'null' ? $container_size: '',
                               "in_container_type" => $container_type != 'null' ? $container_type: '',
                               "in_container_status" => $container_status != 'null' ? $container_status: '',
                               "in_container_hz" => $container_hz != 'null' ? $container_hz: '',
                               "in_container_imo" => $container_imo != 'null' ? $container_imo: '',
                               "in_container_iso_code" => $container_iso_code != 'null' ? $container_iso_code: '',
                               "in_container_height" => $container_height != 'null' ? $container_height: '',
                               "in_container_carrier" => $container_carrier != 'null' ? $container_carrier: '',
                               "in_container_reefer_temp" => $container_reefer_temp != 'null' ? $container_reefer_temp: '',
                               "in_container_booking_sl" => $container_booking_sl != 'null' ? $container_booking_sl: '',
                               "in_container_over_width" => $container_over_width != 'null' ? $container_over_width: '',
                               "in_container_over_length" => $container_over_length != 'null' ? $container_over_length: '',
                               "in_container_over_height" => $container_over_height != 'null' ? $container_over_height: '',
                               "in_container_over_front" => $container_over_front != 'null' ? $container_over_front: '',
                               "in_container_over_rear" => $container_over_rear != 'null' ? $container_over_rear: '',
                               "in_container_over_left" => $container_over_left != 'null' ? $container_over_left: '',
                               "in_container_over_right" => $container_over_right != 'null' ? $container_over_right: '',
                               "in_container_un_number" => $container_un_number != 'null' ? $container_un_number: '',
                               "in_container_pod" => $container_pod != 'null' ? $container_pod: '',
                               "in_container_pol" => $container_pol != 'null' ? $container_pol: '',
                               "in_container_vessel_confirm" => $container_vessel_confirm != 'null' ? $container_vessel_confirm: '',
                               "in_container_comodity" => $komoditi != 'null' ? trim($komoditi): '',
                               "in_container_c_type_code" => $container_comodity_type_code != 'null' ? $container_comodity_type_code: '',
                               "p_ErrMsg" => '');
            $opus_q     = "declare begin pack_create_req_stripping.create_approve_strip_praya(:in_nocont,:in_planreq,:in_reqnbs,:in_asalcont,:in_container_size,:in_container_type,:in_container_status,:in_container_hz,:in_container_imo,:in_container_iso_code,:in_container_height,:in_container_carrier,:in_container_reefer_temp,:in_container_booking_sl,:in_container_over_width,:in_container_over_length,:in_container_over_height,:in_container_over_front,:in_container_over_rear,:in_container_over_left,:in_container_over_right,:in_container_un_number,:in_container_pod,:in_container_pol,:in_container_vessel_confirm,:in_container_comodity,:in_container_c_type_code,:p_ErrMsg); end;";
            //print_r($opus_param);
            
            if($db->query($opus_q,$opus_param)){
                $msg = $opus_param["p_ErrMsg"];
            }
            
            if($msg != 'OK'){
                echo $msg;
                exit();
            }
            else {
			
            
//--------------------------------------------------- End of Interface Ke OPUS&NBS----------------------------------------------------------------------------------------------
        //Insert Uster

        $query_insert_rec	= "INSERT INTO CONTAINER_RECEIVING(NO_CONTAINER, 
                                               NO_REQUEST,
                                               STATUS,
                                               AKTIF,
                                               HZ,
                                               TGL_BONGKAR,
                                               KOMODITI,
                                               DEPO_TUJUAN) 
                                        VALUES('$no_cont', 
                                               '$no_req_rec',
                                               'FCL',
                                               'Y',
                                               '$berbahaya',
                                                TO_DATE('$tgl_bongkar','dd-mm-yy'),
                                               '$komoditi',
                                               '$depo_tujuan')";

        $db->query($query_insert_rec);

        $q_getcounter = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
                            $r_getcounter = $db->query($q_getcounter);
                            $rw_getcounter = $r_getcounter->fetchRow();
                            $cur_counter = $rw_getcounter["COUNTER"];
                            $cur_booking = $rw_getcounter["NO_BOOKING"];	


        $history_rec    = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER, STATUS_CONT)  
                                                                  VALUES ('$no_cont','$no_req_rec','REQUEST RECEIVING',SYSDATE,'$id_user','$id_yard','$cur_booking', '$cur_counter','FCL')";	

        $db->query($history_rec);

        $query = "UPDATE PLAN_CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd'), TGL_APP_SELESAI = TO_DATE('$tgl_app_selesai','yy-mm-dd'), REMARK = '$remark'
                 WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";

         $query_r = "SELECT NO_REQUEST,
                           --ID_YARD,
                           KETERANGAN,
                          -- NO_BOOKING,
                           TGL_REQUEST,
                           TGL_AWAL,
                           TGL_AKHIR,
                           NO_DO,
                           NO_BL,
                           TYPE_STRIPPING,
                           STRIPPING_DARI,
                           NO_REQUEST_RECEIVING,
                           ID_USER,
                           KD_CONSIGNEE,
                           KD_PENUMPUKAN_OLEH,
                           NO_SPPB,
                           TGL_SPPB,
                           AFTER_STRIP,
                           CONSIGNEE_PERSONAL
                      FROM PLAN_REQUEST_STRIPPING
                      WHERE NO_REQUEST = '$no_req'";
        $result_r = $db->query($query_r);
        $row_r	= $result_r->fetchRow();
        //print_r($row_r);die;
        $no_request_a 		= $row_r['NO_REQUEST'];
        //$id_yard 			= $row_r['ID_YARD'];
        $keterangan 		= $row_r['KETERANGAN'];
        //$no_book 			= $row_r['NO_BOOKING'];
        $tgl_req 			= $row_r['TGL_REQUEST'];
        $tgl_awal 			= $row_r['TGL_AWAL'];
        $tgl_akhir 			= $row_r['TGL_AKHIR'];
        $nodo 				= $row_r['NO_DO'];
        $nobl 				= $row_r['NO_BL'];
        $types 				= $row_r['TYPE_STRIPPING'];
        $strip_d 			= $row_r['STRIPPING_DARI'];
        $rec 				= $row_r['NO_REQUEST_RECEIVING'];
        $id_user 			= $row_r['ID_USER'];
        $consig 			= $row_r['KD_CONSIGNEE'];
        $tumpuk 			= $row_r['KD_PENUMPUKAN_OLEH'];
        //$kapal 			= $row_r['NM_KAPAL'];
        $nosppb 			= $row_r['NO_SPPB'];
        $tglsppb 			= $row_r['TGL_SPPB'];
        $after_s 			= $row_r['AFTER_STRIP'];
        $CONSIGNEE_PERSONAL = $row_r['CONSIGNEE_PERSONAL'];


        $query_c = "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.AFTER_STRIP, 
                                PLAN_CONTAINER_STRIPPING.ID_YARD,
                                PLAN_CONTAINER_STRIPPING.HZ,
                                PLAN_CONTAINER_STRIPPING.NO_REQUEST,
                                PLAN_CONTAINER_STRIPPING.NO_CONTAINER,
                                PLAN_CONTAINER_STRIPPING.AKTIF,
                                --PLAN_CONTAINER_STRIPPING.KETERANGAN,
                                PLAN_CONTAINER_STRIPPING.TGL_APPROVE,
                                PLAN_CONTAINER_STRIPPING.TGL_BONGKAR,
                                PLAN_CONTAINER_STRIPPING.TGL_SELESAI,
                                PLAN_CONTAINER_STRIPPING.VIA,
                                PLAN_CONTAINER_STRIPPING.VOYAGE,
                                PLAN_CONTAINER_STRIPPING.REMARK,
                                PLAN_CONTAINER_STRIPPING.UKURAN, 
                                PLAN_CONTAINER_STRIPPING.TYPE,
                                PLAN_CONTAINER_STRIPPING.COMMODITY 
                               FROM PLAN_CONTAINER_STRIPPING 
                               WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req' AND PLAN_CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
                $result_c = $db->query($query_c);
                $row_c	= $result_c->getAll();

        $query_cek_request = "SELECT NO_REQUEST FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
        $result_cek_request = $db->query($query_cek_request);
        $row_cek_request	= $result_cek_request->getAll();

        $query_cek_cont = "SELECT NO_CONTAINER FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
        $result_cek_cont = $db->query($query_cek_cont);
        $row_cek_cont	= $result_cek_cont->getAll();
        $no_req_strip = str_replace( 'P', 'S', $no_req);
        $query_tgl_app = "UPDATE CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd'), TGL_APP_SELESAI = TO_DATE('$tgl_app_selesai','yy-mm-dd'), REMARK = '$remark'
                WHERE NO_REQUEST = '$no_req_strip' AND NO_CONTAINER = '$no_cont'";

        if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){
                $db->query($query);

                $db->query($query_tgl_app);

                $db->query("UPDATE REQUEST_STRIPPING SET STRIPPING_DARI = '$asal_contstrip' WHERE NO_REQUEST = '$no_req_strip'");


        }
        else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
            $db->query($query);
            $no_req_strip = str_replace( 'P', 'S', $no_req);
            foreach($row_c as $rc){
                $after_strip = $rc['AFTER_STRIP'];
                $idyard_c = $rc['ID_YARD'];
                $hz = $rc['HZ'];
                $req = $rc['NO_REQUEST'];
                $cont = $rc['NO_CONTAINER'];

                //CEK TGL GATE
                $tes 			= "select TO_CHAR(TGL_UPDATE,'dd/mm/rrrr') TGL_GATE from history_container where no_container = '$no_cont' AND KEGIATAN = 'BORDER GATE IN' AND TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM history_container WHERE NO_CONTAINER = '$no_cont')";
                $result_tes 	= $db->query($tes);
                $gate			= $result_tes->fetchRow();
                $tgl_gate 		= $gate['TGL_GATE'];

                $aktif 			= $rc['AKTIF'];
                $keterangan 	= $rc['KETERANGAN'];
                $tgl_app 		= $rc['TGL_APPROVE'];
                $tgl_bongkar 	= $rc['TGL_BONGKAR'];
                $tgl_selesai 	= $rc['TGL_SELESAI'];
                $via 			= $rc['VIA'];
                $commo 			= $rc['COMMODITY'];
                $voyage 		= $rc['VOYAGE'];
                $remark 		= $rc['REMARK'];
                $query_ic		= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
                                    VIA, VOYAGE, TGL_BONGKAR, COMMODITY, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE, TGL_APP_SELESAI, REMARK, TGL_GATE, TGL_SELESAI)
                                    VALUES('$cont',
                                    '$no_req_strip',
                                    '$aktif',
                                    '$asal_contstrip',
                                    '$voyage',
                                    '$tgl_bongkar',
                                    '$commo',
                                    '$hz',
                                    '$idyard_c',
                                    '$after_strip',
                                    TO_DATE('$tgl_approve','yy-mm-dd'),
                                    TO_DATE('$tgl_app_selesai','yy-mm-dd'),
                                    '$remark',
                                    TO_DATE('$tgl_gate','dd/mm/rrrr'),
                                    '$tgl_selesai')";

                $db->query($query_ic);
            } 


        }
        else{
            $db->query($query);
            $no_req_strip = str_replace( 'P', 'S', $no_req);
            $query_ir = "INSERT INTO REQUEST_STRIPPING(ID_YARD,  KETERANGAN, TGL_REQUEST,
                        TGL_AWAL, TGL_AKHIR, NO_DO, NO_BL, TYPE_STRIPPING, STRIPPING_DARI, NO_REQUEST_RECEIVING,
                        ID_USER, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, NO_REQUEST, NO_REQUEST_PLAN,PERP_KE, CONSIGNEE_PERSONAL, NOTA)
                            VALUES(
                            '$id_yard',
                            '$keterangan',
                            '$tgl_req',
                            '$tgl_awal',
                            '$tgl_akhir',
                            '$nodo',
                            '$nobl',
                            '$types',
                            '$strip_d',
                            '$rec',
                            '$id_user',
                            '$consig',
                            '$tumpuk','$no_req_strip','$no_req',1,'$CONSIGNEE_PERSONAL','T')";
            //echo $query_ir;
            /*if($db->query($query_ir)){

            }*/

            $no_req_strip = str_replace( 'P', 'S', $no_req);
            foreach($row_c as $rc){
                $after_strip 	= $rc['AFTER_STRIP'];
                $idyard_c 		= $rc['ID_YARD'];
                $hz 			= $rc['HZ'];
                $req 			= $rc['NO_REQUEST'];
                $cont 			= $rc['NO_CONTAINER'];
                $aktif 			= $rc['AKTIF'];
                $keterangan 	= $rc['KETERANGAN'];
                $tgl_app 		= $rc['TGL_APPROVE'];
                $tgl_bongkar 	= $rc['TGL_BONGKAR'];
                $tgl_selesai 	= $rc['TGL_SELESAI'];
                $via 			= $rc['VIA'];
                $voyage 		= $rc['VOYAGE'];
                $remark 		= $rc['REMARK'];
                $commo 			= $rc['COMMODITY'];
                $query_ic		= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
                                        VIA, VOYAGE, TGL_BONGKAR,COMMODITY, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE, TGL_APP_SELESAI, REMARK, TGL_SELESAI)
                                        VALUES('$cont',
                                        '$no_req_strip',
                                        '$aktif',
                                        '$asal_contstrip',
                                        '$voyage',
                                        '$tgl_bongkar',
                                        '$commo',
                                        '$hz',
                                        '$idyard_c',
                                        '$after_strip',
                                        TO_DATE('$tgl_approve','yy-mm-dd'),
                                        TO_DATE('$tgl_app_selesai','yy-mm-dd'),
                                        '$remark',
                                        '$tgl_selesai')";
                $db->query($query_ic);
            }  
        }



        $history_stripp        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER, STATUS_CONT) 
                                                              VALUES ('$no_cont','$no_req_strip','REQUEST STRIPPING',SYSDATE,'$id_user','$id_yard','$cur_booking','$cur_counter','FCL')";
        $db->query($history_stripp);
         echo $msg;
        exit();
        }
    }
    else if($asal_contstrip == "DEPO"){

        $query = "UPDATE PLAN_CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd'), TGL_APP_SELESAI = TO_DATE('$tgl_app_selesai','yy-mm-dd'), REMARK = '$remark'
                 WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";

        $query_r = "SELECT NO_REQUEST,
                           --ID_YARD,
                           KETERANGAN,
                          -- NO_BOOKING,
                           TGL_REQUEST,
                           TGL_AWAL,
                           TGL_AKHIR,
                           NO_DO,
                           NO_BL,
                           TYPE_STRIPPING,
                           STRIPPING_DARI,
                           NO_REQUEST_RECEIVING,
                           ID_USER,
                           KD_CONSIGNEE,
                           KD_PENUMPUKAN_OLEH,
                           NO_SPPB,
                           TGL_SPPB,
                           AFTER_STRIP,
                           CONSIGNEE_PERSONAL
                      FROM PLAN_REQUEST_STRIPPING
                      WHERE NO_REQUEST = '$no_req'";
        $result_r = $db->query($query_r);
        $row_r	= $result_r->fetchRow();
        //print_r($row_r);die;
        $no_request_a 		= $row_r['NO_REQUEST'];
        //$id_yard 			= $row_r['ID_YARD'];
        $keterangan 		= $row_r['KETERANGAN'];
        //$no_book 			= $row_r['NO_BOOKING'];
        $tgl_req 			= $row_r['TGL_REQUEST'];
        $tgl_awal 			= $row_r['TGL_AWAL'];
        $tgl_akhir 			= $row_r['TGL_AKHIR'];
        $nodo 				= $row_r['NO_DO'];
        $nobl 				= $row_r['NO_BL'];
        $types 				= $row_r['TYPE_STRIPPING'];
        $strip_d 			= $row_r['STRIPPING_DARI'];
        $rec 				= $row_r['NO_REQUEST_RECEIVING'];
        $id_user 			= $row_r['ID_USER'];
        $consig 			= $row_r['KD_CONSIGNEE'];
        $tumpuk 			= $row_r['KD_PENUMPUKAN_OLEH'];
        //$kapal 			= $row_r['NM_KAPAL'];
        $nosppb 			= $row_r['NO_SPPB'];
        $tglsppb 			= $row_r['TGL_SPPB'];
        $after_s 			= $row_r['AFTER_STRIP'];
        $CONSIGNEE_PERSONAL = $row_r['CONSIGNEE_PERSONAL'];

        $query_c = "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.AFTER_STRIP, 
                                PLAN_CONTAINER_STRIPPING.ID_YARD,
                                PLAN_CONTAINER_STRIPPING.HZ,
                                PLAN_CONTAINER_STRIPPING.NO_REQUEST,
                                PLAN_CONTAINER_STRIPPING.NO_CONTAINER,
                                PLAN_CONTAINER_STRIPPING.AKTIF,
                                --PLAN_CONTAINER_STRIPPING.KETERANGAN,
                                PLAN_CONTAINER_STRIPPING.UKURAN KD_SIZE,
                                PLAN_CONTAINER_STRIPPING.TYPE KD_TYPE,
                                PLAN_CONTAINER_STRIPPING.TGL_APPROVE,
                                PLAN_CONTAINER_STRIPPING.TGL_BONGKAR,
                                PLAN_CONTAINER_STRIPPING.TGL_SELESAI,
                                PLAN_CONTAINER_STRIPPING.VIA,
                                PLAN_CONTAINER_STRIPPING.VOYAGE,
                                PLAN_CONTAINER_STRIPPING.REMARK,
                                PLAN_CONTAINER_STRIPPING.COMMODITY
                               FROM PLAN_CONTAINER_STRIPPING 
                               WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req' 
                               AND PLAN_CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
                $result_c = $db->query($query_c);
                $row_c	= $result_c->getAll();
        $no_req_strip = str_replace( 'P', 'S', $no_req);
        $query_cek_request = "SELECT NO_REQUEST FROM REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req_strip'";
        $result_cek_request = $db->query($query_cek_request);
        $row_cek_request	= $result_cek_request->getAll();

        $query_cek_cont = "SELECT NO_CONTAINER FROM CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req_strip' AND NO_CONTAINER = '$no_cont'";
        $result_cek_cont = $db->query($query_cek_cont);
        $row_cek_cont	= $result_cek_cont->getAll();

        $query_tgl_app = "UPDATE CONTAINER_STRIPPING SET TGL_APPROVE = TO_DATE('$tgl_approve','yy-mm-dd'), TGL_APP_SELESAI = TO_DATE('$tgl_app_selesai','yy-mm-dd'), REMARK = '$remark'
                WHERE NO_REQUEST = '$no_req_strip' AND NO_CONTAINER = '$no_cont'";

        if(count($row_cek_request) > 0 && count($row_cek_cont) > 0){
                $db->query($query);

                $db->query($query_tgl_app);

                $db->query("UPDATE REQUEST_STRIPPING SET STRIPPING_DARI = '$asal_contstrip' WHERE NO_REQUEST = '$no_req_strip'");
        }
        else if(count($row_cek_request) > 0 && count($row_cek_cont) == 0){
            $db->query($query);
            //print_r($row_c);die;
            foreach($row_c as $rc){
                $after_strip 	= $rc['AFTER_STRIP'];
                $idyard_c 		= $rc['ID_YARD'];
                $hz 			= $rc['HZ'];
                $req 			= $rc['NO_REQUEST'];
                $cont 			= $rc['NO_CONTAINER'];
                $aktif 			= $rc['AKTIF'];
                $keterangan 	= $rc['KETERANGAN'];
                $tgl_app 		= $rc['TGL_APPROVE'];
                $tgl_bongkar 	= $rc['TGL_BONGKAR'];
                $tgl_selesai 	= $rc['TGL_SELESAI'];
                $via 			= $rc['VIA'];
                $voyage 		= $rc['VOYAGE'];
                $remark 		= $rc['REMARK'];
                $commo 			= $rc['COMMODITY'];
                $query_ic		= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
                                        VIA, VOYAGE, TGL_BONGKAR, COMMODITY, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE, TGL_APP_SELESAI, REMARK,TGL_SELESAI)
                                        VALUES('$cont',
                                        '$no_req_strip',
                                        '$aktif',
                                        '$asal_contstrip',
                                        '$voyage',
                                        '$tgl_bongkar',
                                        '$commo',
                                        '$hz',
                                        '$idyard_c',
                                        '$after_strip',
                                        TO_DATE('$tgl_approve','yy-mm-dd'),
                                        TO_DATE('$tgl_app_selesai','yy-mm-dd'),
                                        '$remark',
                                        '$tgl_selesai')";
                //debug($query_ic);die;
                $db->query($query_ic);

            } 


        }
        else{
            $db->query($query);
            $no_req_strip = str_replace( 'P', 'S', $no_req);
            $query_ir = "INSERT INTO REQUEST_STRIPPING(ID_YARD,  KETERANGAN, TGL_REQUEST,
                        TGL_AWAL, TGL_AKHIR, NO_DO, NO_BL, TYPE_STRIPPING, STRIPPING_DARI, NO_REQUEST_RECEIVING,
                        ID_USER, KD_CONSIGNEE, KD_PENUMPUKAN_OLEH, NO_REQUEST, NO_REQUEST_PLAN,CONSIGNEE_PERSONAL, NOTA)
                            VALUES(
                            '$id_yard',
                            '$keterangan',
                            '$tgl_req',
                            '$tgl_awal',
                            '$tgl_akhir',
                            '$nodo',
                            '$nobl',
                            '$types',
                            '$strip_d',
                            '$rec',
                            '$id_user',
                            '$consig',
                            '$tumpuk','$no_req_strip', '$no_req','$CONSIGNEE_PERSONAL','T')";
            /*if($db->query($query_ir)){

            }*/


            foreach($row_c as $rc){
                $after_strip 	= $rc['AFTER_STRIP'];
                $idyard_c 		= $rc['ID_YARD'];
                $hz 			= $rc['HZ'];
                $req 			= $rc['NO_REQUEST'];
                $cont 			= $rc['NO_CONTAINER'];
                $aktif 			= $rc['AKTIF'];
                $keterangan 	= $rc['KETERANGAN'];
                $tgl_app 		= $rc['TGL_APPROVE'];
                $tgl_bongkar 	= $rc['TGL_BONGKAR'];
                $tgl_selesai 	= $rc['TGL_SELESAI'];
                $via 			= $rc['VIA'];
                $voyage 		= $rc['VOYAGE'];
                $remark 		= $rc['REMARK'];
                $commo 			= $rc['COMMODITY'];
                $query_ic		= "INSERT INTO CONTAINER_STRIPPING (NO_CONTAINER,NO_REQUEST, AKTIF,
                                        VIA, VOYAGE, TGL_BONGKAR, COMMODITY, HZ, ID_YARD, AFTER_STRIP, TGL_APPROVE, TGL_APP_SELESAI, REMARK, TGL_GATE, TGL_SELESAI)
                                        VALUES('$cont',
                                        '$no_req_strip',
                                        '$aktif',
                                        '$asal_contstrip',
                                        '$voyage',
                                        '$tgl_bongkar',
                                        '$commo',
                                        '$hz',
                                        '$idyard_c',
                                        '$after_strip',
                                        TO_DATE('$tgl_approve','yy-mm-dd'),
                                        TO_DATE('$tgl_app_selesai','yy-mm-dd'),
                                        '$remark',
                                        TO_DATE('$tgl_gate','yy-mm-dd'),
                                        '$tgl_selesai')";

                $db->query($query_ic);
            }  
        }

        $q_getcounter2 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
        $r_getcounter2 = $db->query($q_getcounter2);
        $rw_getcounter2 = $r_getcounter2->fetchRow();
        $cur_counter2 = $rw_getcounter2["COUNTER"];
        $cur_booking2 = $rw_getcounter2["NO_BOOKING"];

        $history_stripp2  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, NO_BOOKING, COUNTER, STATUS_CONT) 
                                                              VALUES ('$no_cont','$no_req_strip','REQUEST STRIPPING',SYSDATE,'$id_user','$id_yard','$cur_booking2','$cur_counter2','FCL')";
        $db->query($history_stripp2);
        echo "OK"; exit();
    }

?>