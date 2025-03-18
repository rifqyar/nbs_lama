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

<script type="text/javascript">
function ReloadPage() {
	location.reload();
}
$(document).ready(function() {
	setTimeout("ReloadPage()", 180000);
});

jQuery(function() {
 jQuery("#nota_reexport").jqGrid({
	url:'<?=HOME?>datanya/data?q=nota_stackext',
	mtype : "post",
	datatype: "json",

	colNames:['','No Nota','No Request','Vessel','Voyage','Shipping Line','Jml Cont','Tgl Request'],
	colModel:[
		{name:'aksi', width:65, align:"center",sortable:false,search:false},
		{name:'no_pra',index:'no_pra', width:120, align:"center"},
		{name:'idreq',index:'idreq', width:100, align:"center"},
		{name:'vess',index:'vess', width:150, align:"center"},
		{name:'voy',index:'voy', width:80, align:"center"},
		{name:'shipp',index:'shipp', width:200, align:"center"},
		{name:'jml',index:'jml', width:80, align:"center"},
		{name:'tglreq',index:'tglreq', width:100, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 30,
	gridview: true,
	pager: '#pg_nota_stackext',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Nota Reexport"
 });
  jQuery("#nota_stackext").jqGrid('navGrid','#pg_nota_stackext',{del:false,add:false,edit:false,search:false});
 jQuery("#nota_stackext").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});

});

function recalculate(id_nota, no_req)
{
	$.blockUI({ message: '<h1><br>Please wait...re-calculate Pranota</h1><br><img src={$HOME}images/loadingbox.gif /><br><br>' });
	var url="<?=HOME?>billing.reexport.ajax/recalculate";
	$.post(url,{ID_NOTA : id_nota, NO_REQ : no_req},function(data)
				  {
					if (data="sukses")
					{
						$.unblockUI({
						onUnblock: function(){  }
						});
						$("#nota_reexport").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data?q=nota_reexport', datatype:"json"}).trigger("reloadGrid");
					}
					alert(data);
			      });
}
function transfer(a,b)
{
	//alert(a);
    if(confirm("Nota akan ditransfer ke SIMKEU, apakah anda yakin nota telah valid dan benar? Proses ini tidak bisa rollback")){
	$.blockUI({ message: '<h1><br>Please wait... Transfer Nota Stack Ext Export</h1><br><img src=images/loadingbox.gif /><br><br>' });
	var urltransfer = "<?=HOME?><?=APPID?>.ajax/transfernota";
$.post(urltransfer,{id_nota:a, id_rpstv:b}, function(data)
		{

			if(data=='T')
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
			$("#nota_reexport").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data?q=nota_stackext', datatype:"json"}).trigger("reloadGrid");
		});
    }
}
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Billing Perpanjangan Export</h2></p>

	<p><br/>
	  </p>
	<p><br/></p>
	<table id='nota_reexport' width="100%"></table> <div id='pg_nota_stackext'></div>

	<br/>
	<br/>
	</div>
</div>
