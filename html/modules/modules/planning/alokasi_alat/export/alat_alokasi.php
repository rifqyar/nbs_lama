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
		
						$( "#tabspage" ).tabs();
						//setInterval($('#working').html('<img src="<?=HOME?>images/loadingbox.gif" />'),1000);	
						//$("#glc01").load("<?=HOME?><?=APPID?>/working?id_req={$request.ID_REQ}&remark={$remark}&alat=GLC01 #workingtime_list");
						//$("#glc02").load("<?=HOME?><?=APPID?>/working?id_req={$request.ID_REQ}&remark={$remark}&alat=GLC02 #workingtime_list");
						//$("#glc03").load("<?=HOME?><?=APPID?>/working?id_req={$request.ID_REQ}&remark={$remark}&alat=GLC03 #workingtime_list");
						//$("#glc04").load("<?=HOME?><?=APPID?>/working?id_req={$request.ID_REQ}&remark={$remark}&alat=GLC04 #workingtime_list");
						//$("#glc05").load("<?=HOME?><?=APPID?>/working?id_req={$request.ID_REQ}&remark={$remark}&alat=GLC05 #workingtime_list");
						//$("#glc06").load("<?=HOME?><?=APPID?>/working?id_req={$request.ID_REQ}&remark={$remark}&alat=GLC06 #workingtime_list");
						
						/*setInterval(function() {					
							$("#idle").load("<?=HOME?>request.req_glc/r_idle #idletime_list");
							$("#waiting").load("<?=HOME?>request.req_glc/r_waiting #waitingtime_list");
							$("#not").load("<?=HOME?>request.req_glc/r_not #nottime_list");
						},1000);*/						
												
						/*$('a[rel*=downloadr]').downloadr();*/
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

function input_alat(b,vs,c,posisi,no)
{
	//alert(b);
	$('#table_alat').load("<?=HOME?>planning.alokasi_alat.ajax/insert_alat?id="+vs+"&bay_area="+b+"&posisi="+posisi+"&no_bay="+c+"&bay="+no).dialog({modal:true, height:180,width:380,title:"Bay "+c+" "+posisi+""});
}

function alokasi_alat(id_vs,no_bay,bay_area,posisi,no)
{
	var id_vs_      = id_vs;
	var alat_		= document.getElementById("alat").value;
	var activity_	= document.getElementById("activity").value;
	var sz_cont_	= document.getElementById("cont_sz").value;
	var bay_area_ 	= bay_area;
	var bay_no_		= no;
	var posisi_     = posisi;
	var no_bay_     = no_bay;
	var url			= "<?=HOME?>planning.alokasi_alat.ajax/alokasikan_alat";	
	
	$.post(url,{ID_VS : id_vs_, ALAT : alat_, BAY_AREA : bay_area_, POSISI : posisi_, ACTIVITY : activity_, SZ_CONT : sz_cont_, NO_BAY : bay_no_},function(data){
		console.log(data);
		if(data == "OK")
		{
			alert("Alokasi Alat "+alat_+" Berhasil...!!!");
			window.location = "<?=HOME?>planning.alokasi_alat/alat_alokasi?vs="+id_vs_;  	
		}
		else if(data == "NO")
		{
			alert("Alokasi Alat "+alat_+" Gagal...!!!");
		}
		else if(data == "NOT")
		{
			alert("Wrong Bay Allocation...");
		}
		else if(data == "ALOKASI")
		{
			alert("Alat "+alat_+" Sudah Teralokasikan...!!!");
		}
		else if(data == "JUMLAH")
		{
			alert("Bay "+no_bay_+" "+posisi_+" Sudah Teralokasikan Maksimal...!!!");
		}
	});	
}

function delete_alat(id_vs,no_bay,bay_area,posisi,id_alat,seq_alat)
{
	var id_vs_      = id_vs;
	var alat_		= id_alat;
	var bay_area_ 	= bay_area;
	var posisi_     = posisi;
	var no_bay_     = no_bay;
	var seq_alat_   = seq_alat;
	var url			= "<?=HOME?>planning.alokasi_alat.ajax/del_alat";	
	
	$.post(url,{ID_VS : id_vs_, ALAT : alat_, NO_BAY : no_bay_, BAY_AREA : bay_area_, POSISI : posisi_, SEQ_ALAT : seq_alat_},function(data){
		console.log(data);
		if(data == "OK")
		{
			alert("Dealokasi Alat "+alat_+" Berhasil...!!!");
			window.location = "<?=HOME?>planning.alokasi_alat/alat_alokasi?vs="+id_vs_;  	
		}
		else if(data == "NO")
		{
			alert("Dealokasi Alat "+alat_+" Gagal...!!!");
		}
	});	
}

