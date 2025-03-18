<?php
	$db = getDB("storage");
	
	//$tl = xliteTemplate("change_password.htm");
	//echo "akjdaj";die;
	$iduser = $_GET["ID_USER"];
	$q = "SELECT ID, PASSWORD FROM MASTER_USER WHERE ID = '$iduser'";
	$result = $db->query($q);
	$row_pass = $result->fetchRow();
	//$tl->assign('row_pass',$row_pass);
	//$tl->renderToScreen();
?>

<div id="change">
<table>
	<!--<tr>
		<td>Current Password (*):</td>
		<td><input type="password" id="cur_pass"></td>		
	</tr>-->
	<tr>
		<td>New Password (*):</td>
		<td><input type="password" id="new_pass"></td>		
	</tr>
	<tr>
		<td>Confirm New Password (*):</td>
		<td><input type="password" id="conf_new_pass">
		<input type="hidden" id="id" value="<?php echo $row_pass["ID"];?>"></td>		
		
	</tr>
</table>
</div>