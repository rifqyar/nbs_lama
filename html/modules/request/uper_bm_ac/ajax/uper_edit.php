<?php $action = $_GET['action']; $dis = ($action == 'hold') ? 'disabled' : ''; ?>
<style type="text/css">
	<?php if($action == 'hold'): ?>
	input{
		border-radius: 0px !important;
	    background: #e1e0de !important;
	}
	select{
		background-color: #f0f0f0 !important;
		background: #e1e0de !important;
	}
	<?php endif; ?>
</style>
<script type="text/javascript">
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_uper').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_uper"></div>');
	});

	console.log('action : <?=$_GET[action]?>');
	
	$("#COMPANY_NAME").autocomplete({
		minLength: 3,
		source: "request.uper_bm_ac.auto/company",
		focus: function( event, ui ) 
		{
			$("#COMPANY_NAME").val( ui.item.COMPANY_NAME);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#COMPANY_NAME").val(ui.item.COMPANY_NAME);
			$("#COMPANY_ID").val(ui.item.COMPANY_ID);		

			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data("item.autocomplete", item )
		.append("<a><b>" + item.COMPANY_ID + " || " + item.COMPANY_NAME +"</b></a>")
		.appendTo(ul);
	};
	
	
	
	<?php if($action == 'hold'): ?>
		$('#insert_uper').val(cek_ac());
	<?php endif; ?>
});

function send_ac(no_uper)
{
	console.log('no uper : '+no_uper);

	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });


	<?php if($action == 'hold'): ?>
	console.log('HOLD');
	var urll	= "<?=HOME?>request.uper_bm_ac.ajax/auto_collection";
	$.ajax({
        url : urll,
        type: "POST",
        data: {"no_uper":no_uper,"mdl":"callAc"},
        dataType: "json",
        success: function(data)
        {
			console.log(data);
			var d2 = data;
			if (d2.includes('HOLDGAGAL'))
			{
				alert('Simpan Dan Hold Gagal');
			}
			else if (d2.includes('GAGAL_ACBARANGLOG'))
			{
				alert('Simpan Dan Hold Sukses, Gagal Masuk AC_BARANG_LOG');
			}
			else if (d2.includes('HOLDSUKSES'))
			{
				alert('Simpan Dan Hold Sukses');
			}
			else
			{
				alert(d2);
			}
           // $('#insert_uper').val(data);
           //alert('Simpan Dan Hold Sukses');
           window.location = "<?=HOME?>request.uper_bm_ac/";
		   return false;
         },
         error: function (jqXHR, textStatus, errorThrown)
         {
          alert('Error Ajax ');
         }
      });
	
	<?php else: ?>


	console.log('Edit');
	//return false;

	/*var urll	= "<?=HOME?>request.uper_bm_ac.ajax/auto_collection";
	$.ajax({
        url : urll,
        type: "POST",
        data: {"no_uper":no_uper,"mdl":"callAc"},
        dataType: "json",
        success: function(data)
        {
         	console.log(data);
           // $('#insert_uper').val(data);
           alert('Simpan Dan Hold Sukses');
           window.location = "<?=HOME?>request.uper_bm_ac/";
		   return false;
         },
         error: function (jqXHR, textStatus, errorThrown)
         {
          alert('Error Ajax ');
         }
      });*/

      uper_entry_edit(no_uper);

	<?php endif; ?>

}


function uper_entry_edit(no_uper)
{
	var url = "<?=HOME?>request.uper_bm_ac.ajax/add_uper";
	var jum_detail = $("#jum_detail").val();
	//var no_uper = no_uper;
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
	console.log(v_TRD_TYPE_ID);
	console.log(v_ORDERBY);
	//return false;
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
	$.post(url,{NO_UKK : $("#no_ukk").val(), JUM_DETAIL : jum_detail, COUNTER : counter, SIZE : size, TYPE : type, STATUS : status, HEIGHT : height, BAHAYA : bahaya, BONGKAR : bongkar, MUAT : muat, PERDAGANGAN : perdagangan, KEG : keg, SUBKEG : subkeg, heead : param, KEMASAN : kemasan, SATUAN : satuan, VIA : via , NO_UPER : no_uper},function(data){
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
		/*else
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
			//window.location = "<?=HOME?>request.uper_bm_ac/";
		}*/
	});	
}


function cek_ac()
{

	var cust_id = $('#COMPANY_ID').val();

	var urll	= "<?=HOME?>request.uper_bm_ac.ajax/auto_collection";
		//save_method = 'add';
	      $.ajax({
	        url : urll,
	        type: "POST",
	        data: {"cust_id":cust_id,"mdl":"cek_cust"},
	        dataType: "json",
	        success: function(data)
	        {
	         	console.log('bb');
				console.log(data + 'ccc');
				console.log('aaa');
	           $('#insert_uper').val(data);
	         },
	         error: function (jqXHR, textStatus, errorThrown)
	         {
	          alert('Error Ajax ');
	         }
	      });

}

