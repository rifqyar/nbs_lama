<?php
	$tl 		=  xliteTemplate('time.htm');
	$db 		= getDB("storage");
	$booking 	= $_POST["booking"];
	
	$q_cek 	= "select * from master_booking_time where no_booking = '$booking'";
	$r_cek	= $db->query($q_cek);
	$rwc	= $r_cek->recordCount();
	if($rwc > 0){
	
	}
	else {
		$query		= "select a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING,  TO_CHAR(a.tgl_jam_berangkat,'dd/mm/rrrr') TGL_BERANGKAT,TO_DATE(a.tgl_stacking) TGL_STACKING, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT, 
						a.TGL_JAM_BERANGKAT ETD, a.TGL_JAM_TIBA ETA, TO_CHAR(A.TGL_JAM_CLOSE,'DD/Mon/YYYY') TGL_JAM_CLOSE,
						TO_CHAR(A.TGL_JAM_CLOSE-4,'DD') TGL_USTER,  TO_CHAR(A.TGL_JAM_CLOSE-4,'Mon') BLN_USTER,
						TO_CHAR(A.TGL_JAM_CLOSE-4,'YYYY') THN_USTER,
						TO_CHAR(A.TGL_JAM_CLOSE-2,'DD') TGLCL_USTER,  TO_CHAR(A.TGL_JAM_CLOSE-2,'Mon') BLNCL_USTER,
						TO_CHAR(A.TGL_JAM_CLOSE-2,'YYYY') THNCL_USTER
						from v_booking_stack_tpk a 
						where --to_date(A.TGL_JAM_BERANGKAT) >=add_months(sysdate,-1) and 
						a.NO_BOOKING LIKE '%$booking%'";
		$result		= $db->query($query);
		$row		= $result->fetchRow();
		$kdate 		= $row[TGL_JAM_CLOSE];
		
		$qdual		= "SELECT CAST(to_char(LAST_DAY('$kdate'),'dd') AS INT) jum_date
					  FROM dual";
		$rdual		= $db->query($qdual);
		$rd			= $rdual->fetchRow();
		$jum_date	= $rd[JUM_DATE];
		
		//tanggal open stack uster
		$op_tgl = "<select id='tglop' name='tglop'>";
		for($i=1;$i<=$jum_date;$i++){
			$op_tgl .= "<option ";
			if($i == $row[TGL_USTER]){
				$op_tgl .= "selected ";
			}
			$op_tgl .= "value='$i'>$i</option>";
		}
		$op_tgl .= "</select>";
		
		//tanggal closing time uster
		$cl_tgl = "<select id='tglcl' name='tglcl'>";
		for($i=1;$i<=$jum_date;$i++){
			$cl_tgl .= "<option ";
			if($i == $row[TGLCL_USTER]){
				$cl_tgl .= "selected ";
			}
			$cl_tgl .= "value='$i'>$i</option>";
		}
		$cl_tgl .= "</select>";
		
		//bulan closing time uster
		$qmon = $db->query("WITH MONTH_COUNTER AS (
						  SELECT LEVEL-1 AS ID
						  FROM DUAL
						  CONNECT BY LEVEL <= 12
						)
						SELECT TO_CHAR(ADD_MONTHS(TO_DATE('01/01/1000', 'DD/MM/RRRR'), ID),'Month') month,
						TO_CHAR(ADD_MONTHS(TO_DATE('01/01/1000', 'DD/MM/RRRR'), ID),'Mon') month_v FROM MONTH_COUNTER");
		$rmon = $qmon->getAll();
		$cl_bln = "<select id='blncl' name='blncl'>";
		foreach($rmon as $rm){
			$cl_bln .="<option ";
			if($rm[MONTH_V] == $row[BLNCL_USTER]){
				$cl_bln .= "selected ";
			}
			$cl_bln .= "value='$rm[MONTH_V]'>$rm[MONTH]</option>";
			$cl_bln .="</option>";
		}
		$cl_bln .= "</select>";
		
		//bulan open stack uster
		$qmon = $db->query("WITH MONTH_COUNTER AS (
						  SELECT LEVEL-1 AS ID
						  FROM DUAL
						  CONNECT BY LEVEL <= 12
						)
						SELECT TO_CHAR(ADD_MONTHS(TO_DATE('01/01/1000', 'DD/MM/RRRR'), ID),'Month') month,
						TO_CHAR(ADD_MONTHS(TO_DATE('01/01/1000', 'DD/MM/RRRR'), ID),'Mon') month_v FROM MONTH_COUNTER");
		$rmon = $qmon->getAll();
		$op_bln = "<select id='blnop' name='blnop'>";
		foreach($rmon as $rm){
			$op_bln .="<option ";
			if($rm[MONTH_V] == $row[BLN_USTER]){
				$op_bln .= "selected ";
			}
			$op_bln .= "value='$rm[MONTH_V]'>$rm[MONTH]</option>";
			$op_bln .="</option>";
		}
		$op_bln .= "</select>";
		
		//tahun open stack uster
		$op_thn = "<select id='thnop' name='thnop'>";		
		$op_thn .= "<option value='".($row[THN_USTER]-1)."'>".($row[THN_USTER]-1)."</option>";
		$op_thn .= "<option selected value='".$row[THN_USTER]."'>".$row[THN_USTER]."</option>";
		$op_thn .= "<option value='".($row[THN_USTER]+1)."'>".($row[THN_USTER]+1)."</option>";
		$op_thn .= "</select>";
		
		
		//tahun closing time uster
		$cl_thn = "<select id='thncl' name='thncl'>";		
		$cl_thn .= "<option value='".($row[THNCL_USTER]-1)."'>".($row[THNCL_USTER]-1)."</option>";
		$cl_thn .= "<option selected value='".$row[THNCL_USTER]."'>".$row[THNCL_USTER]."</option>";
		$cl_thn .= "<option value='".($row[THNCL_USTER]+1)."'>".($row[THNCL_USTER]+1)."</option>";
		$cl_thn .= "</select>";
		
		$tl->assign('op_tgl',$op_tgl);
		$tl->assign('op_bln',$op_bln);
		$tl->assign('op_thn',$op_thn);
		
		$tl->assign('cl_tgl',$cl_tgl);
		$tl->assign('cl_bln',$cl_bln);
		$tl->assign('cl_thn',$cl_thn);
		
		$tl->assign('cl_tpk',$kdate);
	}
	$tl->renderToScreen();
?>