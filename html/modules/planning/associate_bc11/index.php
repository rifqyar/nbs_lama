<script type="text/javascript">
function associatebc11()
{
	var idves_ = $('#idvesvoyage').val();
	var nobc11_ = $('#nobc11').val();
	var tgbc11_ = $('#tglbc11').val();
	var vesselbc11_ = $('#vesselbc').val();
	var voyagebc11_ = $('#voyagebc').val();
	
	var fields_tgl = tgbc11_.split('-');
	var tgbc11_temp = fields_tgl[2]+fields_tgl[1]+fields_tgl[0];

	var conf    = 'N';
	var url ="<?=HOME;?>planning.associate_bc11.ajax/save_associate";
	
	if (nobc11_ == ''){
		alert('NO BC11 still empty');
		return false;
	}  else { 
		$.post(url,{idves:idves_,nobc11:nobc11_, tgbc11:tgbc11_temp, vesselbc11:vesselbc11_, voyagebc11:voyagebc11_, confirm:conf},function(data){
				//alert(data);
				if ((data != 'OK') && (data != 'NOT')){
					var r = confirm("BC11 already set with number "+data+" , Do you want to replace it?");
					var conf  = 'Y';
					if (r == true) {
						$.post(url,{idves:idves_,nobc11:nobc11_,tgbc11:tgbc11_temp, vesselbc11:vesselbc11_, voyagebc11:voyagebc11_, confirm:conf},function(data){	
							if (data == 'OK'){
								alert('sukses');
								$("#l_associatebc").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');			
							} else {
								alert('failed');
							}
						});
					} else {
						txt = "You pressed Cancel!";
					}
					
				} else {
					if (data == 'OK'){
						alert('sukses');
						$("#l_associatebc").jqGrid('setGridParam',{datatype:'json'}).trigger('reloadGrid');	
						$( "#vessel" ).val('');
						$( "#voyagein" ).val('');
						$( "#voyageout" ).val('');
						$( "#idvesvoyage" ).val('');				
						$( "#vesselbc" ).val('');	
						$( "#voyagebc" ).val('');	
						$( "#nobc11" ).val('');	
						$( "#tglbc11" ).val('');						
					} else {
						alert('failed');
					}
				}
		});
	}
}

$(document).ready(function() 
{	
	$("#tglbc11").datepicker({
			dateFormat: 'dd-mm-yy'
    });
			
	//======================================= autocomplete vessel==========================================//
	$( "#vessel" ).autocomplete({
		minLength: 3,
		source: "<?=HOME;?>planning.associate_bc11.auto/vessel",
		focus: function( event, ui ) 
		{
			$( "#vessel" ).val( ui.item.VESSEL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#voyagein" ).val( ui.item.VOYAGE_IN);
			$( "#voyageout" ).val( ui.item.VOYAGE_OUT);
			$( "#idvesvoyage" ).val( ui.item.ID_VSB_VOYAGE);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.VESSEL + "<br> ETA :" +item.TGL_JAM_TIBA +" <br>" +item.VOYAGE_IN+" - "+ item.VOYAGE_OUT+" </a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
	
	
	/*$( "#vesselbc" ).autocomplete({
		minLength: 3,
		source: "<?=HOME;?>planning.associate_bc11.auto/associatebc11",
		focus: function( event, ui ) 
		{
			$( "#vesselbc" ).val( ui.item.NAMA_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#vesselbc" ).val( ui.item.NAMA_KAPAL);
			$( "#voyagebc" ).val( ui.item.NO_VOYAGE);
			$( "#nobc11" ).val( ui.item.NOBC11);
			$( "#tglbc11" ).val( ui.item.TGBC11);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NAMA_KAPAL + "/" +item.NO_VOYAGE+" <br> "+ item.AGEN_PELAYARAN+" <br> NOBC : "+ item.NOBC11 +"/"+item.TGBC11+"</a>")
		.appendTo( ul );
	
	};*/
	
	
	//======================================= autocomplete vessel==========================================//
	
});


jQuery(function() {
 jQuery("#l_associatebc").jqGrid({
	url:'<?=HOME?>datanya/data_associatebc?q=assbc11',
	mtype : "post",
	datatype: "json",
	colNames:['Status','Vessel','Voyage','Tgl Tiba','BC 1.1','Tgl BC 1.1'], 
	colModel:[
		{name:'aksi', width:100, align:"center",sortable:false,search:false},
		{name:'Vessel',index:'Vessel', width:200, align:"center"},
		{name:'Voyage',index:'Voyage', width:150, align:"center"},
		{name:'Tiba',index:'Tiba', width:100, align:"center"},
		{name:'bc11',index:'bc11', width:100, align:"center"},
		{name:'tglbc11',index:'tglbc11', width:100, align:"center"}
	],
	rowNum:20,
	width: 800,
	height: "100%",//250
	ignoreCase:true,
	rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_l_associatebc',
	viewrecords: true,
	shrinkToFit: false,
	caption:"Data Associate BC11"
 });
  jQuery("#l_associatebc").jqGrid('navGrid','#pg_l_associatebc',{del:false,add:false,edit:false,search:false}); 
 jQuery("#l_associatebc").jqGrid('filterToolbar',{defaultSearch:'cn',stringResult: true,searchOnEnter : false});
 
});



</script>

<div class="content">
	<div class="main_side">
	  <fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px;align=center"> 
		 <table>
			<tr height="10">
				<td colspan="3"</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="center" colspan="2">
					<font size="6" > <img src="<?=HOME?>images/crdc.png" style="vertical-align:middle"> Associate BC11 </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>Vessel/Voyage OPUS</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="vessel" name="vessel" size="30" title="entry" class="suggestuwriter" type="text"/>
					<input style="font-size:20px; font-weight:bold;" id="voyagein" name="voyage" size="5" title="entry" class="suggestuwriter" type="text" readonly="readonly"/>
					<input style="font-size:20px; font-weight:bold;" id="voyageout" name="voyageout" size="5" title="entry" class="suggestuwriter" type="text" readonly="readonly"/>
					<input style="font-size:20px; font-weight:bold;" id="idvesvoyage" name="idvesvoyage" size="5" title="entry" class="suggestuwriter" type="hidden"/>
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>Vessel/Voyage BC</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="vesselbc" name="vesselbc" size="30" title="entry" class="suggestuwriter" type="text"/>
					<input style="font-size:20px; font-weight:bold;" id="voyagebc" name="voyagebc" size="10" title="entry" class="suggestuwriter" type="text" />
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>NO BC 11</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="nobc11" name="nobc11" size="20" title="entry" class="suggestuwriter" type="text" />
				  </font></b><br>
				</td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="right"><b>Tanggal BC 11</b></td>
				<td align="left">
					<font size="1"><input style="font-size:20px; font-weight:bold;" id="tglbc11" name="tglbc11" size="30" title="entry" class="suggestuwriter" type="text" />
				  </font></b><br>
				</td>
			</tr>
			<tr height="10">
				<td colspan="3"></td>
			</tr>
			<tr>
				<td width="120"></td>
				<td align="center" colspan="2">
					<input type="button" value=" ASSOCIATE BC11 " onclick="associatebc11()"> </input></font></b><br>
				</td>
			</tr>
			<tr height="10">
				<td colspan="3"></td>
			</tr>
		</table>		
		</fieldset>
		
		<center>
		<table id='l_associatebc' width="100%"></table> <div id='pg_l_associatebc'></div>
		</center>
		<br>
	</div>
</div>