function sync_mov(no_ukk,bay_area,posisi,id_alat,seq_alat,sz,act)
{
	var id_vs_      = no_ukk;
	var alat_		= id_alat;
	var bay_area_ 	= bay_area;
	var posisi_     = posisi;
	var seq_alat_   = seq_alat;
	var sz_cont_    = sz;
	var keg_        = act;
	var url			= "<?=HOME?>planning.alokasi_alat.export.ajax/sync_mov";	
	
	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/loading81.gif width="30" height="30" /><br><br>' });
	$.post(url,{ID_VS : no_ukk, ALAT : alat_, BAY_AREA : bay_area_, POSISI : posisi_, SEQ_ALAT : seq_alat_, SZ_CONT : sz_cont_, ACT : keg_},function(data){
		console.log(data);
		if(data == "OK")
		{
			$.unblockUI({
				onUnblock: function(){ }
				});
			alert("Success");
			window.location = "<?=HOME?>planning.alokasi_alat.export/alat_alokasi?vs="+id_vs_;  	
		}
		else if(data == "NO")
		{
			$.unblockUI({
				onUnblock: function(){ }
				});
			alert("Failed");
		}
	});	
}

function openWin(id_vs,id_bay_area,bay_number,posisi,alat)
{
	myWindow=window.open('<?=HOME?>planning.alokasi_alat.export.ajax/priority_alat?id='+id_vs+'&bay_area='+id_bay_area+'&bay_no='+bay_number+'&posisi='+posisi+'&id_alat='+alat,'','width=900,height=400,scrollbars=yes,resizable=no');
	myWindow.focus();
}

function detail_cont(id_vs,id_bay_area,bay_number,posisi,alat)
{
	$('#table_detail').load("<?=HOME?>planning.alokasi_alat.export.ajax/detail_cont?vs="+id_vs+"&bay_area="+id_bay_area+"&alat="+alat+"&posisi="+posisi+" #cont_detail").dialog({modal:true, height:300,width:400, title:"Bay "+bay_number+" "+posisi+""});
}

function cetak_csl(no_ukk,alat)
{
	window.open('<?=HOME?>planning.alokasi_alat.export.print/csl_printout/?no_ukk='+no_ukk+'&alat='+alat,'_blank');
}

</script>


