<script type="text/javascript">
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#add_user').dialog('destroy').remove();
		$('#mainform').append('<div id="add_user"></div>');
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
				<td class="form-field-caption" align="right">Nama</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="nama" id="nama" size="30"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">NIPP</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="nipp" id="nipp" size="9" maxlength="9"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Divisi</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="divisi" id="divisi" size="30" />
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Jabatan</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="jabatan" id="jabatan" size="30" />
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Username</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="username" id="username" size="20"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Password</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="password" name="password" id="password" size="20"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Group</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="group" name="group">
<?php
$db = getDB();
$query="SELECT ID_GROUP,NAME_GROUP FROM TB_GROUP ORDER BY ID_GROUP";
if($res = $db->query($query))
	while ($row = $res->fetchRow())
		echo '<option value="'.$row[ID_GROUP].'">'.$row[NAME_GROUP].'</option>';
?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Aktif</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="aktif" name="aktif">
						<option value="1">Ya</option>
						<option value="0">Tidak</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="right">
					<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>
					<input type="button" name="Insert User" value="&nbsp;Insert&nbsp;" onclick="valid_entry()"/>
				</td>
			</tr>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
		</table>		