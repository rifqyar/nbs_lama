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

function upload_bapli(a)
{
	//alert(a);
	$('#upload_bap').load("<?=HOME?>planning.new_baplie/upload?id_vessel="+a).dialog({modal:true, height:300,width:400,title: 'Upload Bapli'});
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
	
	var r		= confirm("Data will be grouped, Please make sure that data is already fix?");
	var url = "<?=HOME?>planning.baplie_import.ajax/create_masking_gr";
	
	if (r==true)
	  {
		var s		= confirm("Are You Sure?");
		if (s==true)
		{
				$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME?>images/loadingbox.gif /><br><br>' });
				$.post(url,{ID_VS: v_ukk},function(data) {
				console.log(data);
				$.unblockUI({onUnblock: function(){  }});
				alert(data);
				$("#v_baplie_import").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_dso?q=v_baplie_import&template=false', datatype:"json"}).trigger("reloadGrid");
				});	
		} else {
				//$.unblockUI({onUnblock: function(){  }});
				$("#v_baplie_import").jqGrid('setGridParam',{url:"<?=HOME?>data_dso?q=v_baplie_import&template=false", datatype:"json"}).trigger("reloadGrid");
		}
	  }
	else
	  {
		//$.unblockUI({onUnblock: function(){  }});
		$("#v_baplie_import").jqGrid('setGridParam',{url:"<?=HOME?>data_dso?q=v_baplie_import&template=false", datatype:"json"}).trigger("reloadGrid");
	  }
}

jQuery(function() {
 jQuery("#v_baplie_import").jqGrid({
	url:'<?=HOME?>datanya/data_dso?q=v_baplie_import&template=false',
	mtype : "post",
	datatype: "json",
	colNames:['SYNC/UP','Action','NO UKK','VESSEL','VOYAGE','DISCHARGE','ARRIVAL','DEPARTURE'], 
	colModel:[
		{name:'sync',index:'sync', width:120, align:"center",sortable:false,search:false},		
		{name:'detail',index:'detail', width:100, align:"center",sortable:false,search:false},
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

function sync_bpi(i)
{
	//alert(i);
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME?>images/loadingbox.gif /><br><br>' });
	var url = "<?=HOME?>planning.baplie_import.ajax/get_data_baplie";
	
	$.post(url,{ID_VS: i},function(data){
		console.log(data);
		$.unblockUI({onUnblock: function(){  }});
		alert(data);
		$("#v_baplie_import").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_dso?q=v_baplie_import&template=false', datatype:"json"}).trigger("reloadGrid");
	});
}

function detail_baplie(i)
{
	//alert(i);
	$('#det_bp').load("<?=HOME?>planning.baplie_import.ajax/detail_baplie?id_vessel="+i).dialog({modal:true, height:450,width:900,title: 'Detail Baplie'});
}

function vip_bp(i)
{
	$('#vip_bp').load("<?=HOME?>planning.baplie_import.ajax/vip_group?id_vessel="+i).dialog({modal:true, height:400,width:500,title: 'VIP Marking Container'});
}
</script>

<div class="content">
	<div class="main_side">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/stevedoring.gif" height="9%" width="9%" />
        <font color="#0378C6">Baplie</font> Import
        </span></h2>	
		
		<h3 style="margin-left: 700px"> <a href="./uploads/opus_baplie_template.xls">Download Templete Baplie</a></h3>
		
	<p><br/></p>
    <table id='v_baplie_import' width="100%"></table> <div id='pg_v_baplie_import'></div>
	
	<div id="dialog-form">	
	
	<form>
		<div id="table_profil"></div>
			<div id="det_bp"></div>
			<div id="vip_bp"></div>
			<div id="upload_bap"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>