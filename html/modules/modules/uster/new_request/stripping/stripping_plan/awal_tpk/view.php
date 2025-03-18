<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view.htm');
	
	$db = getDB("storage");
	$db2 = getDB("ora");
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		
		
		
		$query = "SELECT NO_REQUEST_RECEIVING
					FROM PLAN_REQUEST_STRIPPING
					WHERE NO_REQUEST = '$no_req'";
					
		$result 	= $db->query($query);
		$row_req2	= $result->fetchRow();
		$no_req_rec	= $row_req2["NO_REQUEST_RECEIVING"];
		
		$no_req2	= substr($no_req_rec,3);	
		$no_req2	= "UREC".$no_req2;
		
		$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STRIPPING.*, 
								  PLAN_CONTAINER_STRIPPING.COMMODITY COMMO
								  --A.KD_SIZE, 
								  --A.KD_TYPE
							   FROM PLAN_CONTAINER_STRIPPING 
									--LEFT JOIN PETIKEMAS_CABANG.TTD_BP_CONT A            
										--ON PLAN_CONTAINER_STRIPPING.NO_CONTAINER = A.CONT_NO_BP
							   WHERE PLAN_CONTAINER_STRIPPING.NO_REQUEST = '$no_req'";
		$result_list	= $db->query($query_list);
		$row_list		= $result_list->getAll();
		$jum = count($row_list);			
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
						   
	//echo $no_req	
	$query_request	= "SELECT PLAN_REQUEST_STRIPPING.*, 
                              emkl.NM_PBM AS NAMA_PEMILIK, 
                              emkl.NM_PBM AS NAMA_PENUMPUK 
                        FROM PLAN_REQUEST_STRIPPING 
                            INNER JOIN KAPAL_CABANG.MST_PBM emkl 
                                ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM
                                AND emkl.KD_CABANG = '05'  
                        WHERE PLAN_REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
	//debug($query_request);							
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	$result_all = $db->query("SELECT TO_CHAR (TGL_MULAI, 'yyyy-mm-dd') TGL_MULAI, 
									 TO_CHAR (TGL_SELESAI, 'yyyy-mm-dd') TGL_SELESAI 
								FROM PLAN_CONTAINER_STRIPPING 
								WHERE NO_REQUEST = '$no_req'");
	$row_all = $result_all->getAll();
	
	//debug($row_request);
	$sql_no  		= "SELECT AUTO_NO+1 AS AUTO_NO  
						FROM PETIKEMAS_CABANG.MST_AUTO_NO 
						WHERE CODE='7'";
	$rs 	 		= $db2->query( $sql_no );
	$row	 		= $rs->FetchRow();
	$sp2		 	= $row['AUTO_NO'];	
	$dl = '<select id ="TYPE_S" name="TYPE_S">';
	if($row_request["TYPE_STRIPPING"] == "DOMESTIC") {
				$dl .= "<option value='DOMESTIC'> DALAM NEGERI </option> 
				<option value='INTERNATIONAL'> LUAR NEGERI </option>";
	} else{
				$dl .= '<option value="INTERNATIONAL"> LUAR NEGERI </option> 
				<option value="DOMESTIC"> DALAM NEGERI </option>'; 
			
	}
	$dl .= '</select>';

	$tl->assign("dla", $dl);
	//$tl->assign("dla", 'adad');
	$tl->assign("jum", $jum);
	$tl->assign("row_list", $row_list);
	$tl->assign("row_request", $row_request);
	$tl->assign("row_all", $row_all);
	$tl->assign("no_req2", $no_req2);
	$tl->assign("sp2", $sp2);	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
