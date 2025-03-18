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
.tabss{
	margin-top: 20px;
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

<script type="text/javascript">
setInterval(function()
		{
			jQuery('#cont_disch').jqGrid('setSelection', '0');
		},
		2000);

      	$(document).ready(function()
        {				
			var watermark = 'Autocomplete';						
			<!------------------- watermark Vessel ------------>
			$('#vessel').val(watermark).addClass('watermark');
			//if blur and no value inside, set watermark text and class again.
			$('#vessel').blur(function(){
				if ($(this).val().length == 0){
					$(this).val(watermark).addClass('watermark');
				}
			});
		 
			//if focus and text is watermrk, set it to empty and remove the watermark class
			$('#vessel').focus(function(){
				if ($(this).val() == watermark){
					$(this).val('').removeClass('watermark');
				}
		    });
			<!------------------- watermark Vessel ------------>
											
	   });
		
		
$(function() {
	
	<!------------------- autocomplete Vessel ------------>
	$( "#vessel" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>operational.disch_confirm.auto/vessel",
		focus: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL );
			return false;
		},
		select: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL );
			$( "#voyage" ).val( ui.item.VOYAGE );
			$( "#id_vs" ).val( ui.item.ID_VS );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_KAPAL + " [" + item.VOYAGE + "] " + "</a>" )
			.appendTo( ul );
	};
	<!------------------- autocomplete Vessel ------------>
	
});

jQuery(function() {
 jQuery("#cont_disch").jqGrid({
	url:'<?=HOME?>datanya/data_dso?q=list_cont_disch',
	mtype : "post",
	datatype: "json",
	colNames:['No Container','Size/Type/Status','Vessel','Ship Position','Discharge Date','Yard Plan<br>Block Slot','Placement<br>B S R T','Placement Date'], 
	colModel:[
		{name:'cont_no', width:120, align:"center"},
		{name:'spec', width:100, align:"center",sortable:false,search:false},
		{name:'vessel', width:180, align:"center",sortable:false,search:false},
		{name:'ship_pss', width:120, align:"center",sortable:false,search:false},
		{name:'disch_date', width:120, align:"center",sortable:false,search:false},
		{name:'yard_plan', width:80, align:"center",sortable:false,search:false},
		{name:'plc', width:80, align:"center",sortable:false,search:false},
		{name:'plc_date', width:100, align:"center",sortable:false,search:false}
		
	],
	rowNum:10,
	width: 870,
	height: "100%",//250

	//rowList:[10,20,30,40,50,60],
	loadonce:true,
	rownumbers: true,
	rownumWidth: 15,
	gridview: true,
	pager: '#pg_cont_disch',
	viewrecords: true,
	shrinkToFit: false,
	//grid.setRowData ( id, false, {height: 30} ),
	caption:"Update Discharge Confirm"
 });
  jQuery("#cont_disch").jqGrid('navGrid','#pg_cont_disch',{del:false,add:false,edit:false,search:false}); 
 jQuery("#cont_disch").jqGrid('filterToolbar',{stringResult: true,searchOnEnter : false});
});

function info_bay(vs,b,g,ket,posisi)
{
	$('#table_alokasi').load("<?=HOME?>operational.disch_confirm.ajax/alokasi_bay?bay_area="+g+"&id_vs="+vs+"&no_bay="+b+"&posisi="+posisi+" #bay_alokasi").dialog({modal:true, height:600,width:800, title:"Bay "+ket+" "+posisi+""});
}

function v_confirm_disch(no_ukk,nocont,spec,brt,st)
{
	var alat = $('#alat').val();
	var op_alat = $('#op_alat').val();
	$('#table_disch_cont').load("<?=HOME?>operational.disch_confirm.ajax/v_disch/?nocont="+nocont+"&ukk="+no_ukk+"&spec="+spec+"&brt="+brt+"&st="+st+"&alat="+alat+"&op_alat="+op_alat).dialog({modal:true, height:300,width:380, title : "Discharge Confirm"});
}

