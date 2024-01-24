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
			url:"<?=HOME?>datanya/data_2?q=list_cont_st&kg="+keg+"&vessel="+vessel+"&vin="+vin+"&vot="+vot,
			mtype : "post",
			datatype: "json",
			colNames:['Container Numb.','No Request','Sz - Ty - Sts - Hz','Gross','POL','POD','Kode Status','Bay Position<br>B - R - T','Date<br>Discharge Confirm', 'Date<br>Placement', 'Yard<br>Placement<br>B - S - R - T','Gate In','Gate Out', 'No Nota', 'Status Pembayaran', 'Customer', 'Custom Number'], 
			colModel:[
				{name:'cont',index:'cont', width:100, align:"center"},
                                {name:'no_req',index:'no_req', width:100, align:"center"},
				{name:'stst',index:'stst', width:100, align:"center"},
				{name:'gross',index:'gross', width:70, align:"center"},
				{name:'pol',index:'pol', width:50, align:"center"},
				{name:'pod',index:'pod', width:50, align:"center"},
				{name:'ks',index:'ks', width:50, align:"center"},
				{name:'bp',index:'bp', width:80, align:"center"},
				{name:'dtbp',index:'dtbp', width:110, align:"center"},				
				{name:'dtplc',index:'dtplc', width:110, align:"center"},
				{name:'ydplc',index:'ydplc', width:80, align:"center"},	
				{name:'gi',index:'gi', width:110, align:"center"},
				{name:'go',index:'go', width:110, align:"center"}, 
                                {name:'no_nota',index:'no_nota', width:110, align:"center"},
				{name:'status',index:'status', width:110, align:"center"},
				{name:'custname',index:'custname', width:110, align:"center"},
				{name:'sppb',index:'sppb', width:110, align:"center"}
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
	var no_ukk = $( "#NO_UKK" ).val();		
	window.open("<?=$HOME?>report.delivery_kapal.excel_pdf/export_excel?no_ukk="+no_ukk);	
}

function export_pdf()
{
	var no_ukk = $( "#NO_UKK" ).val();		
	window.open("<?=$HOME?>report.delivery_kapal.excel_pdf/export_pdf?no_ukk="+no_ukk);
	
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<img src="<?=HOME?>images/kapal_.gif" height="7%" width="7%" style="vertical-align:middle">
		<b> <font color='#69b3e2' size='4px'>Laporan Delivery Per Kapal</font> </b>

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



