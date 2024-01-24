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
	url:'<?=HOME?>datanya/data_dso?q=m_ba',
	mtype : "post",
	datatype: "json",
	colNames:['NO REF HUMAS','TERMINAL','SIMOP','PENGGUNA JASA','TGL_MASUK','TGL_KELUAR','PIC','ACTION'], 
	colModel:[
		{name:'no_humas',index:'no_humas' , width:120, align:"center"},
		{name:'to',index:'to', width:80, height:40, align:"center"},
		{name:'simop',index:'simop', width:100, align:"center"},
		{name:'pbm',index:'pbm', width:250, align:"center"},
		{name:'tgl_masuk',index:'tgl_masuk', width:120, align:"center"},
		{name:'tgl_keluar',index:'tgl_keluar', width:120, align:"center"},
		{name:'pic',index:'pic', width:100, align:"center"},
		{name:'act',index:'act', width:200, align:"center",sortable:false,search:false}
	],
	rowNum:20,
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
	caption:"Monitoring BA"
 });
  jQuery("#booking").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
  //jQuery("#booking").grid.setGridHeight('50px');
 jQuery("#booking").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});

function sync_pelanggan()
{
	var cek_	= "0";	
	var url	= "<?=HOME?>monitoring.ba.ajax/sync_plg";
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loading81.gif width="30" height="30" /><br><br>' }); 
	$.post(url,{CEK : cek_},function(data){
		if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Success...");
				window.location = "<?=HOME?>monitoring.ba/";
			}
		else
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Failed...");
				window.location = "<?=HOME?>monitoring.ba/";
			}
	});
}

function sync_agen()
{
	var cek_	= "0";	
	var url	= "<?=HOME?>monitoring.ba.ajax/sync_agen";
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loading81.gif width="30" height="30" /><br><br>' }); 
	$.post(url,{CEK : cek_},function(data){
		if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Success...");
				window.location = "<?=HOME?>monitoring.ba/";
			}
		else
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Failed...");
				window.location = "<?=HOME?>monitoring.ba/";
			}
	});
}

function ba_entry()
{
	var ref_humas	= document.getElementById("no_ref_humas").value;
	var simop    	= document.getElementById("simop").value;
	var terminal	= document.getElementById("terminal").value;
	var kd_plg  	= document.getElementById("kd_pj").value;
    var kd_agen  	= document.getElementById("no_akun").value;	
	var url			= "<?=HOME?>monitoring.ba.ajax/add_ba";
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
	$.post(url,{REF : ref_humas, SIMOP : simop, TERMINAL : terminal, KD_PLG : kd_plg, KD_AGEN : kd_agen},function(data){
		if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Success...");
				window.location = "<?=HOME?>monitoring.ba/";
			}
		else if(data == "NO")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Data Not Complete...");
				window.location = "<?=HOME?>monitoring.ba/";
			}
		else
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Failed...");
				window.location = "<?=HOME?>monitoring.ba/";
			}
	});	
}

function confirm_out(id)
{
	var id_ba  	= id;	
	var url		= "<?=HOME?>monitoring.ba.ajax/confirm_out";
	
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{ID_BA : id_ba},function(data){
			console.log(data);
			if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
			else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
		});
	}
	else
	{
		return false;
	}	
}

function remarks_ba(id)
{
	var id_ba = id;
	var remarkba = document.getElementById("remark").value;
	var url = "<?=HOME?>monitoring.ba.ajax/remarks";
	
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{ID_BA : id_ba, REMARK_BA : remarkba},function(data){
			console.log(data);
			if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
			else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
		});
	}
	else
	{
		return false;
	}	
}

function assign_pic(id)
{
	var id_ba = id;
	var pic_ = document.getElementById("user_ba").value;
	var url	= "<?=HOME?>monitoring.ba.ajax/assign_pic";
	
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{ID_BA : id_ba, ID_USER : pic_},function(data){
			if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
			else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
		});
	}
	else
	{
		return false;
	}	
}

function del_ba(id)
{
	var id_ba = id;
	var url	= "<?=HOME?>monitoring.ba.ajax/del_ba";
	
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{ID_BA : id_ba},function(data){
			if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
			else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
		});
	}
	else
	{
		return false;
	}	
}

function ver_ba(id)
{
	var id_ba  	= id;	
	var url		= "<?=HOME?>monitoring.ba.ajax/verifikasi_ba";
	
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
		$.post(url,{ID_BA : id_ba},function(data){
			console.log(data);
			if(data == "OK")
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Success...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
			else
				{
					$.unblockUI({
					onUnblock: function(){ }
					});
					alert("Failed...");
					window.location = "<?=HOME?>monitoring.ba/";
				}
		});
	}
	else
	{
		return false;
	}	
}

function add_ba()
{
	$('#add_ba').load("<?=HOME?>monitoring.ba.ajax/ba_insert").dialog({modal:true, height:280,width:450, title : "Insert Berita Acara"});
}

function pic_ba(id)
{
	$('#pic_ba').load("<?=HOME?>monitoring.ba.ajax/pic_insert?id_ba="+id).dialog({modal:true, height:180,width:250, title : "Assign PIC"});
}

function insert_remarks(id)
{
	$('#insert_remarks').load("<?=HOME?>monitoring.ba.ajax/remarks_insert?id_ba="+id).dialog({modal:true, height:200,width:280, title : "Remarks Berita Acara"});
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/bookd.png" height="7%" width="7%" style="vertical-align:middle"> <font color="#81cefa">Monitoring </font>
	<font size="3px" color="#606263">Berita Acara</font></h2></p>
	<a class="link-button" style="height:25" onclick="add_ba()">
            <img border="0" src="<?=HOME?>images/tambah.png">
            Insert BA
            </a>
	<p><br/></p>
	<table id='booking' width="100%"></table> <div id='pg_l_booking'></div>
<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
		<div id="pic_ba"></div>
		<div id="add_ba"></div>
		<div id="insert_remarks"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>