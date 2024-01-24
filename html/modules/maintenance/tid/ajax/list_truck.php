<?php
	$registrant_name=$_GET['registrant_name'];
	$registrant_phone=$_GET['registrant_phone'];
	$company_name=$_GET['company_name'];
	$company_phone=$_GET['company_phone'];
	$company_address=$_GET['company_address'];
	$email=$_GET['email'];
?>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_truck").jqGrid({
	url:"<?=HOME?>maintenance.tid/data_truck?q=list_truck&registrant_name=<?=urlencode($registrant_name);?>&registrant_phone=<?=urlencode($registrant_phone);?>&company_name=<?=urlencode($company_name);?>&company_phone=<?=urlencode($company_phone);?>&company_address=<?=urlencode($company_address);?>&email=<?=urlencode($email);?>",
	mtype : "post",
	datatype: "json",
	colNames:['','TID','Police Number','No STNK','Expired STNK'], 
	colModel:[
		{name:'aksi', width:70, align:"center", search:false},
		{name:'tid', width:120, align:"center"},
		{name:'police_number', width:160, align:"center"},
		{name:'no_stnk',index:'hz', width:200, align:"center"},
		{name:'expired_stnk',index:'izo', width:100, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",
	ignoreCase:true,
	//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_truck',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Truck"
 });
   jQuery("#l_truck").jqGrid('navGrid','#pg_l_truck',{del:false,add:false,edit:false,search:false});
 jQuery("#l_truck").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

});

</script>

<table id='l_truck' width="100%"></table> <div id='pg_l_truck'></div>
<br>
<button onclick="update_req()"><img src="<?=HOME?>images/sg3.png"></button>