function cek_kegiatan(obj)
{
	var par=obj.parentNode;
	while(par.nodeName.toLowerCase()!='tr'){
		par=par.parentNode;
	}
	var index = par.rowIndex;

	$("#subkeg"+index).html("");
	if(obj.value=="BM") {
		for(i=1; i<=$('#jum_bm').val(); i++) {
			id_subkeg = $('#id_bm'+i).val();
			ket_subkeg = $('#ket_bm'+i).val();
			$("#subkeg"+index).append("<option value='"+id_subkeg+"'>"+ket_subkeg+"</option>")

		}
	}
	else if(obj.value=="TRANS") {
		for(i=1; i<=$('#jum_trans').val(); i++) {
			id_trans = $('#id_trans'+i).val();
			ket_trans = $('#ket_trans'+i).val();
			$("#subkeg"+index).append("<option value='"+id_trans+"'>"+ket_trans+"</option>")

		}
	}
	else if(obj.value=="SHIFT") {
		for(i=1; i<=$('#jum_shift').val(); i++) {
			id_shift = $('#id_shift'+i).val();
			ket_shift = $('#ket_shift'+i).val();
			$("#subkeg"+index).append("<option value='"+id_shift+"'>"+ket_shift+"</option>")

		}
	}
}

function valid_kemasan(obj){
	var kemasan = $('#'+obj).val();
	var counter = obj.substr(-1);

	if(kemasan=='BREAK_BULK'){
			$('#status'+counter).prop('readonly', true);
			$('#type'+counter).prop('readonly', true);
			$('#height'+counter).prop('readonly', true);

			$('#status'+counter).css('background-color', '#b9b9b9');
			$('#type'+counter).css('background-color', '#b9b9b9');
			$('#height'+counter).css('background-color', '#b9b9b9');

		}else{

			$('#status'+counter).removeAttr('readonly', true);
			$('#type'+counter).removeAttr('readonly', true);
			$('#height'+counter).removeAttr('readonly', true);

			$('#status'+counter).css('background-color', '#fff');
			$('#type'+counter).css('background-color', '#fff');
			$('#height'+counter).css('background-color', '#fff');
		}

}

function addRowToTable(tableid, perdagangan)
{
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length;
	// if there's no header row in the table, then iteration = lastRow + 1
	var iteration = lastRow;
	var row = tbl.insertRow(lastRow);
	
	var cell = row.insertCell(0);
	var el = document.createElement('input');
	el.type = 'button';
	el.value = ' - ';
	el.title = 'Hapus';
	el.onclick = function() {removeRowIndex(this,tableid)};
	cell.appendChild(el);
	
	cell = row.insertCell(1);
	el = document.createElement('input');
	el.type = 'text';
	el.name = 'counter' + iteration;
	el.id = 'counter' + iteration;
	el.size = 2;
	el.value = iteration;
	el.readOnly = true;
	cell.appendChild(el);

	cell = row.insertCell(2);
	var sel = document.createElement('select');
	sel.name = 'via' + iteration;
	sel.id = 'via' + iteration;
	sel.options[0] = new Option('--Pilih--', '');
	sel.options[1] = new Option('TL', 'Y');
	sel.options[2] = new Option('LAPANGAN', 'N');
	// sel.options[2] = new Option('Ocean Going', 'O');
	cell.appendChild(sel);
	
	/*cell = row.insertCell(3);
	var sel = document.createElement('select');
	sel.name = 'perdagangan' + iteration;
	sel.id = 'perdagangan' + iteration;
	sel.disabled = true;
	if(perdagangan == 'O') {
		sel.options[0] = new Option('Ocean Going', 'O');
		sel.options[1] = new Option('Intersuler', 'I');
	}
	else {
		sel.options[0] = new Option('Intersuler', 'I');
		sel.options[1] = new Option('Ocean Going', 'O');
	}
	cell.appendChild(sel);*/
	
	cell = row.insertCell(3);
	sel = document.createElement('select');
	sel.name = 'size' + iteration;
	sel.id = 'size' + iteration;
	sel.options[0] = new Option('20', '20');
	sel.options[1] = new Option('40', '40');
	sel.options[2] = new Option('45', '45');
	cell.appendChild(sel);
	
	cell = row.insertCell(4);
	sel = document.createElement('select');
	sel.name = 'type' + iteration;
	sel.id = 'type' + iteration;
	sel.options[0] = new Option('DRY', 'DRY');
	sel.options[1] = new Option('REFEER', 'RFR');
	sel.options[2] = new Option('FLAT TRACK', 'FLT');
	sel.options[3] = new Option('HIGH CUBE', 'HQ');
	sel.options[4] = new Option('OPEN TOP', 'OT');
	sel.options[5] = new Option('TANKER', 'TNK');
	sel.options[6] = new Option('OVD', 'OVD');
	cell.appendChild(sel);
	
	cell = row.insertCell(5);
	sel = document.createElement('select');
	sel.name = 'status' + iteration;
	sel.id = 'status' + iteration;
	sel.options[0] = new Option('FCL', 'FCL');
	sel.options[1] = new Option('LCL', 'LCL');
	sel.options[2] = new Option('MTY', 'MTY');
	sel.options[3] = new Option('UC', 'UC');
	cell.appendChild(sel);
	
	cell = row.insertCell(6);
	sel = document.createElement('select');
	sel.name = 'height' + iteration;
	sel.id = 'height' + iteration;
	sel.options[0] = new Option('8.6 / 9.6', 'BIASA');
	sel.options[1] = new Option('OOG', 'OOG');
	cell.appendChild(sel);	

	// satuan
	cell = row.insertCell(7);
	sel = document.createElement('select');
	sel.name = 'satuan' + iteration;
	sel.id = 'satuan' + iteration;
	sel.options[0] = new Option('BOX', 'BOX');
	sel.options[1] = new Option('M3', 'M3');
	sel.options[2] = new Option('TON', 'TON');
	sel.options[3] = new Option('UNIT', 'UNIT');
	sel.options[4] = new Option('EKOR', 'EKOR');
	cell.appendChild(sel);

	// kemasan
	cell = row.insertCell(8);
	sel = document.createElement('select');
	sel.name = 'kemasan' + iteration;
	sel.id = 'kemasan' + iteration;
	//sel.onclick = '';

	sel.onclick = function () {
	    valid_kemasan('kemasan' + iteration);
	};

	sel.options[0] = new Option('PETIKEMAS', 'PETIKEMAS');
	sel.options[1] = new Option('BREAK BULK', 'BREAK_BULK');
	cell.appendChild(sel);

	
	cell = row.insertCell(9);
	sel = document.createElement('select');
	sel.name = 'bahaya' + iteration;
	sel.id = 'bahaya' + iteration;
	sel.options[0] = new Option('Tidak', 'T');
	sel.options[1] = new Option('Ya', 'Y');
	cell.appendChild(sel);
	
	cell = row.insertCell(10);
	el = document.createElement('input');
	var txtNode = document.createTextNode("Box"); 
	el.type = 'text';
	el.name = 'bongkar' + iteration;
	el.id = 'bongkar' + iteration;
	el.size = 4;
	el.onkeypress = isNumberKey;
	cell.appendChild(el);
	var br = document.createElement("br");
	cell.appendChild(br);
	cell.appendChild(txtNode);
	
	cell = row.insertCell(11);
	el = document.createElement('input');
	txtNode = document.createTextNode("Box"); 
	el.type = 'text';
	el.name = 'muat' + iteration;
	el.id = 'muat' + iteration;
	el.size = 4;
	el.onkeypress = isNumberKey;
	cell.appendChild(el);
	br = document.createElement("br");
	cell.appendChild(br);
	cell.appendChild(txtNode);
	
	cell = row.insertCell(12);
	sel = document.createElement('select');
	sel.name = 'keg' + iteration;
	sel.id = 'keg' + iteration;
	sel.onchange = function() {cek_kegiatan(this)};
	for(var i=1; i<=$('#jum_keg').val(); i++) {
		id_keg = $('#id_keg'+i).val();
		ket_keg = $('#ket_keg'+i).val();
		sel.options[i-1] = new Option(ket_keg, id_keg);
	}
	cell.appendChild(sel);
	var sel2 = document.createElement('select');
	sel2.name = 'subkeg' + iteration;
	sel2.id = 'subkeg' + iteration;
	for(i=1; i<=$('#jum_bm').val(); i++) {
		id_subkeg = $('#id_bm'+i).val();
		ket_subkeg = $('#ket_bm'+i).val();
		sel2.options[i-1] = new Option(ket_subkeg, id_subkeg);
	}
	br = document.createElement("br");
	cell.appendChild(br);
	cell.appendChild(sel2);
	
	var x = tbl.rows;
	x[iteration].vAlign="top";
	
	document.getElementById('jum_detail').value = iteration;
}

