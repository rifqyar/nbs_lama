<?php
$db = getDB('dbint');
$call_sign = $_GET['call_sign'];
$voyage_in = $_GET['voyage_in'];
$voyage_out = $_GET['voyage_out'];

//Untuk Penamaan File
$query_file = "SELECT VESSEL FROM M_VSB_VOYAGE WHERE CALL_SIGN='$call_sign' and VOYAGE_IN= '$voyage_in' and voyage_out = '$voyage_out'";
//echo $query_file;
//die();
$result_file = $db->query($query_file)->fetchRow();
$nama_kapal = urlencode($result_file[VESSEL]);
//echo $nama_kapal;
//die();

$query_p = "SELECT 
					POD,
					POL,
					SUBSTR (LOKASI_BP, 2, 2) AS STOWAGE,
					LOKASI_BP AS BAY,
					NO_CONTAINER,
					CASE
						  WHEN ISO_CODE LIKE '%2%' THEN '20'
						  WHEN ISO_CODE LIKE '%4%' THEN '40'
					END
					  SIZE_,
					WEIGHT,
					ISO_CODE,
					STATUS,
					'HEIGHT',
					CARRIER,
					IMO,
					to_char(TEMP,'000')||':CEL' as TEMP,
					UN_NUMBER,
					OVER_LEFT,
					OVER_RIGHT,
					OVER_FRONT,
					OVER_REAR,
					OVER_TOP,
					FPOD
			FROM 
					M_ETV_COLDLTCONT
			WHERE 
					call_sign = '$call_sign'
					AND voyage_in = '$voyage_in' 
					AND voyage_out = '$voyage_out'";

//echo $query_p;
$result			= $db->query($query_p);
$row			= $result->getAll();

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$nama_kapal.$voyage_in.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<table border="1">
    <tr>
        <th>POD</th>
        <th>POL</th>
        <th>OPTIONAL PORT</th>
        <th>BAY</th>
        <th>SLOT</th>
        <th>CONTAINER</th>
        <th>SIZE</th> 
        <th>WEIGHT</th>
        <th>ISO CODE</th>
        <th>GROUP TYPE</th>
        <th>STATUS</th>
        <th>HEIGHT</th>
        <th>CARRIER</th>
        <th>IMO</th>
        <th>TEMP</th>
        <th>FPOD</th>
        <th>COMMODITY</th>
        
        <th>OH</th>
        <th>OL</th>
        <th>OR</th>
        <th>OF</th>
		<th>OB</th>
		
        
        <th>HANDLING INSTRUCTION</th>
        <th>SPECIAL INSTRUCTION</th>
        <th>CONTAINER LOADING REMARK</th>
        <th>HAZARD CODE</th>
		<th>IMDG PAGE NO</th>
		<th>UN NO</th>
		<th>FLASH POINT</th>
		<th>MEASURE UNITS</th>
		<th>PACKING GROUP</th>
		
		<th>EMRG SCHEDULE NO</th>
		<th>MEDICAL FIRST AID GUIDE NO</th>
		<th>HAZARD CARD UPPER</th>
		
		<th>HAZARD CARD LOWER</th>
		<th>DANGEROUS GOOD LABEL 1</th>
		<th>DANGEROUS GOOD LABEL 2</th>
		<th>DANGEROUS GOOD LABEL 3</th>
		<th>Dangerous goods additional info</th>
		<th>NET Weight of dangerous goods</th>
		<th>DG Reference</th>
		
		<th>Dangerous</th>
		<th>Temp</th>
		<th>Temp</th>
		<th>Maximum</th>        
    </tr>
    
