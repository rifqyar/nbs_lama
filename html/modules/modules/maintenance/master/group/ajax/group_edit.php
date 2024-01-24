<?php
$id = $_GET['id'];
$db = getDB();
$query="SELECT * FROM TB_GROUP WHERE ID_GROUP='".$id."'";
if($res = $db->query($query))
	$row = $res->fetchRow();
?>

<script type="text/javascript">
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_group').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_group"></div>');
	});
});
</script>

<br>
		<table>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
			<tr>
				<td class="form-field-caption" align="right">ID Group</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="id_group" id="id_group" size="5" value="<?=$id?>" ReadOnly />
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Nama Group</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="nama_group" id="nama_group" size="30" value="<?=$row[NAME_GROUP]?>" />
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="right">
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