<div class="content">
	<h2><span class="graybrown">
    	<img class="icon" border="0" src="images/container_crane.png" height="9%" width="9%"/>
        &nbsp;<font color="#DE7E21">Container Crane</font> Allocation<font color="#0066CC"> [ EXPORT ]</font>
        </span></h2>
	<br/>
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	<form enctype="multipart/form-data" action="<?=HOME;?>planning.alokasi_alat.export/alat_alokasi" method="post">
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
<div style="margin-right:155px; margin-bottom:180px; margin-top:80px; width:600; height:100;">
<p style="width:80%;">
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
				
				$bay_no = $row['BAY'];
				$bay_id = $row['ID'];
				$occ = $row['OCCUPY'];
				
				$cell_address = "SELECT ID_ALAT, SEQ_ALAT, ACTIVITY FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$bay_id' AND POSISI_STACK = 'ABOVE' AND TRIM(SZ_PLAN) IN ('40b','45b') AND ACTIVITY = 'E'";
                $result15     = $db->query($cell_address);
                $cell         = $result15->getAll();
				
				$query_jml = "SELECT count(*) AS JML FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$bay_id' AND POSISI_STACK = 'ABOVE' AND TRIM(SZ_PLAN) IN ('40b','45b') AND ACTIVITY = 'E'";
                $jml15     = $db->query($query_jml);
                $jml_hsl   = $jml15->fetchRow();
				$jumlah_record = $jml_hsl['JML'];				
							
				if(($occ=='T')||($occ=='0'))
				{
					if(($jumlah_record > 0)&&($bay_no!=0)&&($occ='Y'))
					{						
				?>
						<td colspan="2" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;">
						<table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td colspan="2" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;">
								<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="18" width="18"/>
							</td>
						  </tr>
						  <? 
						   } 
						  ?>
						</table>
						</td>
												
                <?
					}
					else if($bay_no==0)
					{
				?>	
						<td align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;">&nbsp;</td>
				<?	
					}
				}
					} 
				?>
	</tr>
	<tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				
				$bay_no = $row['BAY'];
				$occ2 = $row['OCCUPY'];
							
				if($occ2=='Y')
				{
					$bays = $bay_no."(".($bay_no+1).")";
				}
				else
				{
					$bays = $bay_no;
				}
				
			?>
                        <div id="x" class="drag blue">
                          <td align="center" style="width:40px;height:10px;font-size:8px; font-family:Tahoma;"><? if($bays>0) { echo $bays; } ?></td>
                        </div>  
              <?
			    } ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
    <tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, ABOVE, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				$id_bay_area  = $row['ID'];
				$occ3 = $row['OCCUPY'];
				
				$cell_address = "SELECT ID_ALAT, SEQ_ALAT, ACTIVITY FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$id_bay_area' AND POSISI_STACK = 'ABOVE' AND TRIM(SZ_PLAN) = '20' AND ACTIVITY = 'E'";
                $result15     = $db->query($cell_address);
                $cell         = $result15->getAll();
				
				$query_jml = "SELECT count(*) AS JML FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$id_bay_area' AND POSISI_STACK = 'ABOVE' AND TRIM(SZ_PLAN) = '20' AND ACTIVITY = 'E'";
                $jml15     = $db->query($query_jml);
                $jml_hsl   = $jml15->fetchRow();
				$jumlah_record = $jml_hsl['JML'];
				
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
							
            if ($row['ABOVE'] == "NON AKTIF")
			{        
			?>
                        <div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#f1ff4f'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#f1ff4f;" >
						  <? if($jumlah_record > 0) { ?>
						  <table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td>
							<a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ3=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ3=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above','<?=$row['BAY'];?>')">
							<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="18" width="18"/>
							</a>
							</td>
						  </tr>
						  <? 
							} 
						  ?>
						  </table>
						  <? } else { ?>
						  <a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ3=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ3=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above','<?=$row['BAY'];?>')">
						  <img src="<?=HOME?>images/cc/MTY.png" height="18" width="18"/>
						  </a>
						  <? } ?>
						  </td>
                        </div>                  
               <? } 
					else if($row['ABOVE'] == 'NONE')
					{
				   ?>
							<div id="x" class="drag blue">
							  <td style="width:40px;height:50px;font-size:8px; font-family:Tahoma;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
							</div>
				   <?
					}
					else 
					{
						if($cek_disch>0)
						{
						?>
						
							<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#CCFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#CCFFFF;">
						  <? if($jumlah_record > 0) { ?>
						  <table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td>
							<a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ3=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ3=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above','<?=$row['BAY'];?>')">
							<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="18" width="18"/>
							</a>
							</td>
						  </tr>
						  <? 
							} 
						  ?>
						  </table>
						  <? } else { ?>
						  <a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ3=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ3=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above','<?=$row['BAY'];?>')">
						  <img src="<?=HOME?>images/cc/MTY.png" height="40" width="30"/>
						  </a>
						  <? } ?>
						  </td>
                        </div>
						
						<?
						}
						else
						{
						?>
			   
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;">
						  <? if($jumlah_record > 0) { ?>
						  <table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td>
							<a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ3=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ3=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above','<?=$row['BAY'];?>')">
							<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="18" width="18"/>
							</a>
							</td>
						  </tr>
						  <? 
							} 
						  ?>
						  </table>
						  <? } else { ?>
						  <a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ3=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ3=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','above','<?=$row['BAY'];?>')">
						  <img src="<?=HOME?>images/cc/MTY.png" height="40" width="30"/>
						  </a>
						  <? } ?>
						  </td>
                        </div>	
                    
              <? }
				  } 
			    } ?>
	</tr>
	<!---------------------------- ABOVE -------------------------------->
	<tr>
         <?php
            $db         = getDB();
            $query_jml  = "SELECT count(BAY) AS JML_BAY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY BAY DESC";
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
            $query_blok = "SELECT ID, BAY, BELOW, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				$id_bay_area  = $row['ID'];
				$occ4 = $row['OCCUPY'];
				
				$cell_address = "SELECT ID_ALAT, SEQ_ALAT, ACTIVITY FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$id_bay_area' AND POSISI_STACK = 'BELOW' AND TRIM(SZ_PLAN) = '20' AND ACTIVITY = 'E'";
                $result15     = $db->query($cell_address);
                $cell         = $result15->getAll();
				
				$query_jml = "SELECT count(*) AS JML FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$id_bay_area' AND POSISI_STACK = 'BELOW' AND TRIM(SZ_PLAN) = '20' AND ACTIVITY = 'E'";
                $jml15     = $db->query($query_jml);
                $jml_hsl   = $jml15->fetchRow();
				$jumlah_record = $jml_hsl['JML'];
				
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
								
				if ($row['BELOW'] == "NON AKTIF")
				{                    
				?>
                        <div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#f1ff4f'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#f1ff4f;" >
						  <? if($jumlah_record > 0) { ?>
						  <table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td>
							<a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ4=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ4=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below','<?=$row['BAY'];?>')">
							<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="40" width="30"/>
							</a>
							</td>
						  </tr>
						  <? 
							} 
						  ?>
						  </table>
						  <? } else { ?>
						  <a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ4=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ4=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below','<?=$row['BAY'];?>')">
						  <img src="<?=HOME?>images/cc/MTY.png" height="40" width="30"/>
						  </a>
						  <? } ?>
						  </td>
                        </div>                    
				<? } 
				    else if($row['BELOW'] == 'NONE')
					{
				   ?>
							<div id="x" class="drag blue">
							  <td style="width:40px;height:50px;font-size:8px; font-family:Tahoma;"><img src="<?=HOME;?>images/mty_stw.png"/></td>
							</div>
				   <?
					}
					else 
					{ 
						if($cek_disch>0)
						{
						?>
						
							<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#CCFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#CCFFFF;">
						  <? if($jumlah_record > 0) { ?>
						  <table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td>
							<a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ4=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ4=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below','<?=$row['BAY'];?>')">
							<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="18" width="18"/>
							</a>
							</td>
						  </tr>
						  <? 
							} 
						  ?>
						  </table>
						  <? } else { ?>
						  <a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ4=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ4=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below','<?=$row['BAY'];?>')">
						  <img src="<?=HOME?>images/cc/MTY.png" height="40" width="30"/>
						  </a>
						  <? } ?>
						  </td>
                        </div>
						
						<?
						}
						else
						{
					?>
			   
						<div id="x" class="drag blue">
                          <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:40px;height:50px;font-size:8px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF;">
						  <? if($jumlah_record > 0) { ?>
						  <table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td>
							<a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ4=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ4=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below','<?=$row['BAY'];?>')">
							<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="18" width="18"/>
							</a>
							</td>
						  </tr>
						  <? 
							} 
						  ?>
						  </table>
						  <? } else { ?>
						  <a href="javascript:input_alat('<?echo $row['ID']?>','<?=$id?>','<? if ($occ4=='Y') { ?><? echo $row['BAY']; ?>(<? echo $row['BAY']+1;?>)<?  } else if ($occ4=='Y') { ?><?echo $row['BAY']?>(<? echo $row['BAY']+1; ?>)<? } else { ?><? echo $row['BAY'];?><? } ?>','below','<?=$row['BAY'];?>')">
						  <img src="<?=HOME?>images/cc/MTY.png" height="40" width="30"/>
						  </a>
						  <? } ?>
						  </td>
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
	<tr>
		<?php
			$db         = getDB();
			$query_jml  = "SELECT count(BAY) AS JML_BAY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY BAY DESC";
			$result12_  = $db->query($query_jml);
			$jml        = $result12_->fetchRow();
			$jml_bay    = $jml['JML_BAY'];
				
		?>
			<td colspan="<?=$jml_bay;?>" align="center" style="width:20px;height:50px;"></td>
	</tr>
	<tr>
         <?php
            $db         = getDB();
            $query_blok = "SELECT ID, BAY, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id' ORDER BY ID DESC";
            $result_    = $db->query($query_blok);
            $blok       = $result_->getAll();
            
			// debug($blok2);die;
            foreach ($blok as $row){
                //echo $row['INDEX_CELL'];
				
				$bay_no = $row['BAY'];
				$bay_id = $row['ID'];
				$occ6 = $row['OCCUPY'];
				
				$cell_address = "SELECT ID_ALAT, SEQ_ALAT, ACTIVITY FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$bay_id' AND POSISI_STACK = 'BELOW' AND TRIM(SZ_PLAN) IN ('40b','45b') AND ACTIVITY = 'E'";
                $result15     = $db->query($cell_address);
                $cell         = $result15->getAll();
				
				$query_jml = "SELECT count(*) AS JML FROM STW_BAY_CSL WHERE ID_BAY_AREA = '$bay_id' AND POSISI_STACK = 'BELOW' AND TRIM(SZ_PLAN) IN ('40b','45b') AND ACTIVITY = 'E'";
                $jml15     = $db->query($query_jml);
                $jml_hsl   = $jml15->fetchRow();
				$jumlah_record = $jml_hsl['JML'];				
							
				if(($occ6=='T')||($occ6==0))
				{
					if($bay_no==0)
					{
				?>	
						<td align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;">&nbsp;</td>
				<?	
					}
					else if(($jumlah_record > 0)&&($bay_no!=0)&&($occ='Y'))
					{						
				?>
						<td colspan="2" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;">
						<table>
						  <? 
						  foreach ($cell as $alat)
						  {
							$id_alat  = $alat['ID_ALAT'];
							$seq_alat = $alat['SEQ_ALAT'];
							$act_alat = $alat['ACTIVITY'];						
						  ?>
						  <tr>
							<td colspan="2" align="center" style="width:40px;height:30px;font-size:8px; font-family:Tahoma;">
								<img src="<?=HOME?>images/cc/<?=$act_alat?>/<?=$id_alat?>/<?=$seq_alat?>.png" height="18" width="18"/>
							</td>
						  </tr>
						  <? 
						   } 
						  ?>
						</table>
						</td>
												
                <?
					}
				}
					} 
				?>
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
<!--<table>
	<tr>
		<td width="20" style="background-color:#E6E6E6;"></td><td>&nbsp;Status Aktif</td>
		<td width="20"></td>
		<td width="20" style="background-color:#f1ff4f;"></td><td>&nbsp;Status Tidak Aktif</td>
		<td width="20"></td>
		<td width="20" style="background-color:#06b31b;"></td><td>&nbsp;Receiving</td>
		<td width="20"></td>
		<td width="20" style="background-color:#ff0000;"></td><td>&nbsp;Delivery</td>
		<td width="20"></td>		
	</tr>
