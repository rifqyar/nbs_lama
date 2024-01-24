<?php
$tgl=date('d F Y H:i');
$tanggal=date("dmY");
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Movement_Import-".$tanggal.".xls");
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

$date=date('d F Y H:i');
$data_jum = 10;
$jum_data_page = 18;	
$jum_page = ceil($data_jum/$jum_data_page);	
$no_ukk=$_GET["id_vessel"];
$db = getDB();
//debug($no_ukk);die;
//print_r ($list_det_ukk); die;
//$no_ukk='2';
$query_h	= 	"SELECT B.VESSEL, 
						B.VOYAGE_IN, 
						B.VOYAGE_OUT, 
						A.ID_CONT,
						A.NO_UKK, 
						A.SIZE_, 
						A.TYPE_, 
						A.STATUS, 
						A.WEIGHT, 
						A.CLASS_, 
						A.TEMP, 
						A.POD, 
						A.ID_STATUS, 
						TO_CHAR (A.GATE_IN,'DD/MM/YYYY HH24:MI:SS') GATE_IN,
						TO_CHAR (A.GATE_OUT,'DD/MM/YYYY HH24:MI:SS') GATE_OUT, 
						TO_CHAR (A.PLACEMENT,'DD/MM/YYYY HH24:MI:SS')PLACEMENT, 
						TO_CHAR (A.PLUG_IN,'DD/MM/YYYY HH24:MI:SS') PLUG_IN, 
						TO_CHAR (A.PLUG_OUT,'DD/MM/YYYY HH24:MI:SS') PLUG_OUT, 
						A.STAT_SEGEL, 
						A.CONSIGNEE, 
						A.EMKL, 
						A.POS_CY, 
						A.NPE, 
						A.NO_SEAL, 
						A.OPERATOR_SHIP,
						A.REMARK_CONTAINER,
						A.BLOK,
						A.SLOT,
						A.ROW_,
						A.TIER
				FROM 
						MX_RBM_DETAIL A, MX_RBM_HEADER B 
				WHERE 
						A.NO_UKK= '$no_ukk' AND A.REMARK_='D'
						AND A.NO_UKK=B.NO_UKK";

				
