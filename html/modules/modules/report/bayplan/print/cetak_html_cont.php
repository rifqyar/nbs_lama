<?		
	$db = getDB();
	$id_vs = $_GET['id'];
	$ves_voy = "SELECT NM_KAPAL,
					   VOYAGE_IN|| ' - ' ||VOYAGE_OUT AS VOYAGE
					FROM RBM_H
                    WHERE NO_UKK = '$id_vs'";
	$vvoy = $db->query($ves_voy);
	$hasil_vv = $vvoy->fetchRow();
	$vessel = $hasil_vv['NM_KAPAL'];
	$voyage = $hasil_vv['VOYAGE'];
?>
<center>
<h3><? echo $vessel." / ".$voyage; ?></h3>
<br/>
<div align="center">
<table width="100%" cellspacing="3" border="0">
<tbody>
<tr>
<?				
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ2 = $row18['OCCUPY'];
			
			if(($occ2=='Y')&&($bay_name<24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ2=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ2=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,
								   CELL_NUMBER,
								   ROW_,
								   TIER_,
								   STATUS_STACK,
								   PLUGGING 
						    FROM STW_BAY_CELL 
							WHERE ID_BAY_AREA = '$id_area' 
							ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			//debug($blok2);die;
			
            foreach ($blok2 as $row8){                
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$idx_cell = $row8['ID'];            
				
				//echo $index."_".$row8['STATUS_STACK']."<br/>";
				
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   TRIM(TYPE_) AS TYPE_,
								   TRIM(STATUS_) AS STATUS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$type_cont = $pol2['TYPE_'];
				$st_cont = $pol2['STATUS'];
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK_RFR.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{ ?>
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>
                  
               <? 
			   }
			   else if ($row8['STATUS_STACK'] == 'R')
				{
					if ($pol_bay<>'IDJKT')
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>                  
               <? 	
					}
					else
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>                  
               <? 
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {	
					if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
						<div id="x" class="drag blue">
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
						</div>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<tr>
<?
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ3 = $row18['OCCUPY'];
			
			if(($occ3=='T')&&($bay_name<24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ3=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ3=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			// debug($blok2);die;
            foreach ($blok2 as $row8){
                //echo $row['INDEX_CELL'];
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$id_cell = $row8['ID'];
				//echo $tr;			
            
				$idx_cell = $row8['ID'];
            
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   TRIM(TYPE_) AS TYPE_,
								   TRIM(STATUS_) AS STATUS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$type_cont = $pol2['TYPE_'];
				$st_cont = $pol2['STATUS'];
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK_RFR.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell' and ACTIVITY = 'MUAT'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont = $sz_cont['SIZE_'];
					
					if(($size_cont!='40')&&($size_cont!='45'))
					{
					
				?>
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>	           
               <? 
					}
					else
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 	
					
					}
			   
			   }
			    else if ($row8['STATUS_STACK'] == 'R')
				{ 
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont3 = $sz_cont['SIZE_'];
					
					if(($size_cont3=='40')||($size_cont3=='45'))
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 
					}
					else
					{
						if($pol_bayx<>'IDJKT')
						{
				?>
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
						 </div>
				<?		
						}
						else
						{
				?>							
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
						 </div>                  
               <?	
						}
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {?>
                    <? if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
					&nbsp;
					</td>
                    </div>
					<? } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<tr>
<?				
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ2 = $row18['OCCUPY'];
			
			if(($occ2=='Y')&&($bay_name>24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ2=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ2=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,
								   CELL_NUMBER,
								   ROW_,
								   TIER_,
								   STATUS_STACK,
								   PLUGGING 
						    FROM STW_BAY_CELL 
							WHERE ID_BAY_AREA = '$id_area' 
							ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			//debug($blok2);die;
			
            foreach ($blok2 as $row8){                
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$idx_cell = $row8['ID'];            
				
				//echo $index."_".$row8['STATUS_STACK']."<br/>";
				
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   TRIM(TYPE_) AS TYPE_,
								   TRIM(STATUS_) AS STATUS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$type_cont = $pol2['TYPE_'];
				$st_cont = $pol2['STATUS'];
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK_RFR.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{ ?>
				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>
                  
               <? 
			   }
			   else if ($row8['STATUS_STACK'] == 'R')
				{
					if ($pol_bay<>'IDJKT')
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>                  
               <? 	
					}
					else
					{
				?>				
                     <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>                  
               <? 
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {	
					if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
						<div id="x" class="drag blue">
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
						&nbsp;
						</td>
						</div>
					<? } 
				} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
<tr>
<?
			$db = getDB();
			$query_bay = "SELECT DISTINCT JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0";
			$result_   = $db->query($query_bay);
			$bay_      = $result_->fetchRow();
			
			$jumlah_row = $bay_['JML_ROW'];
			$jml_tier_under = $bay_['JML_TIER_UNDER'];
			$jml_tier_on = $bay_['JML_TIER_ON'];
			$width = $jumlah_row+1;
			
			$query_cell3 = "SELECT ID,BAY,OCCUPY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' AND BAY > 0 ORDER BY ID DESC";
			$result8    = $db->query($query_cell3);
			$blok8      = $result8->getAll();
			 
			foreach ($blok8 as $row18){
			$id_area = $row18['ID'];
			$bay_name = $row18['BAY'];
			$occ3 = $row18['OCCUPY'];
			
			if(($occ3=='T')&&($bay_name>24))
			{
?>
<td valign="bottom" colspan="4" align="center">
<table bordercolor="#037ACA" border="0" cellspacing="1" cellpadding="1" align="center">
<tbody>	
	   <tr>
		<td colspan="<?=$jumlah_row;?>" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><font size="1px"><b>Bay <? if ($occ3=='Y') { ?><?echo $bay_name;?>(<? echo $bay_name+1; ?>)<?  } else if ($occ3=='Y') { ?><? echo $bay_name;?>(<? echo $bay_name+1; ?>)<? } else { ?><? echo $bay_name;?><? } ?></b></font></td>
		<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
	   </tr>
       <tr>   
         <?php
		 
            $query_cell2 = "SELECT ID,CELL_NUMBER,ROW_,TIER_,STATUS_STACK,PLUGGING FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$id_area' ORDER BY CELL_NUMBER ASC";
            $result3    = $db->query($query_cell2);
            $blok2      = $result3->getAll();
            $n='';
			$br='';
			$tr='';
			// debug($blok2);die;
            foreach ($blok2 as $row8){
                //echo $row['INDEX_CELL'];
				$index = $row8['CELL_NUMBER']+1;
				$cell_address = $index-1;
				$br = $n;
				$tr = $row8['TIER_'];
				$n = $tr;
				$rw = $row8['ROW_'];
				$id_cell = $row8['ID'];
				//echo $tr;			
            
				$idx_cell = $row8['ID'];
            
				$cek_pol = "SELECT TRIM(ID_PEL_ASAL) AS POL,
								   TRIM(TYPE_) AS TYPE_,
								   TRIM(STATUS_) AS STATUS
						    FROM STW_PLACEMENT_BAY 
							WHERE ID_CELL = '$idx_cell'
								AND ACTIVITY = 'MUAT'";
				$hsl_pol = $db->query($cek_pol);
				$pol2    = $hsl_pol->fetchRow();
				$pol_bay = $pol2['POL'];
				$type_cont = $pol2['TYPE_'];
				$st_cont = $pol2['STATUS'];
				
				if(($type_cont=='HQ')&&($st_cont=='FCL'))
				{
					$pic = 'HC';
				}
				else if(($type_cont=='HQ')&&($st_cont=='MTY'))
				{
					$pic = 'HCMTY';
				}
				else if(($type_cont=='RFR')&&($st_cont=='FCL'))
				{
					$pic = 'REEFER';
				}
				else if(($type_cont=='RFR')&&($st_cont=='MTY'))
				{
					$pic = 'REEFERMTY';
				}
			
			if ($index%$width != 0) 
			{
				if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'T'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if (($row8['STATUS_STACK'] == 'A')&&($row8['PLUGGING'] == 'Y'))
				{ 
				?>
					<div id="x" class="drag blue">
                    <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><img src='<?=HOME?>images/container/STACK_RFR.png' width="10" height="10" border='0'/></td>
                    </div>	
				<?
			   }
			   else if ($row8['STATUS_STACK'] == 'P')
				{
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell' and ACTIVITY = 'MUAT'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont = $sz_cont['SIZE_'];
					
					if(($size_cont!='40')&&($size_cont!='45'))
					{
					
				?>
					 <div id="x" class="drag blue">
                     <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FF0000'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FF0000; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
                     </div>	           
               <? 
					}
					else
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 	
					
					}
			   
			   }
			    else if ($row8['STATUS_STACK'] == 'R')
				{ 
					$query_sz = "select SIZE_ from STW_PLACEMENT_BAY where ID_CELL = '$id_cell'";
					$hsl_sz = $db->query($query_sz);
					$sz_cont = $hsl_sz->fetchRow();
					$size_cont3 = $sz_cont['SIZE_'];
					
					if(($size_cont3=='40')||($size_cont3=='45'))
					{
					
				?>
							
                     <div id="x" class="drag blue">
					 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#FFFFFF'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#FFFFFF; "><b>+</b></td>
                     </div>
                  
               <? 
					}
					else
					{
						if($pol_bayx<>'IDJKT')
						{
				?>
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#B84DB8'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#B84DB8; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
						 </div>
				<?		
						}
						else
						{
				?>							
						 <div id="x" class="drag blue">
						 <td onMouseOver="this.style.backgroundColor='#CCFF33'" onMouseOut="this.style.backgroundColor='#33CC33'" align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#33CC33; "><img src='<?=HOME?>images/container/<?=$pic?>.png' width="10" height="10" border='0'/></td>
						 </div>                  
               <?	
						}
					}
			   }
			   else if(($index > ($width*($jml_tier_on+1)))&&($index <= ($width*($jml_tier_on+2))))
			   {
			   ?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; border:1px solid #000000;background-color:#663300; ">&nbsp;
					</td>
                </div>	
			   <?
			   }
			   else if ($row8['STATUS_STACK'] == 'N')
			   {?>
                    <? if(($index>=1)&&($index<$width)) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else if(($index>=(($width*($jml_tier_under+$jml_tier_on+2))+1))&&($index<=($width*($jml_tier_under+$jml_tier_on+3)))) {
					?>
						<td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma; "><?=$rw;?></td>
					<? }
						else
					   {
					?>
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma; background-color:#efedd9; ">
					&nbsp;
					</td>
                    </div>
					<? } ?>
              <?} 
			}			
			else if (($index == ($width*($jml_tier_under+$jml_tier_on+2)))&&($index%$width == 0)) 
			{ 	?>					
					<div id="x" class="drag blue">
                    <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">
					<?=$br?>
					</td>
                    </div>
					</tr>
			  <? }
			else if ($index%$width == 0)
			{ ?>
				<div id="x" class="drag blue">                    
					<? if ($br != 0)
					   { 
						 if ($index==$width)
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						  else {
						 ?>
						<td align="center" style="width:10px;height:10px;font-size:5px; font-family:Tahoma;"><?=$br;?></td>
						<? 
						  } 
						  ?>
					<?   }
					   else
					   {  
						  if ($index==($width*($jml_tier_under+$jml_tier_on+3)))
						 { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">&nbsp;</td>
						 <? }
						    else { ?>
						 <td align="center" style="width:10px;height:10px;font-size:7px; font-family:Tahoma;">HATCH</td>
					<? }
						} ?>
                </div>
					</tr>				
			<?
				}
            }
			?>
		
</tbody>
</table>
</td>
<? } } ?>
</tr>
</tbody>
</table>
</div>
</center>
<br/>
<table border="1" bordercolor="#4D5A77" width=45% style="border-collapse:collapse">
	<tr>
		<td align="center" style="background-color:#FFFFFF" height="30"><img src='<?=HOME?>images/container/HC.png' width="20" height="20" border='0'/></td>
		<td align="center" style="background-color:#FFFFFF">&nbsp;High-Cube</td>
		<td align="center" style="background-color:#FFFFFF"><img src='<?=HOME?>images/container/EMPTY.png' width="20" height="20" border='0'/></td>
		<td align="center" style="background-color:#FFFFFF">&nbsp;Empty</td>
	</tr>
	<tr>
		<td align="center" style="background-color:#FFFFFF" height="30"><img src='<?=HOME?>images/container/REEFER.png' width="20" height="20" border='0'/></td>
		<td align="center" style="background-color:#FFFFFF">&nbsp;Reefer</td>
		<td align="center" style="background-color:#FFFFFF"><img src='<?=HOME?>images/container/CLASS.png' width="20" height="20" border='0'/></td>
		<td align="center" style="background-color:#FFFFFF">&nbsp;Hazardous</td>
	</tr>
</table>
<div align="right"><i>Generated by ISWS</i><br/>&copy; Sistem Informasi Tanjung Priok 2012</div>