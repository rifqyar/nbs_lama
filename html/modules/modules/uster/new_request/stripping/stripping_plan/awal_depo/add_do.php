<?php	
	
	$ID_CONSIGNEE	= $_POST["ID_CONSIGNEE"];
	$ID_PENUMPUKAN	= $_POST["ID_PENUMPUKAN"];
	$TYPE_S			= $_POST["TYPE_S"];
	$NO_DO			= $_POST["NO_DO"];
	$NO_BL			= $_POST["NO_BL"];
	$tgl_awal		= $_POST["TGL_AWAL"];
	$tgl_akhir		= $_POST["TGL_AKHIR"];
	$KETERANGAN		= $_POST["keterangan"];
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	$db = getDB("storage");
	
	//debug($_POST);
	//die();
	//Cek nilai request existing yang paling besar
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM PLAN_REQUEST_STRIPPING
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
	
	$result_select 	= $db->query($query_cek);
	
	
	$row_select 	= $result_select->fetchRow();
	
	
	$no_req			= $row_select["JUM"];
	$month			= $row_select["MONTH"];
	$year			= $row_select["YEAR"];
	$no_req_s		= "PTR".$month.$year.$no_req;
	
	
	$query_req 	= "INSERT INTO PLAN_REQUEST_STRIPPING(NO_REQUEST, 
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
												 STRIPPING_DARI,
												 APPROVE
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
												'DEPO',
												'NY'
												) ";
	

	if($db->query($query_req))	
	{
		header('Location: '.HOME.APPID.'/view?no_req='.$no_req_s);		
	}
	else
	{
		echo "not ok";	
	}
	
?>		