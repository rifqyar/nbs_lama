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

<script>
function tambah(b)
{
	//alert(b);
	$('#table_profil').load("<?=HOME?>planning.booking/insert_booking?id="+b+" #add_booking").dialog({modal:true, height:800,width:700,title: 'booking shipping line'});
	//$('#detail').load("<?=HOME?>planning.booking/detail_booking?id="+b+" #list").dialog({modal:true, height:280,width:400,title: 'booking shipping line'});
}

function add_booking(id_vs)
{
	//alert(id_vs);
	alert($("#size").val());
	var size_ = $("size").val;
	var type_ = $("type").val;
	var status_ = $("status").val;
	var box_ = $("box").val;
	var teus_ = $("teus").val;
	var pelabuhan_ = $("pelabuhan").val;
	var id_pelabuhan_ = $("id_pelabuhan").val;
	var id_vs_ = id_vs;
	var url		= "{$HOME}planning.booking.ajax/booking_ship";
	
	//alert(size_);
	$.post(url,{ID_VS: id_vs_, SIZE : size_, TYPE : type_, STATUS : status_, BOX : box_, TEUS : teus, PELABUHAN : pelabuhan_,ID_PELABUHAN : id_pelabuhan_ },function(data){
		console.log(data);
		if(data == "sukses")
		{
			alert("Data Booking Disimpan");
			//window.location = "{$HOME}{$APPID}";
		}
		else
		{
			alert("Gagal Simpan Data Booking...!!!");
		}		
	});
}

function get_data_header(i)
{
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loadingbox.gif /><br><br>' });
	var url="<?=HOME?>monitoring.cont_move.excel.download/get_cont_ict";
	$.post(url,{NO_UKK : i},function(data) 
				  {
					if (data="sukses")
					{
						$.unblockUI({
						onUnblock: function(){  }
						});
						$("#booking").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_cont_move?q=cont_move', datatype:"json"}).trigger("reloadGrid");
					}
			      });	
}
function sync_cont_move(i)
{
	$.blockUI({ message: '<h1><br>Please wait...re-update Detail Container Movement</h1><br><img src=<?=HOME;?>images/loadingbox.gif /><br><br>' });
	var url="<?=HOME?>monitoring.cont_move.excel.download/sync_cont";
	$.post(url,{NO_UKK : i},function(data) 
				  {
					if (data="sukses")
					{
					//alert('test');
						$.unblockUI({
						onUnblock: function(){  }
						});
						$("#booking").jqGrid('setGridParam',{url:'<?=HOME?>datanya/data_cont_move?q=cont_move', datatype:"json"}).trigger("reloadGrid");
					}
			      });	
}

function shift(i)
{
	$('#shift_r').load("<?=HOME?>billing.rbm.ajax/shifting_rbm?no_ukk="+i).dialog({modal:true, height:400,width:400});
}

function hatch(i)
{
	$('#hatch_r').load("<?=HOME?>billing.rbm.ajax/hatch_rbm?no_ukk="+i).dialog({modal:true, height:400,width:400});
}

function codeco(no_ukk)
{
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loadingbox.gif /><br><br>' });
	var act = document.getElementById("codeco_act").value;
	var url = "<?=HOME?>monitoring.cont_move.ajax/create_codeco";
	$.post(url,{NO_UKK : no_ukk, ACT : act},function(data) 
				  {
					console.log(data);
					if (data="sukses")
					{
						$.unblockUI({
						onUnblock: function(){  }
						});
						alert("Success");
						window.location = "<?=HOME?>monitoring.cont_move/";
					}
					else
					{
						$.unblockUI({
						onUnblock: function(){  }
						});
						alert("Failed");
						window.location = "<?=HOME?>monitoring.cont_move/";
					}
			      });
}

