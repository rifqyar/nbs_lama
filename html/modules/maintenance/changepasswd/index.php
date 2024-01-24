<script>
function save_passwd(id_user)
{
	var nw_psswd = $("#new_passwd").val();
	var cf_psswd = $("#conf_passwd").val();
	var url	= "<?=HOME?>maintenance.changepasswd.ajax/edit_passwd";
	
	if(nw_psswd==cf_psswd)
	{
			$.post(url,{
				NEW_PASSWD:nw_psswd,
				CONF_PASSWD:cf_psswd,
				ID : id_user
				},function(data){
			//alert(data);
			console.log(data);
			if(data == "OK")
			{
				alert("Success...");
				window.location = "<?=HOME?>main/";
			}
			else
			{
				alert("Failed...");
				return false;
			}
		});	
	}
	else
	{
		alert("Password Not Valid");
		return false;
	}
}
</script>
<?
	$id_user = $_POST['ID_USER'];
?>
<div class="content">
<div class="main_side">
<p>
<span class="graybrown">
<img class="icon" border="0" src="images/user_ba.png" height="32px" width="32px">
CHANGE <font color="#0378C6">Password</font>
</span>
</p>
<br/>
<br/>
<table style="padding-left:20px">
	<tr>
		<td>New Password</td>
		<td> : </td>
		<td><input type="password" name="new_passwd" id="new_passwd" size="20"/></td>
	</tr>
	<tr>
		<td>Confirm Password</td>
		<td> : </td>
		<td><input type="password" name="conf_passwd" id="conf_passwd" size="20"/></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" align="center">
			<input type="button" name="Save Password" value="&nbsp;Save&nbsp;" onclick="save_passwd('<?=$id_user?>')"/>
		</td>
	</tr>
</table>
<p><br/></p>
</div>
</div>