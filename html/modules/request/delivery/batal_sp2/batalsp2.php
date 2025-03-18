<script>
function create_req()
{
	var url="<?=HOME;?>request.delivery.batal_sp2/save_req";
	
	var no_ba=$("#no_ba").val();
	var container=$("#nc").val();	
    var size_cont=$("#sc").val();  
    var type_cont=$("#tc").val();
    var status_cont=$("#stc").val();
    var status_payment=$("#status").val();
    var id_req=$("#id_req").val();
    var no_ukk=$("#no_ukk").val();
    var vessel=$("#vessel").val();
    var voyage_in=$("#voyage_in").val();
	
	if(status_payment!='P')
	{
		alert('SP2 Belum Lunas, silahkan dilunasi terlebih dahulu..');
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
					NO_UKK:no_ukk,
					VESSEL:vessel,
					VOYAGE_IN:voyage_in,
					NO_BA:no_ba},
		function(data){	
			alert('sukses menyimpan data');

			$( "#nc").val("");
			$( "#sc").val("");
			$( "#tc").val("");
			$( "#stc").val("");
			$( "#status").val("");
			$( "#vessel").val("");
			$( "#voyage_in").val("");		
			$( "#id_req").val("");
			$( "#no_ukk").val("");
			$("#no_ba").val("");

			window.location="<?=HOME?>request.delivery.batal_sp2/";

		});		
	}
}


$(document).ready(function() 
{
	//======================================= autocomplete container==========================================//
	$( "#nc" ).autocomplete({
		minLength: 6,
		source: "request.delivery.batal_sp2/container",
		focus: function( event, ui ) 
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			//console.log(ui.item);
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			$( "#sc" ).val( ui.item.SIZE_CONT);
			$( "#tc" ).val( ui.item.TYPE_CONT);
			$( "#stc" ).val( ui.item.STATUS_CONT);
			$( "#status" ).val( ui.item.STATUS);
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#voyage_in" ).val( ui.item.VOYAGE_IN);		
			$( "#id_req" ).val( ui.item.ID_REQ);
			$( "#no_ukk" ).val( ui.item.NO_UKK);
			$( "#cont_location" ).val( ui.item.CONT_LOCATION);

			
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_CONTAINER + "<br>" +item.SIZE_CONT+"<br>" +item.TYPE_CONT+"<br>" +item.ID_REQ+"<br>"+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete container==========================================//	
	
	document.getElementById("nc").focus();

});    
    
</script>

<div style="margin:15 20 20 50">
<h1>Batal SP2</h1>
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
	<input type="text" name="id_req" id="id_req" placeholder="No Req Del" disabled class="input_text">
	<input type="text" name="no_ukk" id="no_ukk" hidden class="input_text">
	<input type="text" name="cont_location" id="cont_location" placeholder="Lokasi Cont" disabled class="input_text">
	
</p>
<br>
<br>

<button onclick="create_req()"><img src="<?=HOME;?>images/add_ct.png"/></button>

</div>