function removeRowIndex(obj,tableid)
{
	var par=obj.parentNode;
	while(par.nodeName.toLowerCase()!='tr'){
		par=par.parentNode;
	}
	var index = par.rowIndex;
	
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length;
	tbl.deleteRow(index);
	while(index < tbl.rows.length) {
		indexnya = index+1;
		counter = document.getElementById('counter' + indexnya);
		counter.id="counter"+index;
		counter.name="counter"+index;
		counter.value=counter.value-1;
		via = document.getElementById('via' + indexnya);
		via.id="via"+index;
		via.name="via"+index;
		perdagangan = document.getElementById('perdagangan' + indexnya);
		perdagangan.id="perdagangan"+index;
		perdagangan.name="perdagangan"+index;
		size = document.getElementById('size' + indexnya);
		size.id="size"+index;
		size.name="size"+index;
		type = document.getElementById('type' + indexnya);
		type.id="type"+index;
		type.name="type"+index;
		status = document.getElementById('status' + indexnya);
		status.id="status"+index;
		status.name="status"+index;
		height = document.getElementById('height' + indexnya);
		height.id="height"+index;
		height.name="height"+index;
		bahaya = document.getElementById('bahaya' + indexnya);
		bahaya.id="bahaya"+index;
		bahaya.name="bahaya"+index;
		satuan = document.getElementById('satuan' + indexnya);
		satuan.id="satuan"+index;
		satuan.name="satuan"+index;
		kemasan = document.getElementById('kemasan' + indexnya);
		kemasan.id="kemasan"+index;
		kemasan.name="kemasan"+index;
		bongkar = document.getElementById('bongkar' + indexnya);
		bongkar.id="bongkar"+index;
		bongkar.name="bongkar"+index;
		muat = document.getElementById('muat' + indexnya);
		muat.id="muat"+index;
		muat.name="muat"+index;
		keg = document.getElementById('keg' + indexnya);
		keg.id="keg"+index;
		keg.name="keg"+index;
		subkeg = document.getElementById('subkeg' + indexnya);
		subkeg.id="subkeg"+index;
		subkeg.name="subkeg"+index;
		index++;
	}		
	
	document.getElementById('jum_detail').value = (tbl.rows.length-1);
}
	
