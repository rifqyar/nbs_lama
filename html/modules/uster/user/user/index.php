<?php
	
	$tl =  xliteTemplate('home.htm');
	
	$db         	= getDB("storage");
	$query_yard    	= "SELECT blocking_cell.X1, blocking_cell.X2,blocking_cell.Y1,blocking_cell.Y2,blocking_cell.SLOT_, blocking_cell.ID_BLOCKING_AREA, blocking_cell.ROW_, concat(concat(concat(concat(concat('D_',blocking_cell.ID_BLOCKING_AREA),'_'),blocking_cell.slot_),'_'),blocking_cell.row_) LOKASI 
						FRoM BLOCKING_CELL, blocking_area 
						WHERE blocking_cell.ID_BLOCKING_AREA = blocking_area.ID 
						AND blocking_area.ID_YARD_AREA ='46'";
	$result		    = $db->query($query_yard);
	$data		    = $result->getAll();
	
	$query			= "select 	a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING, 	   
							TO_DATE(a.tgl_jam_berangkat,'dd/mm/RRRR') TGL_BERANGKAT,TO_CHAR(a.tgl_stacking, 'DD MONTH YYYY HH24:MI:SS') OPEN_STUFF, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT, 
							TO_CHAR(a.DOC_CLOSING_DATE_DRY,'DD MONTH YYYY HH24:MI:SS') CLOSING_STUFF, TO_CHAR(a.DOC_CLOSING_DATE_DRY-2,'DD MONTH YYYY HH24:MI:SS') CLOSING_CARGO,
						a.NM_PELABUHAN_ASAL, a.NM_PELABUHAN_TUJUAN
						from v_booking_stack_tpk a
						where to_timestamp(sysdate) between to_timestamp (a.TGL_STACKING)
										and to_timestamp (a.DOC_CLOSING_DATE_DRY) AND A.NO_UKK <> 'NTH05000001'
						ORDER BY a.DOC_CLOSING_DATE_DRY DESC";
							
	$result		= $db->query($query);
	$row		= $result->getAll();
	
	$tl->assign("data",$data);
	$tl->assign("row_list",$row);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);

	$tl->renderToScreen();

?>
