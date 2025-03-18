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
</style>

<script type="text/javascript">
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
		source: "<?=HOME?>planning.bay_allocation.auto/vessel",
		focus: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL );
			return false;
		},
		select: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL );
			$( "#voyage" ).val( ui.item.VOYAGE );
			$( "#id_vs" ).val( ui.item.ID_VS );
			$( "#j_vs" ).val( ui.item.JENIS_KAPAL );
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

function display_bay(b,vs,ket,posisi)
{
	$('#table_bay').load('<?=HOME?>planning.bay_allocation.ajax/insert_bay?id='+vs+'&bay='+b+'&posisi='+posisi).dialog({modal:true, height:600,width:800, title:"Bay "+ket+" "+posisi+""});
}

function detail_bay(b,g)
{
	$('#table_detail').load("<?=HOME?>planning.bay_allocation.ajax/detail_bay?bay_area="+g+" #bay_detail").dialog({modal:true, height:300,width:400, title:"Bay "+b+""});
}

function info_bay(vs,b,g,ket,posisi)
{
	$('#table_alokasi').load("<?=HOME?>planning.bay_allocation.ajax/alokasi_bay?bay_area="+g+"&id_vs="+vs+"&no_bay="+b+"&posisi="+posisi+" #bay_alokasi").dialog({modal:true, height:300,width:410, title:"Bay "+ket+" "+posisi+""});
}

function reset_alokasi(vs,bay_area,no_bay,posisi)
{
	var id_vs_      = vs;
	var bay_area_ 	= bay_area;
	var no_bay_     = no_bay;
	var posisi_		= posisi;
	var url			= "<?=HOME?>planning.bay_allocation.ajax/reset";	
	
	var r = confirm("Anda Yakin?");
	if(r == true)
	{	
		$.post(url,{ID_VS : id_vs_, BAY_AREA : bay_area_, NO_BAY : no_bay_, POSISI : posisi_},function(data){
		console.log(data);
			if(data == "OK")
			{
				alert("Reset Alokasi Bay "+no_bay_+" Berhasil...!!!");
				window.location = "<?=HOME?>planning.bay_allocation/bay_alokasi?vs="+id_vs_;  	
			}
			else if(data == "NO")
			{
				alert("Reset Alokasi Bay "+no_bay_+" Gagal...!!!");
			}
			else if(data == "PLAN")
			{
				alert("Bay "+no_bay_+" Sudah Planning...!!!");
			}
		});	
	}
	else
	{
		return false;
	}
}

function submit_capacity(jumlah_row)
{
	var jml_rw = "";
	var r = confirm("Are you sure?");
	if(r == true)
	{	
		document.forms["form_height"].submit();	
	}
	else
	{
		return false;
	}
}

</script>


<div class="content">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/ship6.png" height="9%" width="9%" />
        &nbsp;<font color="#0378C6">Profile</font> Editor
        </span></h2>
	<br/>
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<form enctype="multipart/form-data" action="<?=HOME;?>planning.bay_allocation/bay_alokasi" method="post">
	<table>
        <tr>
			<td>VESSEL / VOYAGE</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="vessel" id="vessel" title="Autocomplete" style="background-color:#FFCCFF" />
                <input type="hidden" name="id_vs" id="id_vs" /> / <input type="text" name="voyage" id="voyage" size="10" style="background-color:#FFCCFF" readonly="readonly"/></td>
            <td><input type="hidden" name="j_vs" id="j_vs" /></td>
			<td><input type="submit" value=" Proses " /></td>
		</tr>       
      </table>
	  </form>
	<br/>
	<div id="main_side">
	
<?php
$id_vs = $_POST['id_vs'];
$j_vs= $_POST['j_vs'];
$id_vs_ = $_GET['vs'];
$j_vs_ = $_GET['j_k'];
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
	if(($j_vs != NULL) or ($j_vs_ != NULL))
	{
		if(($j_vs=='T')or($j_vs_=='T'))
		{
			$pct_kapal='ves_tongkang.png';
			$mrgt='95px';
		
		}
		else
		{
			$pct_kapal='vespro.png';
			$mrgt='82px';
		}
	}
