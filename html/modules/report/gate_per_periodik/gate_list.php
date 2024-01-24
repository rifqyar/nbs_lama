<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('gate_list.htm');
	
	$db 	= getDB();
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	$shift		= $_POST["shift"]; 
	$size		= $_POST["size"]; 
	$status		= $_POST["status"];
	$type		= $_POST["type"]; 
	$id_vs		= $_POST["id_vs"];
	
if ($shift == NULL){
		$query_shift = '';
	} else {
		if ($shift == 1){
			$query_shift = "and to_date(substr(to_char(a.tgl_gate_in,'mm/dd/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss')  between to_date('08:00:00','hh24:mi:ss')
and to_date('20:00:00','hh24:mi:ss')";
		} else if ($shift == 2){
			$query_shift = "and to_date(substr(to_char(a.tgl_gate_in,'mm/dd/rrrr hh24:mi:ss'),11,9),'hh24:mi:ss')  between to_date('20:00:00','hh24:mi:ss')
and to_date('08:00:00','hh24:mi:ss')";
		} else {
			$query_shift = "";
		}
	}
	
	if ($size == NULL){
		$query_size = '';
	} else {
		if ($size == 20){
			$query_size = "and a.size_ = '20'";
		} else if ($size == 40){
			$query_size = "and a.size_ = '40'";
		} else if ($size == 45){
			$query_size = "and a.size_ = '45'";
		} else {
			$query_size = '';
		}
	}
	
	if ($status == NULL){
		$query_status = '';
	} else {
		if ($status == 'FCL'){
			$query_status = "and REGEXP_REPLACE (a.status, '[[:space:]]', '') = 'FCL'";
		} else if ($status == 'MTY'){
			$query_status = "and REGEXP_REPLACE (a.status, '[[:space:]]', '') = 'MTY'";
		} else {
			$query_status = "";
		}
	}
	
	if ($id_vs == NULL){
		$query_vs = '';
	} else {
	    $query_vs = "and REGEXP_REPLACE (d.NM_KAPAL, '[[:space:]]', '') = '$id_vs'";
	}
	
	if ($type == NULL){
		$query_type = '';
	} else {
		if ($type == 'DRY'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'DRY'";
		} else if ($type == 'FLT'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'FLT'";
		} else if ($type == 'HQ'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'HQ'";
		} else if ($type == 'OVD'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'OVD'";
		} else if ($type == 'RFR'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'RFR'";
		} else if ($type == 'TNK'){
			$query_type = "and REGEXP_REPLACE (a.type_, '[[:space:]]', '') = 'TNK'";
		} else {
			$query_type = "";
		}
	}
	
	//echo $tgl_awal;die;
	

	
	if ($jenis == 'GATI'){
		$query_list_ = "SELECT TO_CHAR (a.TGL_GATE_IN, 'dd Mon rrrr hh24:ii:ss') TGL_GATI,
                           a.NO_CONTAINER,
                           a.SIZE_,
                           a.TYPE_,
                           a.STATUS,
                           d.NM_KAPAL,
                           d.VOYAGE_IN,
                           d.VOYAGE_OUT,
                           a.BLOCK,
                           a.SLOT,
                           a.USER_CONFIRM,
                          (SELECT c.NO_POLISI FROM TB_CONT_JOBSLIP c WHERE a.NO_CONTAINER = c.NO_CONT) NO_POLISI,
                           a.BERAT,
                           (SELECT b.KLASIFIKASI FROM MASTER_TONAGE b WHERE (a.BERAT > MIN_ and a.berat < MAX_ ) and (b.SIZE_ = a.SIZE_)) KATEGORI_BERAT,
                           a.HZ,
                           a.SEAL_ID
                      FROM ISWS_LIST_CONTAINER a, RBM_H d
                      WHERE a.NO_UKK = d.NO_UKK
						    and substr(to_date(a.tgl_gate_in,'dd/mm/yyyy'),0,9) between to_date('$tgl_awal', 'dd/mm/yyyy') and to_date('$tgl_akhir', 'dd/mm/yyyy')
						   ".$query_shift." ".$query_size." ".$query_status." ".$query_type." ".$query_vs." order by a.TGL_GATE_IN desc ";
	} else if ($jenis == 'GATO'){
		$query_list_ = "SELECT TO_CHAR (a.TGL_GATE_OUT, 'dd Mon rrrr hh24:ii:ss') TGL_GATOUT,
                           a.NO_CONTAINER,
                           a.SIZE_,
                           a.TYPE_,
                           a.STATUS,
                           d.NM_KAPAL,
                           d.VOYAGE_IN,
                           d.VOYAGE_OUT,
                           a.BLOCK,
                           a.SLOT,
                           a.USER_CONFIRM,
                          (SELECT c.NO_POLISI FROM TB_CONT_JOBSLIP c WHERE a.NO_CONTAINER = c.NO_CONT) NO_POLISI,
                           a.BERAT,
                           (SELECT b.KLASIFIKASI FROM MASTER_TONAGE b WHERE (a.BERAT > MIN_ and a.berat < MAX_ ) and (b.SIZE_ = a.SIZE_)) KATEGORI_BERAT,
                           a.HZ,
                           a.SEAL_ID
                      FROM ISWS_LIST_CONTAINER a, RBM_H d
                      WHERE a.NO_UKK = d.NO_UKK
						    and substr(to_date(a.tgl_gate_out,'dd/mm/yyyy'),0,9) between to_date('$tgl_awal', 'dd/mm/yyyy') and to_date('$tgl_akhir', 'dd/mm/yyyy')
						   ".$query_shift." ".$query_size." ".$query_status." ".$query_type." ".$query_vs." order by a.TGL_GATE_IN desc  ";
	} else {
		$query_list_ = " SELECT TO_CHAR (a.TGL_GATE_IN, 'dd Mon rrrr hh24:ii:ss') TGL_GATIN,
							TO_CHAR (a.TGL_GATE_OUT, 'dd Mon rrrr hh24:ii:ss') TGL_GATOUT,
                           a.NO_CONTAINER,
                           a.SIZE_,
                           a.TYPE_,
                           a.STATUS,
                           d.NM_KAPAL,
                           d.VOYAGE_IN,
                           d.VOYAGE_OUT,
                           a.BLOCK,
                           a.SLOT,
                           a.USER_CONFIRM,
                          (SELECT c.NO_POLISI FROM TB_CONT_JOBSLIP c WHERE a.NO_CONTAINER = c.NO_CONT) NO_POLISI,
                           a.BERAT,
                           (SELECT b.KLASIFIKASI FROM MASTER_TONAGE b WHERE (a.BERAT > MIN_ and a.berat < MAX_ ) and (b.SIZE_ = a.SIZE_)) KATEGORI_BERAT,
                           a.HZ,
                           a.SEAL_ID
                      FROM ISWS_LIST_CONTAINER a, RBM_H d
                      WHERE a.NO_UKK = d.NO_UKK
						    and substr(to_date(a.tgl_gate_out,'dd/mm/yyyy'),0,9) between to_date('$tgl_awal', 'dd/mm/yyyy') and to_date('$tgl_akhir', 'dd/mm/yyyy')
						   ".$query_shift." ".$query_size." ".$query_status." ".$query_type." ".$query_vs." order by a.TGL_GATE_IN desc   ";
	}
	
	$result 	= $db->query($query_list_);
	$row_list 	= $result->getAll();
	
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
