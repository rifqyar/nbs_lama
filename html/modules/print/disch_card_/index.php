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
</style>

<script>
function tambah(b,c)
{
	//alert(b);
	$('#table_profil').load("<?=HOME?>planning.profil/insert_profil?id="+b+" #profil_input").dialog({modal:true, height:250,width:400,title: c});
}

function insert_profil(id_vs)
{
	//alert(id_rq);
	var jml_bay_ = document.getElementById("jml_bay").value;
	var jml_row_ = document.getElementById("jml_row").value;
	var jml_tier_ondeck_ = document.getElementById("jml_tier_ondeck").value;
	var jml_tier_underdeck_ = document.getElementById("jml_tier_underdeck").value;
	var id_vs_ = id_vs;
	var url = "{$HOME}{$APPID}.ajax/insert_booking";
	
	$.post(url,{ID_VS: id_vs_, JML_BAY : jml_bay_, JML_ROW : jml_row_, JML_TIER_ONDECK : jml_tier_ondeck_, JML_TIER_UNDERDECK : jml_tier_underdeck_},function(data){
		console.log(data);
		if(data == "OK")
		{
			alert("Profil Kapal Disimpan");
			window.location = "{$HOME}{$APPID}";
		}
		else if(data == "gagal")
		{
			alert("Gagal Simpan Profil Kapal...!!!");
		}		
	});
}

function group_bp(v_ukk)
{
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME?>images/loadingbox.gif /><br><br>' });
	var url = "<?=HOME?>planning.baplie_import.ajax/create_masking_gr";
	
	$.post(url,{ID_VS: v_ukk},function(data)
	{
		console.log(data);
		$.unblockUI({onUnblock: function(){  }});
		alert(data);
		$("#v_baplie_import").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_dso?q=v_baplie_import&template=false', datatype:"json"}).trigger("reloadGrid");
	});
}

jQuery(function() {
 jQuery("#v_baplie_import").jqGrid({
	url:'<?=HOME?>datanya/data_dso?q=v_disch_card&template=false',
	mtype : "post",
	datatype: "json",
	colNames:['Info','Sync','Print','NO UKK','VESSEL','VOYAGE','DISCHARGE','ARRIVAL','DEPARTURE'], 
	colModel:[
		{name:'info',index:'info', width:110, align:"center",sortable:false,search:false},		
		{name:'sync',index:'sync', width:50, align:"center",sortable:false,search:false},		
		{name:'print',index:'print', width:50, align:"center",sortable:false,search:false},
		{name:'ukk',index:'ukk', width:120, align:"center"},
		{name:'vessel',index:'vessel', width:180, align:"center"},
		{name:'voyage',index:'voyage', width:100, align:"center"},
		{name:'discharge',index:'discharge', width:80, align:"center"},
		{name:'datang',index:'datang', width:150, align:"center"},		
		{name:'berangkat',index:'berangkat', width:150, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_v_baplie_import',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Kapal"
 });
  jQuery("#v_baplie_import").jqGrid('navGrid','#pg_v_baplie_import',{del:false,add:false,edit:false,search:false}); 
 jQuery("#v_baplie_import").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});

function sync_yard(i)
{
	//alert(i);
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME?>images/loadingbox.gif /><br><br>' });
	var url = "<?=HOME?>print.disch_card.ajax/sync_card";
	
	$.post(url,{NO_UKK: i},function(data){
		console.log(data);
		$.unblockUI({onUnblock: function(){  }});
		alert(data);
		$("#v_baplie_import").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_dso?q=v_disch_card&template=false', datatype:"json"}).trigger("reloadGrid");
	});
}

</script>

<div class="content">
	<div class="main_side">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/c_r.png" />
        <font color="#0378C6">Print Discharging </font> Card
        </span></h2>	
	<p><br/></p>
    <table id='v_baplie_import' width="100%"></table> <div id='pg_v_baplie_import'></div>
	
	<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
			<div id="det_bp"></div>
			<div id="vip_bp"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>