<?php foreach($row as $datanya){
$over_left = $datanya[OVER_LEFT]."CMT";
$over_right = $datanya[OVER_RIGHT]."CMT";
$over_front = $datanya[OVER_FRONT]."CMT";
$over_back = $datanya[OVER_REAR]."CMT";
$over_top = $datanya[OVER_TOP]."CMT";

//Untuk Temperatur
if ($datanya[TEMP]==":CEL")
{
	$temp = "";
}	
else
{
$temp = $datanya[TEMP];
}
//Akhir Case Temperature

//Query Untuk Type Container, Height, Size
$iso = "SELECT 
				size_, 
                type_, 
                h_iso as height          
		FROM 
                bl_master_iso_code
		WHERE 
                ISO_CODE = '$datanya[ISO_CODE]'";   
					
$result_iso = $db->query($iso)->fetchRow();					
					
$size = $result_iso[SIZE_];
$type = $result_iso[TYPE_];
$height = $result_iso[HEIGHT];

if(($datanya[OVER_LEFT]) or ($datanya[OVER_RIGHT]) or ($datanya[OVER_FRONT]) or ($datanya[OVER_REAR]) or ($datanya[OVER_TOP])){
$height = "OOG";
}		

//Case Untuk Container Group TYPE

//Untuk Flat Track non Hazard
if($result_iso[TYPE_]=="FLT")
	{
		$gt="PL";
	}

//Untuk Tipe Dry	
else if($result_iso[TYPE_]=="DRY"){

		$gt="GP";
	}
	
else if($result_iso[TYPE_]=="HQ"){

		$gt="GP";
	}

else if($result_iso[TYPE_]=="RFR"){

		$gt="RF";
	}	

else if($result_iso[TYPE_]=="OT"){

		$gt="UT";
	}		

//Untuk Tank
else if($result_iso[TYPE_]=="TNK"){

		$gt="TK";
	}
?>

<tr>
	<td><?php echo $datanya[POD]; ?></td>
	<td><?php echo $datanya[POL]; ?></td>
	<td></td>
	<td><?php echo "0".$datanya[STOWAGE]; ?></td>
	<td><?php echo $datanya[BAY]; ?></td>
	<td><?php echo $datanya[NO_CONTAINER]; ?></td>	
	<td><?php echo $size; ?></td>
	
	<td><?php echo $datanya[WEIGHT]; ?></td>
	<td><?php echo $datanya[ISO_CODE]; ?></td>
	<td><?php echo $gt; ?></td>
	<td><?php echo $datanya[STATUS]; ?></td>
	<td><?php echo $height; ?></td>
	
	<td><?php echo $datanya[CARRIER]; ?></td>
	<td><?php echo $datanya[IMO]; ?></td>
	<td><?php echo $temp; ?></td>
	<td><?php echo $datanya[FPOD]; ?></td>
	<td></td>
	
	<!-- Untuk Over TOp -->
	<?php if ($datanya[OVER_TOP]) {?>	
	<td><?php echo $over_top;?></td>
	<?php } else {?>	
	<td></td>
	<?php } ?>
	<!-- AKhir Over TOp -->
	
    <!-- Untuk Over Right -->  
	<?php if ($datanya[OVER_RIGHT]){?>
	
    <td><?php echo $over_right; ?></td>
	<?php } else {?>
	<td></td>
	<?php } ?>
	<!-- Akhir Over Right -->	
	
	<!-- Untuk Over Left -->  
	<?php if ($datanya[OVER_LEFT]){?>
	
    <td><?php echo $over_left; ?></td>
	<?php } else {?>
	<td></td>
	<?php } ?>
	<!-- Akhir Over Left -->
	
	<!-- Untuk Over Front -->  
	<?php if ($datanya[OVER_FRONT]){?>
	
    <td><?php echo $over_front; ?></td>
	<?php } else {?>
	<td></td>
	<?php } ?>
	<!-- Akhir Over Front -->
	
	
	<!-- Untuk Over Back -->  
	<?php if ($datanya[OVER_BACK]){?>
	
    <td><?php echo $over_back; ?></td>
	<?php } else {?>
	<td></td>
	<?php } ?>
	<!-- Akhir Over Back -->
	
	<td></td>
	<td></td>
	<td></td>
    <td><?php echo $datanya[IMO]; ?></td>
	
	<?php if ($datanya[IMO]) { ?>
	<td>III</td>
	<?php } else {?>
	<td></td>
	<?php } ?>
	 
	
	<td><?php echo $datanya[UN_NUMBER]; ?></td>
	<td></td>
    <td></td>
	<td></td>
    <td></td>
	
	<td></td>
    <td></td>
	<td></td>
    <td></td>
	<td></td>
    <td></td>
	<td></td>
    <td></td>
	
	
	<td></td>
    <td></td>
	<td></td>
    <td></td>
	<td></td>	
        
</tr>

<?php } ?>   
</table>

