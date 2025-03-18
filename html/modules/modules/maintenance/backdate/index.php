<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }
</style>
<script type="text/javascript">
jQuery(function() {
 jQuery("#l_backdate").jqGrid({
	url:'<?=HOME?>datanya/data?q=l_backdate',
	mtype : "post",
	datatype: "json",
	colNames:['No Ex Nota','No Request','Tanggal Koreksi','No Berita Acara', 'User','Keterangan'], 
	colModel:[
		
		{name:'EX_ID_NOTA',index:'EX_ID_NOTA', width:120, align:"center"},
		{name:'NO_REQUEST',index:'NO_REQUEST', width:150, align:"center"},
		{name:'TGL_KOREKSI',index:'TGL_KOREKSI', width:90, align:"center"},
		{name:'BA',index:'BA', width:150, align:"center"},
		{name:'USER',index:'USER', width:150, align:"center"},
		{name:'KETERANGAN',index:'KETERANGAN', width:150, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250
	sortname: "TGL_KOREKSI",
	sortorder: "desc",
	rowList:[10,20,30,40,50,60],
	//loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_backdate',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Koreksi Nota"
 });
  jQuery("#l_backdate").jqGrid('navGrid','#pg_l_backdate',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_backdate").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

function reqbh_entry()
{
	var url = "<?=HOME?>maintenance.backdate.ajax/add_reqbh";
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
	
	$.post(url,{TIPE : $("#jenis").val(), NOTA : $("#nota").val(), BA : $("#ba").val(), KET:$("#ket").val()}, function(data){
		alert(data);
		//console.log(data);
		if(data == "OK")
		{
			$.unblockUI({
			onUnblock: function(){ }
			});
			alert("Success...");
			window.location = "<?=HOME?>maintenance.backdate/";
		}
		else
		{
			$.unblockUI({
			onUnblock: function(){ }
			});
			alert("Failed...");
			//window.location = "<?=HOME?>maintenance.backdate/";
			
		}
	});	
}

function add_reqbh()
{
	$('#add_reqbh').load("<?=HOME?>maintenance.backdate.ajax/reqbh_insert").dialog({closeOnEscape: false, modal:true, height:400,width:600, title : "Insert Cancel Invoice", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
}

</script>



<div class="content">
<div class="main_side">
<p>
<span class="graybrown">
<img class="icon" border="0" src="images/invo2.png">
Cancel Invoice (Backdate)
</span>
</p>
<br>
<br>
<p>

<a class="link-button" onclick="add_reqbh()">
<img border="0" src="images/tambah.png">
Tambah
</a>
</p>
<br/>
<table id='l_backdate' width="100%"></table> <div id='pg_l_backdate'></div>
<div id="dialog-form">
<form id="mainform">

	<div id="add_reqbh"></div>
	<div id="edit_reqbh"></div>
</form>
</div>

</div>	

</div>
