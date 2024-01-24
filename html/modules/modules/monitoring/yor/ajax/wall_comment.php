<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"> </script>
<script type="text/javascript" src="js/ajax.js"></script>
<?php
	$no_ukk=$_GET['no_ukk'];
?>
<script>
$(document).ready(function() 
{
	$('#comment_list').html('<img src="<?=HOME?>images/loadingF.gif" />');
	$('#comment_list').load("<?=HOME?>monitoring.rbm.ajax/list_comment?no_ukk=<?=$no_ukk?>");
});
function add_comm()
{
	var comment = $('#wallp').val();
	var url="<?=HOME?>monitoring.rbm.ajax/add_wall?no_ukk=<?=$no_ukk?>";
	$.post(url,{COMMENT:comment},function(data){
		console.log(data);
			//alert(data);
			$('#comment_list').html('<img src="<?=HOME?>images/loadingF.gif" />');
			$('#comment_list').load("<?=HOME?>monitoring.rbm.ajax/list_comment?no_ukk=<?=$no_ukk?>");
			//$(this).dialog('close');
	});
	//alert(comment);
}
</script>

<table width="100%" style="border:none;" cellspacing="0" cellpadding="0">
<tr bgcolor="#3b5998" height="40px"><td><font color="#fffffff" size="2px"><b>&nbsp;&nbsp;Wall Post Comment</b></font></td></tr>
</table>
<br>
<table width="100%">
<tr>
	<td align="right"><textarea cols=52 rows=3 id="wallp"></textarea>
	</td>
</tr>
<tr><td align="right"><button onclick="add_comm()">Post Wall</button></td>
</tr>
</tr>
<tr><td align="left"><i>Maximum 30 character</i></td>
</tr>
</table>
<br>
<div id="comment_list"></div>
