<?php	
	
	$no_req			= $_GET["no_req"];
	$ID_USER		= $_SESSION["LOGGED_STORAGE"];
	$id_yard		= $_SESSION["IDYARD_STORAGE"];
	
	$db = getDB("storage");
	$masa_berlaku = 3;
	//debug($_POST);
	//die();
	//Cek nilai request existing yang paling besar
	
	//----------------get data plan
	
	$query_get	= "SELECT * FROM PLAN_REQUEST_STRIPPING WHERE NO_REQUEST = '$no_req'";
	$result_get	= $db->query($query_get);
	$row_plan	= $result_get->fetchRow();
	
	//debug($query_get);
	
	//die();
	//----------------------------
	
	$query_cek	= "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM, 
							  TO_CHAR(SYSDATE, 'MM') AS MONTH, 
							  TO_CHAR(SYSDATE, 'YY') AS YEAR 
					   FROM REQUEST_STRIPPING
					   WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
					   
	
	$result_select 	= $db->query($query_cek);
	
	
	$row_select 	= $result_select->fetchRow();
	
	
	$no_req_		= $row_select["JUM"];
	$month			= $row_select["MONTH"];
	$year			= $row_select["YEAR"];
	$no_req_s		= "STR".$month.$year.$no_req_;
	//die();
	//----------------------------- Insert ke tabel request
	$query_req 	= "INSERT INTO REQUEST_STRIPPING(NO_REQUEST, 
												 KD_CONSIGNEE, 
												 KD_PENUMPUKAN_OLEH,
												 KETERANGAN,
												 TGL_REQUEST, 
												 TGL_APPROVE,
												 TGL_AWAL, 
												 TGL_AKHIR,
												 ID_USER,
												 TYPE_STRIPPING,
												 NO_DO,
												 NO_BL,
												 STRIPPING_DARI,
												 NO_REQUEST_PLAN,
												 NO_REQUEST_RECEIVING,
												 AFTER_STRIP
												 ) 
										VALUES(	'$no_req_s', 
												'".$row_plan["KD_CONSIGNEE"]."', 
												'".$row_plan["KD_PENUMPUKAN_OLEH"]."', 
												'$KETERANGAN',
												'".$row_plan["TGL_REQUEST"]."', 
												SYSDATE,
												SYSDATE,  
												SYSDATE + $masa_berlaku,   
												'$ID_USER',
												'".$row_plan["TYPE_STRIPPING"]."',
												'".$row_plan["NO_DO"]."',
												'".$row_plan["NO_BL"]."',
												'".$row_plan["STRIPPING_DARI"]."',
												'$no_req',
												'".$row_plan["NO_REQUEST_RECEIVING"]."',
												'".$row_plan["AFTER_STRIP"]."'
												) ";
												
	//--------------------------------------------------------------

	if($db->query($query_req))	
	{
		//---------------insert data container
		$query_get_cont		= "SELECT * FROM PLAN_CONTAINER_STRIPPING WHERE NO_REQUEST = '$no_req'"; 
		$result_cont		= $db->query($query_get_cont);
		$row_cont			= $result_cont->getAll();
		//debug($row_cont);
		//debug($query_get_cont);
		debug($row_cont);
		debug($query_get_cont);
		foreach($row_cont as $cont)
		{
			$query_ins	 	= "INSERT INTO CONTAINER_STRIPPING(NO_CONTAINER, 
													   NO_REQUEST,
													   AKTIF,
													   VIA,
													   TGL_BONGKAR,
													   HZ,
													   ID_YARD,
													   VOYAGE
													  ) 
												VALUES('".$cont["NO_CONTAINER"]."', 
													   '$no_req_s',
													   'Y',
													   '".$cont["VIA"]."',
													   '".$cont["TGL_BONGKAR"]."',
													   '".$cont["HZ"]."',
													   '".$cont["ID_YARD"]."',
													   '".$cont["VOYAGE"]."'
													   )";
			$db->query($query_ins);
		}
		
		//----------------update status Approve
		$query_upd	= "UPDATE PLAN_REQUEST_STRIPPING SET APPROVE = 'Y' WHERE NO_REQUEST = '$no_req'";
		
		if($db->query($query_upd))
		{	
			//echo "OK";
			header('Location: '.HOME.APPID.'/');		
		}
	}
	else
	{
		echo "not ok";	
	}
	
?>		