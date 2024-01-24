<?php
$tgl = date('d F Y H:i');
$tanggal = date("dmY");
$no_ukk = $_GET['no_ukk'];
$vesvoy = $_GET['vesvoy'];

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=BPE_".$no_ukk.".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Untitled Document</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>

<body>
<?php
$db = getDB();
 ?>		
		<table>
		  <tr>
			<th>Discharge Port</th>
			<th>Load port</th>
			<th>Optional Port</th>
			<th>Bay</th>
			<th>Slot</th>
			<th>Container Id</th>
			<th>Size</th>
			<th>Weight</th>
			<th>Type</th>
			<th>Group Type</th>
			<th>Loaded</th>
			<th>Height</th>
			<th>Carrier</th>
			<th>Class</th>
			<th>Setting</th>
			<th>Delivery Port</th>
			<th>Commodity</th>
			<th>Over Height</th>
			<th>Over Size Left</th>
			<th>Over Size Right</th>
			<th>Over Size Front</th>
			<th>Over Size Aft</th>
			<th>Handling instructions</th>
			<th>Special Instructions</th>
			<th>Container loading remark</th>
			<th>Hazard Code</th>
			<th>IMDG Page No</th>
			<th>UN Number</th>
			<th>Flash Point</th>
			<th>Measure units</th>
			<th>Packing group</th>
			<th>Emrg schedule no</th>
			<th>Medical first aid guide no</th>
			<th>Hazard card Upper</th>
			<th>Hazard card Lower</th>
			<th>Dangerous good label 1</th>
			<th>Dangerous good label 2</th>
			<th>Dangerous good label 3</th>
			<th>Dangerous goods additional info</th>
			<th>NET Weight of dangerous goods</th>
			<th>DG Reference</th>
			<th>Dangerous goods technical name</th>
			<th>Temp Measure units</th>
			<th>Temp Minimum range</th>
			<th>Maximum range</th>			
		  </tr>
		  <!--  -->
		  <?php
			
				$query_h = "SELECT spb.ID_PEL_TUJ AS POD, 
									LPAD(spb.BAY,3,0) BAY, 
									LPAD(spb.BAY,3,0)||LPAD(spb.ROW_,2,0)||LPAD(spb.TIER_,2,0) SLOT,
									spb.NO_CONTAINER CONTAINER_ID,
									spb.SIZE_,
									spb.GROSS WEIGHT,
									spb.ISO_CODE TYPE,
									CASE
									WHEN spb.TYPE_= 'DRY' THEN 'GP'
									WHEN spb.TYPE_ = 'RFR' THEN 'RF'
									WHEN spb.TYPE_ = 'TNK' THEN 'TK'
									WHEN spb.TYPE_ = 'FLT' THEN 'PL'
									WHEN spb.TYPE_ = 'OT' THEN 'UT'
									WHEN spb.TYPE_ = 'HQ' THEN 'VH'
									END GROUP_TYPE,
									CASE
									WHEN spb.STATUS_ = 'FCL' THEN 'Full'
									WHEN spb.STATUS_ = 'MTY' THEN 'MT'
									END LOADED,
									mic.H_ISO HEIGHT,
									spb.IMO_CLASS CLASS,
									spb.CELCIUS SETTING
						FROM STW_PLACEMENT_BAY spb, MASTER_ISO_CODE mic 
						WHERE spb.ID_VS = 'WANO01120009'
							 AND spb.ISO_CODE = mic.ISO_CODE";	
				$result_h = $db->query($query_h);
				while ($row = $result_h->fetchrow()){
			
				?>
				<tr>
					<td><?php echo $row['POD'];?></td>
					<td>IDJKT</td>
					<td>&nbsp;</td>
					<td><?php echo $row['BAY'];?></td>
					<td><?php echo $row['SLOT'];?></td>
					<td><?php echo $row['CONTAINER_ID'];?></td>
					<td><?php echo $row['SIZE_'];?></td>
					<td><?php echo $row['WEIGHT'];?></td>
					<td><?php echo $row['TYPE'];?></td>
					<td><?php echo $row['GROUP_TYPE'];?></td>
					<td><?php echo $row['LOADED'];?></td>
					<td><?php echo $row['HEIGHT'];?></td>
					<td>&nbsp;</td>
					<td><?php echo $row['CLASS'];?></td>
					<td><?php echo $row['SETTING'];?></td>
					<td><?php echo $row['POD'];?></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td><?php echo $row['CLASS'];?></td>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
					<td>&nbsp;</th>
				</tr>
				<?php
			}
			?>
		  <!--  -->		  
		</table>

</body>
</html>
