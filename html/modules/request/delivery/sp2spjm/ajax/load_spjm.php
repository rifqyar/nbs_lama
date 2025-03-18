<script type="text/javascript" src="<?=HOME?>js/jquery.dualListBox-1.3.min.js"></script>
<script type="text/javascript">
$(function() {
	$.configureBoxes();
})
</script>

<div id="loadspjm">
<?
	$setopt = $_GET['setoption'];
	$id_plp = $_GET['idplp'];
	$noukk = $_GET['noukk'];
	$db = getDB('dbportal');
	$db2 = getDB('ora');
	
	$string_query = "";
	$q_sppud = "SELECT CONT_NO FROM SPPUD WHERE PIB_NO LIKE '$id_plp'";
	$res_sppud = $db->query($q_sppud);
	while ($row = $res_sppud->fetchRow()) {
		$string_query.="'";
		$string_query.=$row['CONT_NO'];
		$string_query.="'";
		$string_query.=",";
	}
	$string_query.="''";
	
	$jml_cek="SELECT count(A.CONT_NO_BP) JML
			FROM TTD_BP_CONT A INNER JOIN TTM_BP_CONT B ON (A.BP_ID = B.BP_ID)
		   WHERE     A.STATUS_CONT = '03'
				 AND B.KD_CABANG = '01'
				 AND no_ukk LIKE '$noukk'
				 AND A.CONT_NO_BP IN ($string_query)";
	$datacont = $db2->query($jml_cek)->fetchRow();
	
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
								$query = "SELECT A.CONT_NO_BP NO_CONT
			FROM TTD_BP_CONT A INNER JOIN TTM_BP_CONT B ON (A.BP_ID = B.BP_ID)
		   WHERE     A.STATUS_CONT = '03'
				 AND B.KD_CABANG = '01'
				 AND no_ukk LIKE '$noukk'
				 AND A.CONT_NO_BP IN ($string_query)
				 ORDER BY A.CONT_NO_BP";
								if($res = $db2->query($query))
									while ($row = $res->fetchRow()) {
						?>
									<option value="<?=$row['NO_CONT']?>"><?=$row['NO_CONT']?></option>
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