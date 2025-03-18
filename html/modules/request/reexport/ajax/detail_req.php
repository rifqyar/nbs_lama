<?php
	$req=$_GET['id'];
	$oukk=$_GET['oukk'];
	$nukk=$_GET['nukk'];
	$oi=$_GET['oi'];
	
	$db2 = getDB('dbint');
	$query="select VESSEL, VOYAGE_IN from M_VSB_VOYAGE where ID_VSB_VOYAGE='$oukk'";
	$hasil=$db2->query($query);
	$row=$hasil->fetchRow();
	
	$vessel=$row[VESSEL];
	$vin=$row[VOYAGE_IN];
?>
<script type="text/javascript" src="<?=HOME?>js/jquery.dualListBox-1.3.min.js"></script>
<script>
$(document).ready(function() 
{
	$.configureBoxes();
});
</script>

<table>
<tr>
		<td align="center">
			<b>Daftar Container <?=$oukk?></b><br/>
			Filter: <input type="text" id="box1Filter" /><button type="button" id="box1Clear">X</button><br />
			<select id="box1View" name="listnya" multiple="multiple" style="height:200px;width:200px;">
<?php
$db = getDB();
$query="SELECT 
				NO_CONTAINER, SIZE_CONT AS SIZE_, 
				TYPE_CONT AS TYPE_, STATUS, HZ, HEIGHT, 
				E_I, '' AS KD_BARANG 
		FROM 
				M_CYC_CONTAINER@dbint_link 
		WHERE 
				E_I='I' 
				AND upper(CONT_LOCATION)='YARD' 
				AND ACTIVE='Y' 
				AND VESSEL='$vessel' 
				and VOYAGE_IN='$vin' 
				AND NO_CONTAINER NOT IN 
										(SELECT 
												NO_CONTAINER 
										FROM 
												REQ_REEXPORT_D 
										WHERE 
												ID_REQ='$req') 
				AND NO_CONTAINER NOT IN 
										(SELECT 
												NO_CONTAINER 
										FROM 
												REQ_DELIVERY_D WHERE 
												NO_UKK='$oukk')";
//echo $query;

if($res = $db->query($query))
while ($row = $res->fetchRow()) {
	$val_cont = $row[NO_CONTAINER]."^^".$row[SIZE_]."^^".$row[TYPE_]."^^".$row[STATUS]."^^".$row[HZ]."^^".$row[HEIGHT]."^^".trim($row[E_I])."^^".trim($row[KD_BARANG]);
?>
				<option value="<?=$val_cont?>"><?=$row[NO_CONTAINER]?> ~ <?=$row[SIZE_]?>-<?=$row[TYPE_]?>-<?=$row[STATUS]?></option>
<?php
}
?>
			</select><br />
			<span id="box1Counter" class="countLabel"></span><select id="box1Storage"></select>
		</td>
		<td align="center"><br/><br/>
			<button id="to2" type="button">&nbsp;>&nbsp;</button><br/>
			<button id="allTo2" type="button">&nbsp;>>&nbsp;</button><br/>
			<button id="allTo1" type="button">&nbsp;<<&nbsp;</button><br/>
			<button id="to1" type="button">&nbsp;<&nbsp;</button>
		</td>
		<td align="center">
			<b>Reexport Ke <?=$nukk?></b><br/>
			Filter: <input type="text" id="box2Filter" /><button type="button" id="box2Clear">X</button><br />
			<select id="box2View" name="list_cont" multiple="multiple" style="height:200px;width:200px;">
<?php
$query="SELECT NO_CONTAINER, SIZE_CONT, TYPE_CONT, STATUS_CONT, HZ, HEIGHT, E_I, ID_CONT FROM REQ_REEXPORT_D WHERE ID_REQ='$req' ORDER BY URUT";
if($res = $db->query($query))
while ($row = $res->fetchRow()) {
	$val_cont = $row[NO_CONTAINER]."^^".$row[SIZE_]."^^".$row[TYPE_]."^^".$row[STATUS_]."^^".$row[HZ]."^^".$row[HEIGHT]."^^".trim($row[E_I])."^^".trim($row[ID_CONT]);
?>
				<option value="<?=$val_cont?>"><?=$row[NO_CONTAINER]?> ~ <?=$row[SIZE_]?>-<?=$row[TYPE_]?>-<?=$row[STATUS_]?></option>
<?php
}
?>
			</select><br/>
			<span id="box2Counter" class="countLabel"></span><select id="box2Storage"></select>
		</td>
</tr>
<tr>
	<td colspan="3"><button onclick="final_add('<?=$req;?>','<?=$oukk;?>','<?=$nukk;?>')"><img src="<?=HOME;?>images/sg3.png"/></button></td>
</tr>
</table>