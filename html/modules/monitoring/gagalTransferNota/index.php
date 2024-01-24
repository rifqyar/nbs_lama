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
button{
	border-radius: 4px;
    border: 1px solid #d0d0d0;
	}
button:hover {
	background: #8ed4f3;
	box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-o-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	-webkit-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);
	-moz-box-shadow: 0 2px 4px rgba(0,0,0,0.5), inset 0 1px rgba(255,255,255,0.3), inset 0 10px rgba(255,255,255,0.2), inset 0 10px 20px rgba(255,255,255,0.25), inset 0 -15px 30px rgba(0,0,0,0.3);

	
}
.ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }

</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>

<script>


</script>

<script type="text/javascript">

jQuery(function() {
 jQuery("#bm").jqGrid({ 
	url:'<?=HOME?>datanya/data_rbm?q=nota_gagal',
	mtype : "post",
	datatype: "json",
	colNames:['TRANSFER','NO NOTA','DESCR','NO_REQUEST','CUSTOMER','DATE CREATED','BIAYA','DATE PAID','AR STATUS','AR DATE','RECEIPT STATUS','RECEIPT DATE'], 
	colModel:[
		{name:'transfer',index:'descr', width:60, align:"center",search:false,sortable:false},
		{name:'nonota', width:120, align:"center"},
		{name:'descr',index:'descr', width:100, align:"center"},
		{name:'reqid',index:'reqid', width:100, align:"center"},
		{name:'cust',index:'cust', width:100, align:"center"},
		{name:'datcr',index:'datcr', width:80, align:"center",search:false},
		{name:'biaya',index:'biaya', width:80, align:"center",search:false},
		{name:'datpd',index:'datpd', width:80, align:"center"},
		{name:'arsts',index:'arsts', width:140, align:"center"},
		{name:'ardt',index:'ardt', width:100, align:"center"},
		{name:'rcsts',index:'rcsts', width:100, align:"center"},
		{name:'rcdt',index:'rcdt', width:100, align:"center"}
	],
	rowNum:1000,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_booking',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Nota Bongkar Muat"
 });
  jQuery("#bm").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
 jQuery("#bm").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});

function transfer($no_nota)
{
	alert('coba');
	$.blockUI({ message: '<h1><br>Please wait... Transfer Nota</h1><br><img src=images/loadingbox.gif /><br><br>' });
	var urltransfer='<?=HOME?>monitoring.gagalTransferNota.ajax/transfernota';
	$.post(urltransfer,{NO_NOTA:$no_nota}, function(data)
		{
			
			if(data=='S')
			{
				alert('Sukses Transfer');
				$.unblockUI({
					onUnblock: function(){ }
					});
			}
			else
			{
				alert('Gagal Transfer');
				$.unblockUI({
					onUnblock: function(){ }
					});	
			}
			$("#bm").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_rbm?q=nota_gagal', datatype:"json"}).trigger("reloadGrid");
		});	
}
</script>


<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/stevedoring.gif" height="9%" width="9%" style="vertical-align:middle"> <font color="blue">Monitoring Transfer </font>Nota</h2></p>
	<p><br/><img src='images/trflfg2.png' > : <i>digunakan untuk <b>transfer</b> AR Nota dengan status Nota Gagal</i></p>
	<BR>
		<br/><br/>
	<table id='bm' width="100%"></table> <div id='pg_l_booking'></div>
<div id="dialog-form">
	<form>
		
		<div id="save_d"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>

