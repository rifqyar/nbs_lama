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
Update Customer
</span>
</p>
<br>
<br>
<p>
<!--<a class="link-button" href="<?=HOME?>maintenance.rename_container/add_req">-->
<table>
	<tr><td>Masukan Nama Customer</td>
	<td>: <input type="text" id="custname" size="50" /></td>
	</tr>
	<tr><td>Alamat Customer</td>
	<td>: <input type="text" id="custadd" size="100" /></td>
	</tr>
	<tr><td>No. NPWP</td>
	<td>: <input type="text" id="custnpwp" size="50" /></td>
	</tr>
	<tr><td>Kode Pelanggan</td>
	<td>: <input type="text" id="custid" size="20" readonly /></td>
	</tr>
	<tr><td><a class="link-button" onclick="updateCust()">
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
	//======================================= autocomplete EMKL==========================================//
	$( "#custname" ).autocomplete({
		minLength: 3,
		source: "request.anne.auto/emkl",
		focus: function( event, ui ) 
		{
			$( "#custname" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#custname" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#custid" ).val( ui.item.KD_PELANGGAN);
			$( "#custadd" ).val( ui.item.ALAMAT_PERUSAHAAN);
			$( "#custnpwp" ).val( ui.item.NO_NPWP);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NAMA_PERUSAHAAN + "<br>" +item.ALAMAT_PERUSAHAAN+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete EMKL==========================================//
});

function updateCust()
{
	var id=$( "#custid" ).val();
	var url="<?=HOME?>maintenance.updateCustomer.ajax/updateCust";
	$.blockUI({ message: '<h1><br>Please wait...re-update RBM</h1><br><img src={$HOME}images/loadingbox.gif /><br><br>' });
	$.post(url,{ID:id}, function(data){
		alert(data);
		$.unblockUI({
			onUnblock: function(){  }
		});
	});
}
</script>