<?php
//utk menon-aktifkan template default
outputRaw();

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:20; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
$sord = $_POST['sord']; // get the direction

$wh = " WHERE 1=1";
$filters = json_decode($_REQUEST['filters']);
//echo '<pre>';print_r($_REQUEST);
$searchOn = $_REQUEST['_search'];
if($searchOn=='true') {	
	$sarr = $filters->rules;
	foreach( $sarr as $k) {
		$wh .= " AND UPPER(".$k->field.") LIKE UPPER('%".$k->data."%')";
	}
}

$db = getDB();
$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM UPER_H ".$wh;
//print_r($query);die;
$res = $db->query($query)->fetchRow();
$count = $res[NUMBER_OF_ROWS];

function is_ac($COMPANY_ID){
	$db 	= getDB();
	$sql_cust = $db->query("SELECT * FROM AC_REKENING_USER WHERE CUSTOMER_ID = '".$COMPANY_ID."'")->fetchRow();
	if(!empty($sql_cust['NOREK_IDR'])){
		return 'Y';
	}else{
		return 'N';
	}
}

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
}
else { 
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)	
$limit = $limit*$page;
/*$query="SELECT * FROM (SELECT A.*, DECODE(A.LUNAS,'T','belum lunas','P','sudah lunas') AS STATUS_LUNAS, ROWNUM rnum 
		FROM VW_UPER A ".$wh." AND ROWNUM <= ".$limit." ORDER BY ".$sidx." ".$sord.") where rnum > ".$start;*/
		/*$query="SELECT * FROM (SELECT A.NO_UPER,
				A.NO_UKK,
				B.VESSEL AS NM_KAPAL,
				B.VOYAGE_IN VOY_IN,
				B.VOYAGE_OUT VOY_OUT,
				A.TOTAL,
				A.VALUTA,
				A.COMPANY_ID,
				B.OPERATOR_NAME AS NM_PEMILIK,
				A.USER_LUNAS,
				DECODE(A.LUNAS,'T','belum lunas','P','sudah lunas') AS STATUS_LUNAS,
				ROWNUM rnum
				,C.NOTAJB_ID				
				FROM UPER_H A 
		--LEFT JOIN ITOS_OP.VES_VOYAGE B ON B.ID_VES_VOYAGE = A.NO_UKK
		LEFT JOIN OPUS_REPO.M_VSB_VOYAGE B ON TO_CHAR(B.ID_VSB_VOYAGE) = TO_CHAR(A.NO_UKK)
		LEFT JOIN ac_bypass_log c on a.no_uper = c.notajb_id
		".$wh."  ORDER BY ".$sidx." ".$sord.") where rnum > ".$start." AND ROWNUM <= ".$limit;*/
		// ganti sorting
		$query ="select *from (
					SELECT b.*, ROWNUM rnum FROM (
								SELECT A.NO_UPER,
									A.NO_UKK,
									B.VESSEL AS NM_KAPAL,
									B.VOYAGE_IN VOY_IN,
									B.VOYAGE_OUT VOY_OUT,
									A.TOTAL,
									A.VALUTA,
									A.COMPANY_ID,
									B.OPERATOR_NAME AS NM_PEMILIK,
									A.USER_LUNAS,
									DECODE(A.LUNAS,'T','belum lunas','P','sudah lunas') AS STATUS_LUNAS
									,C.NOTAJB_ID                
									FROM UPER_H A 
							--LEFT JOIN ITOS_OP.VES_VOYAGE B ON B.ID_VES_VOYAGE = A.NO_UKK
							LEFT JOIN OPUS_REPO.M_VSB_VOYAGE B ON TO_CHAR(B.ID_VSB_VOYAGE) = TO_CHAR(A.NO_UKK)
							LEFT JOIN ac_bypass_log c on a.no_uper = c.notajb_id
							 ".$wh."  ORDER BY ".$sidx." ".$sord."
							 ) b where 1=1 AND ROWNUM <= ".$limit." ) 
							 where rnum > ".$start."";
		
		
		
//echo $query; die;
$result = $db->query($query);
$rows = $result->GetAll();

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

//print_r($rows);die;

