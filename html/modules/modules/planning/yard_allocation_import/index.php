<!--<link rel="stylesheet" href="yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
<script src="yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="yard/src/css/main_yai.css">
<link type="text/css" href="css/default.css" rel="stylesheet" />
<link type="text/css" href="css/application.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.20.custom.css" />
<script type="text/javascript" src="js/ajax.js"></script>-->
<?php
    $db			   = getDb();
    $query_        = "SELECT ID, NAMA_YARD FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
    $result_       = $db->query($query_);
    $yard          = $result_->fetchRow();
    $nama_ya       = $yard['NAMA_YARD'];
	$yard_id       = $yard['ID'];
    $id_bg   	   = 1;
?>
<html>
<head>
 <script>
$(function() {
$( "#tabs" ).tabs();
$( "#tabspage" ).tabs();
$( "#tabspage1" ).tabs();
});
</script>
</head>
<body>
<span class="graybrown"><img src='<?=HOME?>images/layout-icon.gif' width="50" height="50" border='0' class="icon"/> &nbsp Yard Allocation Import</span><br/><br/>

<div id="tabs">
<ul>
<li><a href="#tabs-1">Yard Allocation Import</a></li>
<li><a href="#tabs-2">Yard Allocation Monitoring</a></li>
<li><a href="#tabs-3">Logging Yard Allocation</a></li>
</ul>
<div id="tabs-1">	
	<b><font size="5">Vessel-Voy: </font></b><input style="font-size:18px; font-weight:bold;" id="pelabuhan" name="pelabuhan" size="20" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" />
		<input style="font-size:18px; font-weight:bold;" id="voy_a" name="voy_a" size="10" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" />	
		<input style="font-size:18px; font-weight:bold;" id="no_ukk" name="no_ukk" size="10" title="entry" class="suggestuwriter" type="hidden" maxlength="16" value="" />	
		  <input type="button" value=" Go " onclick="get_detail()"> </input>
<br><br>
<table id='info_group' width="80%"></table> <div id='pg_l_gr'></div>
<br />
<table id='master_grouping' width="80%"></table> <div id='pg_l_gro'></div>
<div id="master_gr"></div>
<div id="yai"></div>
<div id="hapus"></div>
</div>
<div id="tabs-2">
	<div id="load_layout" ALIGN="center" style="margin-top:0px;border:1px solid black;width:860;height:900;overflow-y:scroll;overflow-x:scroll;"></div>
	
	<br><br>
<div>
<table id='list_kategori' width="80%"></table> <div id='pg_l_kat'></div>
</div>
</div>
<div id="tabs-3">
<div>
<table id='log_alokasi' width="80%"></table> <div id='pg_l_log'></div>
</div>
</div>
	
</div>
</body>
</html>
<style>
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
<?  
	//$_SESSION['NO_UKK'] ='';
	$_SESSION['ID_BOOK'] ='';
	$no_ukk 		= $_SESSION['NO_UKK'];
	$query2        	= "SELECT CONCAT(CONCAT(CONCAT(CONCAT(CONCAT(NM_KAPAL,' [ '),VOYAGE_IN),'/'), VOYAGE_OUT),' ] ') KAPAL FROM RBM_H WHERE NO_UKK = '$no_ukk'";
    $result2        = $db->query($query2);
    $yard2          = $result2->fetchRow();
    $kapal          = $yard2['KAPAL'];
?>
<script>

