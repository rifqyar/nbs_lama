<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.butsave {
    background: none repeat scroll 0 0 #CCCCCC;
    border-color: #CCCCCC black black #CCCCCC;
    border-style: solid;
    border-width: 2px;
	margin-bottom: 10px;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 20px;
    padding: 4px 10px 3px 7px;
    width: 100%;
}
</style>
<script type="text/javascript">
jQuery(function() {
 jQuery("#del_spjm").jqGrid({
	url:'<?=HOME?>datanya/data_spjm?q=del_spjm&template=false',
	mtype : "post",
	datatype: "json",
	colNames:['No Request','No SPJM','Jenis','Request Date','Vessel - Voyage','Qty Container','User'], 
	colModel:[
		{name:'req',index:'req', width:110, align:"center"},
		{name:'spjm',index:'req', width:90, align:"center"},
		{name:'jenis',index:'req', width:90, align:"center"},
		{name:'req_dt',index:'req_dt', width:100, align:"center",search:false},
		{name:'ves',index:'ves', width:250, align:"center",search:false},
		{name:'jum_cont',index:'jum_cont', width:80, align:"center",sortable:false,search:false},
		{name:'user',index:'user', width:80, align:"center",search:false},
	],
	rowNum:20,
	width: 875,
	height: "100%",//250
	sortname: "No Request",
	sortorder: "desc",
	rowList:[10,20,30,40,50,60],
	//loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_del_spjm',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data SP2 SPJM"
 });
  jQuery("#del_spjm").jqGrid('navGrid','#pg_del_spjm',{del:false,add:false,edit:false,search:false}); 
 jQuery("#del_spjm").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

function add_reqspjm()
{
	$('#add_reqspjm').load("<?=HOME?>request.delivery.sp2spjm.ajax/spjm_insert").dialog({modal:true, height:600,width:560,title: "Insert Request SP2 TPFT"});
}

function add_reqspjm_manual()
{
	$('#add_reqspjm').load("<?=HOME?>request.delivery.sp2spjm.ajax/spjm_insert_manual").dialog({modal:true, height:600,width:560,title: "Insert Request SP2 TPFT Manual"});
}

function print_plp(idplp)
{
	$('#printplp').load("<?=HOME?>request.plplini1.ajax/plp_print?plpid="+idplp).dialog({modal:true, height:100,width:300,title: "Cetak PLP Terminal"});
}

function perp_sp2spjm(iddel)
{
	$('#approve_plp').load("<?=HOME?>request.delivery.sp2spjm.ajax/perp_spjm?id_del="+iddel).dialog({modal:true, height:480,width:495,title: "Perpanjangan SP2 SPJM"});
}

function perp_entry(iddel)
{
	var tglexp = $("#tgl_perp").val();
	var url = "<?=HOME?>request.delivery.sp2spjm.ajax/update_spjm";
	var r = confirm("Data akan disimpan, anda yakin?");
		if(r == true)
		{
			$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
			$.post(url,{
						ID_DEL : iddel,
						TGL_DEL : tglexp
			},function(data){
				//alert(data);
				if(data == "OK")
					{
						$.unblockUI({
						onUnblock: function(){ }
						});
						alert("Success...");
						window.location = "<?=HOME?>request.delivery.spjm/";
					}
				else
					{
						$.unblockUI({
						onUnblock: function(){ }
						});
						alert("Failed...");
						window.location = "<?=HOME?>request.delivery.spjm/";
					}
			});	
		}
		else
		{
			return false;
		}
}

</script>



<div class="content">
<div class="main_side">
<p>
<span class="graybrown">
<img class="icon" border="0" src="images/truckyellow1.png" height="60px" width="60px" style="vertical-align:middle">
Permintaan SP2 TPFT
</span>
</p>
<br>
<p>
<!--<a class="link-button" href="<?=HOME?>request.behandle/add_req">-->
<a class="link-button" onclick="add_reqspjm()">
<img border="0" src="images/tambah.png">
Request SP2
</a>
&nbsp;
<a class="link-button" onclick="add_reqspjm_manual()">
<img border="0" src="images/tambah.png">
Request Manual
</a>
</p>
<br/>
<table id='del_spjm' width="100%"></table> <div id='pg_del_spjm'></div>
<p><br/></p>
<div id="dialog-form">
<form id="mainform">
	<!--<input type="hidden" name="tes" value="tes"/>-->
	<div id="add_reqspjm"></div>
	<div id="printplp"></div>
	<div id="approve_plp"></div>
</form>
</div>

</div>	

</div>

