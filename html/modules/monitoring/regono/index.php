<script>
$(document).ready(function()
{
	//======================================= autocomplete container==========================================//
	$( "#nc" ).autocomplete({
		minLength: 6,
		source: "monitoring.regono.Auto/container",
		focus: function( event, ui )
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui )
		{
			$( "#nc" ).val( ui.item.CYY_CONT_CONTNO);
			$( "#truck" ).val( ui.item.CYY_CONT_REGONO);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item )
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.CYY_CONT_CONTNO + "<br>" +item.CYY_CONT_REGONO+"<br>" +"</a>")
		.appendTo( ul );
	};
	document.getElementById("nc").focus();
});


function hap(){

var truck = $("#truck").val();
var no_container = $("#nc").val();

var url="<?=HOME;?>monitoring.regono.Ajax/update";

$.post(url,{TRUCK:truck,NO_CONTAINER:no_container},
		function(data){ alert(data); });
}


</script>





<div style="margin:50 50 50 50">
<h1>UPDATE REGO NO</h1>

<br>
<p>
<i>Masukan nomor container :</i>
<input type="text" id="nc"> <i>----->>

Nomor Truck</i>
<input type="text" id ="truck">
</p>

<button onclick="hap()">update Truck No</button>

</div>