jQuery(function() {
 jQuery("#master_grouping").jqGrid({
	url:'<?=HOME?>datanya/data_2?q=master_grouping&no_ukks=<?=$no_ukk?>',
	mtype : "post",
	datatype: "json",
	colNames:['ACTION','Kategori', 'BOX','Teus','Allocated','Allocated Left','User'], 
	colModel:[
		{name:'act', width:90, align:"center",sortable:false,search:false},
		{name:'size', width:70, align:"center"},
		{name:'type', width:70, align:"center",sortable:false,search:false},
		{name:'height', width:50, align:"center",sortable:false,search:false},
		{name:'hz', width:100, align:"center",sortable:false,search:false},
		{name:'plug', width:100, align:"center",sortable:false,search:false},
		{name:'allo', width:120, align:"center",sortable:false,search:false}
	],
	rowNum:50,
	width: 860,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_gro',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Grouping Container Detail <?=$kapal?>"
 });
	
  jQuery("#master_grouping").jqGrid('navGrid','#pg_l_gro',{del:false,add:false,edit:false,search:false}); 
 //jQuery("#master_group").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


jQuery(function() {
 jQuery("#log_alokasi").jqGrid({
	url:'<?=HOME?>datanya/data_2?q=log_alokasi',
	mtype : "post",
	datatype: "json",
	colNames:['Kategori', 'Nama Block','SLOT','ROW','Tanggal Update','Activity','User ID'], 
	colModel:[
		{name:'act', width:100, align:"center",sortable:false,search:false},
		{name:'size', width:100, align:"center"},
		{name:'type', width:100, align:"center",sortable:false,search:false},
		{name:'height', width:100, align:"center",sortable:false,search:false},
		{name:'hz', width:100, align:"center",sortable:false,search:false},
		{name:'plug', width:100, align:"center",sortable:false,search:false},
		{name:'allo', width:100, align:"center",sortable:false,search:false}
	],
	rowNum:50,
	width: 860,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_log',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Log Allocation Planning"
 });
 
  jQuery("#log_alokasi").jqGrid('navGrid','#pg_l_log',{del:false,add:false,edit:false,search:false}); 
 //jQuery("#master_group").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


jQuery(function() {
 jQuery("#list_kategori").jqGrid({
	url:'<?=HOME?>datanya/data_2?q=list_kategori_im',
	mtype : "post",
	datatype: "json",
	colNames:['Allocation Block','Kategori','Vessel - Voy','Allocation Plan Qty','Alocation Date','User',], 
	colModel:[
	{name:'all', width:60, align:"center",sortable:false,search:false},
		{name:'hz', width:50, align:"center"},
		{name:'ves', width:150, align:"center",sortable:false,search:false},
		{name:'apq', width:60, align:"center",sortable:false,search:false},
		{name:'dt', width:60, align:"center",sortable:false,search:false},
		{name:'us', width:60, align:"center",sortable:false,search:false}
		
	],
	rowNum:50,
	width: 860,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_kat',
	viewrecords: true,
	shrinkToFit: true,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Matrix Kategori"
 });
  jQuery("#list_kategori").jqGrid('navGrid','#pg_l_kat',{del:false,add:false,edit:false,search:false})
  .navButtonAdd('#pg_l_kat',{
   caption:"Print Allocation", 
   buttonicon:"ui-icon-add", 
   onClickButton: function(){		
		print_alokasi();
		//shift('{$no_ukk}');
   }, 
   position:"last"
  }); 
  //jQuery("#list_kategori").jqGrid('navGrid','#pg_l_kat',{del:false,add:false,edit:false,search:false}); 
 jQuery("#list_kategori").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

jQuery(function() {
 jQuery("#info_group").jqGrid({
	url:'<?=HOME?>datanya/data_2?q=info_group&no_ukks=<?=$no_ukk?>',
	mtype : "post",
	datatype: "json",
	colNames:['ACTION','SIZE', 'TYPE', 'STATUS','HEIGHT','HZ','PLUG','ALLO REQUIRED','USER ID','DATE PLAN'], 
	colModel:[
		{name:'act', width:90, align:"center",sortable:false,search:false},
		{name:'size', width:70, align:"center"},
		{name:'type', width:70, align:"center"},
		{name:'status', width:70, align:"center"},
		{name:'height', width:50, align:"center",sortable:false,search:false},
		{name:'hz', width:20, align:"center",sortable:false,search:false},
		{name:'plug', width:40, align:"center",sortable:false,search:false},
		{name:'allo', width:120, align:"center",sortable:false,search:false},
		{name:'user', width:60, align:"center",sortable:false,search:false},
		{name:'date', width:80, align:"center",sortable:false,search:false}
	],
	rowNum:50,
	width: 860,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_gr',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Grouping Container Detail <?=$kapal?>"
 });
  jQuery("#info_group").jqGrid('navGrid','#pg_l_gr',{del:false,add:false,edit:false,search:false})
  .navButtonAdd('#pg_l_gr',{
   caption:"Master Group", 
   buttonicon:"ui-icon-add", 
   onClickButton: function(){		
		master_gr();
		//shift('{$no_ukk}');
   }, 
   position:"last"
  }); 
  jQuery("#info_group").jqGrid('navGrid','#pg_l_gr',{del:false,add:false,edit:false,search:false}); 
 //jQuery("#master_group").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});


function get_detail()
{
	var no_ukk = $("#no_ukk").val(); 
	$("#master_grouping").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=master_grouping&no_ukks="+no_ukk+"", datatype:"json"}).trigger("reloadGrid");	
	
	$("#info_group").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data_2?q=info_group&no_ukks="+no_ukk+"", datatype:"json"}).trigger("reloadGrid");
}

function print_alokasi(){

	var id_vs		= '<?=$no_ukk?>';
	
	var url 	    = "<?=HOME?>planning.yard_allocation_import.toexcel/toexcel?id_vs="+id_vs;
	
	window.open(url, "_blank");

}


</script>
<script>
$(document).ready(function() 
{
	
    $('#load_layout').html('<img src="<?=HOME?>images/loadingF.gif" />');
    $('#load_layout').load("<?=HOME?>planning.yard_allocation_import/load_layout?id=<?=$yard_id?> #load_layout"); 
	
	$('#block_id').change(function(){
		var kate=$('#kategori').val();
		alert($('#kategori').val());
		var blid = $('#block_id').val();
		$.ajaxSetup ({
		// Disable caching of AJAX responses
		cache: false
		});
		
		
	   $('#info').load("<?=HOME?>planning.yard_allocation_import.ajax/load_info?id=<?=$yard_id?>&id_block="+blid+"&kategori="+kate);   
		$('#lp<?=$id_bg?>').load("<?=HOME?>planning.yard_allocation_import.ajax/load_lapangan?id=<?=$yard_id?>&id_bg=<?=$id_bg?>&id_block="+blid+"&kategori="+kate);   
		$('#detail_slot').load("<?=HOME?>planning.yard_allocation_import.ajax/detail_slot?id=<?=$yard_id?>&id_block="+blid);   
		
	});
	
    $( "#pelabuhan" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>planning.booking.auto/vessel",
		focus: function( event, ui ) {
			$( "#pelabuhan" ).val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) {
			$( "#pelabuhan" ).val( ui.item.NM_KAPAL);
            $( "#no_ukk" ).val( ui.item.NO_UKK);
			$( "#voy_a" ).val( ui.item.VOYAGE);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_KAPAL + " | " + item.VOYAGE + "</a>")
			.appendTo( ul );
	};  

	
	
});



function plan_t(a)
{
	//alert(dama);
	$('#yai').load("<?=HOME?>planning.yard_allocation_import.ajax/yai?kategori="+a).dialog({modal:true, height:1000,width:1000,title: "Yard Allocation Planning Import"});
}

function hapus()
{
	//alert(dama);
	$('#hapus').load("<?=HOME?>planning.yard_allocation_import.ajax/hapus").dialog({modal:true, height:100,width:300,title: "Hapus Alokasi"});
}


function master_gr()
{
	var noukk=$('#no_ukk').val();
	if (noukk == ''){
		noukk = '<?=$no_ukk?>';
	}
	//alert(noukk);
	$('#master_gr').load("<?=HOME?>planning.yard_allocation_import.ajax/master?NO_UKK="+noukk+"").dialog({modal:true, height:400,width:500,title: "Master Grouping"});
}
 

</script>
