<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
</style>

<div class="content">
<div class="main_side">
<p>
<span class="graybrown">
<img class="icon" border="0" width="100" src="images/customerR.png">
Update Master Container
</span>
</p>
<br>
<br>
<p>
<!--<a class="link-button" href="<?=HOME?>maintenance.rename_container/add_req">-->
<table>
	<tr><td>Masukan Nomor Container</td>
	<td>: <input type="text" id="cont" size="12" /></td>
	</tr>
	<tr><td>ISO CODE</td>
	<td>: <input type="text" id="iso" size="5" /></td>
	</tr>
	<tr><td colspan="2"><a class="link-button" onclick="updateCont()">
<img border="0" src="images/tambah.png">
Update</a></td> 
	</tr>
</table>
<br>
</div>	

</div>
<script>
$(document).ready(function() 
{	
	//======================================= autocomplete Container==========================================//
	$( "#cont" ).autocomplete({
		minLength: 4,
		source: "request.anne.auto/container",
		focus: function( event, ui ) 
		{
			$( "#cont" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#cont" ).val( ui.item.NO_CONTAINER);
			$( "#iso" ).val( ui.item.ISO_CODE);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_CONTAINER +"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete EMKL==========================================//
});

function updateCont()
{
	var nc=$( "#cont" ).val();
	var iso=$('#iso').val();
	var url="<?=HOME?>maintenance.updateMSTCont.ajax/updateCont";
	$.post(url,{NC:nc, ISO:iso}, function(data){
		alert(data);
	});
}
</script>