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

<script>
function tambah(b,c)
{
	//alert(b);
	$('#table_profil').load("<?=HOME?>planning.profil/insert_profil?id="+b+" #profil_input").dialog({modal:true, height:250,width:280,title: c});
}

function print_perbay(x,y)
{
	//alert(b);
	$('#table_profil').load("<?=HOME?>print.bayplan.perbay/get_bay?id="+x+" #print_html_perbay").dialog({modal:true, height:250,width:280,title: y});
}

function bayplan_disch(no_ukk,ves_voy)
{
	$('#table_bay_disch').load("<?=HOME?>report.bayplan.ajax/print_bay_disch?no_ukk="+no_ukk).dialog({modal:true, height:180,width:350,title: ves_voy});
}
function bayplan_load(no_ukk,ves_voy)
{
	$('#table_bay_load').load("<?=HOME?>report.bayplan.ajax/print_bay_load?no_ukk="+no_ukk).dialog({modal:true, height:180,width:350,title: ves_voy});
}

function vespro_print(no_ukk,ves_voy)
{
	$('#vespro').load("<?=HOME?>report.bayplan.ajax/vs_print?no_ukk="+no_ukk).dialog({modal:true, height:150,width:280,title: ves_voy});
}

function cetak_vs(no_ukk)
{
	var method_     = $("#print_act").val();

	if(method_=='WEIGHT')
	{
		var url = "<?=HOME?>report.bayplan.print/cetak_html?id="+no_ukk;
	}
	else
	{
		var url = "<?=HOME?>report.bayplan.print/cetak_html_cont?id="+no_ukk;
	}
	
	window.open(url, "_blank");
}

function cetak_bay_disch(no_ukk)
{
	var ukk = no_ukk;
	var print_ = document.getElementById("print_act").value;
	var bay_id_no = document.getElementById("bay_act").value;
	var bay_pss = document.getElementById("posisi_bay").value;
		var explode = bay_id_no.split(',');
		var bay_id = explode[0];
		var bay_no = explode[1];
	
	if((ukk=="")||(print_==""))
	{
		alert("Data Not Completed");
		return false;
	}
	else
	{
		if(print_=="BAY")
		{
			window.open('<?=HOME?>report.bayplan.print/bay_disch/?no_ukk='+ukk+'&id_bay='+bay_id+'&no_bay='+bay_no+'&posisi_bay='+bay_pss,'_blank');
		}
		else
		{
			alert("Module Not Created");
			return false;
		}
	}
}
function cetak_bay_load(no_ukk)
{
	var ukk = no_ukk;
	var print_ = document.getElementById("print_act").value;
	var bay_id_no = document.getElementById("bay_act").value;
	var bay_pss = document.getElementById("posisi_bay").value;
		var explode = bay_id_no.split(',');
		var bay_id = explode[0];
		var bay_no = explode[1];
	
	if((ukk=="")||(print_==""))
	{
		alert("Data Not Completed");
		return false;
	}
	else
	{
		if(print_=="BAY")
		{
			window.open('<?=HOME?>report.bayplan.print/bay_load/?no_ukk='+ukk+'&id_bay='+bay_id+'&no_bay='+bay_no+'&posisi_bay='+bay_pss,'_blank');
		}
		else
		{
			alert("Module Not Created");
			return false;
		}
	}
}
function bay_acc(id_vs,vesvoy)
{
	var id_vs_ = id_vs;
	var url	= "{$HOME}{$APPID}.ajax/acc_bay";	
	
	var r=confirm("Bayplan "+vesvoy+" akan disetujui, pastikan data sudah benar");
	if(r==true)
	{
		$.post(url,{REMARK : remark_},function(data){
		console.log(data);
			if(data == "OK")
			{			
				alert("Proses Invoice Berhasil");
				window.location = "{$HOME}{$APPID}";
			}
			else if(data == "NO")
			{
				alert("Data Tidak Lengkap...Proses Invoice Gagal");
			}
			else if(data == "gagal")
			{
				alert("Proses Invoice Gagal");
			}
			else if(data == "UPER")
			{
				alert("Uper Alat Belum Lunas...!!!");
			}
			else if(data == "RTD")
			{
				alert("Realisasi Keberangkatan Kapal Belum Terisi...!!!");
			}
			else if(data == "SUDAH")
			{
				alert("Sudah Invoice Sebelumnya...");
			}
		});
		return true;
	}
	else
	{
		return false;
	}	
}

</script>

<script type="text/javascript">
jQuery(function() {
 jQuery("#v_bayplan").jqGrid({
	url:'<?=HOME?>datanya/data_dso?q=v_bayplan&template=false',
	mtype : "post",
	datatype: "json",
	colNames:['BAYPLAN LOAD','BAYPLAN DISCH','NO UKK','VESSEL','VOYAGE','ARRIVAL','DEPARTURE','BAYPLAN APPROVAL'], 
	colModel:[
		{name:'action1',index:'action1', width:150, align:"center",sortable:false,search:false},
		{name:'action2',index:'action2', width:150, align:"center",sortable:false,search:false},
		{name:'ukk',index:'ukk', width:120, align:"center"},
		{name:'vessel',index:'vessel', width:180, align:"center"},
		{name:'voyage',index:'voyage', width:100, align:"center"},
		{name:'datang',index:'datang', width:150, align:"center"},		
		{name:'berangkat',index:'berangkat', width:150, align:"center"},
		{name:'bayplan',index:'bayplan', width:160, align:"center",sortable:false,search:false}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_v_bayplan',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Kapal"
 });
  jQuery("#v_bayplan").jqGrid('navGrid','#pg_v_bayplan',{del:false,add:false,edit:false,search:false}); 
 jQuery("#v_bayplan").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/dokumenbig.png" width="32" height="32"/>
        &nbsp;<font color="#0378C6">List</font> Bayplan
        </span></h2>	
	<p><br/></p>
    <table id='v_bayplan' width="100%"></table> <div id='pg_v_bayplan'></div>
	
	<div id="dialog-form">
	<form>
		<div id="table_bay_disch"></div>
		<div id="table_bay_load"></div>
	</form>
	<form>
		<div id="vespro"></div>
	</form>
	</div>
	<br/>
	<br/>
	</div>
</div>