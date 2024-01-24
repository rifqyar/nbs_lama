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
.ui-jqgrid tr.jqgrow td {
        white-space: normal !important;
    }
</style>

<script type="text/javascript">
jQuery(function() {
 jQuery("#nota_delivery").jqGrid({
	url:'<?=HOME?>datanya/data_cont?q=nota_delivery_full',
	mtype : "post",
	datatype: "json",
	colNames:['Req Date','','Status','Jenis Request','F/M','No Proforma','No Request','Qty','EMKL','Vessel'],
	colModel:[
		{name:'reqd',index:'reqd', width:120, align:"center", datefmt:'Y/m/d h:i:s'},
		{name:'aksi', width:75, align:"center",sortable:false,search:false},
		{name:'status',index:'status', width:60, align:"center"},
		{name:'tipe_req',index:'tipe_req', width:70, align:"center"},
		{name:'fm',index:'fm', width:40, align:"center"},
		{name:'id_nota',index:'id_nota', width:140, align:"center"},
		{name:'id_req',index:'id_req', width:140, align:"center"},
		{name:'qty',index:'qty', width:60, align:"center"},
		{name:'emkl',index:'emkl', width:200, align:"center"},
		{name:'vessel',index:'vessel', width:200, align:"center"},
		
	],
	rowNum:20,
	width: 865,
	height: "100%",
	rowList:[10,20,30,40,50,60],
	loadonce:true,
	sortname: 'reqd',
    sortorder: "desc",
	
	loadComplete: function(data) {
                    if (jQuery("#nota_delivery").jqGrid('getGridParam','datatype') === "json") {
                        setTimeout(function(){
                            jQuery("#nota_delivery").trigger("reloadGrid");
                        },100);
                    }
                },
    
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_nota_delivery',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Nota Delivery"
 });
  jQuery("#nota_delivery").jqGrid('navGrid','#pg_nota_delivery',{del:false,add:false,edit:false,search:false}); 
  jQuery("#nota_delivery").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
  
});

function recalc(a,b)
{
	
	var url="<?=HOME?>billing.delivery.ajax/recalc";
	$.post(url,{ID_NOTA:a,ID_REQ:b},function (data){
		alert(data);
		
	});
	
	
	//alert(a+' OKE '+b);
	return false;
}

function confirm_no_charge()
{
	var answer = confirm("Are you sure you want to make the request become no charge?");
	if(answer != 0){
		var url="<?=HOME;?>billing.delivery/no_charge";
			$.post(url,{},function(data){	
			alert(data);			
		});	
	}
}

function cek_save(tipe_req,id_req){
	var url="<?=HOME?>billing.delivery.full.ajax/cek_save";
	$.post(url,{noreq:id_req},function (data){
		if(data=="1"){
			alert("Request belum disave. Silahkan save terlebih dahulu!");
			return(false);
		} else {
			window.location="<?=HOME?>billing.delivery.full/preview?tipereq="+tipe_req+"&id="+id_req;
		}
	});
}

</script>

<div class="content">
	<div class="main_side">
	<p>
	<h2> <img src="<?=HOME?>images/delivery.png" height="5%" width="5%" style="vertical-align:middle"> Billing SP2 Delivery - Full Container</h2></p>
	
	<p><br/>
	  </p>
	<p><br/></p>
	<table id='nota_delivery' width="100%"></table> <div id='pg_nota_delivery'></div>

	<br/>
	<br/>
	</div>
</div>