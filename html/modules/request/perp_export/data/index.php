<?php
//utk menon-aktifkan template default
outputRaw();

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
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
$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM RBM_H ".$wh;
//print_r($query);die;
$res = $db->query($query)->fetchRow();
$count = $res[NUMBER_OF_ROWS];

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
}
else { 
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)	
$limit = $limit*$page;
// if($sidx=="")	$sidx=1;
/*$query="/* Formatted on 18-Nov-13 3:14:00 PM (QP5 v5.163.1008.3004) 
  SELECT B.ID_REQUEST,
         A.NO_UKK ID_VS,
         A.NM_KAPAL NAMA_VESSEL,
         (SELECT C.KETERANGAN
            FROM MASTER_JENIS_KAPAL C
           WHERE C.ID_JK = A.JENIS_KAPAL)
            AS JENIS_KAPAL2,
         A.VOYAGE_IN,
         A.VOYAGE_OUT,
         TO_CHAR (A.TGL_JAM_TIBA, 'DD MON YYYY HH24:Mi') ETA,
         TO_CHAR (A.TGL_JAM_BERANGKAT, 'DD MON YYYY HH24:Mi') ETD,
         A.NM_PELABUHAN_ASAL,
         A.NM_PELABUHAN_TUJUAN,
         TO_CHAR (A.RTA, 'DD MON YYYY HH24:Mi') RTA,
         TO_CHAR (A.RTD, 'DD MON YYYY HH24:Mi') RTD,
         TO_DATE(C.ATA, 'YYYYMMDDHHMiSS') ATA,
         TO_DATE(C.ATD, 'YYYYMMDDHHMiSS') ATD,
         A.STATUS
    FROM RBM_H A, REQ_REEKSPOR_H B, M_VSB_VOYAGE@dbint_link C
   WHERE A.NO_UKK(+) = B.NO_UKK AND A.ID_VS = C.VESSEL_CODE AND A.VOYAGE_IN = C.VOYAGE_IN AND A.VOYAGE_OUT = C.VOYAGE_OUT AND ROWNUM <= ".$limit."
ORDER BY B.DATE_INSERT DESC";*/
//echo $query; die;
$query = "  SELECT A.ID_REQUEST,
         A.NO_UKK ID_VS,
         A.NM_KAPAL NAMA_VESSEL,
         (SELECT B.KETERANGAN
            FROM MASTER_JENIS_KAPAL B
           WHERE A.ID_JK = B.ID_JK)
            AS JENIS_KAPAL2,
         A.VOYAGE_IN,
         A.VOYAGE_OUT,
         TO_CHAR (A.ETA, 'DD MON YYYY HH24:Mi') RTA,
         TO_CHAR (A.ETD, 'DD MON YYYY HH24:Mi') RTD,
         TO_DATE(A.RTA,  'DD MON YYYY HH24:Mi') ATA,
         TO_DATE(A.RTD,  'DD MON YYYY HH24:Mi') ATD,
         A.STATUS
    FROM REQ_REEKSPOR_H A
   ";
$result = $db->query($query);
$rows = $result->GetAll();

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

$i=0;
foreach ($rows as $row){
	$pw=$row[ID_VS];
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			if($row[ID_REQUEST]<>'')
			{
				$edit="<font color='red'><i><b>Already<br>Created</b></i></font>";
			}
			else
			{
				if ($row[ETA] > $row[RTA]) {
					$edit="<button onclick='ext(\"$pw\")' title='perp_export'><img src='images/EXT.png' width=15px height=15px border='0'></button>";
				} else {
					$edit="<button onclick='ext(\"$pw\")' title='perp_export'><img src='images/EXT.png' width=15px height=15px border='0'></button>";
				}
				
			}
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($edit,$row[ID_REQUEST], $row[ID_VS],$row[NAMA_VESSEL],$voy,$row[JENIS_KAPAL2], $row[RTA],$row[RTD],$row[ATA],$row[ATD]);
	$i++;
}
echo json_encode($responce);
?>