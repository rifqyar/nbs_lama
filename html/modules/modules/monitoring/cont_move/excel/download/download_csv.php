<?php
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Movement_Export.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>


 <?php 
$id_vessel = $_GET["id_vessel"];
$id_vs	=	$GET["ID_VS"];
$db  = getDB();
$rows = "SELECT B.VESSEL, B.VOYAGE_IN, B.VOYAGE_OUT, A.NO_UKK, A.ID_CONT , A.SIZE_, A.TYPE_, A.STATUS, A.WEIGHT, A.CLASS_, A.TEMP, A.POD, A.ID_STATUS, A.GATE_IN,
 A.GATE_OUT, A.PLACEMENT, A.PLUG_IN, A.PLUG_OUT, A.STAT_SEGEL, A.CONSIGNEE, A.EMKL, A.POS_CY, A.NPE, A.NO_SEAL, A.OPERATOR_SHIP 
 FROM MX_RBM_DETAIL A, MX_RBM_HEADER B WHERE A.NO_UKK= '$id_vs' AND A.REMARK_='R'";

$data = $db->query($rows);
$data_ = $data->getAll();

// create a file pointer connected to the output stream
//$output = fopen('php://output', 'w');

// output the column headings
//fputcsv($output,array('No Container', 'Size', 'Type'));

foreach ($data_ as $row){
$csvFile = $row['NO_UKK'].",". $row['SIZE_'].",". $row['TYPE_'].",". $row['STATUS'].",". $row['WEIGHT'].",". $row['CLASS_'].",". $row['TEMP'].",". $row['POD'].",". $row['ID_STATUS'].",". $row['GATE_IN'].",". $row['GATE_OUT'].",". $row['PLACEMENT'].",". $row['PLUG_IN'].",". $row['PLUG_OUT'].",". $row['STAT_SEGEL'].",". $row['CONSIGNEE'].",". $row['EMKL'].",". $row['POS_CY'].",". $row['NPE'].",". $row['NO_SEAL'].",". $row['OPERATOR_SHIP']."\r\n";
}


echo $csvFile;

?>

<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<title>CETAK CONTAINER MOVEMENT EXPORT</title>

<div class="art-post-body">
<div class="art-post-inner art-article">
<h2 class="art-postheader">
</h2>

<div class="art-postcontent">
 
                

		<h2 align='center'>Container Movement Export <?echo $row['VESSEL'] ?>&nbsp;&nbsp: <?echo $row['VOYAGE_IN'] ?>/<?echo $row['VOYAGE_OUT'] ?></H2> 
		
			


</div>
</div>