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
</style>

<?
$id_user = $_SESSION["ID_USER"];
?>

<script>
function tambah(b,c)
{
	//alert(b);
	$('#table_profil').load("<?=HOME?>planning.profil.ajax/insert_profil?id="+b).dialog({modal:true, height:220,width:300,title: c});
}

function vessel_assign(no_ukk)
{
	//alert(b);
	var kd_kapal_ = document.getElementById("kd_kapal").value;
	var id_user_ = <?=$id_user?>;
	var url = "<?=HOME?>planning.profil.ajax/vs_assign";
	
	var r = confirm("Are you sure?");
	if(r == true)
	{	
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loading81.gif width="30" height="30" /><br><br>' }); 
		$.post(url,{ID_VS: no_ukk, KD_KAPAL : kd_kapal_, ID_USER : id_user_},function(data){
		console.log(data);
			if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Success...");
				window.location = "<?=HOME?>planning.profil/";
			}
			else
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Failed...!!!");
			}		
		});
	}
	else
	{
		return false;
	}
	
}

function insert_profil(id_vs)
{
	//alert(id_rq);
	var jml_bay_ = document.getElementById("jml_bay").value;
	var jml_row_ = document.getElementById("jml_row").value;
	var jml_tier_ondeck_ = document.getElementById("jml_tier_ondeck").value;
	var jml_tier_underdeck_ = document.getElementById("jml_tier_underdeck").value;
	var id_vs_ = id_vs;
	var url = "{$HOME}{$APPID}.ajax/insert_booking";
	
	$.post(url,{ID_VS: id_vs_, JML_BAY : jml_bay_, JML_ROW : jml_row_, JML_TIER_ONDECK : jml_tier_ondeck_, JML_TIER_UNDERDECK : jml_tier_underdeck_},function(data){
		console.log(data);
		if(data == "OK")
		{
			alert("Profil Kapal Disimpan");
			window.location = "{$HOME}{$APPID}";
		}
		else if(data == "gagal")
		{
			alert("Gagal Simpan Profil Kapal...!!!");
		}		
	});
}
</script>

<script type="text/javascript">
jQuery(function() {
 jQuery("#v_profil_kapal").jqGrid({
	url:'<?=HOME?>datanya/data_dso?q=v_profil_kapal&template=false',
	mtype : "post",
	datatype: "json",
	colNames:['UKK','VESSEL','VOYAGE','ARRIVAL','DEPARTURE','VESSEL PROFILE'], 
	colModel:[
		{name:'ukk',index:'ukk', width:120, align:"center"},
		{name:'vessel',index:'vessel', width:180, align:"center"},
		{name:'voyage',index:'voyage', width:100, align:"center"},
		{name:'datang',index:'datang', width:150, align:"center"},		
		{name:'berangkat',index:'berangkat', width:150, align:"center"},
		{name:'profile',index:'profile', width:120, align:"center",sortable:false,search:false}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_v_profil_kapal',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Kapal"
 });
  jQuery("#v_profil_kapal").jqGrid('navGrid','#pg_v_profil_kapal',{del:false,add:false,edit:false,search:false}); 
 jQuery("#v_profil_kapal").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/kapal_.gif" height="7%" width="7%" style="vertical-align:middle"/>
        &nbsp;<font color="#0378C6">Vessel</font> Profile
        </span></h2>	
	<p><br/></p>
    <table id='v_profil_kapal' width="100%"></table> <div id='pg_v_profil_kapal'></div>
	
	<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>