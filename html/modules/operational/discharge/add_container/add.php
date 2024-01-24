<?php

	$js=$_GET['container'];	
?>

<div>
	Add Container Export
	<br>
	<br>
	<table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" id="list_cont_dev">
	<tr>
		<th class="grid-header">No Container</th>
		<th class="grid-header">Size</th>
		<th class="grid-header">Type</th>
	</tr>
	<tr>
		<td><input type="text" name="no_conts" id="no_conts" value="<?=$js;?>"/></td>
		<td><select name="size_s" id="size_s">
				<option value="20">20"</OPTION>
				<option value="40">40"</OPTION>
				<option value="45">45"</OPTION>
			</select>
		</td>
		<td><select name="tipe_s" id="tipe_s">
				<option value="DRY">DRY</OPTION>
				<option value="HQ">HQ</OPTION>
				<option value="TNK">TNK</OPTION>
			</select></td>
	</tr>
	<TR>
	<TD>
	<br>
	<input type="button" id="relocate" name="relocate" value=" Add this container " onclick="add_cont_s()"/></TD></TR>
	</table>
	
</div>