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
.ganjil {
  background-color: #FFF; /* Warna untuk baris ganjil */
}
.genap {
  background-color: #bbe3fe; /* Warna untuk baris genap */
}   

</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>
<script type="text/javascript">
function export_excel()
{
	window.open("<?=$HOME?>maintenance.tid.excel_pdf/export_excel");
}

jQuery(function() {
 jQuery("#l_tid").jqGrid({
	url:'<?=HOME?>maintenance.tid/data_truck?q=l_tid',
	mtype : "post",
	datatype: "json",
	colNames:['','Truck ID','Truck No','Expired Date','Registrant Name','Registrant Phone','Company Name','Company Address','Company Phone','email','KIU','Expired KIU', 'No STNK', 'Expired STNK','User Entry', 'Date Entry'], 
	colModel:[
		{name:'aksi', width:50, align:"center",sortable:false,search:false},
		{name:'truck_id',index:'truck_id', width:80, align:"center"},
		{name:'truck_number',index:'truck_number', width:60, align:"center"},
		{name:'expired_date',index:'expired_date', width:95, align:"center"},
		{name:'registrant_name',index:'registrant_name', width:100, align:"center"},
		{name:'registrant_phone',index:'registrant_phone', width:100, align:"center"},
		{name:'company_name',index:'company_name', width:200, align:"center"},
		{name:'company_address',index:'company_address', width:200, align:"center"},
		{name:'company_phone',index:'company_phone', width:100, align:"center"},
		{name:'email',index:'email', width:150, align:"center"},
		{name:'kiu',index:'kiu', width:100, align:"center"},
		{name:'expired_kiu',index:'expired_kiu', width:100, align:"center"},
		{name:'no_stnk',index:'no_stnk', width:100, align:"center"},
		{name:'expired_stnk',index:'expired_stnk', width:100, align:"center"},
		{name:'user_entry',index:'user_entry', width:100, align:"center"},
		{name:'date_entry',index:'date_entry', width:100, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250
	ignoreCase:true,
	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_tid',
	viewrecords: true,
	shrinkToFit: false,
	caption:"List of Registered Truck ID"
 });
  jQuery("#l_tid").jqGrid('navGrid','#pg_l_tid',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_tid").jqGrid('filterToolbar',{defaultSearch:'cn',stringResult: true,searchOnEnter : false});
 
});


</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Truck ID Registration</h2></p>
	<p><br/>
	  <a href="<?=HOME?>maintenance.tid/add_new" class="link-button">
      <img border="0" src="images/sp2p.png" />
		Create New
      </a>&nbsp;
	  <a onclick="export_excel()" style="height: 35px; width:80px;" target="_blank" title="export to excel">
		<img src="<?=HOME?>images/mexcel2.png" >
	  </a>	  
	  </p>
	<p><br/>
	  </p>
	<p><br/></p>
	<table id='l_tid' width="100%"></table> <div id='pg_l_tid'></div>

	<br/>
	<br/>
	</div>
</div>