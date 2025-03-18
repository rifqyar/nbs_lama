<script>
function create_req()
{
	var url="<?=HOME;?>uster.new_request.batal_spps/save_req";

	var no_ba=$("#no_ba").val();
	var container=$("#nc").val();
    var size_cont=$("#sc").val();
    var type_cont=$("#tc").val();
    var status_cont=$("#stc").val();
    var status_payment=$("#status").val();
    var id_req=$("#id_req").val();
    var id_ureq=$("#id_ureq").val();
    var no_ukk=$("#no_ukk").val();
    var vessel=$("#vessel").val();
    var voyage_in=$("#voyage_in").val();

	if(status_payment!='Sudah Bayar')
	{
		alert('Request Belum Lunas, silahkan dilunasi terlebih dahulu..');
		return false;
	}

	else if(no_ba=='')
	{
		alert('Berita Acara harus disertakan..');
		return false;
	}

	else
	{
		//alert('e');
		$.post(url,{NO_CONTAINER:container,
					SIZE_CONT:size_cont,
					TYPE_CONT:type_cont,
					STATUS_CONT:status_cont,
					STATUS_PAYMENT:status_payment,
					ID_REQ:id_req,
					ID_UREQ:id_ureq,
					NO_UKK:no_ukk,
					VESSEL:vessel,
					VOYAGE_IN:voyage_in,
					NO_BA:no_ba},
		function(data){
			if(data == 'sukses'){
				$.unblockUI({ 
				onUnblock: function(){ alert('Batal Spps Sukses'); } 
				});
				window.location="<?=HOME?>uster.new_request.batal_spps/";
			}else {
				$.unblockUI({ 
				onUnblock: function(){ alert('Batal Spps Gagal '); } 
				});
			}	
		});
	}
}


$(document).ready(function()
{
	//======================================= autocomplete container==========================================//
	$( "#nc" ).autocomplete({
		minLength: 6,
		source: "uster.new_request.batal_spps/container",
		focus: function( event, ui )
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui )
		{	
			//console.log(ui.item);
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			$( "#sc" ).val( ui.item.SIZE_);
			$( "#tc" ).val( ui.item.TYPE_);
			$( "#stc" ).val( ui.item.STATUS_CONT);
			$( "#status" ).val( ui.item.STATUS);
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#voyage_in" ).val( ui.item.VOYAGE_IN);
			$( "#id_req" ).val( ui.item.NO_REQUEST);
			$( "#id_ureq" ).val( ui.item.ID_UREQ);
			$( "#no_ukk" ).val( ui.item.NO_UKK);
			$( "#cont_location" ).val( ui.item.LOCATION);


			return false;
			/* var url_disable_container = 'uster.new_request.batal_spps.ajax/get_disable_cont_praya';
			$.post(url_disable_container,{no_cont : ui.item.NO_CONTAINER},function(data){
				var json = $.parseJSON(data);

				if (json.code === '1'){
//console.log(ui.item);
					$( "#nc" ).val( ui.item.NO_CONTAINER);
					$( "#sc" ).val( ui.item.SIZE_);
					$( "#tc" ).val( ui.item.TYPE_);
					$( "#stc" ).val( ui.item.STATUS_CONT);
					$( "#status" ).val( ui.item.STATUS);
					$( "#vessel" ).val( ui.item.VESSEL);
					$( "#voyage_in" ).val( ui.item.VOYAGE_IN);
					$( "#id_req" ).val( ui.item.NO_REQUEST);
					$( "#id_ureq" ).val( ui.item.ID_UREQ);
					$( "#no_ukk" ).val( ui.item.NO_UKK);
					$( "#cont_location" ).val( ui.item.LOCATION);


					return false;
				} else {
					$.blockUI({
						message: `<h1>Container in Service disableContainer : ${json.msg}</h1>`,
						timeout: 10000
					});
					$("#nc").val('');
					return false;
				}
			}); */
		}
	}).data( "autocomplete" )._renderItem = function( ul, item )
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_CONTAINER + "<br>" +item.SIZE_+"<br>" +item.TYPE_+"<br>" +item.NO_REQUEST+"<br>"+"</a>")
		.appendTo( ul );

	};
	//======================================= autocomplete container==========================================//

	document.getElementById("nc").focus();

});

</script>

<div style="margin:15 20 20 50">
<h1>Batal SPPS</h1>
<br>
<br>


<p>
	<label>No Berita Acara</label>
</p>
<input type="text" name="no_ba" id="no_ba" class="input_text">
<br>

<br>

<p>
	<label class="label">No Container : </label>
</p>
<input type="text" name="nc" id="nc" class="input_text">

<br>
<br>

<p>
	<input type="text" name="sc" id="sc" placeholder="Size Container" disabled class="input_text">
	<input type="text" name="tc" id="tc" placeholder="Type Container" disabled class="input_text">
	<input type="text" name="stc" id="stc" placeholder="Status Container" disabled class="input_text">
</p>

<br>

<p>
	<input type="text" name="vessel" id="vessel" placeholder="Vessel" disabled class="input_text">
	<input type="text" name="voyage_in" id="voyage_in" placeholder="Voyage In" disabled class="input_text">
	<input type="text" name="status" id="status" style="color:red" placeholder="Status Payment" disabled class="input_text">
</p>

<br>

<p>
	<input type="text" name="id_req" id="id_req" placeholder="No Req SPPS" disabled class="input_text">
	<input type="text" name="id_ureq" id="id_ureq" disabled class="input_text">
	<input type="text" name="no_ukk" id="no_ukk" hidden class="input_text">
	<input type="text" name="cont_location" id="cont_location" placeholder="Lokasi Cont" disabled class="input_text">

</p>
<br>
<br>

<button onclick="create_req()"><img src="<?=HOME;?>images/add_ct.png"/></button>

</div>
