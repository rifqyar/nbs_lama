<script type="text/javascript" src="<?=HOME?>js/jquery.dualListBox-1.3.min.js"></script>
<script type="text/javascript">
$(function() {
	$.configureBoxes();
})
</script>

<div id="loadobx">
<?
	$setopt = $_GET['setoption'];
	$id_plp = $_GET['idplp'];
	$noukk  = $_GET['noukk'];
	$db = getDB();
	
	$jmlcek = "SELECT COUNT(*) JML FROM OBX_APPROVAL_D WHERE TRIM(ID_PLP) = TRIM('$id_plp') AND FLAG_SP2OBX = '0'";
	$datacont = $db->query($jmlcek)->fetchRow();
	
	if($setopt==1)
	{
?>
		<table>
			<tr>
				<td align="center">
				List Container (<?=$datacont['JML']?>)<br/>
				Filter: <input type="text" id="box1Filter" /><button type="button" id="box1Clear">X</button><br/><br/>
						<select id="box1View" name="listnya" multiple="multiple" style="height:120px;width:200px;">
						<?php								
								/*$query = "SELECT ID_BARANG, LINE_NUMBER FROM OBX_APPROVAL_D 
										  WHERE TRIM(ID_PLP) = TRIM('$id_plp') AND FLAG_SP2OBX = '0' ORDER BY LINE_NUMBER ASC";
								*/
								$query = "  SELECT DISTINCT ID_BARANG, LINE_NUMBER
											FROM OBX_APPROVAL_D
										   WHERE     TRIM (ID_PLP) = TRIM ('$id_plp')
												 AND FLAG_SP2OBX = '0'
												 AND ID_BARANG NOT IN (SELECT a.NO_CONTAINER
																		 FROM REQ_DELIVERY_D a, REQ_DELIVERY_H b
																		WHERE a.NO_REQ_DEV = b.ID_REQ AND b.NO_UKK = '$noukk')
										ORDER BY LINE_NUMBER ASC";
								if($res = $db->query($query))
									while ($row = $res->fetchRow()) {
						?>
									<option value="<?=$row['ID_BARANG']?>"><?=$row['ID_BARANG']?></option>
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
				Selected Container<br/>
				Filter: <input type="text" id="box2Filter" /><button type="button" id="box2Clear">X</button><br/><br/>
						<select id="box2View" name="approval" multiple="multiple" style="height:120px;width:200px;"></select><br/>
						<span id="box2Counter" class="countLabel"></span><select id="box2Storage"></select>
				</td>
			</tr>
		</table>
<?
	}
	else
	{
?>
		<table>
			<tr>
				<td align="center">
				List Container (0)<br/>
				Filter: <input type="text" id="box1Filter" /><button type="button" id="box1Clear">X</button><br/><br/>
						<select id="box1View" name="listnya" multiple="multiple" style="height:120px;width:200px;">						
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
				Selected Container<br/>
				Filter: <input type="text" id="box2Filter" /><button type="button" id="box2Clear">X</button><br/><br/>
						<select id="box2View" name="approval" multiple="multiple" style="height:120px;width:200px;"></select><br/>
						<span id="box2Counter" class="countLabel"></span><select id="box2Storage"></select>
				</td>
			</tr>
		</table>
<?
	}
?>	
</div>