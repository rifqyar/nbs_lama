<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;

}
.ui-jqgrid .ui-jqgrid-htable th div {
    height:auto;
    overflow:hidden;
    padding-right:4px;
    padding-top:2px;a
    position:relative;
    vertical-align:text-top;
    white-space:normal !important;
}
</style>

<script type='text/javascript'>

jQuery(function() {
 jQuery("#list_nota_plp").jqGrid({
	url:'<?=HOME?>datanya/data_bhd_tpft?q=list_nota_tpft',
	mtype : "post",
	datatype: "json",
	colNames:['Cetak','No Nota','No Pranota','Tanggal Pranota','Periode Kegiatan','Pengguna Jasa','Jumlah Container','Tagihan','Lunas','Tanggal Lunas'], 
	colModel:[
	   	{name:'cetak', width:40, align:"center",sortable:false,search:false},
		{name:'nota', width:110, align:"center",sortable:false},
		{name:'pranota', width:110, align:"center",sortable:false},
		{name:'tgl_pranota', width:60, align:"center",sortable:false,search:false},
		{name:'periode', width:120, align:"center",sortable:false,search:false},
		{name:'pengguna_jasa', width:180, align:"center",sortable:false,search:false},
		{name:'container', width:60, align:"center",sortable:false,search:false},
		{name:'tagihan', width:80, align:"center",sortable:false,search:false},
		{name:'lunas', width:40, align:"center",sortable:false,search:false},
        {name:'tgl_lunas', width:80, align:"center",sortable:false,search:false}		
	],
	rowNum:10,
	width: 865,
	height: "100%",//250
	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_cont',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Data Pranota Petikemas Behandle TPFT"
 });
  jQuery("#list_nota_plp").jqGrid('navGrid','#pg_l_cont',{del:false,add:false,edit:false,search:false}); 
 jQuery("#list_nota_plp").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


function preview_nota(id_pranota)
{
  //alert(id_pranota);
  window.open("<?=HOME?>billing.behandle_tpft/preview_nota_bhd?id_pranota="+id_pranota,'_blank');
}

function cetak_nota(id_pranota)
{
  //alert(id_pranota);
  window.open("<?=HOME?>billing.behandle_tpft.print/cetak_nota?id_pranota="+id_pranota,'_blank');
}
</script>

<script src="<?=HOME?>context_menu/jsprint.js" type="text/javascript"></script>
<div class="content" id="finds">
	<p>
	<h1> <img src="<?=HOME?>images/invoice3.png" height="5%" width="5%" style="vertical-align:middle">&nbsp;<font color="#0378C6">Billing</font> Behandle TPFT</h1></p>
	<p><br/>
	<hr width="870" color="#e1e0de"></hr><br/>
	<table id='list_nota_plp' width="100%"></table> 
	<div id='pg_l_cont'></div>
</div>


