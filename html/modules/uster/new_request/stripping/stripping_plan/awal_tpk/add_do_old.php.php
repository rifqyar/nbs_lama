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
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$nm_user		= $_SESSION["NAME"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	if($tgl_sppb == NULL){
		$tgl_sppb = '';
	}
	$db = getDB("storage");
	//$db2 = getDB("ora");
	
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM,
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_RECEIVING 
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
	
	$no_req_rec	= "REC".$month.$year.$jum;
	$autobp 	= "UREC".$month.$year.$jum;	
	
	$detpbm = "SELECT pbm.KD_PBM,pbm.NM_PBM,pbm.ALMT_PBM,pbm.NO_NPWP_PBM, pbm.NO_ACCOUNT_PBM FROM V_MST_PBM pbm
				where pbm.KD_CABANG='05' AND NO_ACCOUNT_PBM = '$NO_ACC_PBM'";
	$rpbm	= $db->query($detpbm)->fetchRow();
	// Entry Request Delivery di TPK -----------------------------------------------------------------------------------
	
	// [1] - Insert ke tabel PETIKEMAS_CABANG.TTM_DEL_REQ
	$sql 	= "INSERT INTO req_delivery_h (JENIS_SPPB,
                            COA,
                            EMKL,
                            ALAMAT,
                            NPWP,
                            NO_DO,
                            TIPE_REQ,
                            TGL_SPPB,
                            TGL_SP2,
                            STATUS,
                            TGL_REQUEST,
                            DEV_VIA,
                            NO_SPPB,
                            KETERANGAN,
                            ID_USER,
                            SHIP,
                            VESSEL,
                            VOYAGE_IN,
                            VOYAGE_OUT,
                            --NO_UKK,
                            --DISCH_DATE,
                            --CALL_SIGN,
                            --TGL_DO,
                            --SP_CUSTOM,
                            --TGL_SP_CUSTOM,
                            --BL_NUMB,
                            --CUST_DSTN,
                            --ICUST_DSTN,
                            IS_EDIT)
				     VALUES ('',
				             '".$NO_ACC_PBM."',
				             '".$NM_PENUMPUKAN."',
				             '".$rpbm[ALMT_PBM]."',
				             '".$rpbm[NO_NPWP_PBM]."',
				             '".$NO_DO."',
				             'NEW',
				             to_date('".$TGL_SPPB."','yyyy-mm-dd'),
				             v_ddel,
				             'N',
				             SYSDATE,
				             'USTER',
				             '".$NO_SPPB."',
				             '".$KETERANGAN."',
				             '".$ID_USER."',
				             '".$TYPE_S."',
				             '".$NM_KAPAL."',
				             '".$voyage_in."',
				             '".$voyage_out."',
				             --v_id_ves_scd,
				             --TO_DATE (v_start_work, 'dd-mm-yyyy hh24:MI'),
				             --v_call_sign,
				             --v_ddo,
				             --v_spcust,
				             --v_dspcust,
				             --v_blnumb,
				             --v_cargow,
				             --v_icargow,
				             1);";
	
	if($db2->query($sql))
	{
		//echo "Query ke SIMOP berhasil";
		//------------------------------------------------------------------------------------------------------------------
	
	
		// Entry di request receiving --------------------------------------------------------------------------------------
	
	// [2] - Insert ke tabel REQUEST_RECEIVING 
			
			$query_req 	= "INSERT INTO REQUEST_RECEIVING(NO_REQUEST, 
														 KD_CONSIGNEE, 
														 KD_PENUMPUKAN_OLEH,
														 TGL_REQUEST, 
														 KETERANGAN, 
														 CETAK_KARTU, 
														 ID_USER,
														 ID_YARD,
														 RECEIVING_DARI,
														 NO_DO,
														 NO_BL,
														 NO_SPPB,
														 TGL_SPPB,
														 PERALIHAN,
														 NO_REQ_ICT
														 ) 
												VALUES(	'$no_req_rec', 
														'$ID_CONSIGNEE',
														'$ID_PENUMPUKAN',
														SYSDATE, 
														'$KETERANGAN', 
														0, 
														$ID_USER,
														$id_yard,
														'TPK',
														'$NO_DO',
														'$NO_BL',
														'$NO_SPPB',
														to_date('".$TGL_SPPB."','yyyy-mm-dd'),
														'STRIPPING',
														'$autobp'
														 ) ";
		
		// end entry ---------------------------------------------------------------------------------------------------------------
		if($db->query($query_req))	
		{
			//debug($_POST);
			//die();
			//Cek nilai request existing yang paling besar
			
			// Entry Request Plan Stripping ----------------------------------------------------------------------------------------
			$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
									  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
									  TO_CHAR(SYSDATE, 'YY') AS YEAR 
							   FROM REQUEST_STRIPPING
							   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
							   AND SUBSTR(request_stripping.NO_REQUEST,1,3) = 'STR'";
							   
			
			$result_select 	= $db->query($query_cek);
			
			
			$row_select 	= $result_select->fetchRow();
			
			
			$no_req			= $row_select["JUM"];
			$month			= $row_select["MONTH"];
			$year			= $row_select["YEAR"];
			$no_req_s		= "PTR".$month.$year.$no_req;
			$no_req_f		= "STR".$month.$year.$no_req;
			
			
			// [3] - Insert ke tabel PLAN_REQUEST_STRIPPING 
			
			$query_req 	= "INSERT INTO PLAN_REQUEST_STRIPPING
														(NO_REQUEST, 
														 KD_CONSIGNEE, 
														 KD_PENUMPUKAN_OLEH,
														 KETERANGAN,
														 TGL_REQUEST, 
														 TGL_AWAL, 
														 TGL_AKHIR,
														 ID_USER,
														 TYPE_STRIPPING,
														 NO_DO,
														 NO_BL,
														 APPROVE,
														 NO_SPPB,
														 TGL_SPPB,
														 NO_REQUEST_RECEIVING,
														 CONSIGNEE_PERSONAL,
														 NO_REQUEST_APP_STRIPPING
														 ) 
												VALUES(	'$no_req_s', 
														'$ID_CONSIGNEE', 
														'$ID_PENUMPUKAN', 
														'$KETERANGAN',
														SYSDATE,
														to_date('".$tgl_awal."','yyyy-mm-dd'),
														to_date('".$tgl_akhir."','yyyy-mm-dd'),
														'$ID_USER',
														'$TYPE_S',
														'$NO_DO',
														'$NO_BL',
														'NY',
														'$NO_SPPB',
														to_date('".$tgl_sppb."','yyyy-mm-dd'),
														'$no_req_rec',
														'$CONSIGNEE_PERSONAL',
														'$no_req_f'
														) ";
			

			// [4] - Insert ke tabel REQUEST_STRIPPING
			
			$query_ir = "INSERT INTO REQUEST_STRIPPING(	
														ID_YARD,  
														KETERANGAN, 
														TGL_REQUEST,
														TGL_AWAL, 
														TGL_AKHIR, 
														NO_DO, 
														NO_BL, 
														TYPE_STRIPPING, 
														NO_REQUEST_RECEIVING,
														ID_USER, 
														KD_CONSIGNEE, 
														KD_PENUMPUKAN_OLEH, 
														NO_REQUEST, 
														NO_REQUEST_PLAN, 
														PERP_KE, 
														CONSIGNEE_PERSONAL, 
														NOTA)
												VALUES(
														'$id_yard',
														'$KETERANGAN',
														SYSDATE,
														to_date('".$tgl_awal."','yyyy-mm-dd'),
														to_date('".$tgl_akhir."','yyyy-mm-dd'),
														'$NO_DO',
														'$NO_BL',
														'$TYPE_S',
														'$no_req_rec',
														'$ID_USER',
														'$ID_CONSIGNEE',
														'$ID_PENUMPUKAN',
														'$no_req_f',
														'$no_req_s',
														1,
														'$CONSIGNEE_PERSONAL',
														'T')";
			
			// ----------------------------------------------------------------------------------------------------------------------
			if($db->query($query_req))	
			{
				$db->query($query_ir);
				echo $no_req_s;	
				exit();
				
			}
			else
			{
				echo "not ok";	
				exit();
			}
		}
		else
		{
			echo "query insert receiving gagal";
			exit();
		}
	}	
	else
	{
		echo "query insert delivery gagal";
		exit();
	}
?>		