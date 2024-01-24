<style type="text/css">
	input{
		border-radius: 0px !important;
	}
</style>
<script type="text/javascript">
$(document).ready(function(){
	
	$("#btnClose").click(function(){
		$('#add_uper').dialog('destroy').remove();
		$('#mainform').append('<div id="add_uper"></div>');
	});
	
	
	$("#vessel").autocomplete({
		minLength: 3,
		source: "request.uper_bm_ac.auto/vesvoy",
		focus: function( event, ui ) 
		{
			$("#vessel").val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#vessel").val(ui.item.NM_KAPAL);
			$("#voyage").val(ui.item.VOYAGE);
			$("#no_ukk").val(ui.item.NO_UKK);
			$("#shipping").val(ui.item.NM_PEMILIK);

			/*COMPANY_NAME
			ACCOUNT_NUMBER*/

			/**/
			//$("#txtCompanyId").val(ui.item.ACCOUNT_NUMBER);
			//$("#txtCompanyName").val(ui.item.COMPANY_NAME);


			$('#txtKade').val(ui.item.ID_KADE);
			$('#txtKadeName').val(ui.item.KADE_NAME);

			//$('#txtPBMId').val("ITPK");
			//$('#txtPBMName').val("PT. IPC TERMINAL PETIKEMAS AREA PONTIANAK");


			$('#id_DETA').val(ui.item.ETA);
			$('#id_DETB').val(ui.item.ETB);
			$('#id_DETD').val(ui.item.ETD);
			
			//console.log('ETA  : '+ui.item.ETA);

			/*  0: "TPK"
				1: "PT. PELABUHAN INDONESIA II CABANG TANJUNG PRIOK"
				2: "S"*/

			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data("item.autocomplete", item )
		.append("<a><b>" + item.NM_KAPAL + "</b> || " + item.VOYAGE + "<br>" + item.NO_UKK + "<br>" + item.NM_PEMILIK + "</a>")
		.appendTo(ul);
	};
	
	
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
	
});



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

function addrowToTable(tableid)
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
	// sel.options[0] = new Option('--Pilih--', '');
	sel.options[0] = new Option('Intersuler', 'I');
	sel.options[1] = new Option('Ocean Going', 'O');
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

	// kemasan
	cell = row.insertCell(7);
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

	// satuan
	cell = row.insertCell(8);
	sel = document.createElement('select');
	sel.name = 'satuan' + iteration;
	sel.id = 'satuan' + iteration;
	sel.options[0] = new Option('BOX', 'BOX');
	//sel.options[1] = new Option('M3', 'M3');
	//sel.options[2] = new Option('TON', 'TON');
	//sel.options[3] = new Option('UNIT', 'UNIT');
	//sel.options[4] = new Option('EKOR', 'EKOR');
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
	// var txtNode = document.createTextNode("Box");
	var txtNode = document.createTextNode(""); 
	el.type = 'text';
	el.name = 'bongkar' + iteration;
	el.id = 'bongkar' + iteration;
	el.size = 8;
	el.onkeypress = isNumberKey;
	cell.appendChild(el);
	var br = document.createElement("br");
	cell.appendChild(br);
	cell.appendChild(txtNode);
	
	cell = row.insertCell(11);
	el = document.createElement('input');
	// txtNode = document.createTextNode("Box"); 
	var txtNode = document.createTextNode(""); 
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
		/*perdagangan = document.getElementById('perdagangan' + indexnya);
		perdagangan.id="perdagangan"+index;
		perdagangan.name="perdagangan"+index;*/
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
                        
                //var url	= "<?=HOME?><?=APPID;?>.ajax/get_pbm";

                //console.log(url);
                //return false;
			
				//$.blockUI({ message: '<h1><br>Please wait...Sedang Cek Zonasi</h1><br><img src=<?=HOME;?>images/glc/loading81.gif width="30" height="30" /><br><br>' });
				
				/*$.post(url,{
					VESSEL:Vessel,
					VESSEL_NAME:Vessel_name,
					VOY:voy,
                    DMY:dmy
				},
					function(data){	
						console.log(data);
						var pbm = JSON.parse(data);
					
					if (data=='S'){
						$.unblockUI({
							onUnblock: function(){ }
						});
					}
					else
					{
						$.unblockUI({
							onUnblock: function(){ }
						});
					}
					
						$('#txtPBMId').val(pbm[0]);
						$('#txtPBMName').val(pbm[1]);
						$('#REMARKS').val(pbm[3]);
						
						$('#txtKade').val(kade);
						$('#txtKadeName').val(kadename);
					});*/
		setTimeout("$('#divVessel').hide();", 100);
	}

function popclose_Vessel() {
	setTimeout("$('#divVessel').hide();", 100);
}

/*END VESSEL*/



</script>
<?php
//== buat dikirim ke javascript==//
$db = getDB();

function spacer($length){
	for ($i=0; $i < $length; $i++) { 
		echo '&nbsp';
	}
}


//print_r($_SESSION);
//$rs = $db->query("SELECT TERMINAL, NAME from TR_TERMINAL where STATUS='A' ORDER BY TERMINAL DESC");
//$combolist["TERMINAL"]=$rs->getAll();

$combolist["ORDERBY"] = array (
  0 =>   array (    'id' => 'P',    '' => 'PBM',  ),
  1 =>   array (    'id' => 'S',    '' => 'Pelayaran/Pemilik Barang',  ),
);

$combolist["TERM"] = array (
  0 =>   array (    'id' => 'F',    '' => 'FIOS',  ),
  1 =>   array (    'id' => 'L',    '' => 'Liners',  ),
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
	      <!-- <td class="form-field-caption">No Upper</td> -->
	      <td class="form-field-caption" align="right"> : </td> 
	        <!-- <input id="id_ORDER_ID" name="ORDER_ID" type="hidden" value="{$row.ORDER_ID}" />  -->
	        <td class="form-field-caption" align="left">
	         <input id="id_ORDER_NO" name="ORDER_NO" type="text" value="" size="18" maxlength="24"/> 
			<!-- <block visible="error.ORDER_NO"><span class="form-field-error">{$error.ORDER_NO}</span></block> -->
	        <?=spacer(7)?> 
	        <span class="form-field-caption">Tanggal : </span> <input id="id_DORDER" name="DORDER" type="date" value="<?=date('Y-m-d')?>" size="10" maxlength="10" /> 
	        <!-- <block visible="error.DORDER"><span class="form-field-error">{$error.DORDER}</span></block>  -->
	        <?=spacer(2)?> 
	        <span class="form-field-caption">Terminal : </span> 
	        <select id="id_TERMINAL" name="TERMINAL">
	        
	        	<option value="PNK">Pontianak</option>
	       
	        </select> 
	      </td>
	      <!--<td class="form-field-caption" align="right">Status</td>-->
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
				<select id="id_ORDERBY" name="ORDERBY">
					<?php foreach($combolist['ORDERBY'] as $val): ?>
	        			<option value="<?=$val['id']?>"><?=$val['']?></option>
	        		<?php endforeach; ?>
        		</select>
			<!-- </td> -->
			<?=spacer(2)?> 
			<!--<span class="form-field-caption">Term : </span> 
			<select id="id_TERM" name="TERM">
				<?php foreach($combolist['TERM'] as $val): ?>
	        			<option value="<?=$val['id']?>"><?=$val['']?></option>
	        		<?php endforeach; ?>
        	</select>-->
	      </td>
    	</tr>

    	<tr>
    		<td class="form-field-caption" align="right">PBM</td>
	      	<td class="form-field-caption" align="right"> : </td>
	      	<td class="form-field-input"><?=spacer(2)?> 
              <input id="txtPBMId" name="PBM_ID" type="text" size="7" maxlength="12" onBlur ="doFillPBM(this.value);" class="form-field-ro" readonly placeholder="ITPK" value="ITPK"/> 
              <input id="txtPBMName" name="PBM_NAME" type="text" size="50" maxlength="50" class="form-field-ro" readonly placeholder="PT. IPC TERMINAL PETIKEMAS AREA PONTIANAK" value="PT. IPC TERMINAL PETIKEMAS AREA PONTIANAK"/> 
            </td>
    	</tr>

    	<tr> 
            <td class="form-field-caption" align="right">Pemakai Jasa</td>
            <td class="form-field-caption" align="right"> : </td>
            <td class="form-field-input"><?=spacer(2)?> 
              
              <input id="COMPANY_NAME" name="COMPANY_NAME" type="text" value="" size="36" maxlength="50" class="form-field-ro" placeholder="Autocomplete"/> 
			  <input id="COMPANY_ID" name="COMPANY_ID" type="text" value="" size="7" maxlength="12" onBlur ="" readonly/> 
              <!--<img src="<?=HOME?>images/ico_find.png" onClick="popup_Consignee(this.value);" > -->
              <!--<block visible="error.COMPANY_ID"><span class="form-field-error">{$error.COMPANY_ID}</span></block> -->
              <div id="divCompany"></div>
              <div class="suggestionsBox" id="divSugCompany" style="display: none;"> 
                <div class="suggestionList" id="popSuggestionsList">&nbsp;</div>
              </div></td>
      	</tr>

      	<!--<tr> 
            <td class="form-field-caption" align="right">Pemakai Jasa</td>
            <td class="form-field-caption" align="right"> : </td>
            <td class="form-field-input"><?=spacer(2)?> 
              <input id="txtAgentId" name="AGENT_ID" type="text" value="" size="7" maxlength="12" onBlur ="doFillAgen(this.value);" /> 
              <input id="txtAgentName" name="AGENT_NAME" type="text" value="" size="36" maxlength="50" class="form-field-ro" readonly /> 
              <img src="<?=HOME?>images/ico_find.png" onClick="popup_agent(this.value);" > 
              <!-- <block visible="error.AGENT_ID"><span class="form-field-error">{$error.AGENT_ID}</span></block> 
              <div id="divAgent"></div>
              <div class="suggestionsBox" id="divSugAgent" style="display: none;"> 
                <div class="suggestionList" id="popSuggestionsList">&nbsp;</div>
              </div></td>
      	</tr>-->

      	<tr> 
	      <td class="form-field-caption" align="right">Perdagangan</td>
	      <td class="form-field-caption" align="right"> : </td>
	      <td class="form-field-input"><?=spacer(2)?> 
	        <select id="id_TRD_TYPE_ID" name="TRD_TYPE_ID" selected="row.TRD_TYPE_ID" >
	          <option value="I">Intersuler</option>
	          <option value="O">Ocean Going</option>
	        </select> 
	        <!-- <block visible="error.TRD_TYPE_ID"><span class="form-field-error">{$error.TRD_TYPE_ID}</span></block> -->
        </td>
   	 	</tr>

	 	<!-- <tr> 
	      <td  align="right" class="form-field-caption">Vessel</td>
	      <td class="form-field-caption" align="right"> : </td>
	      <td class="form-field-input"><?=spacer(2)?> 
	        <input id="id_VESSEL_ID" name="VESSEL_ID" type="text" value="" size="25" onBlur="doFillVessel(this.value);" /> 
	        <input id="txtVesselName" name="VSLNAME" type="text" value="" size="36" class="form-field-ro" readonly /> 
	        <img src="<?=HOME?>images/ico_find.png" onClick="popup_Vessel(this.value);" > 
	        <div class="suggestionsBox" id="divVessel" style="display: none;"> 
	          <div class="suggestionList" id="popSuggestionsList"> &nbsp;</div>
	        </div>
	      </td>
	    </tr> -->

		<!--<tr> 
		  <td class="form-field-caption" align="right">Kade</td>
		  <td class="form-field-caption" align="right"> : </td>
		  <td class="form-field-input" ><?=spacer(2)?>
		    <input id="txtKade" name="KADE" type="text" value="" size="25" maxlength="12" onBlur ="doFillKade(this.value);" style="" readonly /> 
		    <input id="txtKadeName" name="KADE_NAME" type="text" value="" size="36" class="form-field-ro" style="" readonly/> 
		    
		    <div class="suggestionsBox" id="divKade" style="display: none;"> 
		      <div class="suggestionList" id="popSuggestionsList"> &nbsp;</div>
		    </div></td>
		</tr>
		</tr>-->


		<!-- <tr>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr> -->
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete"/> / <input type="text" name="voyage" id="voyage" size="20" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No UKK</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ukk" id="no_ukk" size="15" maxlength="9" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Shipping Line</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="shipping" id="shipping" size="30" ReadOnly />
			</td>
		</tr>

		<tr> 
	      <td class="form-field-caption" align="right">ETA </td>
	     <td class="form-field-caption" align="right"> : </td>
	      <td class="form-field-input" colspan="3"> <?=spacer(2)?>
	        <input id="id_DETA" readonly="readonly" name="DETA" type="date" value="" size="12" />	
	       <?=spacer(2)?> <span class="form-field-caption">ETD : </span> <input id="id_DETD" readonly="readonly" name="DETD" type="date" value="" size="12" /> 
	        <!-- <block visible="error.DETD"><span class="form-field-error">{$error.DETD}</span></block>  -->
	      </td>
    	</tr>

    	<!--<tr> 
	      <td class="form-field-caption" align="right">Valuta </td>
	      <td class="form-field-caption" align="right"> : </td>
	      <td class="form-field-input" colspan="3"> <?=spacer(2)?>
	        <input type="text" name="valuta" id="valuta" size="4" value="<?=$row['VALUTA']?>" ReadOnly />
	        <?=spacer(2)?> <span class="form-field-caption">Total : </span> 
	        <input type="text" name="total" id="total" size="25" value="<?=number_format($row['TOTAL'],2)?>" ReadOnly /> 
	      </td>
    	</tr>-->

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
					</table>
					<input type="hidden" name="jum_detail" id="jum_detail" />
				</div>
				<div style="margin-left:10px;">
					<!--<a onClick="addrowToTable('tableedit');"><img src="images/tambah.png" /></a>
					<input type="button" value="Tambah Detail" onClick="addrowToTable('tableedit');" class="coffemix" />-->
					<a class="link-button" style="height:25" onclick="addrowToTable('tableedit')">
						<img border="0" src="images/tambah.png" />Tambah Detail
					</a>&nbsp; &nbsp;
					<!--<a onClick="removeAllRow('tableedit');"><img src="images/batal.png" /></a>
					<input type="button" value="Hapus Semua" onClick="removeAllRow('tableedit');" class="coffemix" />-->
					<a class="link-button" style="height:25" onclick="removeAllRow('tableedit')">
						<img border="0" src="images/batal.png" />Hapus Semua
					</a>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;
				<input type="button" id="insert_uper" name="Insert Uper" value="&nbsp;Insert&nbsp;" onclick="valid_entry('tableedit')"/>
			</td>
		</tr>
	</table>		