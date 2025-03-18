<?php
$id = $_GET['id'];
$db = getDB();
$query="SELECT * FROM TB_MENU WHERE ID_MENU=".$id."";
if($res = $db->query($query))
	$row = $res->fetchRow();
?>

<!-- dual list box-->
<script type="text/javascript" src="<?=HOME?>js/jquery.dualListBox-1.3.min.js"></script>
<script type="text/javascript">
$(function() {
	$.configureBoxes();
})

$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_menu').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_menu"></div>');
	});
	$("#menu_order").keydown(function(event){
		// Allow: backspace, delete, tab, escape, and enter
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
			// Allow: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) || 
			// Allow: home, end, left, right
			(event.keyCode >= 35 && event.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
		}
		else {
			// Ensure that it is a number and stop the keypress
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault(); 
			}   
		}
	});
});
</script>

		<table>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Nama Menu</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="menu" id="menu" size="30" value="<?=$row[MENU]?>" />
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Otorisasi</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<!--<input type="text" name="otorisasi" id="otorisasi" size="9" maxlength="9" value="<?=$row[OTORISASI]?>" />-->
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="center" colspan="3">
					<table>
						<tr>
							<td align="center">
								Daftar Group<br/>
								Filter: <input type="text" id="box1Filter" /><button type="button" id="box1Clear">X</button><br />
								<select id="box1View" name="listnya" multiple="multiple" style="height:120px;width:200px;">
<?php
$arr_group = str_split($row[OTORISASI]);
$query="SELECT ID_GROUP, NAME_GROUP FROM TB_GROUP ORDER BY ID_GROUP";
if($res_group = $db->query($query))
	while ($row_group = $res_group->fetchRow()) {
		$ada=false;
		for($i=0; $i < count($arr_group); $i++)
			if($arr_group[$i]==$row_group[ID_GROUP]) {
				$ada=true;
				continue;
			}
		if(!$ada) {
?>
									<option value="<?=$row_group[ID_GROUP]?>"><?=$row_group[NAME_GROUP]?></option>
<?php
		}
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
								Otorisasi Group<br/>
								Filter: <input type="text" id="box2Filter" /><button type="button" id="box2Clear">X</button><br />
								<select id="box2View" name="otorisasi" multiple="multiple" style="height:120px;width:200px;">
<?php
for($i=0; $i < count($arr_group); $i++) {
	$query="SELECT ID_GROUP, NAME_GROUP FROM TB_GROUP WHERE ID_GROUP='".$arr_group[$i]."'";
	if($res_group2 = $db->query($query))
		if ($row_group2 = $res_group2->fetchRow()) {
?>
									<option value="<?=$row_group2[ID_GROUP]?>"><?=$row_group2[NAME_GROUP]?></option>
<?php
		}
}
?>
								</select><br/>
								<span id="box2Counter" class="countLabel"></span><select id="box2Storage"></select>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">URL</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="url" id="url" size="30" value="<?=$row[LINKNYA]?>" />
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Parent ID</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="parent_id" name="parent_id">
						<option value='0'>0 - Menu Utama</value>
<?php
$query="SELECT ID_MENU, MENU FROM TB_MENU ORDER BY ID_MENU";
if($res_menu = $db->query($query))
	while ($row_menu = $res_menu->fetchRow()) {
		if ($row_menu[ID_MENU]==$row[PARENT_ID])
			echo '<option value="'.$row_menu[ID_MENU].'" selected>'.$row_menu[ID_MENU].' - '.$row_menu[MENU].'</option>';
		else
			echo '<option value="'.$row_menu[ID_MENU].'">'.$row_menu[ID_MENU].' - '.$row_menu[MENU].'</option>';
	}
?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Urutan Menu (Order)</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="menu_order" id="menu_order" size="2" value="<?=$row[MENU_ORDER]?>" />
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>
					<input type="button" name="Edit User" value="&nbsp;Edit&nbsp;" onclick="valid_edit('<?=$id?>')"/>
				</td>
			</tr>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
		</table>		