$i=0;
foreach ($rows as $row){
	//if($row[LUNAS]=='P') {
		$color="green";
		$edit ="";

		$li  = "<div style='background-color : #fdb813'>";
		$lic = "</div>";

		$cancel = "";
		if ($row[USER_LUNAS] != 'X') {
		 	$cancel = "<a onclick='edit_uper(\"".$row[NO_UPER]."\",\""."cancel"."\")'><img border='0' src='images/cancel_f2.png' width='14' height='14' title='Cancel Uper'></a>&nbsp;&nbsp;";
		} 

		if($row[USER_LUNAS] == 'T'){
			// dimatiin dulu 
			// validasi jikalau sudah download RBM tidak bisa cancel Uper
			$query2 = "SELECT COUNT(1) JML 
                FROM UPER_H A
                JOIN OPUS_REPO.M_VSB_VOYAGE B ON A.NO_UKK = to_char(B.ID_VSB_VOYAGE) 
				and B.ATA IS NOT NULL
                WHERE NO_UPER='".$row[NO_UPER]."'";
			$result2 = $db->query($query2);
			$row2 		= $result2->fetchRow();
			if ($row2['JML'] > 0)
			{
				$edit ="";
			}
			else
			{
				$edit ="$cancel";
			}
			
			// $edit ="$cancel";
		}
		else if($row[USER_LUNAS] == 'X')
		{
			$edit ="";
		}
		else{
			if (isset($row[NOTAJB_ID]) && strlen($row[NOTAJB_ID]) > 0)
			{
				$edit ="<b>BYPASS</b> ";
			}
			else
			{
				$edit = "<a onclick='edit_uper(\"".$row[NO_UPER]."\",\""."edit"."\")'><img border='0' src='images/edit.png' title='Edit Uper'></a>&nbsp;&nbsp;<a onclick='edit_uper(\"".$row[NO_UPER]."\",\""."hold"."\")'><img border='0' src='images/confirm.png' width='14' height='14' title='Hold Dan Simpan'></a>&nbsp;&nbsp;$cancel";
			}
		}
		
		if (is_ac($row[COMPANY_ID]) == 'N')
		{
			$edit = "<a onclick='edit_uper(\"".$row[NO_UPER]."\",\""."edit"."\")'><img border='0' src='images/edit.png' title='Edit Uper'></a>";
		}


		$aksi = "$edit<a href='request.uper_bm_ac.print?p1=$row[NO_UPER]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak uper'></a>";
	//}
	//else {
		// $aksi = "<a href='request.uper_bm_ac.print?p1=$row[NO_UPER]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak uper'></a>";
		//$color="red";
	//}

		/*validate warna*/
		if($row[USER_LUNAS] == 'X'){
			$aksi 				= $li.''.$aksi.''.$lic;
			$row[NO_UPER] 		= $li.''.$row[NO_UPER].''.$lic;
			$row[NO_UKK] 		= $li.''.$row[NO_UKK].''.$lic;
			$row[NM_KAPAL] 		= $li.''.$row[NM_KAPAL].''.$lic;
			$row[VOY_IN] 		= $li.''.$row[VOY_IN].''.$lic;
			$row[VOY_OUT] 		= $li.''.$row[VOY_OUT].''.$lic;
			$row[NM_PEMILIK] 	= $li.''.$row[NM_PEMILIK].''.$lic;
			$row[TOTAL] 		= $li.''.number_format($row[TOTAL],2).''.$lic;
			$row[VALUTA] 		= $li.''.$row[VALUTA].''.$lic;
			$row[COMPANY_ID] 	= $li.''.is_ac($row[COMPANY_ID]).''.$lic;
		}else{
			$aksi 				= $aksi;
			$row[NO_UPER] 		= $row[NO_UPER];
			$row[NO_UKK] 		= $row[NO_UKK];
			$row[NM_KAPAL] 		= $row[NM_KAPAL];
			$row[VOY_IN] 		= $row[VOY_IN];
			$row[VOY_OUT] 		= $row[VOY_OUT];
			$row[NM_PEMILIK] 	= $row[NM_PEMILIK];
			$row[TOTAL] 		= number_format($row[TOTAL],2);
			$row[VALUTA] 		= $row[VALUTA];
			$row[COMPANY_ID] 	= is_ac($row[COMPANY_ID]);
		}

		/*end validate warna*/

	$responce->rows[$i]['id']=$row[NO_UPER];
	$responce->rows[$i]['cell']=array($aksi,$row[NO_UPER],$row[NO_UKK],$row[NM_KAPAL],$row[VOY_IN]." - ".$row[VOY_OUT],$row[NM_PEMILIK],$row[TOTAL],$row[VALUTA],$row[COMPANY_ID],"<blink><b><font color='".$color."'></font></b></blink>");
	$i++;
}
//print_r($responce);die;
echo json_encode($responce);
?>