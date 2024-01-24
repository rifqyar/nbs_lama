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

<script type="text/javascript">
jQuery(function() {
 jQuery("#print_delivery").jqGrid({
	url:'<?=HOME?>datanya/data?q=print_delivery',
	mtype : "post",
	datatype: "json",
	colNames:['','No Nota','No Request','QTY','EMKL','Vessel'], 
	colModel:[
		{name:'aksi', width:60, valign:"center", align:"center",sortable:false,search:false},
		{name:'id_nt',index:'id_nt', width:130, align:"center"},
		{name:'id_req',index:'id_req', width:130, align:"center"},
		{name:'jum_cont',index:'jum_cont', width:60, align:"center"},
		{name:'emkl',index:'emkl', width:235, align:"center"},
		{name:'vessel',index:'vessel', width:200, align:"center"}
		
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_print_delivery',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Print Delivery"
 });
  jQuery("#print_delivery").jqGrid('navGrid','#pg_l_delivery',{del:false,add:false,edit:false,search:false}); 
 jQuery("#print_delivery").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/cetak.png" />
        &nbsp;<font color="#0378C6">Cetak</font> SP2 Delivery
        </span></h2>	
	<p><br/></p>
    <table id='print_delivery' width="100%"></table> <div id='pg_print_delivery'></div>
	<br/>
	<br/>
	</div>
</div>