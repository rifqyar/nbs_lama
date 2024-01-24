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

function close_box()
{
	$('#table_group').dialog('destroy');
	
}

function dnld(i)
{
	$.blockUI({ message: '<h1><br>Please wait...re-update RBM</h1><br><img src={$HOME}images/loadingbox.gif /><br><br>' });
	var url="<?=HOME?>billing.rbm.ajax/sync_rbm";
	$.post(url,{NO_UKK : i},function(data) 
				  {
					alert(data);
						$.unblockUI({
						onUnblock: function(){  }
						});
						$("#booking").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data?q=rbm', datatype:"json"}).trigger("reloadGrid");
					
			      });	
}

function group(i,j,k)
{
	
	if(j=='0')
	{
		alert('Download first');
	}
	else
	{
		$('#table_group').load("<?=HOME?>billing.rbm.ajax/grouping_rbm?id="+i+"&ket="+k).dialog({modal:true, height:600,width:650,title: 'Grouping'});
	}
}

function final_gr(a)
{
	//alert(a);
	$('#final_group').load("<?=HOME?>billing.rbm.ajax/dhasil_final?id="+a).dialog({modal:true, height:300,width:650,title: 'Final'});
}

function canceld(idvsb){
	$('#cancel_final').load("<?=HOME?>billing.rbm.ajax/cancel_final?id="+idvsb).dialog({modal:true, height:300,width:350,title: 'Cancel Step'});
}
</script>

<script type="text/javascript">
jQuery(function() {
 jQuery("#booking").jqGrid({
	url:'<?=HOME?>datanya/data?q=rbm',
	mtype : "post",
	datatype: "json",
	colNames:['Action','Vessel','Voyage','Operator','Disch Qty','Load Qty','Trans Qty','Shifting Qty','RTA','RTD','POD','POL'], 
	colModel:[
		//{name:'status', width:95, align:"center",sortable:false,search:false},
		{name:'acts', width:120, align:"center",sortable:false,search:false},
		{name:'vessel',index:'vessel', width:120, align:"center"},
		{name:'voy',index:'voy', width:100, align:"center",sortable:false,search:false},
		{name:'opr',index:'opr', width:120, align:"center"},
		{name:'dq',index:'dq', width:70, align:"center"},
		{name:'lq',index:'lq', width:70, align:"center"},
		{name:'tr',index:'tr', width:70, align:"center"},
		{name:'sh',index:'sh', width:70, align:"center"},
		{name:'ata1',index:'ata1', width:80, align:"center"},
		{name:'atd1',index:'atd1', width:80, align:"center"},
		{name:'p_a',index:'p_a', width:140, align:"center"},
		{name:'p_b',index:'p_b', width:140, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	sortable:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_booking',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Realisasi Bongkar Muat",
	loadComplete: function () {
        var $self = $(this);
        if ($self.jqGrid("getGridParam", "datatype") === "json") {
            setTimeout(function () {
                $(this).trigger("reloadGrid"); // Call to fix client-side sorting
            }, 50);
        }
    }
 });
  jQuery("#booking").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
 jQuery("#booking").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<p>
	<img src="<?=HOME?>images/Crane-64.png" style="vertical-align:middle">&nbsp;&nbsp;<font color="#047dce" size="5px"><b>Realisasi</b></font> <font color="#6c6c6c" size="5px">Bongkar Muat </font></p>
	<p><br/></p>
	<br>
	<!--<a href="<?=HOME?><?=APPID?>.cetak/save_rbm"> damcuy </a>-->
	<table id='booking' width="100%"></table> <div id='pg_l_booking'></div>
<div id="dialog-form">
	<form>
		<div id="table_group"></div>
		<div id="final_group"></div>
		<div id="cancel_final"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>
<div id="shift_r"></div>
<div id="hatch_r"></div>



