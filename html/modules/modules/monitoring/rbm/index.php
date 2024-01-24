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

</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>


<script type="text/javascript">	
jQuery(function() {
 jQuery("#booking").jqGrid({
	url:'<?=HOME?>datanya/data?q=m_rbm',
	mtype : "post",
	datatype: "json",
	colNames:['Comment Wall','No UKK','Vessel - Voyage','RBM Status','Confirm RBM to\nShipping Line','Pranota RBM','Confirm Status Pranota','Nota RBM','Confirm Status Nota'], 
	colModel:[
		{name:'aksi', width:80, align:"center",sortable:false,search:false},
		{name:'nukk',index:'voyage', width:80, height:40, align:"center"},
		{name:'nama_kapal',index:'nama_kapal', width:185, align:"center"},
		{name:'dq',index:'dq', width:100, align:"center",sortable:false,search:false},
		{name:'lq',index:'lq', width:100, align:"center",sortable:false,search:false},
		{name:'rta',index:'rta', width:100, align:"center",sortable:false,search:false},
		{name:'rtd',index:'rtd', width:100, align:"center",sortable:false,search:false},
		{name:'p_a',index:'p_a', width:100, align:"center",sortable:false,search:false},
		{name:'p_b',index:'p_b', width:100, align:"center",sortable:false,search:false}
	],
	rowNum:6,
	width: 865,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_booking',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Monitoring RBM"
 });
  jQuery("#booking").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
  //jQuery("#booking").grid.setGridHeight('50px');
 jQuery("#booking").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});

function log_email(i)
{
	//alert(i);
	$('#log_em').load("<?=HOME?>monitoring.rbm.ajax/log_email?no_ukk="+i).dialog({modal:true, height:300,width:400});
}

function comment_add(i)
{
	$('#add_comment').load("<?=HOME?>monitoring.rbm.ajax/wall_comment?no_ukk="+i).dialog({modal:true, height:400,width:400});
}
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/kapal_.gif" height="7%" width="7%" style="vertical-align:middle"> <font color="#81cefa">Monitoring </font>
	<font size="3px" color="#606263">Realisasi Bongkar Muat</font></h2></p>
	<table id='booking' width="100%"></table> <div id='pg_l_booking'></div>
<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>
<div id="log_em"></div>
<div id="add_comment"></div>




<script type='text/javascript'>
function closed_rbm($id)
{
	var no_ukk_		= $id;
	var url 	    = "<?=HOME?><?=APPID?>/closed_rbm";
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src={$HOME}images/loading81.gif width="30" height="30" /><br><br>' }); 
	$.post(url,{no_ukk : no_ukk_},function(data){
		if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
			}
		else
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
			}
	});	
}

function koreksi_rbm($id)
{
	var no_ukk_		= $id;
	var url 	    = "<?=HOME?><?=APPID?>/koreksi_rbm";
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src={$HOME}images/loading81.gif width="30" height="30" /><br><br>' }); 
	$.post(url,{no_ukk : no_ukk_},function(data){
		if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
			}
		else
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
			}
	});	
}

function final_rbm($id)
{
	var no_ukk_		= $id;
	var url 	    = "<?=HOME?><?=APPID?>/final_rbm";
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src={$HOME}images/loading81.gif width="30" height="30" /><br><br>' }); 
	$.post(url,{no_ukk : no_ukk_},function(data){
		if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
			}
		else
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
			}
	});	
}


</script>