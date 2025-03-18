<?php
$db = getDB("storage");
$iduser = $_GET["ID_USER"];
$idrole = $_GET["ID_ROLE"];
$query_user = "SELECT MASTER_USER.ID, ID_ROLE, NIPP, USERNAME, NAMA_LENGKAP, KELAS, JABATAN, NAMA_ROLE ROLE FROM MASTER_USER
		INNER JOIN ROLE ON MASTER_USER.ID_ROLE = ROLE.ID WHERE MASTER_USER.ID = '$iduser'";
$result = $db->query($query_user);
$rows = $result->fetchRow();

$query_rule = "SELECT * FROM ROLE";
$res_rule = $db->query($query_rule);
$rowr = $res_rule->getAll();
?>

<div id="change">
<table>
	<tr>
		<td>Nama :</td>
		<td><input type="text" id="name" value="<?php echo $rows["NAMA_LENGKAP"];?>"><input type="hidden" id="id" value="<?php echo $rows["ID"];?>"></td>		
	</tr>
	<tr>
		<td>NIPP :</td>
		<td><input type="text" id="nipp_" value="<?php echo $rows["NIPP"];?>"/></td>				
	</tr>
	<tr>
		<td>Username :</td>
		<td><input type="text" id="username" value="<?php echo $rows["USERNAME"];?>"></td>				
	</tr>
	<tr>
		<td>Kelas :</td>
		<td><input type="text" id="kelas" value="<?php echo $rows["KELAS"];?>"></td>				
	</tr>
	<tr>
		<td>Jabatan :</td>
		<td><input type="text" id="jabatan" value="<?php echo $rows["JABATAN"];?>"></td>				
	</tr>
	<tr>
		<td>Rule :</td>
		<td>
			<select id="role">
				<?php
				foreach($rowr as $rowr){ 
					if($rowr["ID"] == $idrole){	
						echo "<option selected='selected' value='".$rowr["ID"]."'>".$rowr["NAMA_ROLE"]."</option>";
					}
					else{
						echo "<option value='".$rowr["ID"]."'>".$rowr["NAMA_ROLE"]."</option>";
					}
					
				} ?>
			</select>
		</td>			
	</tr>
</table>
</div>