function removeAllRow(tableid)
{
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length;
	while (lastRow > 1)	tbl.deleteRow(--lastRow);
	document.getElementById('jum_detail').value = 0;	
}

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		/*if(charCode=="46")	return true;
		else*/				return false;
	}
		
	return true;
}

// Company
function popup_Consignee(txtCompanyId) { 
		var varIFrame = '<iframe src="<?=HOME?>popup.pop_consignee/" width="100%" height="320px" ></iframe>';
		var myPos = $('#txtCompanyId').position();
		$('#divSugCompany').css({"left":myPos.left-40, "top":myPos.top+40}).show();
		$('#divSugCompany').html(varIFrame);
}

function popfill_Consignee(Consignee, CompName) {
		$('#txtCompanyId').val(Consignee);
		$('#txtCompanyName').val(CompName);
		setTimeout("$('#divSugCompany').hide();", 100);
}

function doFillCompany (compid){	
	if ( compid.length == 0 ) {
		$('#txtCompanyName').val ('');
		return;
	}
	
	$.get("<?=HOME?>fillname/fill_company/", {COMP_ID: compid} , 
		function(data){
			$('#txtCompanyName').val (data);
		});
}

function popclose_Consignee() {
	setTimeout("$('#divSugCompany').hide();", 100);
}
// END Company

// PBM
function doFillPBM (pbmid) 
	{	
		if ( pbmid.length == 0 ) {
			$('#txtPBMName').val ('');
			return;
		}
		
		$.get("<?=HOME?>fillname/fill_pbm/", {PBM_ID: pbmid} , 
			function(data){
				$('#txtPBMName').val (data);
			});
	}
// END PBM

/*AGEN*/
function popup_agent(txtAgentId) { 
		var varIFrame = '<iframe src="<?=HOME?>popup.pop_agent/" width="100%" height="320px" ></iframe>';
		var myPos = $('#txtAgentId').position();
		$('#divSugAgent').css({"left":myPos.left-40, "top":myPos.top+40}).show();
		$('#divSugAgent').html(varIFrame);
}

function doFillAgen (agenid) 
{	
	if ( agenid.length == 0 ) {
		$('#txtAgentName').val ('');
		return;
	}
	
	$.get("<?=HOME?>fillname/fill_agen/", {AGENT_ID: agenid} , 
		function(data){
			$('#txtAgentName').val (data);
		});
}

function popfill_agent(txtAgentId, AgentName) {
	$('#txtAgentId').val(txtAgentId);
	$('#txtAgentName').val(AgentName);
	setTimeout("$('#divSugAgent').hide();", 100);
}

function popclose_agent() {
	setTimeout("$('#divSugAgent').hide();", 100);
}
/*END AGEN*/

/*VESSEL*/
function popup_Vessel(id_VESSEL_ID) { 
		var varIFrame = '<iframe src="<?=HOME?>popup.pop_vessel/" width="100%" height="280px" ></iframe>';
		var myPos = $('#id_VESSEL_ID').position();
		$('#divVessel').css({"left":myPos.left+80, "top":myPos.top-40}).show();
		$('#divVessel').html(varIFrame);
}

function popfill_Vessel(Vessel, Vessel_name,voy,ukk) {
		$('#id_VESSEL_ID').val(Vessel);
		$('#txtVesselName').val(Vessel_name);
		$('#id_VOYAGE_NO').val(voy);
		$('#ukk').val(ukk);

        var dmy  = $('[name="DORDER[d]"]').val();
        dmy += '/'+$('[name="DORDER[m]"]').val();
        dmy += '/'+$('[name="DORDER[y]"]').val();
                        
		setTimeout("$('#divVessel').hide();", 100);
	}

function popclose_Vessel() {
	setTimeout("$('#divVessel').hide();", 100);
}

/*END VESSEL*/



</script>
<?php
$id = $_GET['id'];
$db = getDB();


$query  = "select a.*,id_vsb_voyage NO_UKK, vessel NM_KAPAL, voyage_in || '/'|| voyage_out voyage,operator_name NM_PEMILIK
,to_char(to_date(substr(b.ETA,0,8),'yyyymmdd'),'YYYY-MM-DD') eta
,to_char(to_date(substr(b.ETd,0,8),'yyyymmdd'),'YYYY-MM-DD') etd
,c.NAMA_PERUSAHAAN company_name
                  from uper_h a
                  join opus_repo.m_vsb_voyage b on a.no_ukk = b.id_vsb_voyage
				  join mst_pelanggan c on a.company_id = c.kd_pelanggan
                  where a.no_uper='".$id."'";

if($res = $db->query($query)) {
	$row = $res->fetchRow();
	if($row['LUNAS']=='P') {	//cek status terakhir, sudah payment apa belum?
		echo "<script>
				alert('Uper ini sudah dilunasi, tidak bisa di-edit!');
				ReloadPage();
			  </script>";
	}
}
//print_r($query);
//== buat dikirim ke javascript==//
$db = getDB();

