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

function create_baplie(a,b,c)
{
	//alert(a);
	//alert(b);
	//alert(c);
	$('#create_bap').load("<?=HOME?>planning.baplie_export.ajax/create_baplie?call_sign="+a+"&voyage_in="+b+"&voyage_out="+c);
}

jQuery(function() {
 jQuery("#v_baplie_import").jqGrid({
	url:'<?=HOME?>datanya/data_dso?q=v_baplie_export&template=false',
	mtype : "post",
	datatype: "json",
	colNames:['Gen Data','To Excel','Gen EDI','VESSEL','VOYAGE IN','VOYAGE OUT','CALL SIGN','ATD'], 
	colModel:[		
		{name:'detail',index:'detail', width:80, align:"center",sortable:false,search:false},
		{name:'create',index:'create', width:60, align:"center",sortable:false,search:false},
        {name:'edi',index:'edi', width:60, align:"center",sortable:false,search:false},		
		{name:'vessel',index:'vessel', width:180, align:"center"},
		{name:'voyage_in',index:'voyage_in', width:100, align:"center"},
		{name:'voyage_out',index:'voyage_out', width:100, align:"center"},
		{name:'call_sign',index:'call_sign', width:80, align:"center"},
		{name:'atd',index:'atd', width:150, align:"center"}
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
        <font color="#0378C6">Baplie</font> Export
        </span></h2>	
	<p><br/></p>
    <table id='v_baplie_import' width="100%"></table> <div id='pg_v_baplie_import'></div>
	
	<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
			<div id="det_bp"></div>
			<div id="vip_bp"></div>
			<div id="upload_bap"></div>
			<div id="create_bap"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>