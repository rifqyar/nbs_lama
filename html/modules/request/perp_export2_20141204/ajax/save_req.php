<?php	$vessel	= $_POST['vessel'];	$voyin	= $_POST['voyin'];	$voyout = $_POST['voyout'];	$kd_pel = $_POST['kd_pelanggan'];	$user   = $_SESSION['PENGGUNA_ID'];	/*$param_b_var= array(							"vessel"=>"$vessel",						"voyin"=>"$voyin",						"voyout"=>"$voyout",						"user"=>"$user",						"v_msg"=>""						);*/	$db=getDb();		//echo "begin REQ_STACKEXT_OPUS('$vessel','$voyin','$voyout','$user','$kd_pel');end;";die;		$query_cek  = "SELECT COUNT(1) JML FROM REQ_STACKEXT_H WHERE VESSEL = '$vessel' AND VOYAGE_IN = '$voyin' AND VOYAGE_OUT = '$voyout'";	$result_cek = $db->query($query_cek);	$hasil_cek  = $result_cek->fetchRow();		$jml 		= $hasil_cek['JML'];		if($jml > 0){		echo "EXIST";	} else {	//echo "begin REQ_STACKEXT_OPUS('$vessel','$voyin','$voyout','$user','$kd_pel');end;";		$query="begin REQ_STACKEXT_OPUS('$vessel','$voyin','$voyout','$user','$kd_pel');end;";		$db->query($query);				/*		$query_atd = " SELECT CASE						 WHEN atd IS NOT NULL						 THEN							   SUBSTR (atd, 7, 2)							|| '/'							|| SUBSTR (atd, 5, 2)							|| '/'							|| SUBSTR (atd, 1, 4)						 ELSE							''					  END						 atd,					  vessel_code				 FROM M_VSB_VOYAGE@dbint_link				WHERE VESSEL = '$vessel' AND VOYAGE_IN = '$voyin' AND VOYAGE_OUT = '$voyout'";		$result_atd = $db->query($query_atd);		$hasil_atd  = $result_atd->fetchRow();		$atd 		= $hasil_atd['ATD'];				$query_max = " SELECT MAX (ID_REQ) ID_REQ FROM REQ_STACKEXT_H";		$result_max = $db->query($query_max);		$hasil_max  = $result_max->fetchRow();		$max 		= $hasil_max['ID_REQ'];				$db->query("INSERT INTO REQ_STACKEXT_D (NO_CONTAINER,                                  SIZE_,                                  TYPE_,                                  STATUS_,                                  HZ,                                  HEIGHT,                                  E_I,                                  ID_REQ,                                  ID_CONT,                                  DURASI,                                  TGL_STACK,                                  TGL_DEPARTURE)            ( SELECT a.NO_CONTAINER,             a.SIZE_CONT,             a.TYPE_CONT,             a.STATUS_CONT,             a.HZ,             a.HEIGHT,             'E',             '$max',             a.ID_CONT,             ROUND((TO_DATE ('$atd', 'dd/mm/yyyy') - TGL_STACK)) + 1 DURASI,             b.TGL_STACK,             TO_DATE ('$atd','dd/mm/yyyy')         FROM REQ_RECEIVING_D a, REQ_RECEIVING_H b       WHERE     a.NO_REQ_ANNE = b.ID_REQ             AND a.VESSEL = '$vessel'             AND a.VOYAGE_IN ='$voyin'             AND a.VOYAGE_OUT = '$voyout'             AND TO_DATE ('$atd', 'dd/mm/yyyy') - b.TGL_STACK + 1 > 5)");		*/		//print_r($query);die;		//$msg = $param_b_var['v_msg'];					echo 'OK';	}?>