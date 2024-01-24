<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('report_list.htm');
	
	$db 	= getDB("storage");
	$option_status	= $_POST["option_status"];
	$option_locate	= $_POST["option_locate"];
	
	if($option_locate == 'stripping')
	{
		$option_locate	= "where ba.keterangan = 'STRIPING'";
	} 
	else if($option_locate == 'stuffing')
	{
		$option_locate	= "where ba.keterangan = 'STUFFING'";
	} 
	else if ($option_locate == 'mty') {
		$option_locate	= "where ba.keterangan = 'EMPTY'";		
	}
	else{
		$option_locate	= " ";
	}
	
					
	$query_list_ = "select hs.no_container, mc.size_, mc.type_, hs.status_cont, pl.id_blocking_area, ba.name block_, pl.slot_,
					pl.row_,pl.tier_, pl.tgl_placement, hs.no_booking, ba.keterangan kegiatan, mu.nama_lengkap
					from placement pl inner join blocking_area ba on pl.id_blocking_area = ba.id
					inner join history_container hs on pl.no_container = hs.no_container and
					hs.tgl_update = (select max(tgl_update) from history_container where no_container = hs.no_container)
					inner join master_container mc on hs.no_container = mc.no_container and
					mc.counter = hs.counter
					left join master_user mu on pl.user_name = mu.username ".$option_locate." ";
	if($option_status != 'ALL'){
		$query_list_ .= "and hs.status_cont = '$option_status'";
	}
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 

	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
