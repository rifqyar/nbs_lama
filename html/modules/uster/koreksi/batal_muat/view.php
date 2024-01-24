<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view.htm');
	
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
	}
	else
	{ 
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	
	$query_request	= "select rbm.no_request, em.nm_pbm, rbm.biaya, rbm.jenis_bm, rbm.status_gate, rbm.kapal_tuju, vb.nm_kapal, rbm.kd_emkl
						from request_batal_muat rbm inner join v_mst_pbm em on rbm.kd_emkl = em.kd_pbm and em.kd_cabang = '05'
						inner join v_pkk_cont vb on  REPLACE(rbm.kapal_tuju,'VESSEL_NOTHING','BSK100000023') = vb.no_booking
						where rbm.no_request = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	$jenis_bm = '<select name="jenis_batal" id="jenis_batal" style="width:160px;color:#000000; color:#808080;background-color:#FFFF99">';
	$jenis_bm .= '<option value=""> PILIH</option>';
	if($row_request['JENIS_BM'] == 'alih_kapal'){
		$jenis_bm .= '<option selected value="alih_kapal"> ALIH KAPAL </option>';
		$jenis_bm .= '<option value="delivery"> DELIVERY </option>';
	}
	else if($row_request['JENIS_BM'] == 'delivery'){
		$jenis_bm .= '<option value="alih_kapal"> ALIH KAPAL </option>';
		$jenis_bm .= '<option selected value="delivery"> DELIVERY </option>';
	}
	$jenis_bm .= '</select>';
	
	$res_kapal = $db->query ("select * from v_pkk_cont where no_booking =REPLACE('$row_request[KAPAL_TUJU]','VESSEL_NOTHING','BSK100000023')");
	$rwk = $res_kapal->fetchRow();
	
	$biaya = '<select name="biaya" style="width:160px;color:#000000;color:#808080;background-color:#FFFF99">';
	//$biaya .= '<option value=""> PILIH</option>';
	if($row_request['BIAYA'] == 'Y'){
		$biaya .= '	<option selected value="Y"> YA </option>
							<option value="T"> TIDAK </option>';
	} else if ($row_request['BIAYA'] == 'T'){
		$biaya .= '<option selected value="T"> TIDAK </option>
					<option value="Y"> YA </option>';
	}
	$biaya .= '</select>';
	
	$status_gate = '<select name="status_gate" id="status_gate" style="width:160px;color:#000000; color:#808080;background-color:#FFFF99">
							<option value=""> PILIH</option>';
	if($row_request['STATUS_GATE'] == '1'){
		$status_gate .= '<option selected value="1"> AFTER STUFFING </option>
						<option value="2"> EX REPO </option>
						<option value="3"> BEFORE STUFFING </option>';
	}
	else if ($row_request['STATUS_GATE'] == '2'){
		$status_gate .= '<option value="1"> AFTER STUFFING </option>
						<option  selected value="2"> EX REPO </option>
						<option value="3"> BEFORE REPO </option>';
	}
	else if ($row_request['STATUS_GATE'] == '3'){
		$status_gate .= '<option value="1"> AFTER STUFFING </option>
						<option value="2"> EX REPO </option>
						<option  selected value="3"> BEFORE REPO </option>';
	}
	$status_gate .= '</select>';
	$tl->assign("status_gate", $status_gate );
	$tl->assign("biaya", $biaya );
	$tl->assign("jenis_bm", $jenis_bm );
	$tl->assign("rwk", $rwk );
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
