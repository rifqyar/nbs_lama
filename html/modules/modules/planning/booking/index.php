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
button{
	border-radius: 3px;
    border: 1px solid #d0d0d0;
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
	$('#table_profil').load("<?=HOME?>planning.booking.ajax/add_book?id_vessel="+b).dialog({modal:true, height:500,width:500,title: 'booking shipping line'});
	//$('#detail').load("<?=HOME?>planning.booking/detail_booking?id="+b+" #list").dialog({modal:true, height:280,width:400,title: 'booking shipping line'});
}

function add_booking(id_vs)
{
	
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

function op_st (i)
{
//alert(i);
	$('#open_stack').load("<?=HOME?>planning.booking.ajax/open_stack?id_vessel="+i).dialog({modal:true, height:300,width:300,title: 'Setting Open Stack'});
}
function cl_tm (i)
{
//alert(i);
	$('#closing_time').load("<?=HOME?>planning.booking.ajax/closing_time?id_vessel="+i).dialog({modal:true, height:300,width:300,title: 'Setting Closing Time'});
	
}


</script>

<script type="text/javascript">
jQuery(function() {
 jQuery("#booking").jqGrid({
	url:'<?=HOME?>datanya/data?q=booking',
	mtype : "post",
	datatype: "json",
	colNames:['','Vessel','Voyage','Open Stack','Closing Time','Closing Time Doc','ETA','ETD','P O L','P O D'], 
	colModel:[
		
		{name:'aksi2', width:70, align:"center",sortable:false,search:false},
		{name:'nama_kapal',index:'nama_kapal', width:140, align:"center"},
		{name:'voyage',index:'voyage', width:60, align:"center"},
		
		{name:'etd',index:'etd', width:100, align:"center",search:false},
		{name:'etd',index:'etd', width:100, align:"center",search:false},
		{name:'etd',index:'etd', width:100, align:"center",search:false},
		{name:'etd',index:'etd', width:100, align:"center",search:false},
		{name:'rta',index:'rta', width:100, align:"center"},
		{name:'rtd',index:'rtd', width:140, align:"center"},
		{name:'status',index:'status', width:140, align:"center"}
	],
	rowNum:20,
	width: 865,
	height: "100%",//250

	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_booking',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Booking Shipping Line"
 });
  jQuery("#booking").jqGrid('navGrid','#pg_l_booking',{del:false,add:false,edit:false,search:false}); 
 jQuery("#booking").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 
});
</script>

<div class="content">
	<div class="main_side">
	<p>
	<img src="<?=HOME?>images/bookd.png" height="7%" width="7%" style="vertical-align:middle"> 
	<b> <font color='#69b3e2' size='4px'>Booking</font> </b>
	 <font color='#888b8d' size='4px'>
	 Stack 
	 </font>
	</p>
	<p><br/></p>
	<table id='booking' width="100%"></table> <div id='pg_l_booking'></div>
<div id="dialog-form">
	<form>
		<div id="table_profil"></div>
	</form>
		<div id="open_stack"></div>
			<div id="closing_time"></div>
	</div>
	<br/>
	<br/>
	</div>
</div>