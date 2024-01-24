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
</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri                      = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>

<script>


</script>

<script type="text/javascript">
var urltransfer='<?=HOME?>billing.bm.ajax/transfernota';
jQuery(function() {
 jQuery("#bm").jqGrid({
	url:'<?=HOME?>datanya/data_rbm?q=nota_bm',
	mtype : "post",
	datatype: "json",
	colNames:['','ID','Vessel','Voyage','ATA','Save Date','Customer','Operator'], 
	colModel:[
		{name:'aksix', width:120, align:"center",sortable:false,search:false},
		{name:'noukk',index:'noukk', width:50, align:"center"},
		{name:'nama_kapal',index:'nama_kapal', width:150, align:"center"},
		{name:'voyage',index:'voyage', width:100, align:"center"},
		{name:'ata',index:'ata', width:80, align:"center",search:false},
		{name:'prd',index:'prd', width:80, align:"center",search:false},
		{name:'cust',index:'cust', width:195, align:"center"},
		{name:'op',index:'op', width:100, align:"center"}
	],
	rowNum:50,
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

function preview(a)
{
	window.open('<?=HOME;?>billing.bm/preview?id='+a); 

}

function cetak_nota(a)
{
	window.open('<?=HOME;?>billing.bm.cetak/cetak_nota?id='+a); 

}

function transfer(a,b)
{
	//alert(a);
	$.blockUI({ message: '<h1><br>Please wait... Transfer Nota BM</h1><br><img src=images/loadingbox.gif /><br><br>' });
$.post(urltransfer,{id_nota:a, id_rpstv:b}, function(data)
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
			$("#bm").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_rbm?q=nota_bm', datatype:"json"}).trigger("reloadGrid");
		});	
}
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/stevedoring.gif" height="9%" width="9%" style="vertical-align:middle"> <font color="blue">Nota </font> Bongkar Muat</h2></p>
	<p><br/><img src='images/printer.png' > : <i>digunakan untuk <B>print</B> Nota BM</i><BR/><img src='images/trflfg2.png' > : <i>digunakan untuk <b>transfer</b> Nota BM</i></p>
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

<script type='text/javascript'>
function closed($id)
{
	var closed_	= $id;
	var url 	    = "<?=HOME?><?=APPID?>/closed #list";
	//$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src={$HOME}images/loadingbox.gif /><br><br>' }); 
	$.post(url,{closed : closed_},function(data){
		if(data == "OK")
			{
				//alert(data);
				alert("RBM closed");
			}
		else
			{
				//alert(data);
				alert("failed");
			}
	});	
}

function save_pranota($id)
{
	var no_ukk_	= $id;
	var url    	= "<?=HOME?><?=APPID?>/save_pranota";
	var r		= confirm("Pranota will be saved, Please make sure that the pranota is totally right");
	if (r==true)
	  {
		var s		= confirm("Are You Sure?");
		if (s==true)
		{
				$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src={$HOME}images/loading81.gif width="30" height="30" /><br><br>' }); 
				$.post(url,{no_ukk : no_ukk_},function(data){
				if(data == "OK")
					{
						$.unblockUI({
						onUnblock: function(){ }
						});
						$("#bm").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=bm", datatype:"json"}).trigger("reloadGrid");
						alert('Pranota Saved');
					}
				else
					{
						$.unblockUI({
						onUnblock: function(){ }
						});
						$("#bm").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=bm", datatype:"json"}).trigger("reloadGrid");
						alert('Pranota Saved');
					}
				});	
		} else {
			$("#bm").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=bm", datatype:"json"}).trigger("reloadGrid");
		}
	  }
	else
	  {
		$("#bm").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=bm", datatype:"json"}).trigger("reloadGrid");
	  }
	
}
</script>
