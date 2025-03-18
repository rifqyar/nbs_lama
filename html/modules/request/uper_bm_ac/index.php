<?php
	//$tl = xliteTemplate('grid.htm');
	//$tl->renderToScreen();
?>

<style type="text/css">
	.ui-widget-content {
    border: 1px solid #aaaaaa;
    color: #222222;
</style>

<script type="text/javascript"> 
function ReloadPage() { 
	location.reload();
}
$(document).ready(function() {
	// setTimeout("ReloadPage()", 180000);
});

jQuery(function() {
 jQuery("#l_uper").jqGrid({
	url:'request.uper_bm_ac.data/',
	mtype : "post", 
	datatype: "json",
	colNames:['AKSI','NO UPER','UKK','VESSEL','VOYAGE','SHIPPING LINE','JUMLAH UPER','VALUTA','STATUS AC'], 
	colModel:[
		{name:'aksi', width:90, align:"center",sortable:false,search:false},
		{name:'NO_UPER',index:'NO_UPER', width:130, align:"left"},
		{name:'NO_UKK',index:'NO_UKK', width:100, align:"left"},
		{name:'NM_KAPAL',index:'NM_KAPAL', width:150, align:"left"},
		{name:'(VOYAGE_IN||VOYAGE_OUT)',index:'(VOYAGE_IN||VOYAGE_OUT)', width:60, align:"center",sortable:false},
		{name:'NM_PEMILIK',index:'NM_PEMILIK', width:150, align:"left"},
		{name:'TOTAL',index:'TOTAL', width:100, align:"right",search:false},
		{name:'VALUTA',index:'VALUTA', width:60, align:"center"},
		{name:'LUNAS',index:'LUNAS', width:80, align:"center",search:false}
	],
	rowNum:50,
	width: 875,
	height: "100%",
	rowList:[50,100],
	//loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	sortname: 'NO_UPER',
	sortorder: "DESC",
	pager: '#pg_uper',
	viewrecords: true,
	shrinkToFit: false,
	//addurl: "request.uper_bm/add/",
	caption:"Data Uper"
 }).navGrid('#pg_uper',{
	search:false,
	add:false,
	edit:false,
	del:false
 });
 jQuery("#l_uper").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
 $('#100120190827000051').css("background-color", "teal");
});

function uper_entry()
{
	var url = "<?=HOME?>request.uper_bm_ac.ajax/add_uper";
	var jum_detail = $("#jum_detail").val();
	var counter = "";
	var size = "";
	var type = "";
	var status = "";
	var height = "";
	var bahaya = "";
	var bongkar = "";
	var muat = "";
	var perdagangan = "";
	var keg = "";
	var subkeg = "";
	var kemasan = "";
	var satuan = "";
	var via = "";
	for (var i=1; i<=jum_detail; i++) {
		if(counter=="")	counter = $("#counter"+i).val();
		else			counter = counter + "&" + $("#counter"+i).val();
		if(size=="")	size = $("#size"+i).val();
		else			size = size + "&" + $("#size"+i).val();
		if(type=="")	type = $("#type"+i).val();
		else			type = type + "&" + $("#type"+i).val();
		if(status=="")	status = $("#status"+i).val();
		else			status = status + "&" + $("#status"+i).val();
		if(height=="")	height = $("#height"+i).val();
		else			height = height + "&" + $("#height"+i).val();
		if(bahaya=="")	bahaya = $("#bahaya"+i).val();
		else			bahaya = bahaya + "&" + $("#bahaya"+i).val();
		if(bongkar=="")	bongkar = $("#bongkar"+i).val();
		else			bongkar = bongkar + "&" + $("#bongkar"+i).val();
		if(muat=="")	muat = $("#muat"+i).val();
		else			muat = muat + "&" + $("#muat"+i).val();
		/*if(perdagangan=="")	perdagangan = $("#perdagangan"+i).val();
		else			perdagangan = perdagangan + "&" + $("#perdagangan"+i).val();*/
		if(keg=="")		keg = $("#keg"+i).val();
		else			keg = keg + "&" + $("#keg"+i).val();
		if(subkeg=="")	subkeg = $("#subkeg"+i).val();
		else			subkeg = subkeg + "&" + $("#subkeg"+i).val();
		if(kemasan=="")	kemasan = $("#kemasan"+i).val();
		else			kemasan = kemasan + "&" + $("#kemasan"+i).val();
		if(satuan=="")	satuan = $("#satuan"+i).val();
		else			satuan = satuan + "&" + $("#satuan"+i).val();
		if(via=="")		via = $("#via"+i).val();
		else			via = via + "&" + $("#via"+i).val();
	}

	var v_ORDER_NO 	= $('[name="ORDER_NO"]').val();
	var v_DORDER 	= $('[name="DORDER"]').val();
	var v_ORDERBY  	= $('[name="ORDERBY"]').val();
	var v_TERMINAL 	= $('[name="TERMINAL"]').val();
	//var v_TERM  	= $('[name="TERM"]').val();

	var v_PBM_ID 	  = $('[name="PBM_ID"]').val();
	var v_COMPANY_ID  = $('[name="COMPANY_ID"]').val();
	//var v_AGENT_ID    = $('[name="AGENT_ID"]').val();
	var v_TRD_TYPE_ID = $('[name="TRD_TYPE_ID"]').val();
	//var v_KADE        = $('[name="KADE"]').val();

	var v_DETA 		= $('[name="DETA"]').val();
	//var v_DETB 		= $('[name="DETB"]').val();
	var v_DETD 		= $('[name="DETD"]').val();

	var param  = {ORDER_NO:v_ORDER_NO,
				  DORDER:v_DORDER,
				  ORDERBY:v_ORDERBY,
				  TERMINAL:v_TERMINAL,
				  PBM_ID:v_PBM_ID,
				  COMPANY_ID:v_COMPANY_ID,
				  TRD_TYPE_ID:v_TRD_TYPE_ID,
				  DETA:v_DETA,
				  DETD:v_DETD};

	/*var person = {firstName:"John", lastName:"Doe", age:46};*/
	console.log(url);
	//return false;
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
	$.post(url,{NO_UKK : $("#no_ukk").val(), JUM_DETAIL : jum_detail, COUNTER : counter, SIZE : size, TYPE : type, STATUS : status, HEIGHT : height, BAHAYA : bahaya, BONGKAR : bongkar, MUAT : muat, PERDAGANGAN : perdagangan, KEG : keg, SUBKEG : subkeg, heead : param, KEMASAN : kemasan, SATUAN : satuan, VIA : via},function(data){
		//alert(data);
		//console.log(data);
		if(data == "OK")
		{
			$.unblockUI({
			onUnblock: function(){ }
			});
			alert("Success...");
			window.location = "<?=HOME?>request.uper_bm_ac/";
		}
		else if(data == "KO")
		{
			$.unblockUI({
			onUnblock: function(){ }
			});
			alert("Gagal Insert! \nData uper untuk PKK tersebut sudah ada \nHarap cek kembali");
			window.location = "<?=HOME?>request.uper_bm_ac/";
		}
		else
		{

			console.log('auto collection');
			console.log(data);

			var urll	= "<?=HOME?>request.uper_bm_ac.ajax/auto_collection";

				if(data=="1"){

					//save_method = 'add';
				      $.ajax({
				        url : urll,
				        type: "POST",
				        data: {"no_uper":'',"mdl":"btn_sts"},
				        dataType: "json",
				        success: function(data)
				        {

				        	console.log(data);

				           $('#valuta').val(data.VALUTA);
				           $('#total').val(numberWithCommas(data.TOTAL));
				           $('#insert_uper').val(" Simpan Dan HOLD ");
				           alert("Simpan Sukses...");
				           window.location = "<?=HOME?>request.uper_bm_ac/";  
						   return false;
				           //$('.modal-title').text('Detail View SPPD'); 
				           //$('.modal-body').html(data);
				         },
				         error: function (jqXHR, textStatus, errorThrown)
				         {
				          alert('Error adding / update data');
				         }
				      });



				}else{
					console.log("error");
				}

			//return false;
			$.unblockUI({
			onUnblock: function(){ }
			});
			//alert("Failed...");

			return false;
			//window.location = "<?=HOME?>request.uper_bm/";
		}
	});	
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function valid_entry(tableid)
{

	var btn_valid = $('#insert_uper').val();
	
	if(btn_valid == ' Simpan Dan HOLD '){
		window.location = "<?=HOME?>request.uper_bm_ac/";
		return false;
	}

	var urlcek	= "<?=HOME?>request.uper_bm_ac.ajax/cek_data";
	var jj;
	
	$.ajax({
		url : urlcek,
		type: "POST",
		data: {"kd_pelanggan": $('#COMPANY_ID').val(),"ves_voyage": $('#no_ukk').val() , "no_uper" : ''},
		dataType: "json",
		success: function(data)
		{
			//console.log(data);
			console.log('ab' + data);
			jj = data;
			
			if (jj > 0)
			{
				console.log('1');
				alert ("Data Uper Sudah Pernah Dibuat!!");
			}
			else
			{
				var cek = true;
				var tbl = document.getElementById(tableid);
				var lastRow = tbl.rows.length - 1;
				var i=0;
				if($('#vessel').val()=="" || $('#no_ukk').val()=="") {
					alert("Vessel harap diisi!");
					cek = false;
				}
				else if($('#COMPANY_ID').val()=="") {
					alert("Pelanggan harap diisi!");
					cek = false;
				}
				else if(lastRow > 0) {
					for (i=1; i<=lastRow; i++) {		  
					  if ($('#bongkar'+i).val()=="" && $('#muat'+i).val()=="") {
						alert('Jumlah Bongkar / Muat Detail ke-' + i + ' kosong, harap periksa kembali!');
						cek = false;
						break;
					  }
					  else if ($('#perdagangan'+i).val()=="") {
						alert('Perdagangan Detail ke-' + i + ' belum dipilih, harap periksa kembali!');
						cek = false;
						break;
					  }
					}			
				}
				else {
					alert('Detail Barang kosong!');
					cek = false;
				}		
				if(cek) {		
					question = confirm("data akan disimpan, cek apakah data sudah benar?")
					if (question != "0")	uper_entry();
				}
			}
			
		},
		error: function (jqXHR, textStatus, errorThrown)
		{
			alert('Gagal Cek');
		}
	});
	
	
}

function add_uper()
{
	$('#add_uper').load("<?=HOME?>request.uper_bm_ac.ajax/uper_insert").dialog({closeOnEscape: false, modal:true, height:600,width:900, title : "Insert Uper", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
}

function uper_edit(id)
{
	var url			= "<?=HOME?>request.uper_bm_ac.ajax/edit_uper";
	var jum_detail = $("#jum_detail").val();
	var counter = "";
	var size = "";
	var type = "";
	var status = "";
	var height = "";
	var bahaya = "";
	var bongkar = "";
	var muat = "";
	var keg = "";
	var subkeg = "";
	var kemasan = "";
	var satuan = "";
	for (var i=1; i<=jum_detail; i++) {
		if(counter=="")	counter = $("#counter"+i).val();
		else			counter = counter + "&" + $("#counter"+i).val();
		if(size=="")	size = $("#size"+i).val();
		else			size = size + "&" + $("#size"+i).val();
		if(type=="")	type = $("#type"+i).val();
		else			type = type + "&" + $("#type"+i).val();
		if(status=="")	status = $("#status"+i).val();
		else			status = status + "&" + $("#status"+i).val();
		if(height=="")	height = $("#height"+i).val();
		else			height = height + "&" + $("#height"+i).val();
		if(bahaya=="")	bahaya = $("#bahaya"+i).val();
		else			bahaya = bahaya + "&" + $("#bahaya"+i).val();
		if(bongkar=="")	bongkar = $("#bongkar"+i).val();
		else			bongkar = bongkar + "&" + $("#bongkar"+i).val();
		if(muat=="")	muat = $("#muat"+i).val();
		else			muat = muat + "&" + $("#muat"+i).val();
		if(keg=="")		keg = $("#keg"+i).val();
		else			keg = keg + "&" + $("#keg"+i).val();
		if(subkeg=="")	subkeg = $("#subkeg"+i).val();
		else			subkeg = subkeg + "&" + $("#subkeg"+i).val();
		if(kemasan=="")	kemasan = $("#kemasan"+i).val();
		else			kemasan = kemasan + "&" + $("#kemasan"+i).val();
		if(satuan=="")	satuan = $("#satuan"+i).val();
		else			satuan = satuan + "&" + $("#satuan"+i).val();
	}

	var v_ORDER_NO 	= $('[name="ORDER_NO"]').val();
	var v_DORDER 	= $('[name="DORDER"]').val();
	var v_ORDERBY  	= $('[name="ORDERBY"]').val();
	var v_TERMINAL 	= $('[name="TERMINAL"]').val();
	var v_TERM  	= $('[name="TERM"]').val();

	var v_PBM_ID 	  = $('[name="PBM_ID"]').val();
	var v_COMPANY_ID  = $('[name="COMPANY_ID"]').val();
	var v_AGENT_ID    = $('[name="AGENT_ID"]').val();
	var v_TRD_TYPE_ID = $('[name="TRD_TYPE_ID"]').val();
	var v_KADE        = $('[name="KADE"]').val();

	var v_DETA 		= $('[name="DETA"]').val();
	var v_DETB 		= $('[name="DETB"]').val();
	var v_DETD 		= $('[name="DETD"]').val();

	var param  = {ORDER_NO:v_ORDER_NO,
				  DORDER:v_DORDER,
				  ORDERBY:v_ORDERBY,
				  TERMINAL:v_TERMINAL,
				  TERM:v_TERM,
				  PBM_ID:v_PBM_ID,
				  COMPANY_ID:v_COMPANY_ID,
				  AGENT_ID:v_AGENT_ID,
				  TRD_TYPE_ID:v_TRD_TYPE_ID,
				  KADE:v_KADE,
				  DETA:v_DETA,
				  DETB:v_DETB,
				  DETD:v_DETD};
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
	$.post(url,{NO_UPER : id, NO_UKK : $("#no_ukk").val(), JUM_DETAIL : jum_detail, COUNTER : counter, SIZE : size, TYPE : type, STATUS : status, HEIGHT : height, BAHAYA : bahaya, BONGKAR : bongkar, MUAT : muat, PERDAGANGAN : $("#perdagangan").val(), KEG : keg, SUBKEG : subkeg, KEMASAN : kemasan, SATUAN : satuan,heead : param},function(data){
		//alert(data);
		if(data == "OK")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Success...");
				window.location = "<?=HOME?>request.uper_bm_ac/";
			}
		else if(data == "NO")
			{
				$.unblockUI({
				onUnblock: function(){ }
				});
				alert("Data Not Complete...");
				window.location = "<?=HOME?>request.uper_bm_ac/";
			}
		else
			{
				console.log('auto collection');

				var urll	= "<?=HOME?>request.uper_bm_ac.ajax/auto_collection";

					if(data=="HOLDSUKSES"){

						//save_method = 'add';
					      $.ajax({
					        url : urll,
					        type: "POST",
					        data: {"no_uper":'',"mdl":"btn_sts"},
					        dataType: "json",
					        success: function(data)
					        {

					        	console.log(data);

					           $('#valuta').val(data.VALUTA);
					           $('#total').val(numberWithCommas(data.TOTAL));
					           $('#insert_uper').val(" Simpan Dan HOLD ");
					           //$('.modal-title').text('Detail View SPPD'); 
					           //$('.modal-body').html(data);
					         },
					         error: function (jqXHR, textStatus, errorThrown)
					         {
					          alert('Error adding / update data');
					         }
					      });



					}else{
						console.log("error");
					}

				//return false;
				$.unblockUI({
				onUnblock: function(){ }
				});
				//alert("Failed...");

				return false;
				//window.location = "<?=HOME?>request.uper_bm/";
				}
	});	
}

function valid_edit(tableid, id)
{
	var cek = true;
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length - 1;
	var i=0;
	if(lastRow > 0) {
		for (i=1; i<=lastRow; i++) {		  
		  if ($('#bongkar'+i).val()=="" && $('#muat'+i).val()=="") {
			alert('Jumlah Bongkar / Muat Detail ke-' + i + ' kosong, harap periksa kembali!');
			cek = false;
			break;
		  }
		}			
	}
	else {
		alert('Detail Barang kosong!');
		cek = false;
	}		
	if(cek) {
		question = confirm("data akan disimpan, cek apakah data sudah benar?")
		if (question != "0")	uper_edit(id);
	}
}

function edit_uper(id,action)
{

	if(action == 'cancel'){

		var result = confirm("Batal Uper ?");
		if (result) {
			var urll	= "<?=HOME?>request.uper_bm_ac.ajax/auto_collection";
			$.ajax({
		        url : urll,
		        type: "POST",
		        data: {"no_uper":id,"mdl":"btn_cancel"}, 
		        dataType: "json",
		        success: function(data)
		        {
		        	alert(data);
		        	ReloadPage();
		         },
		         error: function (jqXHR, textStatus, errorThrown)
		         {
		          alert('Error adding / update data');
		         }
		      });
		}else{
			return false;
		}
		
	}else{
		var preview = (action == 'hold') ? 'Preview' : 'Edit Uper';

		$('#edit_uper').load("<?=HOME?>request.uper_bm_ac.ajax/uper_edit?id="+id+"&action="+action).dialog({closeOnEscape: false, modal:true, height:600,width:900, title : preview, open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
	}


}

</script>
<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
</style>

<div class="content">
<h2>&nbsp;<img class="icon" border="0" src="images/uper.png" />&nbsp;Uper Bongkar-Muat</h2>
<br />
<a class="link-button" style="height:25" onclick="add_uper()">
	<img border="0" src="<?=HOME?>images/tambah.png">
	Entry
</a>
<p><br/></p>
<table id='l_uper'></table> <div id='pg_uper'></div>
<div id="dialog-form">
<form id="mainform">
	<!--<input type="hidden" name="tes" value="tes"/>-->
	<div id="add_uper"></div>
	<div id="edit_uper"></div>
</form>
</div>

</div>