$result_h   = $db->query($query_h);
$rows    = $result_h->fetchrow();
$ves_id 	= $rows['VESSEL'];
$voy_in 	= $rows['VOYAGE_IN'];
$voy_out 	= $rows['VOYAGE_OUT'];
$no_cont	= $rows['ID_CONT'];
$size		= $rows['SIZE_'];
$type		= $rows['TYPE_'];
$status		= $rows['STATUS'];
$weight		= $rows['WEIGHT'];
$class		= $rows['CLASS_'];
$temp		= $rows['TEMP'];
$pod		= $rows['POD'];
$status_id	= $rows['ID_STATUS'];
$gatein		= $rows['GATE_IN'];
$gateout	= $rows['GATE_OUT'];
$plcmt		= $rows['PLACEMENT'];
$plugin		= $rows['PLUG_IN'];
$plugout	= $rows['PLUG_OUT'];
$rs			= $rows['STAT_SEGEL'];
$cons		= $rows['CONSIGNEE'];
$emkl		= $rows['EMKL'];
$pcy		= $rows['POS_CY'];
$npe		= $rows['NPE'];
$seal		= $rows['NO_SEAL'];
$os			= $rows['OPERATOR_SHIP'];
$rc			= $rows['REMARK_CONTAINER'];
$blk		= $rows['BLOK'];
$slt		= $rows['SLOT'];
$rw			= $rows['ROW_'];
$tier		= $rows['TIER'];


 ?>

		<table>
		<tr><th align="center" colspan="24" face="tahoma"><h3>CONTAINER MOVEMENT IMPORT </h3></th></tr>&nbsp;
		<tr><td align="center" colspan="24" size="6" face="tahoma"><b><?echo $ves_id?> / <? echo $voy_in?> - <? echo $voy_out?> </b>
		</td></tr>
		<tr><td align="center" colspan="24" size="10" face="tahoma"><b>Date/Time :<?echo $date?></b>
		</td></tr>
		</table>&nbsp;
		<table width="200" border="1">
		  <tr>
			<th width="100"><font size="2" face="tahoma" >No</font></th>
			<th width="100"><font size="2" face="tahoma" >No UKK </font></th>
			<th width="100"><font size="2" face="tahoma" >No Container</font></th>
			<th width="100"><font size="2" face="tahoma" >Size</font></th>
			<th width="100"><font size="2" face="tahoma" >Type</font></th>
			<th width="100"><font size="2" face="tahoma" >Status</font></th>
			<th width="100"><font size="2" face="tahoma" >Weight</font></th>
			<th width="100"><font size="2" face="tahoma" >Class</font></th>
			<th width="100"><font size="2" face="tahoma" >Temp</font></th>
			<th width="100"><font size="2" face="tahoma" >POD</font></th>
			<th width="100"><font size="2" face="tahoma" >Status Code</font></th>
			<th width="100"><font size="2" face="tahoma" >Gate In</font></th>
			<th width="100"><font size="2" face="tahoma" >Gate Out</font></th>
			<th width="100"><font size="2" face="tahoma" >Placement</font></th>
			<th width="100"><font size="2" face="tahoma" >Plug In</font></th>
			<th width="100"><font size="2" face="tahoma" >Plug Out</font></th>
			<th width="100"><font size="2" face="tahoma" >Red Seal</font></th>
			<th width="100"><font size="2" face="tahoma" >Consignee</font></th>
			<th width="100"><font size="2" face="tahoma" >EMKL</font></th>
			<th width="100"><font size="2" face="tahoma" >POS CY</font></th>
			<th width="100"><font size="2" face="tahoma" >NPE</font></th>
			<th width="100"><font size="2" face="tahoma" >No Seal</font></th>
			<th width="100"><font size="2" face="tahoma" >Opertor Ship</font></th>
			<th width="100"><font size="2" face="tahoma" >Remark Container</font></th>
		  </tr>
		  <!--  -->
		  <?php
			$query_h="SELECT B.VESSEL, 
						B.VOYAGE_IN, 
						B.VOYAGE_OUT, 
						A.ID_CONT,
						A.NO_UKK, 
						A.SIZE_, 
						A.TYPE_, 
						A.STATUS, 
						A.WEIGHT, 
						A.CLASS_, 
						A.TEMP, 
						A.POD, 
						A.ID_STATUS, 
						TO_CHAR (A.GATE_IN,'DD/MM/YYYY HH24:MI:SS') GATE_IN,
						TO_CHAR (A.GATE_OUT,'DD/MM/YYYY HH24:MI:SS') GATE_OUT, 
						TO_CHAR (A.PLACEMENT,'DD/MM/YYYY HH24:MI:SS')PLACEMENT, 
						TO_CHAR (A.PLUG_IN,'DD/MM/YYYY HH24:MI:SS') PLUG_IN, 
						TO_CHAR (A.PLUG_OUT,'DD/MM/YYYY HH24:MI:SS') PLUG_OUT, 
						A.STAT_SEGEL, 
						A.CONSIGNEE, 
						A.EMKL, 
						A.POS_CY, 
						A.NPE, 
						A.NO_SEAL, 
						A.OPERATOR_SHIP,
						A.REMARK_CONTAINER,
						A.BLOK,
						A.SLOT,
						A.ROW_,
						A.TIER
				FROM 
						MX_RBM_DETAIL A, MX_RBM_HEADER B 
				WHERE 
						A.NO_UKK= '$no_ukk' AND A.REMARK_='D'
						AND A.NO_UKK=B.NO_UKK
						ORDER BY ID_CONT";
				$db = getDB();
				$result_h   = $db->query($query_h);
				while ($row    = $result_h->fetchrow()){
			
				?>
				<tr>
					<td><?php echo $c=$c+1;?></td>
					<td><?php echo $row['NO_UKK'];?></td>
					<td><?php echo $row['ID_CONT'];?></td>
					<td><?php echo $row['SIZE_'];?></td>
					<td><?php echo $row['TYPE_'];?></td>
					<td><?php echo $row['STATUS'];?></td>
					<td align="left"><?php echo $row['WEIGHT'];?></td>
					<td><?php echo $row['CLASS_'];?></td>
					<td><?php echo $row['TEMP'];?></td>
					<td><?php echo $row['POD'];?></td>
					<td align="center"><?php echo $row['ID_STATUS'];?></td>
					<td align="left"><?php echo $row['GATE_IN'];?></td>
					<td align="left"><?php echo $row['GATE_OUT'];?></td>
					<td align="left"><?php echo $row['PLACEMENT'];?></td>
					<td align="left"><?php echo $row['PLUG_IN'];?></td>
					<td align="left"><?php echo $row['PLUG_OUT'];?></td>
					<td><?php echo $row['STAT_SEGEL'];?></td>
					<td><?php echo $row['CONSIGNEE'];?></td>
					<td><?php echo $row['EMKL'];?></td>
					<td><?php echo $row['BLOK'];?>.<?php echo $row['SLOT'];?>.<?php echo $row['ROW_'];?>.<?php echo $row['TIER'];?></td>
					<td align="left"><?php echo $row['NPE'];?></td>
					<td><?php echo $row['NO_SEAL'];?></td>
					<td><?php echo $row['OPERATOR_SHIP'];?></td>
					<td><?php echo $row['REMARK_CONTAINER'];?></td>
			
				</tr>
				<?php
			}
			?>
		  <!--  -->
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		  <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		  </tr>
		</table>

</body>
</html>