?>

	
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-image:url('<?=HOME;?>images/<?=$pct_kapal?>'); background-repeat:no-repeat;">
<center>      
<div style="margin-right:170px; margin-bottom:80px; margin-top:<?=$mrgt;?>; width:600; height:150;">
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
            $query_blok = "SELECT ID, BAY, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];

				$no_bay = $row['BAY'];
				$occ = $row['OCCUPY'];
				
				if($occ=="Y")
				{
					$bays = $no_bay." (".($no_bay+1).")";
				}
				else if($occ=="T")
				{
					$bays = $no_bay;
				}
				
			?>
                        <div id="x" class="drag blue">
                          <td align="center" style="width:50px;height:10px;font-size:8px; font-family:Tahoma;"><? if($no_bay>0) { echo $bays; }?></td>
                        </div>  
              <?
			    } ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
    <tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, ABOVE, JML_ROW, JML_TIER_UNDER, JML_TIER_ON, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				
            if ($row['ABOVE'] == 'NON AKTIF')
			{                    
                    $jml_row = $row['JML_ROW'];
                    $jml_tier_under = $row['JML_TIER_UNDER'];
                    $jml_tier_on = $row['JML_TIER_ON'];
					$occu = $row['OCCUPY'];
			?>
                        <div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;" ><a href="javascript:display_bay('<?echo $row['BAY']?>','<?=$id?>','<? if ($row['BAY']==1) { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if (($row['BAY']-1)%4==0) { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above')"><img src="images/stack_bay.png" height="50" width="32"/></a></td>
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
			   else { ?>
			   
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66CCFF'" align="center" style="width:50px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66CCFF; "><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<? if ($row['BAY']==1) { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if (($row['BAY']-1)%4==0) { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above')"><img src="images/stack_bay.png" height="50" width="32"/></a></td>
                        </div>	
                    
              <? } 
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
		<?
		if($j_vs<>'T')
		{
	?>
	<!---------------------------- BELOW -------------------------------->
	<tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, BELOW, JML_ROW, JML_TIER_UNDER, JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];		
				
                if($row['BELOW'] == 'NON AKTIF')
			{                    
                    $jml_row = $row['JML_ROW'];
                    $jml_tier_under = $row['JML_TIER_UNDER'];
                    $jml_tier_on = $row['JML_TIER_ON'];
			?>
                        <div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><a href="javascript:display_bay('<?echo $row['BAY']?>','<?=$id?>','<? if ($row['BAY']==1) { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if (($row['BAY']-1)%4==0) { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below')"><img src="images/stack_bay.png" height="50" width="32"/></a></td>
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
				else { ?>
			   
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#66CCFF'" align="center" style="width:50px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#66CCFF; "><a href="javascript:info_bay('<?=$id?>','<?echo $row['BAY']?>','<?=$row['ID']?>','<? if ($row['BAY']==1) { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if (($row['BAY']-1)%4==0) { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below')"><img src="images/stack_bay.png" height="50" width="32"/></a></td>
                        </div>	
                    
              <? } 
			    } ?>
	</tr>
	<!---------------------------- BELOW -------------------------------->
	<?PHP
	}?>
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
						  <font size="2"><b><font color='white'><? echo $vessel." [".$voyage."]"; ?></font></b></font>
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
<table>
	<tr>
		<td width="20" style="background-color:#66CCFF;"></td><td>&nbsp;Status Aktif</td>
	</tr>