function disch_cont(no_ukk,nocont,alat,op_alat)
{
	var no_truck    = $('#no_truck').val();
	var remark    = $('#remark').val();
	var seal_status    = $('#seal_status').val();
	var eir    = $('#eir').val();
	var url			= "<?=HOME?>operational.disch_confirm.ajax/disch_container";	
	
	var r = confirm("Discharge Confirm... Are you sure?");
	if(r == true)
	{	
		$.post(url,{NO_UKK : no_ukk, NO_CONT : nocont, ALAT : alat, OP_ALAT : op_alat, NO_TRUCK : no_truck, REMARK : remark, SEAL_STATUS : seal_status, EIR : eir},function(data){
		console.log(data);
			if(data == "OK")
			{
				alert("Success");
				window.location = "<?=HOME?>operational.disch_confirm/discharge?vs="+no_ukk;  	
			}
			else
			{
				alert("Failed");
			}
		});	
	}
	else
	{
		return false;
	}
}

</script>


<div class="content">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="<?=HOME?>images/discharge2.png" height="6%" width="6%" />
        &nbsp;<font color="#0378C6">Discharge</font> Confirm
        </span></h2>
	<br/>
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<form enctype="multipart/form-data" action="<?=HOME;?>operational.disch_confirm/discharge" method="post">
	<table>
        <tr>
			<td>VESSEL / VOYAGE</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="vessel" id="vessel" title="Autocomplete" style="background-color:#FFCCFF" />
                <input type="hidden" name="id_vs" id="id_vs" /> / <input type="text" name="voyage" id="voyage" size="10" style="background-color:#FFCCFF" readonly="readonly"/></td>
            <td>&nbsp;&nbsp;&nbsp;</td>
			<td><input type="submit" value=" Proses " /></td>
		</tr>       
    </table>
	</form>
	<br/>
	<div id="main_side">
	
<?php
$id_vs = $_POST['id_vs'];
$id_vs_ = $_GET['vs'];

