<?php 
function xlsBOF() {
echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
return;
}

function xlsEOF() {
echo pack("ss", 0x0A, 0x00);
return;
}

function xlsWriteNumber($Row, $Col, $Value) {
echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
echo pack("d", $Value);
return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
$L = strlen($Value);
echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
echo $Value;
return;
}

include "../conf.php";


$querydetail = "SELECT 
						B.VESSEL, 
						B.VOYAGE_IN, 
						B.VOYAGE_OUT, 
						A.NO_UKK, 
						A.ID_CONT , 
						A.SIZE_, 
						A.TYPE_, 
						A.STATUS, 
						A.WEIGHT, 
						A.CLASS_, 
						A.TEMP, 
						A.POD, 
						A.ID_STATUS, 
						A.GATE_IN,
						A.GATE_OUT, 
						A.PLACEMENT, 
						A.PLUG_IN, 
						A.PLUG_OUT, 
						A.STAT_SEGEL, 
						A.CONSIGNEE, 
						A.EMKL, 
						A.POS_CY, 
						A.NPE, 
						A.NO_SEAL, 
						A.OPERATOR_SHIP 
				FROM 
						MX_RBM_DETAIL A, MX_RBM_HEADER B 
				WHERE 
						A.NO_UKK= B.NO_UKK AND A.REMARK_='R'";
$executedetail = mysql_query($querydetail);
while($res = mysql_fetch_array($executedetail)){

	$data['NO_UKK'][] = $res['NO_UKK'];
	$data['ID_CONT'][] = $res['ID_CONT'];
	$data['SIZE_'][] = $res['SIZE_'];
	$data['TYPE_'][] = $res['TYPE_'];
	$data['STATUS'][] = $res['STATUS'];
	$data['WEIGHT'][] = $res['WEIGHT'];
	$data['CLASS_'][] = $res['CLASS_'];
	$data['TEMP'][] = $res['TEMP'];
	$data['POD'][] = $res['POD'];
	$data['ID_STATUS'][] = $res['ID_STATUS'];
	$data['GATE_IN'][] = $res['GATE_IN'];
	$data['GATE_OUT'][] = $res['GATE_OUT'];
	$data['PLACEMENT'][] = $res['PLACEMENT'];
	$data['PLUG_IN'][] = $res['PLUG_IN'];
	$data['PLUG_OUT'][] = $res['PLUG_OUT'];
	$data['STAT_SEGEL'][] = $res['STAT_SEGEL'];
	$data['CONSIGNEE'][] = $res['WEIGHT'];
	$data['EMKL'][] = $res['EMKL'];
	$data['POS_CY'][] = $res['POS_CY'];
	$data['NPE'][] = $res['NPE'];
	$data['NO_SEAL'][] = $res['NO_SEAL'];
	$data['OPERATOR_SHIP'][] = $res['OPERATOR_SHIP'];
} 

$jm = sizeof($data['NO_UKK']);
$tanggal=date("dmY");
header("Pragma: public" );
header("Expires: 0" );
header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header("Content-Type: application/force-download" );
header("Content-Type: application/octet-stream" );
header("Content-Type: application/download" );;
header("Content-Disposition: attachment; filename=Movement_Export-".$tanggal.".xls " );
header("Content-Transfer-Encoding: binary " );
xlsBOF();
xlsWriteLabel(0,3,"Data CONTAINER" );
xlsWriteLabel(2,0,"No" );
xlsWriteLabel(2,1,"No UKK" );
xlsWriteLabel(2,2,"NO CONTAINER" );
xlsWriteLabel(2,3,"SIZE" );
xlsWriteLabel(2,4,"TYPE" );
xlsWriteLabel(2,5,"STATUS" );
xlsWriteLabel(2,6,"WEIGHT" );
xlsWriteLabel(2,7,"CLASS" );
xlsWriteLabel(2,8,"TEMP" );
xlsWriteLabel(2,9,"POD" );
xlsWriteLabel(2,10,"STATUS CODE" );
xlsWriteLabel(2,11,"GATE IN" );
xlsWriteLabel(2,12,"GATE OUT" );
xlsWriteLabel(2,13,"PLACEMENT" );
xlsWriteLabel(2,14,"PLUG IN" );
xlsWriteLabel(2,15,"PLUG OUT" );
xlsWriteLabel(2,16,"RED SEAL" );
xlsWriteLabel(2,17,"CONSIGNEE" );
xlsWriteLabel(2,18,"EMKL" );
xlsWriteLabel(2,19,"POS CY" );
xlsWriteLabel(2,20,"NPE" );
xlsWriteLabel(2,21,"NO SEAL" );
xlsWriteLabel(2,22,"OPERATOR SHIP" );


$xlsRow = 2000;

for ($y=0; $y<$jm; $y++) {
	++$i;
	xlsWriteNumber($xlsRow,0,"$i" );
	xlsWriteLabel($xlsRow,1,$data['NO_UKK'][$y]);
	xlsWriteLabel($xlsRow,2,$data['ID_CONT'][$y]);
	xlsWriteLabel($xlsRow,3,$data['SIZE_'][$y]);
	xlsWriteLabel($xlsRow,4,$data['TYPE_'][$y]);
	xlsWriteLabel($xlsRow,5,$data['STATUS'][$y]);
	xlsWriteLabel($xlsRow,6,$data['WEIGHT'][$y]);
	xlsWriteLabel($xlsRow,7,$data['CLASS_'][$y]);
	xlsWriteLabel($xlsRow,8,$data['TEMP'][$y]);
	xlsWriteLabel($xlsRow,9,$data['POD'][$y]);
	xlsWriteLabel($xlsRow,10,$data['ID_STATUS'][$y]);
	xlsWriteLabel($xlsRow,11,$data['GATE_IN'][$y]);
	xlsWriteLabel($xlsRow,12,$data['GATE_OUT'][$y]);
	xlsWriteLabel($xlsRow,13,$data['PLACEMENT'][$y]);
	xlsWriteLabel($xlsRow,14,$data['PLUG_IN'][$y]);
	xlsWriteLabel($xlsRow,15,$data['PLUG_OUT'][$y]);
	xlsWriteLabel($xlsRow,16,$data['STAT_SEGEL'][$y]);
	xlsWriteLabel($xlsRow,17,$data['CONSIGNEE'][$y]);
	xlsWriteLabel($xlsRow,18,$data['EMKL'][$y]);
	xlsWriteLabel($xlsRow,19,$data['POS_CY'][$y]);
	xlsWriteLabel($xlsRow,20,$data['NPE'][$y]);
	xlsWriteLabel($xlsRow,21,$data['NO_SEAL'][$y]);
	xlsWriteLabel($xlsRow,22,$data['OPERATOR_SHIP'][$y]);
	
	
	$xlsRow++;
}
xlsEOF();
exit();