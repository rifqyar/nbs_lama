<?php
$db 	= getDB("storage"); 
$id_rule = $_POST["ID_RULE"];
$id_kategori = $_POST["ID_KATEGORI"];

$query_view = "SELECT * FROM MENU WHERE ID_KATEGORI = '$id_kategori'";
$res_view = $db->query($query_view);
$view_rule = $res_view->getAll();
	
$rule = $db->query("SELECT * FROM ROLE WHERE ID = '$id_rule'");
$rules = $rule->fetchRow();

$kate = $db->query("SELECT * FROM KATEGORI_MENU WHERE ID = '$id_kategori'");
$kates = $kate->fetchRow();

?>
<div id="add">
	<table>
		<tr>
			<td>Rule</td>
			<td>:</td>
			<td><input type="text" id="rule" value="<?=$rules["NAMA_ROLE"];?>" readonly="readonly"/>
			<input type="hidden" id="id_rule_do" value="<?=$rules["ID"];?>" readonly="readonly"/></td>
			<td>Kategori</td>
			<td>:</td>
			<td><input type="text" id="kate" value="<?=$kates["NAME"];?>" readonly="readonly"/>
			<input type="hidden" id="id_kate_do" value="<?=$kates["ID"];?>" readonly="readonly"/></td>
		</tr>
		<tr>
			<td>Menu</td>
			<td>:</td>
			<td><select id="id_menu_c" onchange="load_submenu()">
				<?php foreach($view_rule as $row) {?>
					<option value="<?=$row["ID"];?>"><?=$row["NAME"];?></option>
				<?php }?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Submenu</td>
			<td>:</td>
			<td>
				<div id="submenu_c"></div>
			</td>
			
		</tr>
	</table>
</div>