function ba_entry()
{
	var ref_humas	= document.getElementById("no_ref_humas").value;
	var simop    	= document.getElementById("simop").value;
	var terminal	= document.getElementById("terminal").value;
	var kd_plg  	= document.getElementById("kd_pj").value;	
	var url			= "<?=HOME?>monitoring.ba.ajax/add_ba";
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
	$.post(url,{REF : ref_humas, SIMOP : simop, TERMINAL : terminal, KD_PLG : kd_plg},function(data){
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

function codeco_select(no_ukk)
{
	$('#edi_codeco').load("<?=HOME?>monitoring.cont_move.ajax/codeco_select/?no_ukk="+no_ukk).dialog({modal:true, height:180,width:200,title: "Codeco"});
}
</script>

<script type="text/javascript">
jQuery(function() {
 jQuery("#booking").jqGrid({
	url:'<?=HOME?>datanya/data_cont_move?q=cont_move',
	mtype : "post",
	datatype: "json",
	//NO_UKK, ID_VESSEL, VESSEL, VOYAGE_IN ,VOYAGE_OUT, RTA, RTD, PEL_ASAL, PEL_TUJUAN, PEL_BERIKUTNYA
	colNames:['Sync Container','Export File','No UKK', 'Detail', 'Vessel Code','Vessel','Voyage','RTA','RTD','POL','POD','PND'], 
	colModel:[
		{name:'sync', width:100, align:"center",sortable:false,search:false},
		{name:'aksi1', width:140, align:"center",sortable:false,search:false},
		{name:'nukk',index:'voyage', width:100, align:"center"},
		{name:'list',index:'list', width:60, align:"center",sortable:false,search:false},
		{name:'vc',index:'vc', width:80, align:"center"},
		{name:'nama_kapal',index:'nama_kapal', width:120, align:"center"},
		{name:'voyage',index:'voyage', width:100, align:"center"},
		{name:'rta',index:'rta', width:80, align:"center"},
		{name:'rtd',index:'rtd', width:80, align:"center"},
		{name:'p_a',index:'p_a', width:140, align:"center"},
		{name:'p_b',index:'p_b', width:140, align:"center"},
		{name:'p_t',index:'p_t', width:140, align:"center"}
		
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
	caption:"Header Shipping Line"
 });
  jQuery("#booking").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
 jQuery("#booking").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/icon_mvmt.png" height="7%" width="7%" style="vertical-align:middle"> <font color="blue">Container Movement </font> Export/Import</h2></p>
	<p><br/></p>
	<table id='booking' width="100%"></table> <div id='pg_l_booking'></div>
<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
	</form>
	<form>
		<div id="edi_codeco"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>
<div id="shift_r"></div>
<div id="hatch_r"></div>



<script type='text/javascript'>
function closed_rbm($id)
{
	var no_ukk_		= $id;
	var url 	    = "<?=HOME?><?=APPID?>/closed_rbm";
	var r		= confirm("RBM will be saved, Please make sure that the RBM is totally right");
	if (r==true)
	  {
		var s		= confirm("Are you sure?");
		if (s==true)
		  {
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
		} else {
			$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
		}
	} else {
		$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
	}
}

function koreksi_rbm($id)
{
	var no_ukk_		= $id;
	var url 	    = "<?=HOME?><?=APPID?>/koreksi_rbm";
	var r		= confirm("RBM will be corrected?");
	if (r==true)
	  {
		var s		= confirm("Are you sure?");
		if (s==true)
		  {
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
			} else {
			$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
		}
	} else {
		$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
	}
}

function final_rbm($id)
{
	var no_ukk_		= $id;
	var url 	    = "<?=HOME?><?=APPID?>/final_rbm";
	var r		= confirm("RBM will be saved into 'FINAL', RBM cann't be edited anymore, Please make sure that the RBM is totally right");
	if (r==true)
	  {
		var s		= confirm("Are you sure?");
		if (s==true)
		  {
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
		} else {
			$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
		}
	} else {
		$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=rbm", datatype:"json"}).trigger("reloadGrid");
	}
}


</script>