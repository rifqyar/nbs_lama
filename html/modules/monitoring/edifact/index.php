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
.ui-jqgrid .ui-jqgrid-htable th div {
    height:auto;
    overflow:hidden;
    padding-right:4px;
    padding-top:2px;
    position:relative;
    vertical-align:text-top;
    white-space:normal !important;
}

</style>
<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
$backuri = str_replace('&_saveok=1', '', $_SERVER['REQUEST_URI']);
$_SESSION['__uriback'][APPID] = $backuri . (strpos($_SERVER['REQUEST_URI'], '?') === false ? '?' : '');

?>

<script>

function codeco(no_ukk)
{
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loadingbox.gif /><br><br>' });
		var url = "<?=HOME?>monitoring.edifact.ajax/create_codeco_msl";
		//var url2 = "<?=HOME?>monitoring.edifact.ajax/create_codeco_evg";
		
		$.post(url,{NO_UKK : no_ukk},function(data) 
				  {
					console.log(data);
					/*
					var explode6 = data.split(',');
					var status = explode6[0];
					var file_name1 = explode6[1];
					var file_name2 = explode6[2];
					*/
					
					if (data="sukses")
					{
						$.unblockUI({
						onUnblock: function(){  }
						});
						alert("Success");
						window.location = "<?=HOME?>monitoring.edifact/";
						
						/*
						if(file_name1!='')
						{
							window.open('./edifact/'+file_name1,'_blank');
						}
						
						if(file_name2!='')
						{
							window.open('./edifact/'+file_name2,'_blank');
						}
						*/
					}
					else
					{
						$.unblockUI({
						onUnblock: function(){  }
						});
						alert("Failed");
						window.location = "<?=HOME?>monitoring.edifact/";
					}
			      });
	}
	else
	{
		return false;
	}
}

function coarri(no_ukk)
{
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loadingbox.gif /><br><br>' });
		var url = "<?=HOME?>monitoring.edifact.ajax/create_coarri_msl";
		//var url2 = "<?=HOME?>monitoring.edifact.ajax/create_coarri_oocl";
		
		$.post(url,{NO_UKK : no_ukk},function(data) 
				  {
					console.log(data);
					/*
					var explode6 = data.split(',');
					var status = explode6[0];
					var file_name1 = explode6[1];
					var file_name2 = explode6[2];
					*/
					
					if (data="sukses")
					{						
						$.unblockUI({
						onUnblock: function(){  }
						});
						alert("Success");
						window.location = "<?=HOME?>monitoring.edifact/";
						
						/*
						if(file_name1!='')
						{
							window.open('./edifact/'+file_name1,'_blank');
						}
						
						if(file_name2!='')
						{
							window.open('./edifact/'+file_name2,'_blank');
						}
						*/
					}
					else
					{
						$.unblockUI({
						onUnblock: function(){  }
						});
						alert("Failed");
						window.location = "<?=HOME?>monitoring.edifact/";
					}
			      });
	}
	else
	{
		return false;
	}
}

function baplie(no_ukk)
{
	var r=confirm("Are you sure?");
	if (r==true)
	{
		$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loadingbox.gif /><br><br>' });
		var url = "<?=HOME?>monitoring.edifact.ajax/create_baplie_evg";
		$.post(url,{NO_UKK : no_ukk},function(data) 
					  {
						console.log(data);
						
						if (data="sukses")
						{
							$.unblockUI({
							onUnblock: function(){  }
							});
							alert("Success Generated...!!!");							
							window.location = "<?=HOME?>monitoring.edifact/";							
						}
						else
						{
							$.unblockUI({
							onUnblock: function(){  }
							});
							alert("Failed");
							window.location = "<?=HOME?>monitoring.edifact/";
						}
					  });
	}
	else
	{
		return false;
	}
}

function coarri_select(no_ukk)
{
	$('#edi_coarri').load("<?=HOME?>monitoring.edifact.ajax/coarri_select/?no_ukk="+no_ukk).dialog({modal:true, height:180,width:200,title: "Coarri"});
}

function baplie_select(no_ukk)
{	
	$('#edi_baplie').load("<?=HOME?>monitoring.edifact.ajax/baplie_select/?no_ukk="+no_ukk).dialog({modal:true, height:180,width:200,title: "Baplie"});
}

</script>

<script type="text/javascript">
jQuery(function() {
 jQuery("#booking").jqGrid({
	url:'<?=HOME?>datanya/data_edifact?q=edifact_file',
	mtype : "post",
	datatype: "json",
	colNames:['CODECO<br/>COARRI','BAPLIE','MANUAL<br/>ACTION','NO UKK','VESSEL','VOYAGE','ARRIVAL','DEPARTURE','POD','POL'], 
	colModel:[
		{name:'coco', width:70, align:"center",sortable:false,search:false},
		{name:'bp', width:60, align:"center",sortable:false,search:false},
		{name:'sch', width:80, align:"center",sortable:false,search:false},
		{name:'nukk',index:'nukk', width:100, align:"center"},
		{name:'vessel',index:'vessel', width:160, align:"center"},
		{name:'voyage',index:'voyage', width:100, align:"center"},
		{name:'arv',index:'arv', width:110, align:"center"},
		{name:'dpr',index:'dpr', width:110, align:"center"},
		{name:'pod',index:'pod', width:110, align:"center"},
		{name:'pol',index:'pol', width:110, align:"center"}		
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 20,
	gridview: true,
	pager: '#pg_l_booking',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Vessel Voyage"
 });
  jQuery("#booking").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
 jQuery("#booking").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/edifact.png" height="7%" width="7%" style="vertical-align:middle"> <font color="blue">Edifact </font> Generator</h2></p>
	<p><br/></p>
	<table id='booking' width="100%"></table> <div id='pg_l_booking'></div>
<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
	</form>
	<form>
		<div id="edi_coarri"></div>
	</form>
	<form>
		<div id="edi_baplie"></div>
	</form>
	</div>
	<br/>
	</div>
</div>
