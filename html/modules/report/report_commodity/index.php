<script type="text/javascript">

$(document).ready(function ()
{
	$("#periode_awal").datepicker({
		dateFormat: 'yymmdd',
		maxDate: 0 
	});

	$("#periode_akhir").datepicker({
		dateFormat: 'yymmdd',
		maxDate: 0
	});
});

$(document).ready(function () {
	$("#vessel").autocomplete({
		minLength	: 3,
		source		: "report.report_commodity.auto/vessel",
		focus		: function(event,ui)
		{
			$("#vessel").val(ui.item.VESSEL);
			return false;
		},
		select: function(event,ui)
		{
			$("#vessel").val(ui.item.VESSEL);
			$("#voyin").val(ui.item.VOYAGE_IN);
			$("#voyout").val(ui.item.VOYAGE_OUT);
			$("#ukk").val(ui.item.ID_VSB_VOYAGE);
			return false;
		}
	}).data("autocomplete")._renderItem = function(ul, item)
	{
		return $("<li></li>")
		.data("item.autocomplete", item)
		.append("<a align='center'>" + item.VESSEL + " " + item.VOYAGE_IN + " " + item.VOYAGE_OUT+" <br>"+ item.ID_VSB_VOYAGE +"</a>")
		.appendTo(ul);
	};
});

function export_report()
{
	periode_awal=$("#periode_awal").val();	
	periode_akhir=$("#periode_akhir").val();
	vessel = $("#vessel").val();
	voyin = $("#voyin").val();
	voyout = $("#voyout").val();
	status = $("#status").val();

	// alert(periode_awal);
	// alert(periode_akhir);
	// alert(vessel);
	// alert(voyin);
	// alert(voyout);
	//alert(status);


    window.open("<?=HOME?>report.report_commodity/detail_excel?periode_awal="+periode_awal+"&periode_akhir="+periode_akhir+"&vessel="+vessel+"&voyin="+voyin+"&voyout="+voyout+"&status="+status);  

	//$('#export_report').load("<?=HOME?>report.report_commodity/detail_excel?periode_awal="+periode_awal+"&periode_akhir="+periode_akhir+"&vessel="+vessel+"&voyin="+voyin+"&voyout="+voyout+"&status="+status);
}

</script>


<h1 style 	="margin: 10 10 30 10"> Report Commodity </h1>

<p style 	="margin: 10 10 10 10">
	<label>	Periode Awal </label>
	<input type="text" id="periode_awal" placeholder="start date" name="periode_awal" autocomplete="off" >
</p>

<p style 	="margin: 10 10 10 10">
	<label> Periode Akhir </label>
	<input type="text" id="periode_akhir" placeholder="end date" name="periode_akhir" autocomplete="off">
</p>

<p style 	="margin: 10 10 10 10">
	<Label> Vessel </Label>
		<input style = "margin: 0 0 0 30" type ="text" size="25" id="vessel" 
			name="vessel" 
			placeholder="Vessel Name"> / 
		<input type="text" size="5" id="voyin" 
			name="voyin" 
			placeholder="VoyIn"> | 
		<input type="text" size="5" id="voyout" 
			name="voyout" 
			placeholder="VoyOut" />
</p>
<p style 	="margin: 10 10 10 10">
	<Label>Jenis</Label>
	<select name="status" id="status" style="margin:0 0 0 35">
		<option value ="ALL">ALL</option>
		<option value ="DISCHARGE">DISCHARGE</option>
		<option value ="LOADING">LOADING</option>
	</select>
</p>

<p style 	="margin: 10 10 10 10">
<button onclick="export_report();">Export to excel</button>
</p>