if (($id_vs != NULL)||($id_vs_ != NULL)) {
		
    if($id_vs != NULL)
	{
		$id = $id_vs;
	}
	else if($id_vs_ != NULL)
	{
		$id = $id_vs_;
	}
	
	$db = getDB();
	$ves_voy = "SELECT NM_KAPAL,
					   VOYAGE_IN|| ' - ' ||VOYAGE_OUT AS VOYAGE
					FROM RBM_H
                    WHERE NO_UKK = '$id'";
	$vvoy = $db->query($ves_voy);
	$hasil_vv = $vvoy->fetchRow();
	$vessel = $hasil_vv['NM_KAPAL'];
	$voyage = $hasil_vv['VOYAGE'];
?>
	
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-image:url('<?=HOME;?>images/vespro.png'); background-repeat:no-repeat;">
<center>      
<div style="margin-right:170px; margin-bottom:130px; margin-top:82px; width:600; height:100;">
<p style="width:100%;">
<div align="center">
<table width="100%" cellspacing="0" border="0">
<tbody>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>
	<tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				$no_bay = $row['BAY'];
				
				if(($no_bay==1)||(($no_bay-1)%4==0))
				{
					$bays = $no_bay." (".($no_bay+1).")";
				}
				else
				{
					$bays = $no_bay;
				}
				
			?>
                        <div id="x" class="drag blue">
                          <td align="center" style="width:50px;height:10px;font-size:8px; font-family:Tahoma;"><? if($bays>0) { echo $bays; } ?></td>
                        </div>  
              <?
			    } ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
    <tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, ABOVE FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				
			$bay_id = $row['ID'];

			$cek_cont_disch = "SELECT COUNT(A.NO_CONTAINER) AS JML_DISCH 
								FROM STW_PLACEMENT_BAY A, STW_BAY_CELL B 
								WHERE A.ID_CELL = B.ID
									AND A.ACTIVITY = 'BONGKAR'
									AND B.ID_BAY_AREA = '$bay_id'
									AND B.POSISI_STACK = 'ABOVE'";
			$cont_disch = $db->query($cek_cont_disch);
			$hasil_disch = $cont_disch->fetchRow();
			$cek_disch = $hasil_disch['JML_DISCH'];
			
            if ($row['ABOVE'] == 'NON AKTIF')
			{                                        
			?>
                        <div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;" ><img src="images/stack_bay.png" height="50" width="32"/></td>
                        </div>                  
               <? }
			   else if($row['ABOVE'] == 'NONE')
				{
			   ?>
						<div id="x" class="drag blue">
                          <td style="width:40px;height:50px;font-size:8px; font-family:Tahoma;"><img src="images/mty_bay.png" height="50" width="32"/></td>
                        </div>
			   <?
				}
			   else 
			   { 
					if($cek_disch>0)
					{
			   ?>			   
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66CCFF'" align="center" style="width:50px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66CCFF; "><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<? if ($row['BAY']==1) { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if (($row['BAY']-1)%4==0) { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above')"><img src="images/stack_bay.png" height="50" width="32"/></a></td>
                        </div>	
                    
              <? 
					}
					else
					{
			  ?>		
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;" ><img src="images/stack_bay.png" height="50" width="32"/></td>
                        </div>
		      <?
					}
				} 
			    } ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
         <?php
            $db         = getDB();
            $query_jml  = "SELECT count(BAY) AS JML_BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result12_  = $db->query($query_jml);
            $jml        = $result12_->fetchRow();
			$jml_bay    = $jml['JML_BAY'];
                    
			?>
                        <div id="x" class="drag blue">
                          <td colspan="<?=$jml_bay;?>" align="center" style="width:50px;height:8px; border:1px solid #000000; background-color:#663300;"></td>
                        </div>  
	</tr>
	<!---------------------------- BELOW -------------------------------->
	<tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, BELOW FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];		
				
				$bay_id = $row['ID'];

				$cek_cont_disch = "SELECT COUNT(A.NO_CONTAINER) AS JML_DISCH 
									FROM STW_PLACEMENT_BAY A, STW_BAY_CELL B 
									WHERE A.ID_CELL = B.ID
										AND A.ACTIVITY = 'BONGKAR'
										AND B.ID_BAY_AREA = '$bay_id'
										AND B.POSISI_STACK = 'BELOW'";
				$cont_disch = $db->query($cek_cont_disch);
				$hasil_disch = $cont_disch->fetchRow();
				$cek_disch = $hasil_disch['JML_DISCH'];
				
                if($row['BELOW'] == 'NON AKTIF')
				{       
			?>
                        <div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src="images/stack_bay.png" height="50" width="32"/></td>
                        </div>                  
               <? }
				else if($row['BELOW'] == 'NONE')
				{
			   ?>
						<div id="x" class="drag blue">
                          <td style="width:40px;height:50px;font-size:8px; font-family:Tahoma;"><img src="images/mty_bay.png" height="50" width="32"/></td>
                        </div>
			   <?
				}
				else 
				{ 
					if($cek_disch>0)
					{
			   ?>			   
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66CCFF'" align="center" style="width:50px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66CCFF; "><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<? if ($row['BAY']==1) { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if (($row['BAY']-1)%4==0) { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below')"><img src="images/stack_bay.png" height="50" width="32"/></a></td>
                        </div>	
                    
              <? 
					}
					else
					{
			  ?>		
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;" ><img src="images/stack_bay.png" height="50" width="32"/></td>
                        </div>
		      <?
					}
					} 
			    } ?>
	</tr>
	<!---------------------------- BELOW -------------------------------->
	<tr>
         <?php
            $db         = getDB();
            $query_jml  = "SELECT count(BAY) AS JML_BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result12_  = $db->query($query_jml);
            $jml        = $result12_->fetchRow();
			$jml_bay    = $jml['JML_BAY'];
                    
			?>
                        <div id="x" class="drag blue">
                          <td colspan="<?=$jml_bay;?>" align="right">
						  <br/>
						  <br/>
						  <font size="2"><b><? echo $vessel." [".$voyage."]"; ?></b></font>
						  </td>
                        </div>  
	</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</div>
</p></div>
    </center>
</fieldset>
 <div id="dialog-form">
	<form>
		<div id="table_disch_cont"></div>
	</form>
	<form>
		<div id="table_alokasi"></div>
	</form>
 </div>
	<br/>
    <table id='cont_disch' width="100%"></table> <div id='pg_cont_disch'></div>	
<? } ?>	
    <br/>	
	</div><!-- main side -->
	<br/>
	</div>