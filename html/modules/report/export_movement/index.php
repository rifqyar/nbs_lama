<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.rightside{ 
	width:25%;
	float:right;
	text-align:center;
}

.main_side{
	width:100%;
	float:left;
	text-align:left;
}
.ui-jqgrid .ui-jqgrid-htable th div {
    height:auto;
    overflow:hidden;
    padding-right:4px;
    padding-top:2px;
    position:relative;
    vertical-align:text-top;
    white-space:normal !important;
}


</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>


<script type="text/javascript">
var no_ukk;
var keg;
var vessel;
var vin;
var vot;

$(function() {

	$( "#NM_KAPAL" ).autocomplete({
		minLength: 3,
		source: "<?=HOME;?>monitoring.list_container.auto/nama_kapal",

		focus: function( event, ui ) {
			$( "#NM_KAPAL" ).val( ui.item.NM_KAPAL );
			return false;
		},
		select: function( event, ui ) {
			$( "#NM_KAPAL" ).val( ui.item.NM_KAPAL );
			$( "#NO_UKK" ).val( ui.item.NO_UKK );
			$( "#VOY" ).val( ui.item.VOYAGE_IN );
			$( "#VOY_OUT" ).val( ui.item.VOYAGE_OUT );
			no_ukk=ui.item.NO_UKK;
			vessel=ui.item.NM_KAPAL;
			vin=ui.item.VOYAGE_IN;
			vot=ui.item.VOYAGE_OUT;
			$( "#NM_AGEN" ).val( ui.item.NM_PEMILIK );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><b>" + item.NO_UKK + " | "+ item.NM_KAPAL + " [ " + item.VOYAGE_IN + "-" + item.VOYAGE_OUT + " ]</b></a>" ) 
			.appendTo( ul );
	};
	
	
});

function search_request()
{
	vessel=$("#NM_KAPAL").val();	
	vin=$("#VOY").val();
	vot=$("#VOY_OUT").val();
	var keg = $("#KEG").val();	
	//alert(keg+' coba');
	$("#list_container").jqGrid('GridUnload');
			
		 jQuery(function() {
		 jQuery("#list_container").jqGrid({
			url:"<?=HOME?>report.export_movement/data?kg="+keg+"&vessel="+vessel+"&vin="+vin+"&vot="+vot,
			mtype : "post",
			datatype: "json",
			colNames:['No Container','Type', 'SIZE','STAT', 'GROSS','POD',
                       'IS','EMKL', 'COMMODITY', 'GATE','EX', 'NO NPE','NO REQ'], 
			colModel:[
				{name:'no_cont',index:'no_cont', width:80, align:"center"},
                {name:'type',index:'type', width:40, align:"center"},
				{name:'size',index:'size', width:50, align:"center"},				
				{name:'fm',index:'fm', width:50, align:"center"},				
				{name:'wgt',index:'wgt', width:50, align:"center"},				
				{name:'pod',index:'pod', width:110, align:"center"},			
				{name:'location',index:'location', width:60, align:"center"},				
				{name:'emkl',index:'emkl', width:50, align:"center"},							
				{name:'commodity',index:'commodity', width:110, align:"center"},
				{name:'gate',index:'gate', width:110, align:"center"},                     
                {name:'ex',index:'ex', width:50, align:"center"},                                
				{name:'npe',index:'npe', width:100, align:"center"}, 
                {name:'ke',index:'ke', width:120, align:"center"}		
			],
			rowNum:10000,
			width: 880,
			height: "100%",//250

			//rowList:[10,20,30,40,50,60],
			loadonce:true,
			rownumbers: true,
			rownumWidth: 15,
			gridview: true,
			pager: '#pg_l_booking',
			viewrecords: true,
			shrinkToFit: false,
			caption:"List Container By Status"
		 });
		  jQuery("#list_container").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
		 jQuery("#list_container").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
		 
		});	
}


function export_excel()
{
    var ves = $("#NM_KAPAL").val();
    var vessel = encodeURIComponent(ves);
    //alert(vessel);
    var voyage_in = $("#VOY").val();
    var voyage_out = $("#VOY_OUT").val();
    var no_ukk = $( "#NO_UKK" ).val();
    var keg = $( "#KEG" ).val();	
    window.open("<?=$HOME?>report.export_movement/detail_excel?vessel="+vessel+"&voyage_in="+voyage_in+"&voyage_out="+voyage_out);
	
}
function export_pdf()
{
	var ves = $("#NM_KAPAL").val();
    var vessel = encodeURIComponent(ves);
    //alert(vessel);
    var voyage_in = $("#VOY").val();
    var voyage_out = $("#VOY_OUT").val();
	var no_ukk = $( "#NO_UKK" ).val();
	//var keg = $( "#KEG" ).val();
	window.open("<?=$HOME?>report.export_movement/detail_pdf?vessel="+vessel+"&voyage_in="+voyage_in+"&voyage_out="+voyage_out);
	
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<img src="<?=HOME?>images/kapal_.gif" height="7%" width="7%" style="vertical-align:middle">
		<b> <font color='#69b3e2' size='4px'>List Kartu Muat Per Kapal</font> </b>
	</p>
	<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; ">
	<center>
		<table style="margin: 10px 10px 10px 10px;" border="0">
			<tr>
				<td>Vessel</td>
				<td> : </td>
				<td> <input type="text" size="25" align="center" name="NM_KAPAL" id="NM_KAPAL" size="30"/></td>
			</tr>
			<tr>
				<td>UKK</td>
				<td> : </td>
				<td> <input type="text" name="NO_UKK" align="center" id="NO_UKK" readonly="readonly"/></td>
			</tr>
			<tr>
			<tr>
				<td>Voyage</td>
				<td> : </td>
				<td> <input type="text" size="5" name="VOY" id="VOY" class="grey" align="center" readonly="readonly"/> - <input type="text" size="5" name="VOY_OUT" id="VOY_OUT" class="grey" align="center" readonly="readonly"/></td>
			</tr>
			<tr>
				<td>Shipping Line</td>
				<td> : </td>
				<td> <input type="text" size="35" name="NM_AGEN" id="NM_AGEN" class="grey" align="center" readonly="readonly"/></td>
			</tr>
			
			<!--
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><button onclick="search_request()" style="height: 35px; width:80px;"><img src="<?=HOME?>images/search_ok.png" ></button></td>
			</tr>
			-->
			
			<tr>
				<td>Export to</td>
				<td> : </td>
				<td align="left">
				<!--<a onclick="export_excel()" style="height: 35px; width:80px;" target="_blank" title="export to excel">
				<img src="<?=HOME?>images/mexcel2.png" ></a>&nbsp;-->
				<button onclick="export_pdf();">PDF</button>
				
				
			</tr>
		</table>
	</center>
	</fieldset>
	
	
	<p><br/></p>
	<table id='list_container' width="100%"></table> <div id='pg_l_booking'></div>
	
	<br>
	<br>
	
	<div id="dialog-form">
		<form>
			<div id="table_profil"></div>
		</form>
	</div>
		<br/>
		<br/>
		</div>
	</div>
	<div id="shift_r"></div>
	<div id="hatch_r"></div>



