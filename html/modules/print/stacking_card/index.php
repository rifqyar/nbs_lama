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
 jQuery("#print_stacking_card").jqGrid({
	url:'<?=HOME?>datanya/data?q=print_stacking_card',
	mtype : "post",
	datatype: "json",
	colNames:['','No Nota','No Req','QTY','EMKL','Vessel'], 
	colModel:[
		{name:'aksi', width:60, valign:"center", align:"center",sortable:false,search:false},
		{name:'nota',index:'nota', width:120, align:"center"},
		{name:'id_req',index:'id_req', width:120, align:"center"},
		{name:'jum_cont',index:'jum_cont', width:60, align:"center"},
		{name:'emkl',index:'emkl', width:260, align:"center"},
		{name:'vessel',index:'vessel', width:200, align:"center"}
		
	],
	//rowNum:6,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_print_stacking_card',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Stacking Card"
 });
  jQuery("#print_stacking_card").jqGrid('navGrid','#pg_l_stacking_card',{del:false,add:false,edit:false,search:false}); 
 jQuery("#print_stacking_card").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/cetak.png" />
        &nbsp;<font color="#0378C6">Cetak</font> Receiving Card
        </span></h2>	
	<p><br/></p>
    <table id='print_stacking_card' width="100%"></table> <div id='pg_print_stacking_card'></div>
	<br/>
	<br/>
	</div>
</div>