</table>
 <div id="dialog-form">
	<form>
		<div id="table_bay"></div>
	</form>
	<form>
		<div id="table_detail"></div>
	</form>
	<form>
		<div id="table_alokasi"></div>
	</form>
 </div>
	<br/>
    <table class="grid-table" id="zebra" border="1" bordercolor="#FFFFFF" width=100% style="border-collapse:collapse">
	  <tr>
		<th class="grid-header" height="34" width="30">NO</th>
        <th class="grid-header">BAY</th>
        <th class="grid-header">ABOVE</th>
		<th class="grid-header">BELOW</th>
		<th class="grid-header">AVAILABLE 20"</th>
		<th class="grid-header">USED 20"</th>
      </tr>
     	<? 
			$db = getDB();
            $query_list_bay = "SELECT BAY, ABOVE, BELOW, JML_ROW, JML_TIER_UNDER, JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id' AND BAY > 0 ORDER BY ID ASC";
            $result_bay_ = $db->query($query_list_bay);
            $bay = $result_bay_->getAll(); 
			
			$n = 1;
			foreach ($bay as $row2){
			
					if($n == 1)
					{
						$bgcolor2 = "#ffffff";
					}
					else if ($n % 2 == 0)
					{
						$bgcolor2 = "#E6E6F0";
					}
					else
					{
						$bgcolor2 = "#ffffff";
					}
			             
                    $jml_row = $row2['JML_ROW'];
                    $jml_tier_under = $row2['JML_TIER_UNDER'];
                    $jml_tier_on = $row2['JML_TIER_ON'];
					$bay_cek = $row2['BAY'];
					
					$query_bay = "SELECT ID FROM STW_BAY_AREA WHERE ID_VS = '$id' AND BAY = '$bay_cek'";
					$bay_ = $db->query($query_bay);
					$hasil_bay = $bay_->fetchRow();
					$bay_area = $hasil_bay['ID'];
					
					$query_cell = "SELECT COUNT(*) AS JML FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND STATUS_STACK = 'A'";
					$cell_ = $db->query($query_cell);
					$hasil_cell = $cell_->fetchRow();
					$jml_cell_a = $hasil_cell['JML'];

					$query_cell = "SELECT COUNT(*) AS JML FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND STATUS_STACK = 'P'";
					$cell_ = $db->query($query_cell);
					$hasil_cell = $cell_->fetchRow();
					$jml_cell_p = $hasil_cell['JML'];
					
		?>
		<tr bgcolor="<?=$bgcolor2;?>">        
            <td class="grid-cell" align="center" height="34"><b><?=$n;?></b></td>
            <td class="grid-cell" align="center">Bay <? if ($row2['BAY']==1) { ?><?echo $row2['BAY'];?>(<? echo $row2['BAY']+1; ?>)<?  } else if (($row2['BAY']-1)%4==0) { ?><? echo $row2['BAY'];?>(<? echo $row2['BAY']+1; ?>)<? } else { ?><? echo $row2['BAY'];?><? } ?></td>
			<td class="grid-cell" align="center"><? if($row2['ABOVE']=='NON AKTIF') { ?>NOT ACTIVE<? } else if($row2['ABOVE']=='NONE') { ?>NOT AVAILABLE<? } else { ?>ACTIVE<? } ?></td>
			<td class="grid-cell" align="center"><? if($row2['BELOW']=='NON AKTIF') { ?>NOT ACTIVE<? } else if($row2['BELOW']=='NONE') { ?>NOT AVAILABLE<? } else { ?>ACTIVE<? } ?></td>
			<td class="grid-cell" align="center"><? if((($row2['ABOVE']=='NON AKTIF')||($row2['ABOVE']=='NONE'))&&(($row2['BELOW']=='NON AKTIF')||($row2['BELOW']=='NONE'))) { ?>-<? } else { ?><?=$jml_cell_a;?><? } ?></td>
			<td class="grid-cell" align="center"><? if((($row2['ABOVE']=='NON AKTIF')||($row2['ABOVE']=='NONE'))&&(($row2['BELOW']=='NON AKTIF')||($row2['BELOW']=='NONE'))) { ?>-<? } else { ?><?=$jml_cell_p;?><? } ?></td>
		</tr>
        <? $n++;} ?>
	</table>
<? } 
	else if(($id_vs == NULL)||($id_vs_ == NULL))
	{ ?>
	<div id="dialog-form">
	<form>
		<div id="table_bay"></div>
	</form>
	</div>
	<br/>
    <table class="grid-table" id="zebra" border="1" bordercolor="#FFFFFF" width=100% style="border-collapse:collapse">
	  <tr>
		<th class="grid-header" height="34" width="30">NO</th>
        <th class="grid-header">BAY</th>
        <th class="grid-header">STATUS</th>
		<th class="grid-header">JUMLAH BOX</th>
		<th class="grid-header">DETAIL</th>
      </tr>
	</table>
<? } ?>
		<!--<map name="profil_kapal">
		  <area shape="rect" coords="184,80,228,143" alt="Sun" onclick="display_bay('34')" />
		  <area shape="rect" coords="238,80,261,201" alt="Sun" onclick="display_bay('31')" />
		  <area shape="rect" coords="261,80,282,200" alt="Sun" onclick="display_bay('29')" />
		  <area shape="rect" coords="290,80,313,200" alt="Sun" onclick="display_bay('27')" />
		  <area shape="rect" coords="314,80,334,200" alt="Sun" onclick="display_bay('25')" />
		  <area shape="rect" coords="342,80,365,201" alt="Sun" href="23" />
		  <area shape="rect" coords="366,80,386,201" alt="Sun" href="21" />
		  <area shape="rect" coords="392,89,413,200" alt="Sun" href="19" />
		  <area shape="rect" coords="414,89,436,200" alt="Sun" href="17" />
		  <area shape="rect" coords="442,89,463,200" alt="Sun" href="15" />
		  <area shape="rect" coords="463,89,486,200" alt="Sun" href="13" />
		  <area shape="rect" coords="492,89,513,201" alt="Sun" href="11" />
		  <area shape="rect" coords="513,89,536,200" alt="Sun" href="9" />
		  <area shape="rect" coords="547,96,568,184" alt="Sun" href="7" />
		  <area shape="rect" coords="568,96,591,185" alt="Sun" href="5" />
		  <area shape="rect" coords="595,96,617,185" alt="Sun" href="3" />
		  <area shape="rect" coords="617,96,640,185" alt="Sun" href="1" />
		</map>-->   
    <br/>	
	</div><!-- main side -->
	<br/>
	</div>