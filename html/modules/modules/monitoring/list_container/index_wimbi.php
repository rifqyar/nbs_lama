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

var no_ukk;
var keg;

function search_request()
{
	no_ukk = $( "#NO_UKK" ).val();
	var nm_kapal = $( "#NM_KAPAL" ).val();
	var voy = $( "#VOY" ).val();
	var nm_agen = $( "#NM_AGEN" ).val();
	keg = $( "#KEG" ).val();
	var kolom = $("#list_container").getGridParam("colNames");
	
	if(kolom[4]=='Gate In<br>Jobslip' && keg=='I'){
		$("#list_container").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=list_cont_st&kg="+keg+"&no_ukks="+no_ukk, datatype:"json"}).trigger("reloadGrid");
		
		$("#list_container").jqGrid("setLabel", 'gi', "Gate Out");
		$("#list_container").jqGrid("setLabel", 'dl', "Date<br>Discharging Confirm");
		$("#list_container").remapColumns([0,1,2,3,8,5,9,6,7,4],true,false);
	}
	if(kolom[9]=='Gate Out' && keg=='E'){
		
		$("#list_container").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=list_cont_st&kg="+keg+"&no_ukks="+no_ukk, datatype:"json"}).trigger("reloadGrid");
		
		$("#list_container").jqGrid("setLabel", 'gi', "Gate In<br>Jobslip");
		$("#list_container").jqGrid("setLabel", 'dl', "Date<br>Loading Confirm");
		$("#list_container").remapColumns([0,1,2,3,9,5,7,8,4,6],true,false);
	}
//	$("#list_container").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=list_cont_st&kg="+keg+"&no_ukks="+no_ukk, datatype:"json"}).jqGrid("GridUnload");
	
}

jQuery(function() {
 jQuery("#list_container").jqGrid({
	url:"<?=HOME?>datanya/data_2?q=list_cont_st&kg=NULL&no_ukks=NULL",
	mtype : "post",
	datatype: "json",
	colNames:['Container Numb.', 'Sz - Ty - Sts - Hz','Kode Status', 'Gate In<br>Jobslip', 'Yard<br>Allocation<br>Block - Slot', 'Date<br>Placement', 'Yard<br>Placement<br>B - S - R - T','Bay Plan<br>B - R - T','Date<br>Loading Confirm'], 
	colModel:[
		{name:'cont',index:'cont', width:100, align:"center"},
		{name:'stst',index:'stst', width:100, align:"center"},
		{name:'ks',index:'ks', width:50, align:"center"},
		{name:'gi',index:'gi', width:110, align:"center"},
		{name:'yalo',index:'yalo', width:75, align:"center"},
		{name:'dtplc',index:'dtplc', width:110, align:"center"},
		{name:'ydplc',index:'ydplc', width:80, align:"center"},
		{name:'bp',index:'bp', width:80, align:"center"},
		{name:'dl',index:'dl', width:110, align:"center"}
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

function ganti()
{
	
}

function export_excel()
{
	var no_ukk = $( "#NO_UKK" ).val();
	var keg = $( "#KEG" ).val();
	//location.href="<?=$HOME?>monitoring.list_container.excel/export_excel?no_ukk="+ukk+"&keg="+keg;
	window.open("<?=$HOME?>monitoring.list_container.excel_pdf/export_excel?no_ukk="+no_ukk+"&keg="+keg);
	
}
function export_pdf()
{
	var no_ukk = $( "#NO_UKK" ).val();
	var keg = $( "#KEG" ).val();
	//location.href="<?=$HOME?>monitoring.list_container.excel/export_excel?no_ukk="+ukk+"&keg="+keg;
	window.open("<?=$HOME?>monitoring.list_container.excel_pdf/export_pdf?no_ukk="+no_ukk+"&keg="+keg);
	
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<img src="<?=HOME?>images/kapal_.gif" height="7%" width="7%" style="vertical-align:middle">
		<b> <font color='#69b3e2' size='4px'>List Container</font> </b>
	 <font color='#888b8d' size='4px'>
	 by Status
	 </font>
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
			<tr>
				<td>Activity</td>
				<td> : </td>
				<td> 
				<select name="KEG" id="KEG" onchange="ganti()">
				  <option value="E">Eksport</option>
				  <option value="I">Import</option>
				</select>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><button onclick="search_request()" style="height: 35px; width:80px;"><img src="<?=HOME?>images/search_ok.png" ></button></td>
			</tr>
			
			<tr>
				<td>Export to</td>
				<td> : </td>
				<td align="center">
				<a onclick="export_excel()" style="height: 35px; width:80px;" target="_blank" title="export to excel">
				<img src="<?=HOME?>images/mexcel2.png" ></a>&nbsp;
				<a onclick="export_pdf()" target="_blank" style="height: 35px; width:80px;" title="export to pdf (unrecomended)"><img src="<?=HOME?>images/mpdf2.png"></a></td>
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