</table>-->
 <div id="dialog-form">
	<form>
		<div id="table_alat"></div>
	</form>
	<form>
		<div id="table_detail"></div>
	</form>
	<form>
		<div id="table_alokasi"></div>
	</form>
 </div>
	<br/>
	
<?
			$db  = getDB();
			$cek_alat = "SELECT count(*) AS JML_ALAT											
									FROM STW_BAY_CSL 
									WHERE ID_VS = '$id'
									AND ACTIVITY = 'E'";
					$result_alat    = $db->query($cek_alat);
					$alat_cek       = $result_alat->fetchRow();
					$jml_alat       = $alat_cek['JML_ALAT'];
			
			if($jml_alat > 0)
			{
?>	
	
    <div id="tabspage" class="tabss">
				<ul>				
					<li><a href="#tabspage-1">CRANE SEQUENCE LIST</a></li>
					<li><a href="#tabspage-2">PRIORITY LIST</a></li>
					<li><a href="#tabspage-3">SUMMARY BOX</a></li>
				</ul>			
								
				<div id="tabspage-1">
				<?
					$query_blok5 = "SELECT DISTINCT ID_ALAT
									FROM STW_BAY_CSL
									WHERE ID_VS = '$id'
										AND ACTIVITY = 'E'
									ORDER BY ID_ALAT ASC";
					$result5_    = $db->query($query_blok5);
					$blok5       = $result5_->getAll();

					foreach ($blok5 as $row5)
					{
				?>
					<div align="center">
					<br/>
					<? $id_alat = $row5['ID_ALAT']; ?>
					<div align="left"><b><font size="+1" style="line-height:30px; vertical-align:middle; padding-bottom:8px;"><?=$id_alat?></font></b>&nbsp;<img src="<?=HOME?>images/cc/<?=$id_alat?>.png" height="15" width="15"/>&nbsp;&nbsp;
					<img src="<?=HOME?>images/printer.png" height="15" width="15" title="Cetal CSL" onclick="cetak_csl('<?=$id?>','<?=$id_alat?>')" /></div>
					<table border="1" bordercolor="#4D5A77" width=100% style="border-collapse:collapse">
					  <tr>
						<th style="background-color:#607095" width="20" height="30">NO</th>
						<th style="background-color:#607095" width="50">CATEGORY</th>
						<th style="background-color:#607095" width="50">BAY</th>
						<th style="background-color:#607095" width="50">POSISI</th>
						<th style="background-color:#607095" width="30">E_I</th>
						<th style="background-color:#607095" width="30">MOVEMENT</th>
						<th style="background-color:#607095" width="100">PRIORITY</th>
						<th style="background-color:#607095" width="50">SYNC</th>
						<th style="background-color:#607095" width="40">DELETE</th>
					 </tr>
					 
					 <?
						$db  = getDB();						
						$query_seq_alat = "SELECT TRIM(A.SZ_PLAN) SZ_PLAN,
                                            B.BAY, 
                                            A.POSISI_STACK,
                                            A.SEQ_ALAT,
											A.ID_BAY_AREA,
											A.ACTIVITY,
											A.PRIORITY,
											A.ID_ALAT,
											A.JML_MOV
                                    FROM STW_BAY_CSL A, STW_BAY_AREA B
                                    WHERE A.ID_VS = '$id' 
                                    AND A.ID_ALAT = '$id_alat'
									AND B.ID = A.ID_BAY_AREA
									AND TRIM(A.SZ_PLAN) NOT IN ('40b','45b')
									AND ACTIVITY = 'E'
                                    ORDER BY SEQ_ALAT ASC";
						$result19_    = $db->query($query_seq_alat);
						$seq_alat     = $result19_->getAll();
						
						$no = 1;
						foreach ($seq_alat as $row19)
						{
							if($row19['SZ_PLAN']=='20')
							{
								$bay_csl = $row19['BAY'];
								$sz_csl = $row19['SZ_PLAN'];
								$sz_mov = "20";
							}
							else
							{
								$bay_csl = $row19['BAY']+1;
								$sz_cs = str_replace('d','',$row19['SZ_PLAN']);
								
								if(($sz_cs=='40')||($sz_cs=='45'))
								{
									$sz_csl = "40/45";
									$sz_mov = $sz_cs;
								}
							}
					 ?>
					 
					 <tr>  
						<td align="center" bgcolor="#FAFAFA" height="30"><?=$no;?></td> 
						<td align="center" bgcolor="#FAFAFA"><?=$sz_csl;?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$bay_csl;?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$row19['POSISI_STACK'];?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$row19['ACTIVITY'];?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$row19['JML_MOV'];?></td>
						<td align="center" bgcolor="#FAFAFA"><? if($row19['PRIORITY']=='T') { ?><a onclick=""><img border='0' src='images/yes.png' width='14' height='14'> Belum Disetujui</a><? } else { ?><img border='0' src='images/yes.png' width='14' height='14'> Sudah Disetujui<? } ?></td>
						<td align="center" bgcolor="#FAFAFA"><button onclick="sync_mov('<?=$id;?>','<?=$row19['ID_BAY_AREA'];?>','<?=$row19['POSISI_STACK'];?>','<?=$row19['ID_ALAT'];?>','<?=$row19['SEQ_ALAT'];?>','<?=$sz_mov;?>','<?=$row19['ACTIVITY'];?>')" title="movement baplie import"><img src='images/Refresh2.png' width=15px height=15px border='0'></button></td>
						<td align="center" bgcolor="#FAFAFA"><input type="button" onclick="delete_alat('<?=$id;?>','<? if ($row19['BAY']==1) { ?><? echo $row19['BAY']; ?>(<? echo $row19['BAY']+1;?>)<?  } else if (($row19['BAY']-1)%4==0) { ?><?echo $row19['BAY']?>(<? echo $row19['BAY']+1; ?>)<? } else { ?><? echo $row19['BAY'];?><? } ?>','<?=$row19['ID_BAY_AREA'];?>','<?=$row19['POSISI_STACK'];?>','<?=$row19['ID_ALAT'];?>','<?=$row19['SEQ_ALAT'];?>')" value="Hapus" name="Hapus"/></td>
					</tr>
					 
					 <? $no++; } ?>					 
					</table>
					<br/>
					</div>					
				<?  } ?>
				</div>			
				<div id="tabspage-2">
				<?
					$query_blok5 = "SELECT DISTINCT ID_ALAT
									FROM STW_BAY_CSL
									WHERE ID_VS = '$id'
									AND ACTIVITY = 'E'
									ORDER BY ID_ALAT ASC";
					$result5_    = $db->query($query_blok5);
					$blok5       = $result5_->getAll();

					foreach ($blok5 as $row5)
					{
				?>
					<div align="center">
					<br/>
					<? $id_alat = $row5['ID_ALAT']; ?>
					<div align="left" style="width:70%;"><b><font size="+1" style="line-height:30px; vertical-align:middle; padding-bottom:8px;"><?=$id_alat?></font></b>&nbsp;<img src="<?=HOME?>images/cc/<?=$id_alat?>.png" height="15" width="15"/></div>
					<table border="1" bordercolor="#4D5A77" width=70% style="border-collapse:collapse">
					  <tr>
						<th style="background-color:#607095" width="20" height="30">NO</th>
						<th style="background-color:#607095" width="50">ALAT</th>
						<th style="background-color:#607095" width="50">BAY</th>
						<th style="background-color:#607095" width="50">POSISI</th>
						<th style="background-color:#607095" width="50">ACTIVITY</th>
						<th style="background-color:#607095" width="30">PRIORITY</th>
					 </tr>
					 
					 <?
						$db  = getDB();
						$id_alat = $row5['ID_ALAT'];
						$query_seq_alat = "SELECT A.ID_ALAT,
                                            B.BAY, 
                                            A.POSISI_STACK,
                                            A.SEQ_ALAT,
											A.ID_BAY_AREA,
											A.ACTIVITY,
											A.PRIORITY
                                    FROM STW_BAY_CSL A, STW_BAY_AREA B
                                    WHERE A.ID_VS = '$id' 
                                    AND A.ID_ALAT = '$id_alat'
									AND B.ID = A.ID_BAY_AREA
									AND ACTIVITY = 'E'
                                    ORDER BY SEQ_ALAT ASC";
						$result19_    = $db->query($query_seq_alat);
						$seq_alat     = $result19_->getAll();
						
						$no = 1;
						foreach ($seq_alat as $row19)
						{
					 ?>
					 
					 <tr>  
						<td align="center" bgcolor="#FAFAFA" height="30"><?=$no;?></td> 
						<td align="center" bgcolor="#FAFAFA"><?=$id_alat?></td>
						<td align="center" bgcolor="#FAFAFA"><? if ($row19['BAY']==1) { ?><? echo $row19['BAY']; ?>(<? echo $row19['BAY']+1;?>)<?  } else if (($row19['BAY']-1)%4==0) { ?><?echo $row19['BAY']?>(<? echo $row19['BAY']+1; ?>)<? } else { ?><? echo $row19['BAY'];?><? } ?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$row19['POSISI_STACK'];?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$row19['ACTIVITY'];?></td>
						<td align="center" bgcolor="#FAFAFA"><? if($row19['PRIORITY']=='T') { ?><input type="button" onclick="openWin('<?=$id;?>','<?=$row19['ID_BAY_AREA'];?>','<? if ($row19['BAY']==1) { ?><? echo $row19['BAY']; ?>(<? echo $row19['BAY']+1;?>)<?  } else if (($row19['BAY']-1)%4==0) { ?><?echo $row19['BAY']?>(<? echo $row19['BAY']+1; ?>)<? } else { ?><? echo $row19['BAY'];?><? } ?>','<?=$row19['POSISI_STACK'];?>','<?=$row19['ID_ALAT'];?>')" value="Insert" name="Insert"/><? } else { ?><img border='0' src='images/yes.png' width='14' height='14'> OK<? } ?></td>
					</tr>
					 
					 <? $no++; } ?>					 
					</table>
					<br/>
					</div>					
				<?  } ?>
				</div>
				<div id="tabspage-3">
					<div align="center">
					<br/>
					<table border="1" bordercolor="#4D5A77" width=100% style="border-collapse:collapse">
					  <tr>
						<th rowspan="2" style="background-color:#607095" width="20" height="30">NO</th>
						<th rowspan="2" style="background-color:#607095" width="50">BAY</th>
						<th colspan="3" style="background-color:#607095" width="60">ABOVE</th>
						<th colspan="3" style="background-color:#607095" width="60">BELOW</th>
					 </tr>
					 <tr>
						<th style="background-color:#607095" width="20">20</th>
						<th style="background-color:#607095" width="20">40</th>
						<th style="background-color:#607095" width="20">45</th>
						<th style="background-color:#607095" width="20">20</th>
						<th style="background-color:#607095" width="20">40</th>
						<th style="background-color:#607095" width="20">45</th>
					 </tr>
					 
					 <?
						$db  = getDB();						
						$query_seq_alat = "SELECT ID, BAY, OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id' AND BAY > 0 ORDER BY ID ASC";
						$result19_      = $db->query($query_seq_alat);
						$seq_alat       = $result19_->getAll();
						
						$no = 1;
						foreach ($seq_alat as $row19)
						{
							$bay_no = $row19['BAY'];
							$id_bay = $row19['ID'];
							$occup = $row19['OCCUPY'];
							
							$jum_20abv = "SELECT COUNT(*) AS JUM 
											FROM STW_BAY_CELL A, STW_PLACEMENT_BAY B 
											WHERE A.ID_BAY_AREA = '$id_bay' 
											AND A.ID = B.ID_CELL 
											AND TRIM(B.SIZE_) = '20' 
											AND TRIM(A.POSISI_STACK) = 'ABOVE'";
							$result_20abv = $db->query($jum_20abv);
							$abv20 = $result_20abv->fetchRow();
							$cont_20abv = $abv20['JUM'];
							
							$jum_40abv = "SELECT COUNT(*) AS JUM 
											FROM STW_BAY_CELL A, STW_PLACEMENT_BAY B 
											WHERE A.ID_BAY_AREA = '$id_bay' 
											AND A.ID = B.ID_CELL 
											AND TRIM(B.SIZE_) = '40' 
											AND TRIM(A.POSISI_STACK) = 'ABOVE'";
							$result_40abv = $db->query($jum_40abv);
							$abv40 = $result_40abv->fetchRow();
							$cont_40abv = $abv40['JUM'];
							
							$jum_45abv = "SELECT COUNT(*) AS JUM 
											FROM STW_BAY_CELL A, STW_PLACEMENT_BAY B 
											WHERE A.ID_BAY_AREA = '$id_bay' 
											AND A.ID = B.ID_CELL 
											AND TRIM(B.SIZE_) = '45' 
											AND TRIM(A.POSISI_STACK) = 'ABOVE'";
							$result_45abv = $db->query($jum_45abv);
							$abv45 = $result_45abv->fetchRow();
							$cont_45abv = $abv45['JUM'];
							
							$jum_20blw = "SELECT COUNT(*) AS JUM 
											FROM STW_BAY_CELL A, STW_PLACEMENT_BAY B 
											WHERE A.ID_BAY_AREA = '$id_bay' 
											AND A.ID = B.ID_CELL 
											AND TRIM(B.SIZE_) = '20' 
											AND TRIM(A.POSISI_STACK) = 'BELOW'";
							$result_20blw = $db->query($jum_20blw);
							$blw20 = $result_20blw->fetchRow();
							$cont_20blw = $blw20['JUM'];
							
							$jum_40blw = "SELECT COUNT(*) AS JUM 
											FROM STW_BAY_CELL A, STW_PLACEMENT_BAY B 
											WHERE A.ID_BAY_AREA = '$id_bay' 
											AND A.ID = B.ID_CELL 
											AND TRIM(B.SIZE_) = '40' 
											AND TRIM(A.POSISI_STACK) = 'BELOW'";
							$result_40blw = $db->query($jum_40blw);
							$blw40 = $result_40blw->fetchRow();
							$cont_40blw = $blw40['JUM'];
							
							$jum_45blw = "SELECT COUNT(*) AS JUM 
											FROM STW_BAY_CELL A, STW_PLACEMENT_BAY B 
											WHERE A.ID_BAY_AREA = '$id_bay' 
											AND A.ID = B.ID_CELL 
											AND TRIM(B.SIZE_) = '45' 
											AND TRIM(A.POSISI_STACK) = 'BELOW'";
							$result_45blw = $db->query($jum_45blw);
							$blw45 = $result_45blw->fetchRow();
							$cont_45blw = $blw45['JUM'];
							
					 ?>
					 
					 <tr>  
						<td align="center" bgcolor="#FAFAFA" height="30"><?=$no;?></td> 
						<td align="center" bgcolor="#FAFAFA"><? if ($occup=='Y') { ?><? echo $bay_no; ?>(<? echo $bay_no+1;?>)<?  } else if ($occup=='Y') { ?><?echo $bay_no?>(<? echo $bay_no+1; ?>)<? } else { ?><? echo $bay_no;?><? } ?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$cont_20abv;?></td>
						<td align="center" bgcolor="#FAFAFA"><? if ($occup=='Y') { echo $cont_40abv; } else if ($occup=='Y') { echo $cont_40abv; } else { echo "-"; } ?></td>
						<td align="center" bgcolor="#FAFAFA"><? if ($occup=='Y') { echo $cont_45abv; } else if ($occup=='Y') { echo $cont_45abv; } else { echo "-"; } ?></td>
						<td align="center" bgcolor="#FAFAFA"><?=$cont_20blw;?></td>
						<td align="center" bgcolor="#FAFAFA"><? if ($occup=='Y') { echo $cont_40blw; } else if ($occup=='Y') { echo $cont_40blw; } else { echo "-"; } ?></td>
						<td align="center" bgcolor="#FAFAFA"><? if ($occup=='Y') { echo $cont_45blw; } else if ($occup=='Y') { echo $cont_45blw; } else { echo "-"; } ?></td>
					</tr>
					 
					 <? $no++; } ?>					 
					</table>
					<br/>
					</div>	
				</div>
	</div>
<? }
	}
    ?>
	</div><!-- main side -->
	<br/>
	</div>