<?php
$q = $_GET['q'];
$registrant_name=$_GET['registrant_name'];
$registrant_phone=$_GET['registrant_phone'];
$company_name=$_GET['company_name'];
$company_phone=$_GET['company_phone'];
$company_address=$_GET['company_address'];
$email=$_GET['email'];

if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$id = $_GET['id'];
	
	$db = getDB();
	if($q=='l_tid') {
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TID_REPO)";
	} else if($q=='list_truck') {
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TID_REPO)";
	}
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

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if($q=='l_tid') 
	{
		$query="  SELECT TID,
					 TRUCK_NUMBER,
					 REGISTRANT_NAME,
					 REGISTRANT_PHONE,
					 COMPANY_NAME,
					 COMPANY_ADDRESS,
					 COMPANY_PHONE,
					 EMAIL,
					 KIU,
					 TO_CHAR (EXPIRED_KIU, 'dd-mm-yyyy') EXPIRED_KIU,
					 NO_STNK,
					 TO_CHAR (EXPIRED_STNK, 'dd-mm-yyyy') EXPIRED_STNK,
					 TO_CHAR (EXPIRED_DATE, 'dd-mm-yyyy') EXPIRED_DATE,
					 USER_ENTRY,
					 TO_CHAR (DATE_ENTRY, 'dd-mm-yyy hh:mi:ss') DATE_ENTRY
				FROM TID_REPO
			ORDER BY TID";
	} else if($q=='list_truck') {
		$query="  SELECT TID,
					 TRUCK_NUMBER,
					 NO_STNK,
					 TO_CHAR (EXPIRED_STNK, 'dd-mm-yyyy') EXPIRED_STNK
				FROM TID_REPO
				WHERE REGISTRANT_NAME=UPPER('$registrant_name') AND
					 REGISTRANT_PHONE=UPPER('$registrant_phone') AND
					 COMPANY_NAME=UPPER('$company_name') AND
					 COMPANY_ADDRESS=UPPER('$company_address') AND
					 COMPANY_PHONE=UPPER('$company_phone') AND
					 EMAIL=LOWER('$email')
			ORDER BY TID";
	}

	$res = $db->query($query);								
	while ($row = $res->fetchRow()) 
	{
		$aksi = "";
		if($q == 'l_tid') 
		{
			$aksi   = "<a href=".HOME."maintenance.tid/edit_tid?tid=".$row[TID]." target=\"_blank\"><img src='images/edit.png' title='edit'></a>"."&nbsp;&nbsp;"."<a href=".HOME."maintenance.tid/print_slip?tid=".$row[TID]." target=\"_blank\"><img src='images/printer.png' title='print_nota'></a>";
			$responce->rows[$i]['id']=$row[TID];
			$responce->rows[$i]['cell']=array($aksi, $row[TID],$row[TRUCK_NUMBER],$row[EXPIRED_DATE],$row[REGISTRANT_NAME],$row[REGISTRANT_PHONE],$row[COMPANY_NAME],$row[COMPANY_ADDRESS],$row[COMPANY_PHONE],$row[EMAIL],$row[KIU],$row[EXPIRED_KIU],$row[NO_STNK],$row[EXPIRED_STNK],$row[USER_ENTRY], $row[DATE_ENTRY]);
		} else if($q == 'list_truck') 
		{
			$aksi="<button onclick='del(\"".$row[TID]."\")'><img src=".HOME."images/del2.png></button>";
			$responce->rows[$i]['id']=$row[TID];
			$responce->rows[$i]['cell']=array($aksi, $row[TID],$row[TRUCK_NUMBER],$row[NO_STNK],$row[EXPIRED_STNK]);
		}

		$i++;
	}
	echo json_encode($responce);
	die();
}
?>