function spacer($length){
	for ($i=0; $i < $length; $i++) { 
		echo '&nbsp';
	}
}

function get_comp_name($company_id){
	$db = getDB();
	$rs = $db->query("SELECT NAME FROM TR_COMPANY WHERE COMPANY_ID = '".$company_id."'")->fetchRow();
	return $rs['NAME'];
}

function getPBMName($pbmid){
	$db = getDB();
	$rs = $db->query("SELECT NAME from barang_prod.TR_PBM@dbint_kapal where PBM_ID = '" . $pbmid . "'")->fetchRow();
	return $rs['NAME'];
}



/*$rs = $db->query("SELECT TERMINAL, NAME from TR_TERMINAL where STATUS='A' ORDER BY TERMINAL DESC");
$combolist["TERMINAL"]=$rs->getAll();*/

$combolist["ORDERBY"] = array (
  0 =>   array (    'id' => 'P',    '' => 'PBM',  ),
  1 =>   array (    'id' => 'S',    '' => 'Pelayaran/Pemilik Barang',  ),
);




$i=1;
$q_keg = "SELECT ID_KEG, KET FROM TR_KEG ORDER BY URUT";
$res = $db->query($q_keg);
while($data_keg = $res->fetchRow()) {
?>
	<input type="hidden" id="id_keg<?=$i?>" name="id_keg<?=$i?>" value="<?=$data_keg[ID_KEG]?>">
	<input type="hidden" id="ket_keg<?=$i?>" name="ket_keg<?=$i?>" value="<?=$data_keg[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_keg" name="jum_keg" value="<?=($i-1)?>">
	
<?php
$i=1;
$q_subkeg = "SELECT ID_SUBKEG, KET FROM TR_SUBKEG WHERE ID_KEG='BM' ORDER BY URUT";
$res = $db->query($q_subkeg);
while($data_sub1 = $res->fetchRow()) {
?>
	<input type="hidden" id="id_bm<?=$i?>" name="id_bm<?=$i?>" value="<?=$data_sub1[ID_SUBKEG]?>">
	<input type="hidden" id="ket_bm<?=$i?>" name="ket_bm<?=$i?>" value="<?=$data_sub1[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_bm" name="jum_bm" value="<?=($i-1)?>">

<?php
$i=1;
$q_subkeg = "SELECT ID_SUBKEG, KET FROM TR_SUBKEG WHERE ID_KEG='TRANS' ORDER BY URUT";
$res = $db->query($q_subkeg);
while($data_sub2 = $res->fetchRow()) {
?>
	<input type="hidden" id="id_trans<?=$i?>" name="id_trans<?=$i?>" value="<?=$data_sub2[ID_SUBKEG]?>">
	<input type="hidden" id="ket_trans<?=$i?>" name="ket_trans<?=$i?>" value="<?=$data_sub2[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_trans" name="jum_trans" value="<?=($i-1)?>">

<?php
$i=1;
$q_subkeg = "SELECT ID_SUBKEG, KET FROM TR_SUBKEG WHERE ID_KEG='SHIFT' ORDER BY URUT";
$res = $db->query($q_subkeg);
while($data_sub3 = $res->fetchRow()) {
?>
	<input type="hidden" id="id_shift<?=$i?>" name="id_shift<?=$i?>" value="<?=$data_sub3[ID_SUBKEG]?>">
	<input type="hidden" id="ket_shift<?=$i?>" name="ket_shift<?=$i?>" value="<?=$data_sub3[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_shift" name="jum_shift" value="<?=($i-1)?>">
	
	<br>
 	
	<table>
		
		<tr height='20'> 

	      <td class="form-field-caption" align="right">Surat Order</td>
	      
	      <td class="form-field-caption" align="right"> : </td> 
	        
	        <td class="form-field-caption" align="left">
	         <input readonly id="id_ORDER_NO" name="ORDER_NO" type="text" value="<?=$row['ORDER_NO']?>" size="18" maxlength="24"/> 
			
	        <?=spacer(7)?> 
	        <span class="form-field-caption">Tanggal : </span> 
	        <input readonly id="id_DORDER" name="DORDER" type="date" value="<?=date('Y-m-d',strtotime($row['DORDER']))?>" size="10" maxlength="10" /> 
	       
	        <?=spacer(2)?> 
	        <span class="form-field-caption">Terminal : </span> 
	        <select id="id_TERMINAL" name="TERMINAL">
	        
	        	<option value="PNK">Pontianak</option>
	       
	        </select> 
	      </td>
	      
	      <td class="form-field-caption" align="right"></td>
	      <td align="left" class="form-field-caption"> 
	          <select id="id_STATUS" name="STATUS" selected="row.STATUS" list="combolist.STATUS" key="id" label="" class="form-field-ro" style="display: none;">
	        </select> 
		  </td>
    	</tr>
		

		<tr>
    		<td class="form-field-caption" align="right">Asal Order</td>
	      	<td class="form-field-caption" align="right"> : </td>
	      	<td class="form-field-caption" align="left">
				<select <?=$dis?> readonly id="id_ORDERBY" name="ORDERBY">
					<?php foreach($combolist['ORDERBY'] as $val): ?>
						<?php if($row['ORDERBY']==$val['id']): ?>
	        				<option value="<?=$val['id']?>" selected><?=$val['']?></option>
	        			<?php else: ?>
	        				<option value="<?=$val['id']?>"><?=$val['']?></option>
	        			<?php endif; ?>
	        		<?php endforeach; ?>
        		</select>
			
			<?=spacer(2)?> 
			
	      </td>
    	</tr>

    	<tr>
    		<td class="form-field-caption" align="right">PBM</td>
	      	<td class="form-field-caption" align="right"> : </td>
	      	<td class="form-field-input"><?=spacer(2)?> 
		      <input id="txtPBMId" name="PBM_ID" type="text" size="7" maxlength="12" class="form-field-ro" readonly placeholder="ITPK" value="ITPK"/> 
              <input id="txtPBMName" name="PBM_NAME" type="text" size="50" maxlength="50" class="form-field-ro" readonly placeholder="PT. IPC TERMINAL PETIKEMAS AREA PONTIANAK" value="PT. IPC TERMINAL PETIKEMAS AREA PONTIANAK"/> 
             
            </td>
    	</tr>

    	<tr> 
            <td class="form-field-caption" align="right">Pemakai Jasa</td>
            <td class="form-field-caption" align="right"> : </td>
            <td class="form-field-input"><?=spacer(2)?> 
			  <input id="COMPANY_NAME" name="COMPANY_NAME" type="text" value="<?=$row['COMPANY_NAME']?>" size="36" maxlength="50" class="form-field-ro" placeholder="Autocomplete"/> 
			  <input id="COMPANY_ID" name="COMPANY_ID" type="text" value="<?=$row['COMPANY_ID']?>" size="7" maxlength="12" onBlur ="" readonly/> 
              
			  </td>
      	</tr>

      	

      	<tr> 
	      <td class="form-field-caption" align="right">Perdagangan</td>
	      <td class="form-field-caption" align="right"> : </td>
	      <td class="form-field-input"><?=spacer(2)?> 
	        <select readonly id="id_TRD_TYPE_ID" name="TRD_TYPE_ID" <?=$dis?>>
	          <option value="I" <?php if($row["FLAG_OI"] == 'I') { echo 'selected'; } ?> >Intersuler</option>
	          <option value="O" <?php if($row["FLAG_OI"] == 'O') { echo 'selected'; } ?>>Ocean Going</option>
	        </select> 
	        
        </td>
   	 	</tr>

   	 	

		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" value="<?=$row['NM_KAPAL']?>" ReadOnly /> / 
				<input type="text" name="voyage" id="voyage" size="20" value="<?=$row['VOYAGE']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No UKK</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ukk" id="no_ukk" size="15" maxlength="9" value="<?=$row['NO_UKK']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Shipping Line</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="shipping" id="shipping" size="30" value="<?=$row['NM_PEMILIK']?>" ReadOnly />
			</td>
		</tr>
		<?php 

		?>
		<tr> 
	      <td class="form-field-caption" align="right">ETA </td>
	      <td class="form-field-caption" align="right"> : </td>
	      <td class="form-field-input" colspan="3"> <?=spacer(2)?>
	      <!-- C.ETA,C.ETB,C.ETD -->
	        <input readonly id="id_DETA" name="DETA" type="date" value="<?=date('Y-m-d',strtotime($row['ETA']))?>" size="12" />	
	        
	        <?=spacer(2)?> <span class="form-field-caption">ETD : </span> 
	        <input readonly id="id_DETD" name="DETD" type="date" value="<?=date('Y-m-d',strtotime($row['ETB']))?>" size="12" /> 
	        
	      </td>
    	</tr>

    	<tr> 
	      <td class="form-field-caption" align="right">Valuta </td>
	      <td class="form-field-caption" align="right"> : </td>
	      <td class="form-field-input" colspan="3"> <?=spacer(2)?>
	        <input type="text" name="valuta" id="valuta" size="4" value="<?=$row['VALUTA']?>" ReadOnly />
	        <?=spacer(2)?> <span class="form-field-caption">Total : </span> 
	        <input type="text" name="total" id="total" size="25" value="<?=number_format($row['TOTAL'],2)?>" ReadOnly /> 
	      </td>
    	</tr>

		<tr>
			<td colspan="3">
				<div id="tableinput" style="width:850px; overflow-x: scroll ;padding-top:10px; margin-left:10px; margin-bottom:10px">
					<table border="0" cellpadding="2" cellspacing="2" id="tableedit" width="850px">
						<tr height="20" bgcolor="#0066FF" align="center"> 
							<td class="grid-header" width="20">&nbsp;</td>
							<td class="grid-header" width="20">No</td>
							<td class="grid-header" width="20">Via</td>
							<!--<td class="grid-header">Perdagangan</td>-->
							<td class="grid-header">Size</td>
							<td class="grid-header">Type</td>
							<td class="grid-header">Status</td>
							<td class="grid-header">Height</td>
							<td class="grid-header">Kemasan</td>
							<td class="grid-header">Satuan</td>
							<td class="grid-header">Hz</td>
							<td class="grid-header">Bongkar</td>
							<td class="grid-header">Muat</td>								
							<td class="grid-header">Kegiatan</td>
						</tr>
<?php

$sql2 = "SELECT * FROM UPER_D WHERE NO_UPER='".$id."' ORDER BY NO_URUT";
$rs2 = $db->query( $sql2 );
//echo '<pre>'; print_r($rs2->getAll()); echo '</pre>';
$j=0;

while ($row2=$rs2->FetchRow()) {
	$j++;
	unset($pSize1,$pSize2,$pSize3);
	if($row2['SIZE_']==20)	$pSize1="selected";
	else if($row2['SIZE_']==40)	$pSize2="selected";
	else	$pSize3="selected";
	
	unset($pType1,$pType2,$pType3,$pType4,$pType5,$pType6,$pType7);
	if($row2['TYPE_']=='DRY')	$pType1="selected";
	else if($row2['TYPE_']=='RFR')	$pType2="selected";
	else if($row2['TYPE_']=='FLT')	$pType3="selected";
	else if($row2['TYPE_']=='HQ')	$pType4="selected";
	else if($row2['TYPE_']=='OT')	$pType5="selected";
	else if($row2['TYPE_']=='TNK')	$pType6="selected";
	else	$pType7="selected";
	
	unset($pStatus1,$pStatus2,$pStatus3);
	if($row2['STATUS']=='FCL')	$pStatus1="selected";
	else if($row2['STATUS']=='LCL')	$pStatus2="selected";
	else	$pStatus3="selected";

	#kemasan
	unset($pKemasan1,$pKemasan2);
	if($row2['KEMASAN']=='PETIKEMAS')	$pKemasan1="selected";		
	else	$pKemasan2="selected";

	#satuan
	unset($pSatuan1,$pSatuan2,$pSatuan3,$pSatuan4,$pSatuan5,$pSatuan6);
	if($row2['SATUAN']=='TON')			$pSatuan1="selected";
	else if($row2['SATUAN']=='M3')		$pSatuan2="selected";
	else if($row2['SATUAN']=='BOX')		$pSatuan3="selected";
	else if($row2['SATUAN']=='UNIT')	$pSatuan4="selected";
	else if($row2['SATUAN']=='EKOR')	$pSatuan5="selected";
	else	$pSatuan6="selected";
	
	unset($pBahaya1,$pBahaya2);
	if($row2['HZ']=='T')	$pBahaya1="selected";		
	else	$pBahaya2="selected";
	
	/*unset($pPerdagangan1,$pPerdagangan2);
	if($row2['FLAG_OI']=='O')	$pPerdagangan1="selected";		
	else	$pPerdagangan2="selected";
	$perdagangan = $row2['FLAG_OI'];*/

	unset($pvia1,$pvia2);
	if($row2['TL_FLAG']=='Y')	$pvia1="selected";		
	else	$pvia2="selected";
	$pvia = $row2['TL_FLAG'];

	//print_r($row2['TL_FLAG']);

?>
						<tr height="20"> 
							<td valign="top"><input type="button" value=" - " onclick="removeRowIndex(this,'tableedit');" title="hapus" /></td>
							<td valign="top"><input type="text" id="counter<?=$j?>" name="counter<?=$j?>" size="2" value="<?=$j?>" readonly /></td>
							<td valign="top">
								<select <?=$dis?>  readonly name="via<?=$j?>" id="via<?=$j?>">
									<option value="TL"  <?=$pvia1?>>TL</option>
									<option value="LAP" <?=$pvia2?>>LAP</option>
								</select>
							</td>
							<!--<td valign="top">
								<select <?=$dis?> readonly name="perdagangan<?=$j?>" id="perdagangan<?=$j?>">
									<option value="O"  <?=$pPerdagangan1?>>Ocean Going</option>
									<option value="I" <?=$pPerdagangan2?>>Intersuler</option>
								</select>
							</td>-->
							<td valign="top">
								<select <?=$dis?> readonly name="size<?=$j?>" id="size<?=$j?>">
									<option value="20" <?=$pSize1?>>20</option>
									<option value="40" <?=$pSize2?>>40</option>
									<option value="45" <?=$pSize3?>>45</option>
								</select>
							</td>
							<td valign="top">
								<select <?=$dis?> readonly name="type<?=$j?>" id="type<?=$j?>">
									<option value="DRY" <?=$pType1?>>DRY</option>
									<option value="RFR" <?=$pType2?>>REFEER</option>
									<option value="FLT" <?=$pType3?>>FLAT TRACK</option>
									<option value="HQ" <?=$pType4?>>HIGH CUBE</option>
									<option value="OT" <?=$pType5?>>OPEN TOP</option>
									<option value="TNK" <?=$pType6?>>TANKER</option>
									<option value="OVD" <?=$pType7?>>OVD</option>
								</select>
							</td>
							<td valign="top">
								<select <?=$dis?> readonly name="status<?=$j?>" id="status<?=$j?>">
									<option value="FCL" <?=$pStatus1?>>FCL</option>
									<option value="LCL" <?=$pStatus2?>>LCL</option>
									<option value="MTY" <?=$pStatus3?>>MTY</option>						
								</select>
							</td>
							<td valign="top">
								<select <?=$dis?> readonly name="height<?=$j?>" id="height<?=$j?>">
									<option value="BIASA" <?=$pHeight1?>>8.6 / 9.6</option>
									<option value="OOG" <?=$pHeight2?>>OOG</option>	
								</select>
							</td>

							<!-- satuan -->
							<td valign="top">
								<select <?=$dis?> readonly name="satuan<?=$j?>" id="satuan<?=$j?>">
									<option value="TON"  <?=$pSatuan1?> >TON</option>
									<option value="M3" 	 <?=$pSatuan2?> >M3</option>
									<option value="BOX"  <?=$pSatuan3?> >BOX</option>
									<option value="UNIT" <?=$pSatuan4?> >UNIT</option>
									<option value="EKOR" <?=$pSatuan5?> >EKOR</option>
								</select>
							</td>

							<!-- kemasan -->
							<td valign="top">
								<select <?=$dis?> readonly name="kemasan<?=$j?>" id="kemasan<?=$j?>">
									<option value="PETIKEMAS"  <?=$pKemasan1?> >PETIKEMAS</option>
									<option value="BREAK_BULK" <?=$pKemasan2?> >BREAK BULK</option>
								</select>
							</td>


							<td valign="top">
								<select <?=$dis?> readonly name="bahaya<?=$j?>" id="bahaya<?=$j?>">
									<option value="T" <?=$pBahaya1?>>Tidak</option>
									<option value="Y" <?=$pBahaya2?>>Ya</option>						
								</select>
							</td>
							<td valign="top">
								<input <?=$dis?> type="text" style="width: 70px;" id="bongkar<?=$j?>" name="bongkar<?=$j?>" value="<?=$row2["BONGKAR"]?>" size="4" onkeypress="return isNumberKey(event)" /> <!--Box-->
							</td>
							<td valign="top">
								<input <?=$dis?> type="text" id="muat<?=$j?>" name="muat<?=$j?>" value="<?=$row2["MUAT"]?>" size="4" onkeypress="return isNumberKey(event)" /> <!--Box-->
							</td>
							<td valign="top">
								<select readonly <?=$dis?> name="keg<?=$j?>" id="keg<?=$j?>" onChange="cek_kegiatan(this)">
<?php
	$sql_keg = "SELECT * FROM TR_KEG ORDER BY URUT";
	$rs_keg = $db->query( $sql_keg );
	while ($row_keg=$rs_keg->FetchRow()) {
		if($row_keg['ID_KEG']==$row2['KEGIATAN']) {
?>
									<option value="<?=$row_keg['ID_KEG']?>" selected><?=$row_keg['KET']?></option>
<?php
		}
		else {
?>
									<option value="<?=$row_keg['ID_KEG']?>"><?=$row_keg['KET']?></option>
<?php
		}
	}
?>
								</select>
								<br />
								<select <?=$dis?> name="subkeg<?=$j?>" id="subkeg<?=$j?>">
<?php
	$sql_subkeg = "SELECT * FROM TR_SUBKEG WHERE ID_KEG='".$row2['KEGIATAN']."' ORDER BY URUT";
	$rs_subkeg = $db->query( $sql_subkeg );
	while ($row_subkeg=$rs_subkeg->FetchRow()) {
		if($row_subkeg['ID_SUBKEG']==$row2['SUBKEG']) {
?>
									<option value="<?=$row_subkeg['ID_SUBKEG']?>" selected><?=$row_subkeg['KET']?></option>
<?php
		}
		else {
?>
									<option value="<?=$row_subkeg['ID_SUBKEG']?>"><?=$row_subkeg['KET']?></option>
<?php
		}
	}
?>
								</select>
							</td>
						</tr>
<?php
}
?>
					</table>
					<!-- </fieldset> -->
					<input type="hidden" name="perdagangan" id="perdagangan" value="<?=$perdagangan?>" />
					<input type="hidden" name="jum_detail" id="jum_detail" value="<?=$j?>" />
				</div>

				<?php if($action != 'hold'): ?>
				<div style="margin-left:10px;">
					<!--<a onClick="addRowToTable('tableedit');"><img src="images/tambah.png" /></a>
					<input type="button" value="Tambah Detail" onClick="addRowToTable('tableedit');" class="coffemix" />-->
					<a class="link-button" style="height:25" onclick="addRowToTable('tableedit','<?=$perdagangan?>')">
						<img border="0" src="images/tambah.png" />Tambah Detail
					</a>&nbsp; &nbsp;
					<!--<a onClick="removeAllRow('tableedit');"><img src="images/batal.png" /></a>
					<input type="button" value="Hapus Semua" onClick="removeAllRow('tableedit');" class="coffemix" />-->
					<a class="link-button" style="height:25" onclick="removeAllRow('tableedit')">
						<img border="0" src="images/batal.png" />Hapus Semua
					</a>
				</div>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>

		<tr>
			<td colspan="3" align="center">
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>
				<!-- <button type="button" id="btnClose"> Close </button> -->
				<!-- <input type="button" id="insert_uper" name="Edit Uper" value="&nbsp;Edit&nbsp;" onclick="valid_edit('tableedit','<?=$id?>')"/> -->
				<input type="button" id="insert_uper" name="Edit Uper" value="&nbsp;Edit&nbsp;" onclick="send_ac('<?=$id?>')"/>
				<!-- <button type="button" id="insert_uper" onclick="send_ac('<?=$id?>')"> Edit </button> -->
			</td>
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>
